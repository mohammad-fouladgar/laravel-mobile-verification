<?php

namespace Fouladgar\MobileVerifier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed token
 */
class VerificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $tokenLength = config('mobile_verifier.token_length', 5);

        return [
            'token' => 'required|string|size:'.$tokenLength,
        ];
    }
}
