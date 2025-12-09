<section>
    <form method="post" action="{{ route('shop.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Shop Name --}}
        <div>
            <x-input-label for="name" :value="__('Shop Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $activeOwnedShop->name)" required autofocus autocomplete="shop-name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Profile Image Upload --}}
        <div>
            <x-input-label for="profileImage" :value="__('Shop Logo / Image')" />
            
            @if($activeOwnedShop->profileImage)
                <div class="mt-2 mb-2">
                    <img src="{{ asset('storage/' . $activeOwnedShop->profileImage) }}" alt="Current Shop Image" class="w-20 h-20 object-cover rounded-md border border-gray-300">
                </div>
            @endif

            <input id="profileImage" name="profileImage" type="file" class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100" 
                accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('profileImage')" />
        </div>

        {{-- Phone --}}
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $activeOwnedShop->phone)" required autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        {{-- Address --}}
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $activeOwnedShop->address)" required autocomplete="street-address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        {{-- Description --}}
        <div>
            <x-input-label for="description" :value="__('Description')" />
            {{-- Note: Standard Breeze doesn't have a textarea component, so we apply the same classes manually --}}
            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $activeOwnedShop->description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        {{-- Status (Optional: Only show if you want the user to be able to Open/Close their own shop) --}}
        <div>
            <x-input-label for="status" :value="__('Shop Status')" />
            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="OPEN" {{ old('status', $activeOwnedShop->status) === 'OPEN' ? 'selected' : '' }}>Open</option>
                <option value="CLOSED" {{ old('status', $activeOwnedShop->status) === 'CLOSED' ? 'selected' : '' }}>Closed</option>
                {{-- Note: Usually 'SUSPENDED' or 'PENDING' are admin-only controls, so we hide them here --}}
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('status')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Shop Info') }}</x-primary-button>

            @if (session('status') === 'shop-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>