<?php

namespace App\Services;

use DateTime;

class AgeChecker
{
    public function isAgeValid($dob, $minAge)
    {
        $dob = DateTime::createFromFormat('m/d/Y', $dob);

        $age = $dob->diff(new DateTime())->y;

        return $age > $minAge;
    }
}
