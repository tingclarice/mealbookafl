<?php

namespace App\Http\Requests;


class ShopUpdateRequest extends ShopOwnerRequest
{
    public function authorize(): bool
    {
        // This runs the ownership check from ShopOwnerRequest first
        $isOwner = parent::authorize(); 

        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'profileImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}