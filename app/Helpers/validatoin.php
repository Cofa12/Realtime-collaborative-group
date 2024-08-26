<?php

namespace App\Helpers;

trait validatoin
{
    //
    public function validationProcess($credentials){
        $inputs = [];
        foreach ($credentials as $item =>$value) {
            $inputs [$item] = $value;
        }

        foreach ($inputs as $item => $value) {

            if(is_numeric($value)){
                $inputs[$item] = "required|Integer";
            }else if (is_string($value)){
                $inputs[$item] = "required|string|max:35";
            }else {
                $inputs[$item] = "required|Image";
            }
        }

        return $inputs;
    }
}
