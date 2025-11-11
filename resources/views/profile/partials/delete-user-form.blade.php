<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please type "delete my account" to confirm.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')

        <div class="mb-3">
            <x-input-label for="confirm_text" :value="__('Type “delete my account”')" />
            <x-text-input id="confirm_text" name="confirm_text" type="text" class="mt-1 block w-full" required />
            <x-input-error class="mt-2" :messages="$errors->get('confirm_text')" />
        </div>

        <button type="submit"
            class="btn btn-danger bg-danger text-white fw-semibold text-uppercase shadow-sm px-4 py-2"
            onclick="return confirmDeleteText()">
            Delete Account
        </button>
    </form>

    <script>
        function confirmDeleteText() {
            const input = document.getElementById('confirm_text').value.trim().toLowerCase();
            if (input !== 'delete my account') {
                alert('Please type "delete my account" to confirm.');
                return false;
            }
            return confirm('Are you sure you want to permanently delete your account?');
        }
    </script>
</section>
