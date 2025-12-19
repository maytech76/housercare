@extends('admin.layouts.master')

@section('content')


        {{-- Table of all Events --}}
        <div class="row row-sm mt-4 mx-auto">
		
			<!-- container -->
			<div class="main-container container-fluid">

                <!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<h4 class="page-title">Dashboard Eventos</h4>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0);">Admin</a></li>
							<li class="breadcrumb-item active" aria-current="page">Eventos</li>
						</ol>
					</div>
					<div class="d-flex my-xl-auto right-content align-items-center">
						
						<div class="mb-xl-0">
							<div class="dropdown">

								<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo date('d-m-Y'); ?>
								</button>
								
							</div>
						</div>

					</div>
				</div>
				<!-- breadcrumb -->

                  <!-- row  listado de eventos-->
				<div class="row row-sm">

                    @foreach($events as $event)
					<div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
						<a href="{{ route('events.guests', $event->id) }}">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" value="{{$event->id}}" name="id">
                                    <div class="card-order">
                                        <h6 class="mb-2 text-success">{{$event->title}} </h6>
                                        <h6 class="mb-0" style="color: #000">{{$event->event_date}} </h6>
                                        <h2 class="text-end ">
                                            <i class="mdi mdi-account-multiple icon-size float-start text-danger text-primary-shadow"></i>
                                            <span>{{ $event->guests_count }}</span>
                                        </h2>

                                        <p style="color: #000"> Total Invitados : <span class="text-danger my-0">{{$event->limit_guest}} </span></p>
                                        
                                        @php
                                            $percentage = 0;
                                            if ($event->limit_guest > 0) {
                                                $percentage = min(round(($event->guests_count / $event->limit_guest) * 100), 100);
                                            }
                                            
                                            // Determinar clase CSS según el porcentaje
                                            $progressClass = 'progress-bar';
                                            if ($percentage >= 90) {
                                                $progressClass .= ' bg-danger';
                                            } elseif ($percentage >= 60) {
                                                $progressClass .= ' bg-warning';
                                            } else {
                                                $progressClass .= ' bg-success';
                                            }
                                        @endphp

                                        <p class="my-0">
                                            <h6 style="color: #313030">
                                                {{ $event->guests_count }} de {{ $event->limit_guest }} invitados
                                                <span class="float-end">{{ $percentage }}%</span>
                                            </h6>
                                        </p>
                                                                            

                                    </div>
                                </div>
                            </div>
                        </a>
					</div>
                    @endforeach


				</div>
				<!-- /row -->

            </div>
        </div>
<!-- Paginación -->
{{-- <div class="mt-4">
    {{ $events->links() }}
</div>  --}}

@endsection

@push('scripts')


@endpush