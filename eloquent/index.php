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

function csvToArray($fname) {
    if (!($fp = fopen($fname, 'r'))) {
        die("Can't open file...");
    }
    $array = array();
        while ($row = fgetcsv($fp)) {
        array_push($array, $row);
    }
    fclose($fp); 
    return $array;
}

function processImport()
{
    $contacts = [];
    $emails = [];
    $errors_validation = [];
    $data = CsvData::where('csv_status', 'On Hold')->first();
    $csv_header_position = json_decode($data->csv_header_position, true);
    $csv_file = $data->csv_path.'/'.$data->csv_filename;
    $csv_data = csvToArray($csv_file);
    $dbFields = [
        'name',
        'birthday',
        'phone',
        'address',
        'credit_card',
        'email',
        'last_digits',
        'franchise',
        'user_id'
    ];

    foreach ($csv_data as $row) {
        $contact = new Contact();       
        $row = array_combine($csv_header_position, $row);
        ksort($row);
        $row = array_pad($row, count($dbFields), '');
        $row = array_combine($dbFields, $row); 
        
        foreach ($row as $index => $field) { 
           $contact->$index = $field;
        }
     
        $contact->user_id = $data->user_id;
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

        if(array_key_exists($contact->email, $emails)) {
            if(in_array($contact->name, $emails[$contact->email])) {
                array_push($errors_duplicated, $contact);
                continue;
            } else {
                array_push($emails, $contact->name);
            }
            
        } else {
            $emails[$contact->email] = [$contact->name];
        }
        array_push($contacts, $contact);
    }
    
    foreach($contacts as $contact) {
        $contact->save();
    }
    CsvData::where('id', $data->id)->update(array('csv_status' => 'Finished'));
}

processImport();
