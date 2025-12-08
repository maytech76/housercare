@extends('admin.layouts.master')

@section('content')


        {{-- Tabla de Usuarios --}}
        <div class="row row-sm mt-4 mx-auto">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Usuarios del Sistema</h3>
                        {{-- <button type="button" class="btn btn-success-gradient btn-w-xs mb-1"> + Usuario</button> --}}
                        <a class="btn ripple btn-teal" data-bs-target="#select2modal" data-bs-toggle="modal" href="">+ Nuevo</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                <thead>
                                    <tr>
                                        <th class="wd-15p border-bottom-0 bg-light">ID</th>
                                        <th class="wd-15p border-bottom-0 bg-light">Nombres</th>
                                        <th class="wd-20p border-bottom-0 bg-light">Apellidos</th>
                                        <th class="wd-20p border-bottom-0 bg-light">Perfil</th>
                                        <th class="wd-15p border-bottom-0 bg-light">Correo</th>
                                        <th class="wd-10p border-bottom-0 bg-light">Telefono</th>
                                        <th class="wd-25p border-bottom-0 bg-light">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}} </td>
                                        <td class="fw-light">{{$user->name}}</td>
                                        <td class="fw-light">{{$user->last_name}}</td>
                                        <td class="fw-light">{{ $user->role?->name ?? 'Sin rol' }}</td>
                                        <td class="fw-light">{{$user->email}}</td>
                                        <td class="fw-light">{{$user->phone}}</td>
                                        <td>
                                            <button id="bEdit" type="button" class="btn btn-sm btn-warning"  onclick="openEditModal({{ $user->id }})">
                                                <span class="fe fe-edit"></span>
                                            </button>
                                        
                                            <button id="bDel" type="button"  class="btn  btn-sm btn-danger" data-id="{{ $user->id }}"
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


        <!-- Basic Registro Usuarios -->
        <div class="modal" id="select2modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header header-custom">
                        <h6 class="d-block mx-auto text-center text-uppercase">Registro de Usuario</h6>
                    </div>
                    <div class="modal-body">
                        <div class="col col-lg-12">
                        
                                <div class="m-2">
                                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">

                                            <select name="rol_id" id="rol_id" class="form-control border border-success" required>

                                                <option value="" selected disabled>Seleciona un Rol</option>
                                                @foreach ($roles as $rol)

                                                <option value="{{$rol->id}} ">{{$rol->name}} </option>
                                                    
                                                @endforeach
                                            </select>

                                            @error('rol_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Nombre: </label>
                                            <input type="text" name="name" id="name" class="form-control border border-success" placeholder="Nombres">
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Apellido: </label>
                                            <input type="text" name="last_name" id="last_name" class="form-control border border-success"  placeholder="Apellidos">
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Celular: </label>
                                            <input type="text" name="phone" id="phone" class="form-control border border-success"  placeholder="Telefono">
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Correo Eléctronico: </label>
                                            <input type="email" name="email" id="email" class="form-control border border-success" placeholder="Correo Eléctronico">
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Password: </label>
                                            <small>Modificala, solo Si necesitas cambiar la clave de usuario.</small>
                                            <input type="password" name="password" id="password" class="form-control border border-success" placeholder="Password">
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Cerrar</button>
                                            <button class="btn ripple btn-success" type="submit">Registrar</button>
                                        </div>
                                    </form>
                                </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- End Basic modal -->


        {{-- Modal editar Usuarios--}}
        <div class="modal" id="openEditModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header header-custom-edit">
                        <h6 class="d-block mx-auto text-center text-uppercase">Editar Usuario</h6>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" action="{{route('users.update', $user->id)}}  " method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="editRole">Rol</label>
                                <select id="editRole" name="rol_id" class="form-control border border-warning" place holder="">
                                    <!-- Opciones cargadas dinámicamente -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="editName">Nombres</label>
                                <input type="text" id="editName" name="name" class="form-control border border-warning">
                                <small id="errornamee" class="form-text text-danger" style="display: none;">Solo se permiten Letras</small>                  
                                <input type="hidden" id="editId" name="id" "{{$user->id}}">
                            </div>

                            <div class="form-group">
                                <label for="editLastName">Apellidos:</label>
                                <input type="text" id="editLastName" name="last_name" class="form-control form-control border border-warning">
                                <small id="errorApellido" class="form-text text-danger" style="display: none;">Solo se permiten Letras</small>
                            </div>

                            <div class="form-group">
                                <label for="editPhone">Teléfono:</label>
                                <input type="text" id="editPhone" name="phone" class="form-control form-control border border-warning">
                            </div>

                            <div class="form-group">
                                <label for="editEmail">Correo Electrónico:</label>
                                <input type="email" id="editEmail" name="email" class="form-control form-control border border-warning">
                            </div>

                            <div class="form-group">
                                <label for="editPassword">Contraseña:</label>
                                <input type="password" id="editPassword" name="password" class="form-control border border-warning" placeholder="Dejar en blanco para no cambiar">

                            </div>
                        
                            <div class="modal-footer">
                                <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Cerrar</button>
                                <button type="submit" class="btn ripple btn-warning">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Final Modal Editar --}}


       {{-- Modal Eliminar Usuarios--}}
       <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Eliminar Registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p id="deleteMessage" class="text-normal">¿Estás seguro de que deseas eliminar este registro? Esta acción es inrreversible.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>

                </div>
            </div>
       </div>      
       {{-- Final Modal Eliminar --}}

         <!-- Estilos para Modales -->
         <style>

            /* Colores para bootones del campo satus */
            .btn-activo-light {
                background-color: #96f4b4; /* verde claro tipo alerta success */
                color: #035801;
                border: 1px solid #66c07b;
                padding: 0px, 2px;
                
            }

            .btn-reserva-light {
                background-color: #f0e1c1; /* verde claro tipo alerta success */
                color: #dd6a06;
                border: 1px solid #c0aa66;
                padding: 0px, 2px;
            }

            .btn-culminado-light {
                background-color: #f0eeeb; /* verde claro tipo alerta success */
                color: #414040;
                border: 1px solid #aaa9a8;
                padding: 0px, 2px;
            }

            .btn-cancelado-light {
                background-color: #f6a6a6ca; /* verde claro tipo alerta success */
                color: #9b0303;
                border: 1px solid #aea3a3;
                padding: 0px, 2px;
            }



            /* Color para encabezados de Modales */
            @media (max-width: 576px) {
                .modal-dialog {
                    margin: 0.5rem;
                }
        
                .modal-content {
                    border-radius: 1rem;
                }
            }
            .header-custom {
                background-color: #d6f5d9; /* Verde pastel */
                border-bottom: 1px solid #a3f1a6; /* Borde sutil */
            }

            .header-custom-edit {
                background-color: #f2f5d6; /* Verde pastel */
                border-bottom: 1px solid #ecf1a3; /* Borde sutil */
            }

            .header-custom-delete {
                background-color: #f5d6d6; /* Verde pastel */
                border-bottom: 1px solid #f1a3a3; /* Borde sutil */
            }

            /* Tamaño para modals */
            @media (min-width: 768px) {
                .modal-xl-custom {
                    max-width: 770px;
                }
            }
        </style>

@endsection

@push('scripts')

 {{-- Editar Registro --}}
 <script>
    function openEditModal(userId) {
        // Realiza una solicitud AJAX para obtener los datos del usuario
        fetch(`/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                // Rellena el formulario con los datos obtenidos
                const user = data.user;
                const roles = data.roles;

                document.getElementById('editForm').action = `/users/${user.id}`;
                document.getElementById('editRole').innerHTML = roles
                    .map(role => `<option value="${role.id}" ${role.id === user.role_id ? 'selected' : ''}>${role.name}</option>`)
                    .join('');
                document.getElementById('editName').value = user.name;
                document.getElementById('editLastName').value = user.last_name;
                document.getElementById('editPhone').value = user.phone;
                document.getElementById('editEmail').value = user.email;
                document.getElementById('editPassword').value = user.password;
                

                // Muestra el modal
                const editModal = new bootstrap.Modal(document.getElementById('openEditModal'));
                editModal.show();
            })
            .catch(error => console.error('Error:', error));

            console.log(data);
    }
 </script>

{{-- Eliminar registro --}}
<script>

    document.addEventListener('DOMContentLoaded', function () {
        let userIdToDelete = null;

        // Capturar el ID del usuario al abrir el modal
        document.querySelectorAll('#bDel').forEach(button => {
            button.addEventListener('click', function () {
                userIdToDelete = this.getAttribute('data-id');
                document.getElementById('deleteMessage').textContent = '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.';
            });
        });

        // Confirmar y realizar la eliminación
        document.getElementById('confirmDelete').addEventListener('click', function () {
            if (userIdToDelete) {
                fetch(`/users/${userIdToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (response.ok) {
                            document.getElementById('deleteMessage').textContent = 'El registro fue eliminado correctamente.';
                            
                            // Desactivar los botones del modal mientras se muestra el mensaje
                            document.getElementById('confirmDelete').disabled = true;
                            document.querySelector('[data-bs-dismiss="modal"]').disabled = true;

                            // Cerrar el modal después de 2 segundos
                            setTimeout(() => {
                                const modalElement = document.getElementById('deleteModal');
                                const modal = bootstrap.Modal.getInstance(modalElement);
                                modal.hide();

                                // Recargar la página después de cerrar el modal
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