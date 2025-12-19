@extends('admin.layouts.master')

@section('content')

    {{-- Tabla de Productos--}}
    <div class="row row-sm mt-4 mx-auto">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">System Products</h3>
                    <a class="btn ripple btn-teal" href="{{route('products.create')}}">+ Add</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    {{-- <th class="wd-5p border-bottom-0 bg-light">ID</th> --}}
                                    <th class="wd-5p border-bottom-0 bg-light">Image</th>
                                    <th class="wd-20p border-bottom-0 bg-light">Name</th>
                                    <th class="wd-20p border-bottom-0 bg-light">Category</th>
                                    <th class="wd-5p border-bottom-0 bg-light">Existence</th>
                                   <th class="wd-5p border-bottom-0 bg-light">Type</th> 
                                    <th class="border-bottom-0 bg-light">Buys</th>
                                    <th class="border-bottom-0 bg-light">Supply</th>
                                    <th class="border-bottom-0 bg-light">Expired</th>
                                    <th class="wd-10p border-bottom-0 bg-light">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                  

                                    <td class="text-center">
                                            <img src="{{ asset('storage/' . $product->photo) }}" 
                                            style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">                  
                                    </td>

                                    <td class="fw-light">{{$product->name}}</td>
                                    <td class="fw-light">{{$product->category->name ?? 'N/A'}}</td>
                                    <td class="fw-light">
                                        <span class="text-{{ $product->total_stock > 0 ? 'success' : 'danger' }}" style="text-transform: uppercas" ">
                                            {{ number_format($product->total_stock, 2) }}
                                        </span>
                                    </td>
                                    <td class="fw-light">
                                        <span class="text-info">{{$product->type}}</span>
                                    </td> 
                                    
                                    <td class="fw-light">
                                        <span class="bg-secondary py-1 px-2 rounded">{{$product->purchaseUnit->symbol ?? 'N/A'}}</span>
                                    </td>
                                    <td class="fw-light">
                                        <span class="bg-warning text-dark py-1 px-2 rounded">{{$product->consumptionUnit->symbol ?? 'N/A'}}</span>
                                    </td>

                                    <td class="fw-light">
                                        <span class="text-info">
                                            {{ \Carbon\Carbon::parse($product->expired)->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <!-- Botón para ver imágenes -->
                                        <button class="btn btn-sm btn-primary" onclick="openDetailsModal({{ $product->id }})" 
                                                title="Ver imágenes">
                                            <span class="fe fe-image"></span>
                                        </button>
                                        
                                        <!-- Botón editar -->
                                        <button id="bEdit" type="button" class="btn btn-sm btn-warning"  
                                                onclick="openEditModal({{ $product->id }})" title="Editar">
                                            <span class="fe fe-edit"></span>
                                        </button>
                                    
                                        <!-- Botón eliminar -->
                                        <button id="bDel" type="button" class="btn btn-sm btn-danger" 
                                                data-id="{{ $product->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                title="Eliminar">
                                            <span class="fe fe-trash-2"></span>
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

    <!-- Modal para detalles de producto -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Imágenes del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="detailsContainer" class="row row-cols-1 row-cols-md-3 g-4">
                        <!-- Las imágenes se cargarán aquí dinámicamente -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Modal Eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" category="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" category="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Eliminar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage" class="text-normal">¿Estás seguro de que deseas eliminar este registro? Esta acción es irreversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>      

@endsection

@push('styles')
<style>
    .img-thumbnail {
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }
    .badge {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')

    {{-- Mostrar Modal Detalles --}}
    <script>
        function openDetailsModal(productId) {
            fetch(`/products/${productId}/details`)
                .then(response => response.json())
                .then(data => {
                    const detailsContainer = document.getElementById('detailsContainer');
                    detailsContainer.innerHTML = '';

                    if (data.details.length > 0) {
                        data.details.forEach(image => {
                            detailsContainer.innerHTML += `
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="${image.url}" class="card-img-top" alt="Imagen del producto" style="height: 200px; object-fit: cover;">
                                    </div>
                                </div>`;
                        });
                    } else {
                        detailsContainer.innerHTML = '<p class="text-center">No hay imágenes para este producto.</p>';
                    }

                    const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                    detailsModal.show();
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

    {{-- Eliminar registro --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let productIdToDelete = null;

            document.querySelectorAll('#bDel').forEach(button => {
                button.addEventListener('click', function () {
                    productIdToDelete = this.getAttribute('data-id');
                    document.getElementById('deleteMessage').textContent = '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.';
                });
            });

            document.getElementById('confirmDelete').addEventListener('click', function () {
                if (productIdToDelete) {
                    fetch(`/products/${productIdToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            document.getElementById('deleteMessage').textContent = 'El registro fue eliminado correctamente.';
                            document.getElementById('confirmDelete').disabled = true;
                            document.querySelector('[data-bs-dismiss="modal"]').disabled = true;

                            setTimeout(() => {
                                const modalElement = document.getElementById('deleteModal');
                                const modal = bootstrap.Modal.getInstance(modalElement);
                                modal.hide();
                                location.reload();
                            }, 2000);
                        } else {
                            document.getElementById('deleteMessage').textContent = 'Error al eliminar el registro. Por favor, inténtelo de nuevo.';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('deleteMessage').textContent = 'Ocurrió un error inesperado. Por favor, inténtelo más tarde.';
                    });
                }
            });
            
        });
    </script>

@endpush