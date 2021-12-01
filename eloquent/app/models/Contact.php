<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $fillable = [
    'name',
    'birthday',
    'phone',
    'address',
    'credit_card',
    'email',
    'last_digits',
    'franchise'
    ];

    private function isNameValid($name) {
        if (preg_match('/[a-zA-Z-]/', $name) > 0)
            return true;
        else 
            return false;
    }

    private function isDateValid($date) {
        if (preg_match('/^(([12]\d{3})\/((0[1-9]|1[0-2])|\b(january|January)|\b(february|February)|\b(march|March)|\b(april|April)|\b(may|May)|\b(june|June)|\b(july|July)|\b(august|August)|\b(september|September)|\b(octuber|Octuber)|\b(november|November)|\b(december|December))\/(0[1-9]|[12]\d|3[01]))$/', $date) > 0)
            return true;
        else 
            return false;
    }

    private function isPhoneValid($phone) {
        if (preg_match('/^([(]{0,1}[+]\d{1,2}[)]{0,1}[\s]\d{0,3}[\s\-]\d{0,3}[\s\-]\d{0,2}[\s\-]\d{0,2})$/', $phone) > 0) 
            return true;
        else
            return false;
    }

    public function getCreditCardBrand($creditCard) {
        if (preg_match('/^(?:4[0-9]{12}(?:[0-9]{3}))$/',$creditCard) > 0) 
            $brand = "visa";
        elseif (preg_match('/^(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}?$/', $creditCard) > 0)
            $brand = "mastercard";
        elseif (preg_match('/^3[47][0-9]{13}$/', $creditCard) > 0)
            $brand = "american express";
        elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/', $creditCard) > 0)
            $brand = "diners club";
        elseif (preg_match('/^6(?:011|5[0-9]{2})[0-9]{12}$/', $creditCard) > 0)
            $brand = "discover";
        elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $creditCard) > 0)
            $brand = "jcb";
        else
            return false;
        
        return $brand;
    }

    public function encryptCreditCard($creditCard) {
        return md5($creditCard);
    }

    public function getLastCreditCardNumber($creditCard) {
        return substr($creditCard, -4);
    }

    private function isValidEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }

    private function isAddressValid($address) {
        if (empty($address))
            return false;
        else
            return true;
    }

    public function getError() {
        $errors = [];
        if (!$this->isNameValid($this->name))
             array_push($errors, "Name is not valid");
        if (!$this->isDateValid($this->birthday))
            array_push($errors, "Date is not valid");
        if (!$this->isPhoneValid($this->phone))
            array_push($errors, "Phone is not valid");
        if (!$this->isValidEmail($this->email))
            array_push($errors, "Email is not valid");
        if (!$this->isAddressValid($this->address))
            array_push($errors, "Address is empty");
        return $errors;
    }

    public function isValid() {
        return count($this->getError()) == 0;
    }
}
