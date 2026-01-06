<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $shop = $this->route('shop');
        
        if (!$shop) {
            return false;
        }

        // Check if the logged-in user is the OWNER of this specific shop
        return $this->user() && $this->user()->isOwnerOf($shop->id);

    }

    public function rules(): array
    {
        return [
            
        ];
    }
}
