@extends('admin.layouts.master')

@section('content')
   
<section>
    <div class="main-container container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <h4 class="page-title">Administration</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Category Management</li>
                </ol>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="mb-xl-0">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false">
                            
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->

        {{-- Categories Table --}}
        <div class="row row-sm mt-4 mx-auto">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">System Categories</h3>
                        <a class="btn ripple btn-teal" data-bs-target="#select2modal" data-bs-toggle="modal" href="">+ New</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                <thead>
                                    <tr>
                                        <th class="wd-5p border-bottom-0 bg-light text-center">#</th>
                                        <th class="wd-20p border-bottom-0 bg-light">Name</th>
                                        <th class="wd-20p border-bottom-0 bg-light text-center">Description</th>
                                        <th class="wd-5p border-bottom-0 bg-light text-center">Type</th>
                                        <th class="wd-25p border-bottom-0 bg-light text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="fw-light">{{ $category->name }}</td>
                                        <td class="fw-light">{{ $category->description }}</td>
                                        <td class="fw-light">{{ $category->type }}</td> 
                                        
                                        <td class="text-center">
                                        
                                            <!-- Other buttons -->
                                            <button class="btn btn-sm btn-primary mx-3" onclick="openImagesModal({{ $category->id }})">
                                                <span class="fe fe-image"></span>
                                            </button>
                                            
                                            <button id="bEdit" type="button" class="btn btn-sm btn-warning" onclick="openEditModal({{ $category->id }})">
                                                <span class="fe fe-edit"></span>
                                            </button>
                                        
                                            <button id="bDel" type="button"  class="btn  btn-sm btn-danger mx-3" data-id="{{ $category->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
                                                <span class="fe fe-trash-2"> </span>
                                            </button>

                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Basic Register -->
        <div class="modal" id="select2modal">
            <div class="modal-dialog modal-dialog-centered" category="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header bg-success-gradient">
                        <h6 class="d-block mx-auto text-uppercase">New Record</h6>
                    </div>
                    <div class="modal-body">
                        <div class="col col-lg-12">
                        
                                <div class="m-2">
                                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text" name="name" id="name" class="form-control border border-secondary" placeholder="Enter category name">
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <input type="text" name="description" id="description" class="form-control border border-secondary" placeholder="Enter detailed description">
                                        </div>

                                        <div class="form-group">
                                            <label for="type">Category Type</label>
                                            <select name="type" id="type" class="form-select">
                                                @foreach ($types as $type => $label )
                                                    <option value="{{ $type }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        
                                        <div class="modal-footer">
                                            <div class="row w-100 text-center">
                                                <div class="col-md-6 col-sm-12 col-xs-12 mb-sm-4 mb-xs-4">
                                                  <button class="btn ripple btn-danger w-100" data-bs-dismiss="modal" type="button">Close</button>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                  <button class="btn ripple btn-success w-100" type="submit">Register</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- End Basic modal -->


        {{-- Edit Modal --}}
        <div class="modal" id="openEditModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header bg-warning-gradient">
                        <h6 class="d-block mx-auto text-uppercase">Edit Category</h6>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" id="editId" name="id">
                            
                            <div class="form-group">
                                <label for="editName">Name</label>
                                <input type="text" id="editName" name="name" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="editType">Type</label>
                                <select id="editType" name="type" class="form-control">
                                    <!-- Options will be filled with JavaScript -->
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="editDescription">Description</label>
                                <input type="text" id="editDescription" name="description" class="form-control">
                            </div>
                            
                            <div class="modal-footer">
                                <div class="row w-100">
                                    <div class="col-md-6 col-sm-12 col-xs-12 mb-sm-4 mb-xs-4">
                                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
                                    </div> 
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->



        {{-- Delete Modal --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" category="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" category="document">
                <div class="modal-content">
                    
                    <div class="modal-header bg-danger-gradient">
                        <h6 class="d-block mx-auto text-uppercase" id="deleteModalLabel">Delete Record</h6>
                    </div>

                    <div class="modal-body m-10">
                        <p id="deleteMessage" class="text-normal">Are you sure you want to delete this record? This action cannot be undone.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>

                </div>
            </div>
        </div>      
        {{-- End Delete Modal --}}

        

    </div>
</section>
                   
@endsection

@push('scripts')

   {{-- Edit Record --}}
    <script>
        // Function to open modal with data
        function openEditModal(categoryId) {
            fetch(`/categories/${categoryId}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Fill the form
                    document.getElementById('editForm').action = `/categories/${data.category.id}`;
                    document.getElementById('editId').value = data.category.id;
                    document.getElementById('editName').value = data.category.name;
                    document.getElementById('editDescription').value = data.category.description || '';
                    
                    // Fill the select
                    const typeSelect = document.getElementById('editType');
                    typeSelect.innerHTML = '';
                    Object.entries(data.types).forEach(([value, label]) => {
                        const option = new Option(label, value);
                        if (value === data.current_type) option.selected = true;
                        typeSelect.add(option);
                    });

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('openEditModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading data');
                });
        }

        // Handle form submission
        document.getElementById('editForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const responseData = await response.json();
                if (!response.ok) throw responseData;
                return responseData;
            })
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('openEditModal')).hide();
                    setTimeout(() => location.reload(), 500); // Small delay to see feedback
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Error saving data');
            });
        });
    </script>

    {{-- Show Images in Modal --}}
    <script>
        function openImagesModal(eventId) {
            fetch(`/events/${eventId}/images`)
                .then(response => response.json())
                .then(data => {
                    const imagesContainer = document.getElementById('imagesContainer');
                    imagesContainer.innerHTML = ''; // Clear any previous content

                    if (data.images.length > 0) {
                        data.images.forEach(image => {
                            imagesContainer.innerHTML += `
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="${image.url}" class="card-img-top" alt="Event image">
                                    </div>
                                </div>`;
                        });
                    } else {
                        imagesContainer.innerHTML = '<p class="text-center">No images available for this event.</p>';
                    }

                    // Show the modal
                    const imagesModal = new bootstrap.Modal(document.getElementById('imagesModal'));
                    imagesModal.show();
                })
                .catch(error => console.error('Error:', error));
        }
    </script>


    {{-- Delete Record --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let eventIdToDelete = null;

            // Capture user ID when opening modal
            document.querySelectorAll('#bDel').forEach(button => {
                button.addEventListener('click', function () {
                    eventIdToDelete = this.getAttribute('data-id');
                    document.getElementById('deleteMessage').textContent = 'Are you sure you want to delete this record? This action cannot be undone.';
                });
            });

            // Confirm and perform deletion
            document.getElementById('confirmDelete').addEventListener('click', function () {
                if (eventIdToDelete) {
                    fetch(`/categories/${eventIdToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                document.getElementById('deleteMessage').textContent = 'The record was successfully deleted.';
                                
                                // Disable modal buttons while showing message
                                document.getElementById('confirmDelete').disabled = true;
                                document.querySelector('[data-bs-dismiss="modal"]').disabled = true;

                                // Close modal after 2 seconds
                                setTimeout(() => {
                                    const modalElement = document.getElementById('deleteModal');
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    modal.hide();

                                    // Reload page after closing modal
                                    location.reload();
                                }, 2000);
                            } else {
                                document.getElementById('deleteMessage').textContent = 'Error deleting record. Please try again.';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('deleteMessage').textContent = 'An unexpected error occurred. Please try again later.';
                        });
                }
            });
        });
    </script>

@endpush