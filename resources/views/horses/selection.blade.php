@extends('admin.layouts.master')

@section('content')
   
    <secttion>

        <div class="main-container container-fluid">

            <!-- breadcrumb -->
            <div class="breadcrumb-header justify-content-between">
                <div class="my-auto">
                    <h4 class="page-title">Administración</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">gestions</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestion de Procesos</li>
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
        </div>

        <!-- CONTENEDOR PRINCIPAL CENTRADO -->
        <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
            <div class="row justify-content-center">

                {{-- Cards Sectors --}}
                <div class="col-xl-3 col-lg-6 col-sm-12 mb-4">
                    <a href="{{ route('sectors.show')}}" class="text-decoration-none">
                        <div class="card h-100">
                            <img alt="Image" class="img-fluid card-img-top" src="../admin/img/photos/movent.jpg">
                            <div class="card-body d-flex text-center">
                                <h5 class="card-text flex-grow-1 fw-light text-dark text-dark-mode" style="font-weight: 200;">Manager Movents</h5>
                            </div>
                        </div>
                    </a>
                </div>

                 {{-- Cards Assigned Especials --}}
                <div class="col-xl-3 col-lg-6 col-sm-12 mb-4">
                    <a href="{{ route('sectors.show2')}}" class="text-decoration-none">
                        <div class="card h-100">
                            <img alt="Image" class="img-fluid card-img-top" src="../admin/img/photos/especial.jpg">
                            <div class="card-body text-center">
                                <h5 class="card-text flex-grow-1 fw-light text-dark text-dark-mode" style="font-weight: 200;"">Manager Asignaciones</h5>
                            </div>
                        </div>
                    </a>
                </div>

                 {{-- Cards-Supply --}}
                <div class="col-xl-3 col-lg-6 col-sm-12 mb-4">
                    <a href="{{ route('horsecard.index')}}" class="text-decoration-none">
                        <div class="card h-100">
                            <img alt="Image" class="img-fluid card-img-top" src="../admin/img/photos/supply.jpg">
                            <div class="card-body d-flex text-center">
                                <h5 class="card-text flex-grow-1 fw-light text-dark text-dark-mode" style="font-weight: 200;">Manager Supplies</h5>
                            </div>
                        </div>
                    </a>    
                </div>

            </div>
        </div>

        {{-- <style>
            .adaptive-text {
                color: #000000 !important;
            }
            
            @media (prefers-color-scheme: dark) {
                .adaptive-text {
                    color: #ffffff !important;
                }
            }
            
            /* Para Bootstrap dark mode */
            [data-bs-theme="dark"] .adaptive-text {
                color: #ffffff !important;
            }
        </style> --}}
        

    </secttion>
@endsection