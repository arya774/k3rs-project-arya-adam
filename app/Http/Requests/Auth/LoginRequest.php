<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Lockout;

class LoginRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * ✅ VALIDASI (PAKAI NIP)
     */
    public function rules(): array
    {
        return [
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * ❌ NONAKTIFKAN AUTH DEFAULT
     * (karena password bebas)
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // ❌ TIDAK PAKAI Auth::attempt lagi
        // login akan di-handle di controller

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * ✅ LIMIT LOGIN (biar tidak spam)
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nip' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * ✅ KEY RATE LIMIT (pakai NIP)
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->input('nip')) . '|' . $this->ip()
        );
    }
}