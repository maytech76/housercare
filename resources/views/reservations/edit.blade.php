@extends('admin.layouts.master')

@section('content')

<div class="mt-20" style="margin-top: 100px">



    <div class="col-10 m-2 mx-auto">
        <form method="POST" action="{{ route('reservations.update', $reservation->id) }}">

            @csrf
            @method('PUT')
    
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edición de reserva</h3>
                </div>
                <div class="card-body">
                    <div class="row">

                        <!-- Usuario -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="end_time" class="fw-bold">{{ __('Usuario') }}</label>
                            <select class="form-control form-select select2" id="user_id" name="user_id" required>
                                @foreach ($users as $user ) {{-- si el user_id es = al id del usuario que se esta editando agregamos selected de lo contrario '' --}}
                                    <option value="{{ $user->id }}"{{ $user->id == $reservation->user_id ? 'selected' : '' }}>{{ $user->name }} {{ $user->last_name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
    
                        <!-- Consultor -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="end_time" class="fw-bold">{{ __('Consultor') }}</label>
                            <select name="consultant_id" class="form-control form-select select2">
                                @foreach ($consultants as $consultant )
                                      {{-- Si el id del consultor es igual al id del consultor que se esta editando agregamos selected --}}
                                    <option value="{{ $consultant->id }}" {{ $consultant->id == $reservation->consultant_id ? 'selected' : '' }}>{{ $consultant->name }} {{ $consultant->last_name }}</option>
                                @endforeach
                            </select>
                            @error('consultant_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
    
                        <!-- Fecha de reservacion-->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="reservation_date" class="fw-bold">{{ __('Fecha de reserva') }}</label>
                            <input type="date" name="reservation_date" value="{{old('reservation_date', $reservation->reservation_date)}}" class="form-control">
                        </div>
    
                        <!-- Hora de Inicio-->
                        <div class="col-12 col-md-6 mb-3">
                            
                                <label for="start_time" class="fw-bold">{{ __('Hora inicio') }}</label>
                                   <select name="start_time" id="start_time" class="form-control" required>                                   
                                    
                                    <option value="09:00" {{ $reservation->start_time == '09:00' ? 'selected' : '' }}>09:00</option>
                                    <option value="10:00" {{ $reservation->start_time == '10:00' ? 'selected' : '' }}>10:00</option>
                                    <option value="11:00" {{ $reservation->start_time == '11:00' ? 'selected' : '' }}>11:00</option>
                                    <option value="12:00" {{ $reservation->start_time == '12:00' ? 'selected' : '' }}>12:00</option>
                                    <option value="13:00" {{ $reservation->start_time == '13:00' ? 'selected' : '' }}>13:00</option>
                                    <option value="14:00" {{ $reservation->start_time == '14:00' ? 'selected' : '' }}>14:00</option>
                                    <option value="15:00" {{ $reservation->start_time == '15:00' ? 'selected' : '' }}>15:00</option>
                                    <option value="16:00" {{ $reservation->start_time == '16:00' ? 'selected' : '' }}>16:00</option>

                                  </select>
                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message}}</strong>
                                    </span>
                                @enderror
                           
                        </div>
    
                        <!-- Hora Fin -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="end_time" class="fw-bold">{{ __('Hora Fin') }}</label>
                            <input type="text" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', $reservation->end_time) }}" readonly>
                            @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
    
                        <!-- Estado de Reserva -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="status" class="fw-bold">{{ __('Estado de Reserva') }}</label>
                            <select name="status" class="form-control">

                                <option value="">Seleccionar un estado</option>
                                <option value="pendiente" class="text-warning" {{ $reservation->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" class="text-success" {{ $reservation->status == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="cancelada" class="text-danger" {{ $reservation->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>

                            </select>
                        </div>

                        <!-- Estado del pago -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="end_time" class="fw-bold">{{ __('Estado del Pago') }}</label>
                            <select name="payment_status" class="form-control">
                                <option value="">Seleccionar un estado</option>
                                <option value="pendiente" class="text-warning" {{ $reservation->payment_status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="pagado" class="text-success" {{ $reservation->payment_status == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                <option value="fallido" class="text-danger" {{ $reservation->payment_status == 'fallido' ? 'selected' : '' }}>Fallido</option>
                            </select>
                        </div>

                        <!-- Monto a Pagar -->
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                <label for="total_amount" class="form-label">{{ __('Total a pagar (USD)') }}</label>
                                <input type="text" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ old('total_amount', $reservation->total_amount) }}" readonly>
                                @error('total_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message}}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
    
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('reservations.index') }}" class="btn btn-danger">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-warning ms-3">Editar</button>
                    </div>
                </div>

            </div>

        </form>
    </div>
    

</div>


@endsection

@push('scripts')

<script>
   document.getElementById('start_time').addEventListener('change', function() {
    const startTime = this.value;

    if (startTime) {
        // Crear hora de inicio manualmente a partir del valor seleccionado
        const [hours, minutes] = startTime.split(':').map(Number);
        let endHours = hours + 1; // Sumar una hora
        if (endHours >= 24) endHours -= 24; // Ajustar por si pasa de medianoche

        // Formatear la hora de fin como HH:MM
        const endTime = `${endHours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        
        // Actualizar los valores en los campos correspondientes
        document.getElementById('end_time').value = endTime;

        // Calcular el total basado en el precio por hora
        const pricePerHour = 50; // Define el precio por hora
        const total = pricePerHour; // Siempre será una hora
        document.getElementById('total_amount').value = total.toFixed(2);
    } else {
        // Si no se selecciona hora, limpiar campos dependientes
        document.getElementById('end_time').value = "";
        document.getElementById('total_amount').value = "";
    }
});

</script>

     
@endpush