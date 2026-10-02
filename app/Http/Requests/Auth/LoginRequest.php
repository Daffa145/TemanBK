<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => is_string($this->email) ? Str::lower(trim($this->email)) : $this->email,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:50'],
            'password' => ['required', 'string', 'min:6', 'max:15'],
            'hp_field' => ['nullable', 'max:0'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), 60);
            RateLimiter::hit($this->ipThrottleKey(), 60);

            Log::warning('Percobaan login gagal (Kredensial Tidak Valid)', [
                'email' => $this->string('email'),
                'ip' => $this->ip(),
                'user_agent' => $this->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // 1. IP Global Rate Limit: Max 10 failed attempts across accounts from same IP
        if (RateLimiter::tooManyAttempts($this->ipThrottleKey(), 10)) {
            event(new Lockout($this));

            Log::alert('Login Lockout (IP Brute Force Suspect)', [
                'ip' => $this->ip(),
                'user_agent' => $this->userAgent(),
            ]);

            $seconds = RateLimiter::availableIn($this->ipThrottleKey());

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login dari IP Anda. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // 2. Specific Account Lockout: Max 5 failed attempts per email+IP
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            event(new Lockout($this));

            Log::alert('Login Lockout (Account Lockout)', [
                'email' => $this->string('email'),
                'ip' => $this->ip(),
            ]);

            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
    }

    /**
     * Get the rate limiting throttle key for specific email and IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Get the global IP rate limiting throttle key.
     */
    public function ipThrottleKey(): string
    {
        return 'ip_login|'.$this->ip();
    }
}
