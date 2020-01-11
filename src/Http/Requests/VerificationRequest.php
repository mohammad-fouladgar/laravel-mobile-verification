<?php

namespace Renter\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $tokenLength = config('mobile_verifier.token_length');

        return [
            'mobile' => 'required|string|exists:users,mobile',
            'token'  => 'required|string|size:' . $tokenLength
        ];
    }
}
