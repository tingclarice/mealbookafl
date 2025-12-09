<section>
    <form method="post" action="{{ route('shop.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Shop Image --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small text-muted text-uppercase">Shop Logo / Image</label>

            @if($activeOwnedShop?->profileImage)
                <div class="mt-2 mb-2">
                    <img src="{{ asset('storage/' . $activeOwnedShop->profileImage) }}" 
                         alt="Current Shop Image" 
                         class="w-20 h-20 object-cover rounded-md border border-gray-300">
                </div>
            @endif

            <input type="file" class="form-control" name="profileImage" accept="image/*">
            @error('profileImage')
                <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Shop Name --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small text-muted text-uppercase">Shop Name</label>
            <input type="text" class="form-control" name="name" 
                   value="{{ old('name', $activeOwnedShop?->name) }}" placeholder="Enter shop name" required>
            @error('name')
                <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small text-muted text-uppercase">Phone Number</label>
            <input type="text" class="form-control" name="phone" 
                   value="{{ old('phone', $activeOwnedShop?->phone) }}" placeholder="08xxxxx" required>
            @error('phone')
                <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Address --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small text-muted text-uppercase">Address</label>
            <textarea class="form-control" rows="2" name="address" placeholder="Enter shop address" required>{{ old('address', $activeOwnedShop?->address) }}</textarea>
            @error('address')
                <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label class="form-label fw-semibold small text-muted text-uppercase">Description</label>
            <textarea class="form-control" rows="3" name="description" placeholder="Tell us about your shop..." required>{{ old('description', $activeOwnedShop?->description) }}</textarea>
            @error('description')
                <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Shop Status --}}
        <div class="mb-4">
            <label class="form-label fw-semibold small text-muted text-uppercase">Shop Status</label>
            <select class="form-control" name="status">
                <option value="OPEN" {{ old('status', $activeOwnedShop?->status) === 'OPEN' ? 'selected' : '' }}>Open</option>
                <option value="CLOSED" {{ old('status', $activeOwnedShop?->status) === 'CLOSED' ? 'selected' : '' }}>Closed</option>
            </select>
            @error('status')
                <p class="text-danger small mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="d-grid">
            <button type="submit" class="btn text-white fw-bold py-2" style="background-color: #F97352; border-radius: 8px;">
                Save Shop Info
            </button>
        </div>

        {{-- Success Message --}}
        @if (session('status') === 'shop-updated')
            <p class="text-sm text-gray-600 mt-2">{{ __('Saved.') }}</p>
        @endif
    </form>
</section>
