@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">User Role Management</h2>
            <p class="text-muted">Manage user accounts and their roles.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    {{-- Table Head --}}
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4" style="min-width: 250px;">Name</th>
                            <th scope="col" style="min-width: 200px;">Email</th>
                            <th scope="col" style="min-width: 100px;">Role</th>
                            <th scope="col" class="text-end pe-4" style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    
                    {{-- Table Body --}}
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                {{-- Name & Avatar Column --}}
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if ($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="45" height="45" style="object-fit: cover;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            {{-- Fallback div, initially hidden --}}
                                            <div class="rounded-circle bg-secondary text-white d-none align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: 500; font-size: 1.1rem;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @else
                                            {{-- Placeholder with initials --}}
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: 500; font-size: 1.1rem;">
                                                {{-- Get first letter of first name --}}
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <div class="text-muted small">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email Column --}}
                                <td class="align-middle">
                                    <p class="mb-0">{{ $user->email }}</p>
                                </td>

                                {{-- Role Column --}}
                                <td>
                                    <span class="badge rounded-pill fs-6 {{ $user->role == 'ADMIN' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                {{-- Action Column --}}
                                <td class="text-end pe-4">
                                    {{-- MODIFIED: This button now triggers the modal --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-role="{{ $user->role }}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            {{-- State for when no users are found --}}
                            <tr>
                                <td colspan="4" class="text-center text-muted p-5">
                                    <p class="mb-0 fs-5">No users found.</p>
                                    <small>There are no user accounts to display at this time.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ADDED: Edit User Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Form will be populated by JS and action URL set dynamically --}}
            <form id="editUserForm" method="POST" action="">
                @csrf
                @method('PATCH') {{-- Or PUT, depending on your route definition --}}

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modalUserName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="modalUserName" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="modalUserEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="modalUserEmail" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalUserRole" class="form-label">Role</label>
                        <select class="form-select" id="modalUserRole" name="role" required>
                            <option value="USER">USER</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- ADDED: JavaScript to populate the modal --}}
@push('scripts')
<script>
    // Wait for the document to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        
        // Get the modal element
        var editUserModal = document.getElementById('editUserModal');
        
        // Add event listener for when the modal is about to be shown
        editUserModal.addEventListener('show.bs.modal', function (event) {
            // Get the button that triggered the modal
            var button = event.relatedTarget;
            
            // Extract info from data-* attributes
            var userId = button.getAttribute('data-user-id');
            var userName = button.getAttribute('data-user-name');
            var userEmail = button.getAttribute('data-user-email');
            var userRole = button.getAttribute('data-user-role');
            
            // Get the form and elements inside the modal
            var modalForm = document.getElementById('editUserForm');
            var modalUserName = document.getElementById('modalUserName');
            var modalUserEmail = document.getElementById('modalUserEmail');
            var modalUserRole = document.getElementById('modalUserRole');
            
            // Set the form action URL
            // This assumes your update route is something like 'admin/users/{id}'
            modalForm.action = '{{ url('admin/users') }}/' + userId;
            
            // Populate the modal's form fields
            modalUserName.value = userName;
            modalUserEmail.value = userEmail;
            modalUserRole.value = userRole;
        });
    });
</script>
@endpush