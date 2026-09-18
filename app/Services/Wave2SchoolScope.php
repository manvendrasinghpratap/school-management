<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Wave2SchoolScope
{
    public function schoolId(): int
    {
        $schoolId = (int) optional(Auth::user())->school_id;

        if ($schoolId < 1) {
            throw new HttpException(403, 'No school is assigned to the current user.');
        }

        return $schoolId;
    }
}
