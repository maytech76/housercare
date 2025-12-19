<x-guest-layout>
   
        
   <!-- Page -->
	<div class="page">

		<div class="container-fluid">
			<div class="row no-gutter">
				<!-- The image half -->
				<div class="col-md-12 col-lg-12 col-xl-7 d-none d-md-flex bg-primary-transparent p-0">
					<div class="row">
						<div class="col-md-12 col-lg-12">
							<img src="{{asset('admin/img/pngs/bg_2.jpg')}}"
                            class="w-100 h-100" 
                            style="object-fit: cover;"
							 alt="logo">
						</div>
					</div>
				</div>
				
                <div class="col-md-6 col-lg-6 col-xl-5 bg-white py-4">
                    <div class="login d-flex align-items-center py-2">
                        <!-- Demo content-->
                        <div class="container p-0">
                            <div class="row">
                                <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                    <div class="card-sigin">
                                        <div class="mb-1 d-flex ">
                                            <a><img src="{{asset('admin/img/brand/logo.png')}}"
                                                    class="desktop-logo logo-light active" alt="logo">
                                            </a>
                                        </div>
                                        <div class="card-sigin">
                                            <div class="main-signup-header">
                                                <h3>Bienvenido de Nuevo</h3>
                                                {{-- <h5 class="fw-normal mb-4">Ingresar Credenciales..</h5> --}}
                
                                                 {{-- -------------------------------------------- --}}
                
                                                <form method="POST" action="{{ route('login') }}">
                                                    @csrf
                
                                                    <div class="form-group">
                                                        <label>Email</label> <input class="form-control border border-primary rounded" type="email" name="email" :value="old('email')"
                                                            placeholder="Ingresa tu Email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Password</label> 
                                                        <input class="form-control border border-primary rounded" type="password" name="password" required autocomplete="current-password"
                                                            placeholder="Ingresa tu password">
                                                    </div>
                                                    <button class="btn btn-main-primary btn-block uppercase text-center">ingresar</button>
                
                                                   
                                                </form>
                
                                               {{--  -------------------------------------------- --}}
                
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End -->
                    </div>
                </div><!-- End -->
				
			</div>
		</div>

	</div>
	<!-- End Page -->   
        
    
</x-guest-layout>


