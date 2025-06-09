<?php

namespace App\Traits;

use App\Notifications\VerifyPhoneNotification;
use Illuminate\Support\Facades\Log;

trait VerifiesPhone
{
    public function hasVerifiedPhone(): bool
    {
        return ! is_null($this->phone_verified_at);
    }

    public function sendPhoneVerificationNotification(): void
    {
        $this->forceFill([
            'verification_code' => random_int(100000, 999999),
            'verification_code_attempts' => config('mobile.vonage.max_attempts'),
            'verification_code_sent_at' => now(),
        ])->save();
        
        try {
            $this->notify(new VerifyPhoneNotification);
        } catch (\Exception $e) {
            Log::error('Failed to send phone verification notification: ' . $e->getMessage());
        }
    }

    public function markPhoneAsVerified(): void
    {
        $this->forceFill([
            'verification_code' => NULL,
            'verification_code_attempts' => 0,
            'verification_code_sent_at' => NULL,
            'phone_verified_at' => now(),
        ])->save();
    }
}
