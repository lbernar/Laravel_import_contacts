<?php

namespace App\Http\Controllers;

use App\Contact;
use App\CsvData;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
class ImportController extends Controller
{

    public function getImport()
    {
        return view('import');
    }

    public function fieldImport()
    {
        return view('import_fields');
    }

    public function parseImport(CsvImportRequest $request)
    {

        $path_org = $request->file('csv_file')->store('uploads');
        $csv_filename = explode("/", $path_org)[1];
        $path = storage_path('app/');
        if ($request->has('header')) {
            $data = Excel::load($path, function($reader) {})->get()->toArray();
        } else {
            $data = array_map('str_getcsv', file($path.$path_org));
        }

        if (count($data) > 0) {
            $csv_header_fields = [];
            if ($request->has('header')) {
                foreach ($data[0] as $key => $value) {
                    $csv_header_fields[] = $key;
                }
            }
            $csv_data = array_slice($data, 0, 2);
            $user_id = Auth::user()->id;
            $csv_data_file = CsvData::create([
                'csv_filename' => $csv_filename,
                'csv_header' => $request->has('header'),
                'csv_status' => 'On Hold',
                'csv_path' => $path.'uploads',
                'csv_header_position' => 0,
                'user_id' => $user_id
            ]);
        } else {
            return redirect()->back();
        }
        return view('import_fields', compact( 'csv_header_fields', 'csv_data', 'csv_data_file'));

    }

    public function processImport(Request $request)
    {
        $request_fields = json_encode($request->fields);
        CsvData::where('id', $request->csv_data_file_id)->update(array('csv_header_position' => $request_fields));
        $data = CsvData::select('csv_filename', 'csv_status')->where('user_id', Auth::user()->id)->get();
        $csv_data = json_decode($data, true);
        return view('home', compact('csv_data'));
    }

    public function statusImport()
    {
        $data = Contact::select('name', 'birthday', 'phone', 'address', 'last_digits', 'franchise', 'email')->where('user_id', Auth::user()->id)->get();
        $contact_data = json_decode($data, true);
        return view('import_status', compact('contact_data'));
    }

}