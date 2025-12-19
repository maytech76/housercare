<x-guest-layout>

    @section('content')
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0 text-danger">Confirmar Asistencia grupal al: {{ $event->title }}</h4>
                    </div>
    
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
    
                        <form method="POST" action="{{ route('guest.confirmation.store', $event->id) }}" enctype="multipart/form-data">
                       
                            @csrf
    
                            <div class="mb-3">
                                <h6 class="text-letf text-danger">Favor ingresar su correo electronico, alli le enviaremos un correo con un QR de invitado</h6>
                                <label for="email" class="form-label">Email *</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}"required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
    
                            <div class="mb-3">
                                <label for="phone" class="form-label">Celular *</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
    
                            <div class="mb-4">
                                <h6 class="text-letf">Toma tu mejor Selfile, y subela aqui, esta sera tu foto al ingresar al evento</h6>
                                <label for="photo" class="form-label">Foto *</label>
                                <input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       name="photo" required accept="image/*">
                                <small class="text-muted">Formatos aceptados: JPG, PNG. Tamaño máximo: 4MB</small>
                                @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
    
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Confirmar Asistencia
                                </button>
                                <p class="text-center mt-3 text-muted">Recibirás tu código QR por correo al confirmar.</p>
                            </div>
                            
                        </form>
    
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    </x-guest-layout>