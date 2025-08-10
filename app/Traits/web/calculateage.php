<?php
namespace App\Traits\web;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
trait calculateage
{
   
        function calculateAge($dateOfBirth)
        {
            $now = Carbon::now();
            $dob = Carbon::parse($dateOfBirth);
            $age = $dob->diffInYears($now) . ' Y';
            if ($age == '0 Y') {
                $age = $dob->diffInMonths($now) . ' M';

                if ($age == '0 M') {
                    $age = $dob->diffInDays($now) . ' D';
                }
            }
            return $age;
        }
    
}