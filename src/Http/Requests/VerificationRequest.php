<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed token
 */
class VerificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string|size:' . config('mobile_verifier.token_length', 5),
        ];
    }
}
