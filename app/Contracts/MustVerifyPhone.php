<?php

namespace App\Contracts;


interface MustVerifyPhone
{
    public function hasVerifiedPhone(): bool;

    public function markPhoneAsVerified(): void;

    public function sendPhoneVerificationNotification(): void;
}