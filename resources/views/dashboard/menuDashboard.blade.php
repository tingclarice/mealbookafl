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

    <!-- Menu Items List -->
    <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
        
        <!-- Example Menu Item Card 1 (With Customizations) -->
        <div class="col">
            <div class="card shadow-sm h-100">
                <img src="https://placehold.co/600x400/cccccc/333333?text=Pizza" class="card-img-top" alt="Pizza" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title h4">Margherita Pizza</h2>
                    <p class="card-text text-muted">Classic pizza with tomato, mozzarella, and basil.</p>
                    <p class="h5 text-dark mb-3">$12.99</p>
                    
                    <div class="mt-auto d-grid gap-2 d-sm-flex">
                        <button type="button" class="btn btn-warning flex-sm-fill" data-bs-toggle="modal" data-bs-target="#editMenuModal">Edit</button>
                        <button type="button" class="btn btn-danger flex-sm-fill">Delete</button>
                    </div>
                    <button type="button" class="btn btn-dark w-100 mt-2" data-bs-toggle="modal" data-bs-target="#customizationModal">
                        Manage Customizations
                    </button>
                </div>
            </div>
        </div>

        <!-- Example Menu Item Card 2 (No Customizations) -->
        <div class="col">
            <div class="card shadow-sm h-100">
                <img src="https://placehold.co/600x400/cccccc/333333?text=Burger" class="card-img-top" alt="Burger" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title h4">Classic Burger</h2>
                    <p class="card-text text-muted">Beef patty, lettuce, tomato, and cheese on a sesame bun.</p>
                    <p class="h5 text-dark mb-3">$8.99</p>
                    
                    <div class="mt-auto d-grid gap-2 d-sm-flex">
                        <button type="button" class="btn btn-warning flex-sm-fill" data-bs-toggle="modal" data-bs-target="#editMenuModal">Edit</button>
                        <button type="button" class="btn btn-danger flex-sm-fill">Delete</button>
                    </div>
                    <button type="button" class="btn btn-dark w-100 mt-2" data-bs-toggle="modal" data-bs-target="#customizationModal">
                        Manage Customizations
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Example Menu Item Card 3 -->
        <div class="col">
            <div class="card shadow-sm h-100">
                <img src="https://placehold.co/600x400/cccccc/333333?text=Salad" class="card-img-top" alt="Salad" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title h4">Caesar Salad</h2>
                    <p class="card-text text-muted">Romaine lettuce, croutons, parmesan, and Caesar dressing.</p>
                    <p class="h5 text-dark mb-3">$7.49</p>
                    
                    <div class="mt-auto d-grid gap-2 d-sm-flex">
                        <button type="button" class="btn btn-warning flex-sm-fill" data-bs-toggle="modal" data-bs-target="#editMenuModal">Edit</button>
                        <button type="button" class="btn btn-danger flex-sm-fill">Delete</button>
                    </div>
                    <button type="button" class="btn btn-dark w-100 mt-2" data-bs-toggle="modal" data-bs-target="#customizationModal">
                        Manage Customizations
                    </button>
                </div>
            </div>
        </div>

    </div> <!-- End Menu Items List -->

</div> <!-- End Container -->


<!-- Modals -->

<!-- Add Menu Item Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMenuModalLabel">Add New Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="add-name" class="form-label">Menu Name</label>
                        <input type="text" id="add-name" class="form-control" placeholder="e.g., Margherita Pizza">
                    </div>
                    <div class="mb-3">
                        <label for="add-description" class="form-label">Description</label>
                        <textarea id="add-description" rows="3" class="form-control" placeholder="e.g., Classic pizza..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="add-price" class="form-label">Price</label>
                        <input type="number" id="add-price" step="0.01" class="form-control" placeholder="e.g., 12.99">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Item</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Menu Item Modal -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">Edit Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Menu Name</label>
                        <input type="text" id="edit-name" class="form-control" value="Margherita Pizza">
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <textarea id="edit-description" rows="3" class="form-control">Classic pizza with tomato, mozzarella, and basil.</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-price" class="form-label">Price</label>
                        <input type="number" id="edit-price" step="0.01" class="form-control" value="12.99">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Item</button>
            </div>
        </div>
    </div>
</div>

<!-- Manage Customizations Modal -->
<div class="modal fade" id="customizationModal" tabindex="-1" aria-labelledby="customizationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customizationModalLabel">Customizations for "Margherita Pizza"</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <!-- Add New Option Group Form -->
                <form class="mb-4 p-3 bg-light rounded border">
                    <h4 class="h5 mb-3">Add New Option Group</h4>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm">
                            <label for="group-name" class="form-label">Group Name</label>
                            <input type="text" id="group-name" class="form-control" placeholder="e.g., Size, Add-ons, Spice Level">
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-success w-100">Create Group</button>
                        </div>
                    </div>
                    <div class="form-text mt-2">You must create a group (like "Size") before you can add options (like "Small", "Medium").</div>
                </form>

                <!-- Existing Option Groups -->
                <div class="d-flex flex-column gap-4">
                    
                    <!-- Example Option Group 1: Size -->
                    <div class="border rounded p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="h5 mb-0">Size</h4>
                            <div class="d-flex gap-2">
                                <button class="btn btn-link btn-sm p-0">Edit Group</button>
                                <button class="btn btn-link btn-sm text-danger p-0">Delete Group</button>
                            </div>
                        </div>

                        <!-- Option Values for "Size" -->
                        <div class="list-group mb-3">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>Small</div>
                                <div class="fw-bold">+$0.00</div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-link btn-sm p-0 text-warning">Edit</button>
                                    <button class="btn btn-link btn-sm p-0 text-danger">Delete</button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>Medium</div>
                                <div class="fw-bold">+$2.00</div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-link btn-sm p-0 text-warning">Edit</button>
                                    <button class="btn btn-link btn-sm p-0 text-danger">Delete</button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>Large</div>
                                <div class="fw-bold">+$4.00</div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-link btn-sm p-0 text-warning">Edit</button>
                                    <button class="btn btn-link btn-sm p-0 text-danger">Delete</button>
                                </div>
                            </div>
                        </div>

                        <!-- Form to Add New Option Value to "Size" Group -->
                        <form class="p-3 bg-light rounded border">
                            <h5 class="h6 mb-2">Add New Option to "Size"</h5>
                            <div class="row g-3 align-items-end">
                                <div class="col-sm">
                                    <label for="val-name-1" class="form-label">Option Name</label>
                                    <input type="text" id="val-name-1" class="form-control" placeholder="e.g., Extra Large">
                                </div>
                                <div class="col-sm-3">
                                    <label for="val-price-1" class="form-label">Price (e.g., 6.00)</label>
                                    <input type="number" id="val-price-1" step="0.01" class="form-control" placeholder="6.00">
                                </div>
                                <div class="col-sm-auto">
                                    <button type="submit" class="btn btn-primary w-100">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Example Option Group 2: Add-ons -->
                    <div class="border rounded p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="h5 mb-0">Add-ons</h4>
                            <div class="d-flex gap-2">
                                <button class="btn btn-link btn-sm p-0">Edit Group</button>
                                <button class="btn btn-link btn-sm text-danger p-0">Delete Group</button>
                            </div>
                        </div>
                        
                        <!-- Option Values for "Add-ons" -->
                        <div class="list-group mb-3">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>Extra Cheese</div>
                                <div class="fw-bold">+$1.50</div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-link btn-sm p-0 text-warning">Edit</button>
                                    <button class="btn btn-link btn-sm p-0 text-danger">Delete</button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>Pepperoni</div>
                                <div class="fw-bold">+$2.00</div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-link btn-sm p-0 text-warning">Edit</button>
                                    <button class="btn btn-link btn-sm p-0 text-danger">Delete</button>
                                </div>
                            </div>
                        </div>

                        <!-- Form to Add New Option Value to "Add-ons" Group -->
                        <form class="p-3 bg-light rounded border">
                            <h5 class="h6 mb-2">Add New Option to "Add-ons"</h5>
                            <div class="row g-3 align-items-end">
                                <div class="col-sm">
                                    <label for="val-name-2" class="form-label">Option Name</label>
                                    <input type="text" id="val-name-2" class="form-control" placeholder="e.g., Mushrooms">
                                </div>
                                <div class="col-sm-3">
                                    <label for="val-price-2" class="form-label">Price (e.g., 1.00)</label>
                                    <input type="number" id="val-price-2" step="0.01" class="form-control" placeholder="1.00">
                                </div>
                                <div class="col-sm-auto">
                                    <button type="submit" class="btn btn-primary w-100">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection