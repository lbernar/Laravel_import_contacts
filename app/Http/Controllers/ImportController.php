<?php

namespace App\Http\Controllers;

use App\Contact;
use App\CsvData;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getImport()
    {
        return view('import');
    }

    public function parseImport(CsvImportRequest $request)
    {

        $path = $request->file('csv_file')->getRealPath();

        if ($request->has('header')) {
            $data = Excel::load($path, function($reader) {})->get()->toArray();
        } else {
            $data = array_map('str_getcsv', file($path));
        }

        if (count($data) > 0) {
            if ($request->has('header')) {
                $csv_header_fields = [];
                foreach ($data[0] as $key => $value) {
                    $csv_header_fields[] = $key;
                }
            }
            $csv_data = array_slice($data, 0, 2);

            $csv_data_file = CsvData::create([
                'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
                'csv_header' => $request->has('header'),
                'csv_data' => json_encode($data)
            ]);
        } else {
            return redirect()->back();
        }

        return view('import_fields', compact( 'csv_header_fields', 'csv_data', 'csv_data_file'));

    }

    public function processImport(Request $request)
    {
        $data = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);
        foreach ($csv_data as $row) {
            $contact = new Contact();
            foreach (config('app.db_fields') as $index => $field) {
                if ($data->csv_header) {
                    $contact->$field = $row[$request->fields[$field]];
                } else {
                    $contact->$field = $row[$request->fields[$index]];
                }
            }
            $contact->save();
        }

        return view('import_success');
    }

    private function isNameValid($name) {
        if (preg_match('[a-zA-Z-]', $name) > 0)
            return true;
        else 
            return false;
    }

    private function isDateValid($date) {
        if (preg_match('^(([12]\d{3})\/((0[1-9]|1[0-2])|\b(january|January)|\b(february|February)|\b(march|March)|\b(april|April)|\b(may|May)|\b(june|June)|\b(july|July)|\b(august|August)|\b(september|September)|\b(octuber|Octuber)|\b(november|November)|\b(december|December))\/(0[1-9]|[12]\d|3[01]))$', $date) > 0)
            return true;
        else 
            return false;
    }

    private function isPhoneValid($phone) {
        if (preg_match('^([(]{0,1}[+]\d{1,2}[)]{0,1}[\s]\d{0,3}[\s\-]\d{0,3}[\s\-]\d{0,2}[\s\-]\d{0,2})$', $phone) > 0) 
            return true;
        else
            return false;
    }

    private function getCreditCardBrand($creditCard) {
        if (preg_match('^(?:4[0-9]{12}(?:[0-9]{3}))$',$creditCard) > 0) 
            $brand = "visa";
        elseif (preg_match('^(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}?$', $creditCard) > 0)
            $brand = "mastercard";
        elseif (preg_match('^3[47][0-9]{13}$', $creditCard) > 0)
            $brand = "american express";
        elseif (preg_match('^3(?:0[0-5]|[68][0-9])[0-9]{11}$', $creditCard) > 0)
            $brand = "diners club";
        elseif (preg_match('^6(?:011|5[0-9]{2})[0-9]{12}$', $creditCard) > 0)
            $brand = "discover";
        elseif (preg_match('^(?:2131|1800|35\d{3})\d{11}$', $creditCard) > 0)
            $brand = "jcb";
        else
            return false;
        
        return $brand;
    }

    private function encryptCreditCard($creditCard) {
        return bcrypt($creditCard);
    }

    private function getLastCreditCardNumber($creditCard) {
        return substr($creditCard, 0, -4);
    }
}
