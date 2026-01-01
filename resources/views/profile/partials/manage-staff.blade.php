
{{-- Add Staff Form --}}
<form action="{{ route('shops.staff.add', $activeOwnedShop->id) }}" method="POST"
    class="mb-5">
    @csrf
    <label class="form-label fw-semibold small text-muted text-uppercase">Add New
        Staff</label>
    <div class="input-group">
        <input type="email" class="form-control" name="email"
            placeholder="Enter staff email address" required>
        <button class="btn text-white fw-bold px-4" type="submit"
            style="background-color: #F97352;">Add</button>
    </div>
</form>

{{-- Staff List Table --}}
<div class="table-responsive">
    <table class="table align-middle">
        <thead class="bg-light">
            <tr>
                <th class="border-0 small text-uppercase text-muted fw-bold ps-3">
                    Name</th>
                <th class="border-0 small text-uppercase text-muted fw-bold">Email
                </th>
                <th class="border-0 small text-uppercase text-muted fw-bold">Phone
                </th>
                <th
                    class="border-0 small text-uppercase text-muted fw-bold text-center">
                    Notification</th>
                <th
                    class="border-0 small text-uppercase text-muted fw-bold text-end pe-3">
                    Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $allStaff = $activeOwnedShop->users()->wherePivotIn('role', ['OWNER', 'STAFF'])->get()->sortBy(function($user) {
                    return $user->pivot->role === 'OWNER' ? 0 : 1;
                });
            @endphp
            @foreach ($allStaff as $staff)
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center">
                            @if ($staff->avatar)
                                <img src="{{ $staff->avatar }}"
                                    class="rounded-circle me-2" width="32"
                                    height="32" alt="">
                            @else
                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary text-white"
                                    style="width: 32px; height: 32px; font-size: 12px;">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="fw-semibold">
                                {{ $staff->name }}
                                
                            </span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $staff->email }}</td>
                    <td class="text-muted">{{ $staff->phone ?? '-' }}</td>
                    <td class="text-center">
                        <div
                            class="form-check form-switch d-flex justify-content-center">
                            <input class="form-check-input staff-notification-switch"
                                type="checkbox"
                                data-shop-id="{{ $activeOwnedShop->id }}"
                                data-user-id="{{ $staff->id }}"
                                {{ $staff->staff_notification ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td class="text-end pe-3">
                        @if($staff->pivot->role === 'OWNER')
                             <button class="btn btn-link text-muted p-0" disabled style="opacity: 0.5; cursor: not-allowed;">
                                <i class="bi bi-trash"></i>
                            </button>
                        @else
                            <button class="btn btn-link text-danger p-0 delete-staff-btn"
                                data-shop-id="{{ $activeOwnedShop->id }}"
                                data-user-id="{{ $staff->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            @if ($activeOwnedShop->users()->wherePivot('role', 'STAFF')->count() == 0)
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No staff
                        members enrolled.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Notification Toggle
        document.querySelectorAll('.staff-notification-switch').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const shopId = this.dataset.shopId;
                const userId = this.dataset.userId;

                fetch(`/shops/${shopId}/staff/${userId}/notification`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            alert(data.message || 'Failed to update notification');
                            this.checked = !this.checked;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        this.checked = !this.checked;
                    });
            });
        });

        // Delete Staff
        document.querySelectorAll('.delete-staff-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Are you sure you want to remove this staff?')) return;

                const shopId = this.dataset.shopId;
                const userId = this.dataset.userId;
                const row = this.closest('tr');

                fetch(`/shops/${shopId}/staff/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            row.remove();
                            location.reload(); // To update the empty state if needed
                        } else {
                            alert('Failed to remove staff');
                        }
                    })
                    .catch(err => console.error(err));
            });
        });
    });
</script>
