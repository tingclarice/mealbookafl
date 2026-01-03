<section class="space-y-6">
    {{-- SHOP DELETION SECTION --}}
    @if(Auth::user()->isOwner() && Auth::user()->shops->isNotEmpty())
        @php
            $userShop = Auth::user()->shops->first();
        @endphp

        <div class="mb-5 p-4 border rounded bg-white shadow-sm">
            <h4 class="text-lg font-medium fw-bold mb-3">Delete Shop: {{ $userShop->name }}</h4>

            <div class="alert alert-warning border-0 bg-danger bg-opacity-10 text-danger mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Warning:</strong> Only shops with NO active orders (Pending, Confirmed, Ready) can be deleted.
                All meals and shop data will be permanently removed.
            </div>

            <div class="mb-3">
                <label for="confirm_shop_text" class="form-label fw-semibold text-muted small">
                    Type <span class="text-danger">"delete my shop"</span> to confirm
                </label>
                <input type="text" id="confirm_shop_text" class="form-control border-danger-subtle"
                    placeholder="delete my shop" autocomplete="off">
            </div>

            <button type="button" class="btn btn-danger fw-semibold px-4"
                onclick="return confirmDeleteShop('{{ $userShop->id }}')">
                Permanently Delete Shop
            </button>

            {{-- Hidden Form for Shop Deletion --}}
            <form id="delete-shop-form-{{ $userShop->id }}" action="{{ route('shops.destroy', $userShop->id) }}"
                method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
        <hr class="my-5 opacity-25">
    @endif


    {{-- ACCOUNT DELETION SECTION --}}
    <div class="p-4 border rounded bg-white shadow-sm">
        <header class="mb-4">
            <h4 class="text-lg font-medium text-dark fw-bold">Delete Account</h4>
            <p class="mt-1 text-sm text-muted">
                {{ ('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
            </p>
        </header>

        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="mb-3">
                <label for="confirm_text" class="form-label fw-semibold text-muted small">
                    Type <span class="text-danger">"delete my account"</span> to confirm
                </label>
                <x-text-input id="confirm_text" name="confirm_text" type="text" class="form-control" required />
                <x-input-error class="mt-2" :messages="$errors->get('confirm_text')" />
            </div>

            <button type="submit" class="btn btn-danger fw-semibold px-4" onclick="return confirmDeleteAccount()">
                Delete My Account
            </button>
        </form>
    </div>

    <script>
        // Validation for Shop Deletion
        function confirmDeleteShop(shopId) {
            const input = document.getElementById('confirm_shop_text').value.trim().toLowerCase();
            const targetPhrase = 'delete my shop';

            if (input !== targetPhrase) {
                alert('Please type "' + targetPhrase + '" to confirm shop deletion.');
                return false;
            }

            if (confirm('Are you absolutely sure? All shop data, meals, and settings will be lost forever.')) {
                document.getElementById('delete-shop-form-' + shopId).submit();
            }
        }

        // Validation for Account Deletion
        function confirmDeleteAccount() {
            const input = document.getElementById('confirm_text').value.trim().toLowerCase();
            const targetPhrase = 'delete my account';

            if (input !== targetPhrase) {
                alert('Please type "' + targetPhrase + '" to confirm account deletion.');
                return false;
            }
            return confirm('Final Warning: Are you sure you want to permanently delete your account?');
        }
    </script>
</section>