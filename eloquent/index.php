<?php

require_once 'app/init.php';
use App\Models\Contact;
use App\Models\CsvData;
use Illuminate\Support\Facades\Auth;

function write_log($log_msg)
{
    $logFolder = '../storage/';
    $log_filename = "logs";
    $log_file_data = $logFolder.$log_filename.'/error_importation.log';
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
   
}

function processImport()
{
    $contacts = [];
    $errors_validation = [];
    $data = CsvData::where('csv_status', 'On Hold')->first();
    $csv_data = json_decode($data->csv_data, true);
    $dbFields = [
        'name',
        'birthday',
        'phone',
        'address',
        'credit_card',
        'email',
        'last_digits',
        'franchise'
    ];

    foreach ($csv_data as $key => $value) {
        $new_csv_data[] = array_pad($csv_data[$key], count($dbFields), '');
    }
    foreach ($new_csv_data as $row) {
        $contact = new Contact();
        foreach ($dbFields as $index => $field) {
            $contact->$field = $row[$index];
        }
        $contact->user_id = Auth::user()->id;
        $contact->franchise = $contact->getCreditCardBrand($contact->credit_card);
        $contact->last_digits = $contact->getLastCreditCardNumber($contact->credit_card);
        $contact->credit_card = $contact->encryptCreditCard($contact->credit_card);

        if(!$contact->isValid()){ 
            array_push($errors_validation, $contact);
            $errors = $contact->getError();
            for($i=0;$i<count($errors_validation);$i++) {
                $errors = implode(",", $errors);
                $log_msg = date('Y-m-d H:m:s')."- filename: $data->csv_filename - Register id: $data->id - Error: $errors";
                write_log($log_msg);
            }
            continue;
        }
        array_push($contacts, $contact);

        // if(array_key_exists($contact->email, $emails)) {
        //     if(in_array($contact->name, $emails[$contact->email])) {
        //         var_dump(array_push($errors_duplicated, $contact));
        //     } else {
        
        //         array_push($emails, $contact->name);
                
        //         array_push($contacts, $contact);
        //     }
        // } else {
        //     $emails[$contact->email] = [$contact->name];
        // }
   
  
    }
die();
    foreach($contacts as $contact) {
        $contact->save();
    }
    CsvData::where('id', $data->id)->update(array('csv_status' => 'Finished'));
}

processImport();
