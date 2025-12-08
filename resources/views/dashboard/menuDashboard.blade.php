@extends('layouts.app')

@section('content')

<div class="container py-5">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-5">
        <h1 class="mb-3 mb-sm-0">Food Menu Dashboard</h1>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addMenuModal">
            + Add New Menu Item
        </button>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Menu Items List -->
    <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
        
        @forelse($meals as $meal)
        <div class="col">
            <div class="card shadow-sm h-100">
                <img src="{{ $meal->image_url ? asset("storage/" . $meal->image_url) : 'blank' }}" 
                    class="card-img-top" 
                    alt="{{ $meal->name }}" 
                    style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h2 class="card-title h4">{{ $meal->name }}</h2>
                        <span class="badge {{ $meal->isAvailable ? 'bg-success' : 'bg-danger' }}">
                            {{ $meal->isAvailable ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                    <span class="badge bg-secondary mb-2" style="width: fit-content;">{{ $meal->category }}</span>
                    <p class="card-text text-muted">{{ $meal->short_description }}</p>
                    <p class="h5 text-dark mb-3">{{ $meal->formatted_price }}</p>
                    
                    <div class="mt-auto d-grid gap-2 d-sm-flex">
                        <button type="button" 
                            class="btn btn-warning flex-sm-fill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editMenuModal"
                            data-meal-id="{{ $meal->id }}"
                            data-meal-name="{{ $meal->name }}"
                            data-meal-description="{{ $meal->description }}"
                            data-meal-price="{{ $meal->price }}"
                            data-meal-category="{{ $meal->category }}"
                            data-meal-image="{{ $meal->image_url }}"
                            data-meal-available="{{ $meal->isAvailable ? '1' : '0' }}">
                        Edit
                    </button>
                        <form action="{{ route('meals.destroy', $meal->id) }}" method="POST" class="flex-sm-fill" onsubmit="return confirm('Are you sure you want to delete this menu item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Delete</button>
                        </form>
                    </div>
                    <button type="button" 
                        class="btn btn-dark w-100 mt-2 btn-manage-options" 
                        data-bs-toggle="modal" 
                        data-bs-target="#customizationModal"
                        data-meal-id="{{ $meal->id }}"
                        data-meal-name="{{ $meal->name }}">
                        Manage Customizations
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h4>No menu items yet</h4>
                <p>Click "Add New Menu Item" to create your first menu item.</p>
            </div>
        </div>
        @endforelse

    </div>

</div> 


<!-- Modals -->

<!-- Add Menu Item Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('meals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuModalLabel">Add New Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add-name" class="form-label">Menu Name</label>
                        <input type="text" name="name" id="add-name" class="form-control" placeholder="e.g., Nasi Goreng" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-description" class="form-label">Description</label>
                        <textarea name="description" id="add-description" rows="3" class="form-control" placeholder="e.g., Nasi goreng spesial dengan telur..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="add-price" class="form-label">Price (Rp)</label>
                        <input type="number" name="price" id="add-price" step="0.01" class="form-control" placeholder="e.g., 15000" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-category" class="form-label">Category</label>
                        <select name="category" id="add-category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="MEAL">Makanan</option>
                            <option value="SNACK">Snack</option>
                            <option value="DRINK">Minuman</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add-image" class="form-label">Image</label>
                        <input type="file" name="image" id="add-image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="isAvailable" class="form-check-input" id="add-available" checked>
                        <label class="form-check-label" for="add-available">Available</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Menu Item Modal -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editMealForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuModalLabel">Edit Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Menu Name</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <textarea name="description" id="edit-description" rows="3" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-price" class="form-label">Price (Rp)</label>
                        <input type="number" name="price" id="edit-price" step="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-category" class="form-label">Category</label>
                        <select name="category" id="edit-category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="MEAL">Makanan</option>
                            <option value="SNACK">Snack</option>
                            <option value="DRINK">Minuman</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div id="edit-current-image" class="mb-2"></div>
                        <label for="edit-image" class="form-label">Change Image (optional)</label>
                        <input type="file" name="image" id="edit-image" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="isAvailable" class="form-check-input" id="edit-available">
                        <label class="form-check-label" for="edit-available">Available</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manage Customizations Modal -->
<div class="modal fade" id="customizationModal" tabindex="-1" aria-labelledby="customizationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customizationModalLabel">
                    Customizations for "<span id="modal-meal-name"></span>"
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <!-- Add New Option Group Form -->
                <form id="addGroupForm" class="mb-4 p-3 bg-light rounded border">
                    @csrf
                    <h4 class="h5 mb-3">Add New Option Group</h4>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-5">
                            <label for="group-name" class="form-label">Group Name</label>
                            <input type="text" name="name" id="group-name" class="form-control" placeholder="e.g., Size, Add-ons" required>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_multiple" id="group-is-multiple">
                                <label class="form-check-label" for="group-is-multiple">
                                    Allow Multiple
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_required" id="group-is-required">
                                <label class="form-check-label" for="group-is-required">
                                    Required
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-success w-100">Create Group</button>
                        </div>
                    </div>
                    <div class="form-text mt-2">Create a group (like "Size") before adding options (like "Small", "Medium").</div>
                </form>

                <!-- Existing Option Groups -->
                <div id="option-groups-container">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading options...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Edit meal modal population
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editMenuModal');
    
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const mealId = button.getAttribute('data-meal-id');
        const mealName = button.getAttribute('data-meal-name');
        const mealDescription = button.getAttribute('data-meal-description');
        const mealPrice = button.getAttribute('data-meal-price');
        const mealCategory = button.getAttribute('data-meal-category');
        const mealImage = button.getAttribute('data-meal-image');
        const mealAvailable = button.getAttribute('data-meal-available');
        
        const form = document.getElementById('editMealForm');
        form.action = '/meals/' + mealId;
        
        document.getElementById('edit-name').value = mealName;
        document.getElementById('edit-description').value = mealDescription;
        document.getElementById('edit-price').value = mealPrice;
        document.getElementById('edit-category').value = mealCategory;
        document.getElementById('edit-available').checked = mealAvailable === '1';
        
        const imageContainer = document.getElementById('edit-current-image');
        if (mealImage) {
            imageContainer.innerHTML = '<img src="/storage/' + mealImage + '" alt="Current image" class="img-thumbnail" style="max-height: 150px;">';
        } else {
            imageContainer.innerHTML = '<p class="text-muted">No image uploaded</p>';
        }
    });
});

// Customization modal
let currentMealId = null;

document.addEventListener('DOMContentLoaded', function() {
    const customModal = document.getElementById('customizationModal');
    
    customModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        currentMealId = button.getAttribute('data-meal-id');
        const mealName = button.getAttribute('data-meal-name');
        
        document.getElementById('modal-meal-name').textContent = mealName;
        
        // Load options for this meal
        loadMealOptions(currentMealId);
    });
    
    // Handle add group form submission
    document.getElementById('addGroupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(`/meals/${currentMealId}/options/groups`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            this.reset();
            loadMealOptions(currentMealId);
            showToast('Group created successfully!', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to create group', 'error');
        });
    });
});

function loadMealOptions(mealId) {
    const container = document.getElementById('option-groups-container');
    container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
    
    fetch(`/meals/${mealId}/options`)
        .then(response => response.json())
        .then(data => {
            if (data.optionGroups.length === 0) {
                container.innerHTML = '<div class="alert alert-info text-center">No option groups yet. Create one above!</div>';
                return;
            }
            
            let html = '<div class="d-flex flex-column gap-4">';
            
            data.optionGroups.forEach(group => {
                html += renderOptionGroup(group);
            });
            
            html += '</div>';
            container.innerHTML = html;
            
            // Attach event listeners
            attachOptionEventListeners();
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="alert alert-danger">Failed to load options</div>';
        });
}

function renderOptionGroup(group) {
    let html = `
        <div class="border rounded p-3" data-group-id="${group.id}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="h5 mb-1">${group.name}</h4>
                    <small class="text-muted">
                        ${group.is_multiple ? '✓ Multiple' : '○ Single'} | 
                        ${group.is_required ? 'Required' : 'Optional'}
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-link btn-sm p-0 text-danger delete-group" data-group-id="${group.id}">Delete Group</button>
                </div>
            </div>

            <!-- Option Values -->
            <div class="list-group mb-3">
    `;
    
    if (group.values && group.values.length > 0) {
        group.values.forEach(value => {
            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>${value.name}</div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="fw-bold">+Rp ${Number(value.price).toLocaleString('id-ID')}</div>
                        <button class="btn btn-link btn-sm p-0 text-danger delete-value" data-value-id="${value.id}">Delete</button>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<div class="list-group-item text-muted text-center">No options yet</div>';
    }
    
    html += `
            </div>

            <!-- Add Value Form -->
            <form class="add-value-form p-3 bg-light rounded border" data-group-id="${group.id}">
                <h5 class="h6 mb-2">Add New Option to "${group.name}"</h5>
                <div class="row g-3 align-items-end">
                    <div class="col-sm">
                        <label class="form-label">Option Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Large" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Price (Rp)</label>
                        <input type="number" name="price" step="0.01" class="form-control" placeholder="0" required>
                    </div>
                    <div class="col-sm-auto">
                        <button type="submit" class="btn btn-primary w-100">Add</button>
                    </div>
                </div>
            </form>
        </div>
    `;
    
    return html;
}

function attachOptionEventListeners() {
    // Delete group buttons
    document.querySelectorAll('.delete-group').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Delete this option group? All its values will also be deleted.')) return;
            
            const groupId = this.getAttribute('data-group-id');
            
            fetch(`/options/groups/${groupId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
            .then(() => {
                loadMealOptions(currentMealId);
                showToast('Group deleted successfully!', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to delete group', 'error');
            });
        });
    });
    
    // Delete value buttons
    document.querySelectorAll('.delete-value').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Delete this option?')) return;
            
            const valueId = this.getAttribute('data-value-id');
            
            fetch(`/options/values/${valueId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
            .then(() => {
                loadMealOptions(currentMealId);
                showToast('Option deleted successfully!', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to delete option', 'error');
            });
        });
    });
    
    // Add value forms
    document.querySelectorAll('.add-value-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const groupId = this.getAttribute('data-group-id');
            const formData = new FormData(this);
            
            fetch(`/options/groups/${groupId}/values`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(() => {
                this.reset();
                loadMealOptions(currentMealId);
                showToast('Option added successfully!', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to add option', 'error');
            });
        });
    });
}

function showToast(message, type) {
    alert(message); // Simple toast notification
}
</script>

@endsection