<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{

    protected $table = 'csv_data';

    protected $fillable = ['csv_filename', 'csv_header', 'csv_header_position', 'csv_path', 'csv_status', 'user_id'];

}
