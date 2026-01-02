@extends('admin.layouts.master')


@section('content')

<section>

    {{-- Tabla de Sectores--}}
    <div class="row row-sm mt-4 mx-auto">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Sectors of the System</h3>
                    {{-- <button type="button" class="btn btn-success-gradient btn-w-xs mb-1"> + Usuario</button> --}}
                    <a class="btn ripple btn-teal" {{-- data-bs-target="#select2modal" data-bs-toggle="modal" --}} href="{{route('sectors.create')}} ">+ New Sector</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-5p border-bottom-0 bg-light">ID</th>
                                    <th class="wd-5p border-bottom-0 bg-light">Image</th>
                                    <th class="wd-10p border-bottom-0 bg-light">Name</th>
                                    <th class="wd-25p border-bottom-0 bg-light">Description</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Status</th>
                                    <th class="wd-5p border-bottom-0 bg-light">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectors as $sector)
                                <tr>
                                    <td>{{$sector->id}} </td>
                                    <td class="text-center">
                                        <img src="{{ $sector->photo ? asset('storage/' . $sector->photo) : asset('storage/sectors/sector.png') }}" 
                                             style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                    </td>
                                    <td class="fw-light">{{$sector->name}}</td>
                                    <td class="fw-light">{{$sector->description}}</td>

                                   
                                    <td class="fw-light text-center justify-center">
                                        @if((int)$sector->status == 1)
                                            <span class="badge bg-success">ENABLED</span>
                                        @elseif((int)$sector->status == 2)
                                            <span class="badge bg-warning text-white">MAINTENANCE</span>
                                        @else
                                            <span class="badge bg-danger">DISABLED</span>
                                        @endif
                                    </td>

                                    
                                    
                                    
                                <td>
                                    
                                        <!-- Otros botones -->
                                        <button class="btn btn-sm btn-primary" onclick="openImagesModal({{ $sector->id }})">
                                            <span class="fe fe-image"></span>
                                        </button>
                                        
                                        <a id="bEdit" type="button" class="btn btn-sm btn-warning" href="{{route('sectors.edit', $sector->id)}}">
                                            <span class="fe fe-edit"></span>
                                        </a>
                                    
                                        <button class="btn btn-sm btn-danger delete-sector-btn" data-id="{{ $sector->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteSectorModal">
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


    {{-- Modal Eliminar --}}
    <div class="modal fade" id="deleteSectorModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
                   
</section>
@endsection

@push('scripts')

 {{-- Eliminar registro --}}  
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let sectorIdToDelete;
        
        // Cuando se hace clic en el botón de eliminar (CORRECCIÓN: selector correcto)
        $(document).on('click', '.delete-sector-btn', function() {
            sectorIdToDelete = $(this).data('id');
            $('#deleteMessage').text('¿Estás seguro de que deseas eliminar el sector #' + sectorIdToDelete + '? Esta acción no se puede deshacer.');
        });
        
        // Confirmar eliminación
        $('#confirmDelete').on('click', function() {
            if (!sectorIdToDelete) {
                Swal.fire('Error', 'No se ha seleccionado ningún sector para eliminar', 'error');
                return;
            }
            
            $.ajax({
                url: '/sectors/' + sectorIdToDelete,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    $('#deleteSectorModal').modal('hide');
                    
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: response.message || 'El sector ha sido eliminado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message || 'Error al eliminar el sector', 'error');
                    }
                },
                error: function(xhr) {
                    $('#deleteSectorModal').modal('hide');
                    
                    let errorMsg = 'Ocurrió un error al eliminar el registro';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMsg = 'El sector no existe o ya fue eliminado';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Error del servidor. Intente nuevamente.';
                    }
                    
                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });
        
        // Cerrar modal al hacer clic en la X o en Cancelar
        $('.btn-close, .btn-secondary').on('click', function() {
            $('#deleteSectorModal').modal('hide');
        });
    });
</script>
@endpush