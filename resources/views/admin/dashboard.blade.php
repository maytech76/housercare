
@extends('admin.layouts.master')


@section('content')

@section('title', 'Dashboard') 
    <secttion>

     <div class="main-container container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <h4 class="page-title">Dashboard</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Panel</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Administrativo</li>
                </ol>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="mb-xl-0">
                    <div class="dropdown">
                        <button class="btn btn-dark-gradient btn-block" aria-expanded="false">
                            {{$date}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->

        <div class="row">
                    
            <!-- container -->
                <div class="main-container container-fluid">

                    <!-- row -->
                    <div class="row row-sm">

                        {{-- Privados Unico --}}
                        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                            <a href="#">
                              <div class="card overflow-hidden sales-card bg-primary-gradient">
                                 <div class="px-3 pt-3  pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">PRIVADOS</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h5 class="tx-20 fw-bold mb-1 text-white">Exclusivos</h5>
                                                <p class="mb-0 tx-12 text-white op-7">por Invitado</p>
                                            </div>
                                            <span class="float-end my-auto ms-auto">
                                                <i class="fa fa-user text-white"></i>
                                            </span>
                                        </div>
                                    </div>
                                  </div>                      
                                </div> 
                            </a>
                        </div>

                        {{-- Eventos Grupales --}}
                        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                            <a href="#">
                              <div class="card overflow-hidden sales-card bg-danger-gradient">
                                <div class="px-3 pt-3  pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">PERSONALES</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h5 class="tx-20 fw-bold mb-1 text-white">Grupo Social</h5>
                                                <p class="mb-0 tx-12 text-white op-7">Invitación por Grupo</p>
                                            </div>
                                            <span class="float-end my-auto ms-auto">
                                                <i class="fa fa-sitemap text-white"></i>
                                            </span>
                                        </div>
                                    </div>
                                 </div>     
                              </div>
                            </a>
                        </div>

                        {{-- Eventos Certificados --}}
                        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                            <div class="card overflow-hidden sales-card bg-success-gradient">
                                <div class="px-3 pt-3  pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">PROFESIONALES</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h4 class="tx-20 fw-bold mb-1 text-white">Certificados</h4>
                                                <p class="mb-0 tx-12 text-white op-7">Profesionales & Academicos </p>
                                            </div>
                                            <span class="float-end my-auto ms-auto">
                                                <i class="far fa-address-card text-white"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>

                        {{-- Eventos Deportivos --}}
                        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                            <div class="card overflow-hidden sales-card bg-warning-gradient">
                                <div class="px-3 pt-3  pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">ATLETAS</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h4 class="tx-20 fw-bold mb-1 text-white">Competencias</h4>
                                                <p class="mb-0 tx-12 text-white op-7">Deporte & Recreación</p>
                                            </div>
                                            <span class="float-end my-auto ms-auto">
                                                <i class="fa fa-trophy text-white"></i>
                                              
                                                {{-- <span class="text-white op-7"> -152.3</span> --}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>

                    </div>
                    <!-- row closed -->

                    <!-- row opened -->
                    <div class="row row-sm">
                        <div class="col-md-12 col-lg-12 col-xl-7">
                            <div class="card">
                                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title mb-0">Order status</h4>
                                        <a href="javascript:void(0);" class="tx-inverse" data-bs-toggle="dropdown"><i
                                            class="mdi mdi-dots-horizontal text-gray"></i></a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0);">Action</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Another
                                                Action</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Something Else
                                                Here</a>
                                        </div>
                                    </div>
                                    <p class="tx-12 text-muted mb-0">Order Status and Tracking. Track your order from ship date to arrival. To begin, enter your order number.</p>
                                </div>
                                <div class="card-body b-p-apex">
                                    <div class="total-revenue">
                                        <div>
                                        <h4>120,750</h4>
                                        <label><span class="bg-primary"></span>success</label>
                                        </div>
                                        <div>
                                        <h4>56,108</h4>
                                        <label><span class="bg-danger"></span>Pending</label>
                                        </div>
                                        <div>
                                        <h4>32,895</h4>
                                        <label><span class="bg-warning"></span>Failed</label>
                                        </div>
                                    </div>
                                    <div id="bar" class="sales-bar mt-4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-5">
                            <div class="card card-dashboard-map-one">
                                <label class="main-content-label">Sales Revenue by Customers in USA</label>
                                <span class="d-block mg-b-20 text-muted tx-12">Sales Performance of all states in the United States</span>
                                <div class="cl-71">
                                    <div class="vmap-wrapper ht-180" id="vmap12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                

                    
                <!-- /Container -->
            </div>
            <!-- /main-content -->

        </div>
        
      </div>


    </secttion>
                   

@endsection

