<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:25'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:50',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'nis' => ['nullable', 'string', 'max:7', Rule::unique(User::class)->ignore($this->user()->id)],
            'kelas' => ['nullable', 'string', 'max:10'],
            'jurusan' => ['nullable', 'string', 'max:7'],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique(User::class)->ignore($this->user()->id)],
            'spesialisasi_kategori' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:14'],
        ];
    }
}
