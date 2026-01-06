@extends('layouts.app')

@section('content')
<div style="background-color: #fff9f8; min-height: 100vh; padding: 3rem 0;">
    <div class="container">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold mb-1" style="color: #333;">Menu Dashboard</h1>
                <p class="text-muted">Manage your restaurant's offerings and availability</p>
            </div>
            <button type="button" class="btn btn-lg px-4 py-3 text-white fw-bold shadow-sm" 
                style="background-color: #F97352; border-radius: 15px; border: none;"
                data-bs-toggle="modal" data-bs-target="#addMenuModal">
                <i class="bi bi-plus-lg me-2"></i>Add New Item
            </button>
        </div>

        @if(session('success'))
            <div class="alert border-0 shadow-sm mb-4" style="background-color: #d1e7dd; color: #0f5132; border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            @forelse($meals as $meal)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 p-2" style="border-radius: 1.5rem;">
                        <div class="position-relative">
                            <img src="{{ $meal->primary_image_url ? asset('storage/' . $meal->primary_image_url) : 'https://placehold.co/600x400?text=No+Image' }}"
                                class="card-img-top shadow-sm" alt="{{ $meal->name }}" 
                                style="height: 220px; object-fit: cover; border-radius: 1.2rem;">
                            
                            <span class="position-absolute top-0 end-0 m-3 badge rounded-pill px-3 py-2 shadow-sm" 
                                  style="background-color: {{ $meal->isAvailable ? '#fff1ee' : '#f8f9fa' }}; color: {{ $meal->isAvailable ? '#F97352' : '#6c757d' }};">
                                {{ $meal->isAvailable ? '● Available' : '○ Unavailable' }}
                            </span>
                        </div>

                        <div class="card-body d-flex flex-column pt-3">
                            <div class="mb-1">
                                <small class="text-uppercase fw-bold ls-1" style="color: #F97352; font-size: 0.75rem; letter-spacing: 1px;">
                                    {{ $meal->category }}
                                </small>
                                <h4 class="fw-bold mb-2" style="color: #333;">{{ $meal->name }}</h4>
                            </div>
                            
                            <p class="text-muted small mb-3 text-truncate-2" style="min-height: 40px;">
                                {{ $meal->short_description }}
                            </p>
                            
                            <h5 class="fw-bold mb-4" style="color: #F97352;">{{ $meal->formatted_price }}</h5>

                            <div class="mt-auto">
                                <div class="d-flex gap-2 mb-2">
                                    <button type="button" class="btn flex-grow-1 fw-bold py-2" 
                                        style="background-color: #fff1ee; color: #F97352; border: none; border-radius: 10px;"
                                        data-bs-toggle="modal" data-bs-target="#editMenuModal" 
                                        data-meal-id="{{ $meal->id }}"
                                        data-meal-name="{{ $meal->name }}" 
                                        data-meal-description="{{ $meal->description }}"
                                        data-meal-price="{{ $meal->price }}" 
                                        data-meal-category="{{ $meal->category }}"
                                        data-meal-available="{{ $meal->isAvailable ? '1' : '0' }}">
                                        Edit
                                    </button>
                                    
                                    <form action="{{ route('meals.destroy', $meal->id) }}" method="POST" class="flex-grow-1"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-light text-muted w-100 fw-bold py-2" 
                                            style="border-radius: 10px; border: 1px solid #eee;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                
                                <button type="button" class="btn w-100 fw-bold py-2 text-white shadow-sm" 
                                    style="background-color: #333; border-radius: 10px;"
                                    data-bs-toggle="modal" data-bs-target="#customizationModal" 
                                    data-meal-id="{{ $meal->id }}" data-meal-name="{{ $meal->name }}">
                                    <i class="bi bi-gear-fill me-2 small"></i>Manage Options
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white shadow-sm" style="border-radius: 2rem;">
                        <i class="bi bi-egg-fried" style="font-size: 3rem; color: #fff1ee;"></i>
                        <h4 class="mt-3 fw-bold">No menu items yet</h4>
                        <p class="text-muted">Start by adding your first delicious meal.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .modal-content { border: none; border-radius: 1.5rem; overflow: hidden; }
    .modal-header { border-bottom: 1px solid #fff1ee; padding: 1.5rem; }
    .form-control, .form-select { border-radius: 10px; padding: 0.75rem; border: 1px solid #eee; background-color: #fcfcfc; }
    .form-control:focus { border-color: #F97352; box-shadow: 0 0 0 0.25rem rgba(249, 115, 82, 0.1); }
    .ls-1 { letter-spacing: 0.5px; }
    .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<div class="modal fade" id="addMenuModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <form action="{{ route('meals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-white">
                    <h5 class="fw-bold mb-0">Add New Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Menu Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Nasi Goreng" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="MEAL">Makanan</option>
                                <option value="SNACK">Snack</option>
                                <option value="DRINK">Minuman</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Price (Rp)</label>
                        <input type="number" name="price" class="form-control" placeholder="15000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Describe this meal..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Upload Images</label>
                        <input type="file" name="images[]" id="add-images" class="form-control" accept="image/*" multiple>
                        <div id="add-image-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="isAvailable" class="form-check-input" id="add-available" checked>
                        <label class="form-check-label" for="add-available">Mark as Available</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn px-4 text-white fw-bold shadow-sm" style="background-color: #F97352; border-radius: 10px;">Save Menu Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="customizationModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div class="p-2">
                    <h4 class="fw-bold mb-1">Customizations</h4>
                    <p class="text-muted mb-0" id="modal-meal-name"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addGroupForm" class="p-4 mb-4" style="background-color: #fff9f8; border-radius: 1.2rem; border: 1px dashed #F97352;">
                    @csrf
                    <h6 class="fw-bold mb-3" style="color: #F97352;"><i class="bi bi-plus-circle me-2"></i>New Option Group</h6>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-5">
                            <input type="text" name="name" class="form-control" placeholder="Group Name (e.g. Extra Toppings)" required>
                        </div>
                        <div class="col-sm-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_multiple" id="group-is-multiple">
                                <label class="form-check-label small" for="group-is-multiple">Multiple Choice</label>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn text-white fw-bold px-4" style="background-color: #F97352; border-radius: 10px;">Create Group</button>
                        </div>
                    </div>
                </form>

                <div id="option-groups-container">
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMenuModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 1.5rem;">
            <form action="{{ route('meals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold px-2 pt-3">Add New Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Menu Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Nasi Goreng" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="MEAL">Makanan</option>
                                <option value="SNACK">Snack</option>
                                <option value="DRINK">Minuman</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Price (Rp)</label>
                        <input type="number" name="price" class="form-control" placeholder="15000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Describe this meal..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Upload Images</label>
                        <input type="file" name="images[]" id="add-images" class="form-control" accept="image/*" multiple>
                        <div id="add-image-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="isAvailable" class="form-check-input" id="add-available" checked>
                        <label class="form-check-label" for="add-available">Mark as Available</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn fw-bold text-muted" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn px-4 text-white fw-bold shadow-sm" style="background-color: #F97352; border-radius: 12px;">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editMenuModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 1.5rem;">
            <form id="editMealForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold px-2 pt-3">Edit Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Menu Name</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Category</label>
                            <select name="category" id="edit-category" class="form-select" required>
                                <option value="MEAL">Makanan</option>
                                <option value="SNACK">Snack</option>
                                <option value="DRINK">Minuman</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Price (Rp)</label>
                        <input type="number" name="price" id="edit-price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Description</label>
                        <textarea name="description" id="edit-description" rows="3" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Current Images</label>
                        <div id="edit-current-images" class="d-flex flex-wrap gap-2 mb-3 p-2 bg-light rounded"></div>
                        
                        <label class="form-label fw-bold small text-muted">Add More Images</label>
                        <input type="file" name="images[]" id="edit-images" class="form-control" accept="image/*" multiple>
                        <div id="edit-image-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="isAvailable" class="form-check-input" id="edit-available">
                        <label class="form-check-label" for="edit-available">Available</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn fw-bold text-muted" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn px-4 text-white fw-bold shadow-sm" style="background-color: #F97352; border-radius: 12px;">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="customizationModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 1.5rem;">
            <div class="modal-header border-0">
                <div class="p-2">
                    <h4 class="fw-bold mb-1">Manage Options</h4>
                    <p class="text-muted mb-0" id="modal-meal-name"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <form id="addGroupForm" class="p-4 mb-4 shadow-sm" style="background-color: #fff9f8; border-radius: 1.2rem; border: 1px dashed #F97352;">
                    @csrf
                    <h6 class="fw-bold mb-3" style="color: #F97352;"><i class="bi bi-plus-circle me-2"></i>New Option Group</h6>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-5">
                            <input type="text" name="name" class="form-control" placeholder="Group Name (e.g. Extra Toppings)" required>
                        </div>
                        <div class="col-sm-auto">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_multiple" id="group-is-multiple">
                                <label class="form-check-label small" for="group-is-multiple">Multiple Choice</label>
                            </div>
                        </div>
                        <div class="col-sm-auto ms-auto">
                            <button type="submit" class="btn text-white fw-bold px-4" style="background-color: #F97352; border-radius: 10px;">Create Group</button>
                        </div>
                    </div>
                </form>

                <div id="option-groups-container">
                    </div>
            </div>
        </div>
    </div>
</div>



    <script>
        // Image preview for add modal
        document.addEventListener('DOMContentLoaded', function () {
            const addImagesInput = document.getElementById('add-images');
            const addPreviewContainer = document.getElementById('add-image-preview');

            if (addImagesInput) {
                addImagesInput.addEventListener('change', function (e) {
                    addPreviewContainer.innerHTML = '';
                    const files = Array.from(e.target.files);

                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const div = document.createElement('div');
                            div.className = 'position-relative';
                            div.innerHTML = `
                                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                ${index === 0 ? '<span class="badge bg-primary position-absolute top-0 start-0 m-1">Primary</span>' : ''}
                                            `;
                            addPreviewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });

        // Image preview for edit modal
        document.addEventListener('DOMContentLoaded', function () {
            const editImagesInput = document.getElementById('edit-images');
            const editPreviewContainer = document.getElementById('edit-image-preview');

            if (editImagesInput) {
                editImagesInput.addEventListener('change', function (e) {
                    editPreviewContainer.innerHTML = '';
                    const files = Array.from(e.target.files);

                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const div = document.createElement('div');
                            div.className = 'position-relative';
                            div.innerHTML = `
                                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                            `;
                            editPreviewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });

        // Edit meal modal population
        document.addEventListener('DOMContentLoaded', function () {
            const editModal = document.getElementById('editMenuModal');

            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const mealId = button.getAttribute('data-meal-id');
                const mealName = button.getAttribute('data-meal-name');
                const mealDescription = button.getAttribute('data-meal-description');
                const mealPrice = button.getAttribute('data-meal-price');
                const mealCategory = button.getAttribute('data-meal-category');
                const mealAvailable = button.getAttribute('data-meal-available');

                const form = document.getElementById('editMealForm');
                form.action = '/meals/' + mealId;

                document.getElementById('edit-name').value = mealName;
                document.getElementById('edit-description').value = mealDescription;
                document.getElementById('edit-price').value = mealPrice;
                document.getElementById('edit-category').value = mealCategory;
                document.getElementById('edit-available').checked = mealAvailable === '1';

                // Load existing images via AJAX
                loadMealImages(mealId);
            });
        });

        // Load meal images
        function loadMealImages(mealId) {
            const container = document.getElementById('edit-current-images');
            container.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading images...';

            fetch(`/meals/${mealId}/images`) // Assuming you have an endpoint like /meals/{mealId}/images
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = ''; // Clear loading indicator
                    if (data.images && data.images.length > 0) {
                        data.images.forEach(image => {
                            const div = document.createElement('div');
                            div.className = 'position-relative image-item';
                            div.innerHTML = `
                                                <img src="/storage/${image.path}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                ${image.is_primary ? '<span class="badge bg-primary position-absolute top-0 start-0 m-1 primary-badge">Primary</span>' : ''}
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="deleteImage(${mealId}, ${image.id}, this)">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                                <button type="button" class="btn btn-info btn-sm position-absolute bottom-0 start-0 m-1" onclick="setPrimaryImage(${mealId}, ${image.id}, this)" ${image.is_primary ? 'disabled' : ''}>
                                                    Set Primary
                                                </button>
                                            `;
                            container.appendChild(div);
                        });
                    } else {
                        container.innerHTML = '<p class="text-muted">No images uploaded yet.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading images:', error);
                    container.innerHTML = '<p class="text-danger">Failed to load images.</p>';
                });
        }

        // Delete image function
        function deleteImage(mealId, imageId, element) {
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }

            fetch(`/meals/${mealId}/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        element.closest('.image-item').remove();
                        showToast('Image deleted successfully!', 'success');
                        // Re-check if any primary image exists, if not, set the first one as primary
                        const remainingImages = document.querySelectorAll('#edit-current-images .image-item');
                        if (remainingImages.length > 0 && !document.querySelector('.primary-badge')) {
                            const firstImageId = remainingImages[0].querySelector('button[onclick^="setPrimaryImage"]').getAttribute('onclick').match(/\((\d+),\s*(\d+)/)[2];
                            setPrimaryImage(mealId, firstImageId, remainingImages[0]);
                        }
                    } else {
                        showToast(data.message || 'Failed to delete image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to delete image', 'error');
                });
        }

        // Set primary image
        function setPrimaryImage(mealId, imageId, element) {
            fetch(`/meals/${mealId}/images/${imageId}/set-primary`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove primary badge from all images and enable all "Set Primary" buttons
                        document.querySelectorAll('.primary-badge').forEach(badge => badge.remove());
                        document.querySelectorAll('#edit-current-images button[onclick^="setPrimaryImage"]').forEach(btn => btn.disabled = false);

                        // Add primary badge to this image and disable its "Set Primary" button
                        const imageItem = element.closest('.image-item');
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-primary position-absolute top-0 start-0 m-1 primary-badge';
                        badge.textContent = 'Primary';
                        imageItem.querySelector('.position-relative').appendChild(badge);
                        element.disabled = true;
                        showToast('Primary image updated!', 'success');
                    } else {
                        console.log(data.success);
                        showToast(data.message || 'Failed to update primary image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to update primary image', 'error');
                });
        }

        // Customization modal
        let currentMealId = null;

        document.addEventListener('DOMContentLoaded', function () {
            const customModal = document.getElementById('customizationModal');

            customModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                currentMealId = button.getAttribute('data-meal-id');
                const mealName = button.getAttribute('data-meal-name');

                document.getElementById('modal-meal-name').textContent = mealName;

                // Load options for this meal
                loadMealOptions(currentMealId);
            });

            // Handle add group form submission
            document.getElementById('addGroupForm').addEventListener('submit', function (e) {
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
                        // showToast('Group created successfully!', 'success');
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
                <div class="card border-0 shadow-sm mb-4 p-3" style="border-radius: 1rem; background-color: #fdfdfd;" data-group-id="${group.id}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-0">${group.name}</h5>
                            <span class="badge rounded-pill bg-light text-muted fw-normal mt-1">
                                ${group.is_multiple ? 'Multiple Selection' : 'Single Selection'} 
                                ${group.is_required ? '• Required' : ''}
                            </span>
                        </div>
                        <button class="btn btn-sm text-danger fw-bold delete-group" data-group-id="${group.id}">
                            <i class="bi bi-trash3 me-1"></i>Delete Group
                        </button>
                    </div>

                    <div class="list-group list-group-flush mb-3">`;

            if (group.values && group.values.length > 0) {
                group.values.forEach(value => {
                    html += `
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-dot fs-4 text-muted"></i>
                                <span class="ms-2">${value.name}</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold" style="color: #F97352;">+Rp ${Number(value.price).toLocaleString('id-ID')}</span>
                                <button class="btn btn-link btn-sm text-decoration-none text-muted delete-value" data-value-id="${value.id}">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>`;
                });
            } else {
                html += '<div class="text-center py-3 text-muted small">No items added to this group yet</div>';
            }

            html += `
                    </div>

                    <form class="add-value-form p-3 rounded-3" style="background-color: #f8f9fa;" data-group-id="${group.id}">
                        <div class="row g-2 align-items-center">
                            <div class="col-sm-6">
                                <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Extra Cheese" required>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0">Rp</span>
                                    <input type="number" name="price" class="form-control border-start-0" value="0" required>
                                </div>
                            </div>
                            <div class="col-sm-2 text-end">
                                <button type="submit" class="btn btn-sm w-100 text-white fw-bold" style="background-color: #F97352;">Add</button>
                            </div>
                        </div>
                    </form>
                </div>`;

            return html;
        }

        function attachOptionEventListeners() {
            // Delete group buttons
            document.querySelectorAll('.delete-group').forEach(btn => {
                btn.addEventListener('click', function () {
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
                            // showToast('Group deleted successfully!', 'success');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Failed to delete group', 'error');
                        });
                });
            });

            // Delete value buttons
            document.querySelectorAll('.delete-value').forEach(btn => {
                btn.addEventListener('click', function () {
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
                            // showToast('Option deleted successfully!', 'success');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Failed to delete option', 'error');
                        });
                });
            });

            // Add value forms
            document.querySelectorAll('.add-value-form').forEach(form => {
                form.addEventListener('submit', function (e) {
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
                            // showToast('Option added successfully!', 'success');
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