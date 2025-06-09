<?php
namespace App\Services;

use App\Models\User;

class PhoneVerificationService
{
    protected $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    public function verifyPhone(string $code): array
    {
        if (!$this->user) {
            return ['success' => false, 'code' => 404, 'message' => 'User not found'];
        }

        if ($this->user->verification_code != $code) {
            $this->user->decrement('verification_code_attempts');
            if ($this->user->verification_code_attempts <= 0) {
                return ['success' => false, 'code' => 401, 'message' => 'max attempts reached, resend code.'];
            }
            return ['success' => false, 'code' => 401, 'message' => 'Invalid verification code'];
        }

        $sentAt = $this->user->verification_code_sent_at;
        
        if ($sentAt && $sentAt->addMinutes(config('mobile.vonage.time_limit'))->isPast()) {
            return ['success' => false, 'code' => 401, 'message' => 'Verification code expired'];
        }

        return ['success' => true];
    }
}