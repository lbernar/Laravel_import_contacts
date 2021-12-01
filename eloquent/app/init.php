<?php
use Illuminate\Database\Capsule\Manager as Capsule;

require_once 'vendor/autoload.php';

$capsule = new Capsule();

$capsule->addConnection([
    "driver" => "mysql",
    "port" => "33061",
    "host" =>"127.0.0.1",
    "database" => "mydb",
    "username" => "user",
    "password" => "Atd9qecg4DVTrp7Y"
 ]);
 
 //Make this Capsule instance available globally.
 $capsule->setAsGlobal();
 
 // Setup the Eloquent ORM.
 $capsule->bootEloquent();
 $capsule->bootEloquent();