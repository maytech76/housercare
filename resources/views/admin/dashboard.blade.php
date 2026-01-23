@extends('admin.layouts.master')


@section('content')

@section('title', 'Dashboard')
<section>

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
                            {{ $date }}
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

                    {{-- Horses --}}
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <a href="#">
                            <div class="card overflow-hidden sales-card bg-danger-gradient">
                                <div class="px-3 pt-3  pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">HORSES</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h5 class="tx-20 fw-bold mb-1 text-white">{{ $horsesCount }} </h5>
                                                <p class="mb-0 tx-12 text-white op-7">Total de Caballos</p>
                                            </div>
                                            <span class="float-end my-auto ms-auto">
                                                <i class="fa fa-horse text-white"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Especiales --}}
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <a href="#">
                            <div class="card overflow-hidden sales-card bg-primary-gradient">
                                <div class="px-3 pt-3  pb-2">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">ESPECIALS</h6>
                                    </div>
                                    <div class="pb-2 mt-0">
                                        <div class="d-flex justify-content-between gap-3">


                                            <div class="text-center">
                                                <h6 class="mb-1 text-white">Totales</h6>
                                                <h6 class="mb-0 text-white">{{ $especialtStats['total'] ?? 0 }}</h6>
                                            </div>

                                            <div class="text-center">
                                                <h6 class="mb-1 text-white">Asigned</h6>
                                                <h6 class="mb-0 text-white">{{ $especialtStats['assigned'] ?? 0 }}</h6>
                                            </div>

                                            <div class="text-center">
                                                <h6 class="mb-1 text-white">Executed</h6>
                                                <h6 class="mb-0 text-white">{{ $especialtStats['executed'] ?? 0 }}</h6>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>


                    {{-- Movements --}}
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <div class="card overflow-hidden sales-card bg-success-gradient">
                                    
                            <div class="px-3 pt-3  pb-2">
                                <div class="">
                                    <h6 class="mb-3 tx-12 text-white">MOVEMENTS</h6>
                                </div>

                                <div class="pb-2 mt-0">
                                    <div class="d-flex justify-content-between gap-3">
                                        <!-- gap-3 para separación de 1rem -->

                                        <div class="text-center">
                                            <h6 class="mb-1 text-white">Asigned</h6>
                                            <h6 class="mb-0 text-white">{{ $movementStats['assigned'] ?? 0 }}</h6>
                                        </div>

                                        <div class="text-center">
                                            <h6 class="mb-1 text-white">Executed</h6>
                                            <h6 class="mb-0 text-white">{{ $movementStats['executed'] ?? 0 }}</h6>
                                        </div>

                                        <div class="text-center">
                                            <h6 class="mb-1 text-white">Partially</h6>
                                            <h6 class="mb-0 text-white">{{ $movementStats['partially'] ?? 0 }}</h6>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- Supplies --}}
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <div class="card overflow-hidden sales-card bg-warning-gradient">
                            <div class="px-3 pt-3  pb-2 pt-0">
                                <div class="">
                                    <h6 class="mb-3 tx-12 text-white">SUPPLIES</h6>
                                </div>
                                <div class="pb-0 mt-0">
                                    <div class="d-flex">

                                        <div class="pb-2 mt-0">
                                            <div class="d-flex justify-content-between gap-3">
                                                <!-- gap-3 para separación de 1rem -->

                                                <div class="text-center">
                                                    <h6 class="mb-1 text-white">Total</h6>
                                                    <h6 class="mb-0 text-white">{{ $suppliestStats['total'] ?? 0 }}</h6>
                                                </div>

                                                <div class="text-center">
                                                    <h6 class="mb-1 text-white">Asigned</h6>
                                                    <h6 class="mb-0 text-white">{{ $suppliestStats['assigned'] ?? 0 }}</h6>
                                                </div>

                                                <div class="text-center">
                                                    <h6 class="mb-1 text-white">Executed</h6>
                                                    <h6 class="mb-0 text-white">{{ $suppliestStats['executed'] ?? 0 }}</h6>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- row closed -->

                <!-- row opened -->
                <div class="row">

                    {{-- CartBar Specials --}}
                    <div class="col-md-12 col-lg-12 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
                                    SPECIAL ASSIGNMENTS
                                </div>
                                <p class="mg-b-20">Specials Executed Weekly</p>
                                <div class="ht-200 ht-lg-250">
                                    <!-- Asegúrate que este canvas tenga el ID correcto -->
                                    <canvas id="chartBar1" width="450" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CartBar Movements --}}
                    <div class="col-md-12 col-lg-12 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
                                    MOVEMENTS
                                </div>
                                <p class="mg-b-20">Movements Executed Weekly</p>
                                <div class="ht-200 ht-lg-250">
                                    <!-- Asegúrate que este canvas tenga el ID correcto -->
                                    <canvas id="chartBar2" width="450" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CartBar Supplies --}}
                    <div class="col-md-12 col-lg-12 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
                                    SUPPLIES
                                </div>
                                <p class="mg-b-20">Supplies Executed Weekly</p>
                                <div class="ht-200 ht-lg-250">
                                    <!-- Asegúrate que este canvas tenga el ID correcto -->
                                    <canvas id="chartBar3" width="450" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>



                <!-- /Container -->
            </div>
            <!-- /main-content -->

        </div>

    </div>


</section>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{asset('admin/js/chart.chartjs.js')}}"></script>

{{-- Asiganación de Gráfica de Barras - Specials --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* POLAR AREA CHART CON GRADIENTES - 7 DÍAS */
        var ctx9 = document.getElementById('chartBar1').getContext('2d');
        
        // Crear gradientes para cada día
        function createGradient(color1, color2) {
            var gradient = ctx9.createRadialGradient(0, 0, 0, 0, 0, 150);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        }
        
        new Chart(ctx9, {
            type: 'polarArea',
            data: {
                labels: [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'SunDay'
                ],
                datasets: [{
                    label: '# Services',
                    data: [12, 39, 20, 10, 25, 18, 10],
                    backgroundColor: [
                        createGradient('#FF6384', '#FFB1C1'),      // Lunes - Rojo
                        createGradient('#FF9F40', '#FFC988'),      // Martes - Naranja
                        createGradient('#FFCD56', '#FFEAA7'),      // Miércoles - Amarillo
                        createGradient('#4BC0C0', '#A6E3E3'),      // Jueves - Verde azulado
                        createGradient('#36A2EB', '#9BD0F5'),      // Viernes - Azul
                        createGradient('#9966FF', '#CCB3FF'),      // Sábado - Púrpura
                        createGradient('#C9CBCF', '#575654')       // Domingo - Gris
                    ],
                    borderColor: [
                        '#FF6384',
                        '#FF9F40',
                        '#FFCD56',
                        '#4BC0C0',
                        '#36A2EB',
                        '#9966FF',
                        '#C9CBCF'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: "rgba(171, 167, 167,0.9)",
                            font: {
                                size: 11
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw + ' Services';
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    }
                }
            }
        });
   });
</script>

{{-- Movements de Gráfica de Barras - Movements --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* USING GRADIENTE COLOR GRADIENT 3 two Colors */
        var ctx9 = document.getElementById('chartBar2').getContext('2d');
        var gradient = ctx9.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, '#02993C');
        gradient.addColorStop(1, '#8CF5B4');
        new Chart(ctx9, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: '# Movements',
                    data: [22, 49, 30, 15, 20, 12, 15],
                    backgroundColor: gradient
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                },
                scales: {
                    y: {
                        ticks: {
                            beginAtZero: true,
                            fontSize: 10,
                            max: 80,
                            fontColor: "rgba(171, 167, 167,0.9)",
                        },
                        grid: {
                            display: true,
                            color: 'rgba(171, 167, 167,0.2)',
                            drawBorder: false
                        },
                    },
                    x: {
                        barPercentage: 0.6,
                        ticks: {
                            beginAtZero: true,
                            fontSize: 11,
                            fontColor: "rgba(171, 167, 167,0.9)",
                        },
                        grid: {
                            display: true,
                            color: 'rgba(171, 167, 167,0.2)',
                            drawBorder: false
                        },
                    }
                }
            }
        });
   });
</script>

{{-- Suministros de Gráfica de Barras - Suministros --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* DOUGHNUT CHART CON GRADIENTE - 7 DÍAS DE LA SEMANA */
        var ctx9 = document.getElementById('chartBar3').getContext('2d');
        
        // Crear gradientes para cada segmento
        function createGradient(color1, color2) {
            var gradient = ctx9.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        }
        
        new Chart(ctx9, {
            type: 'doughnut',
            data: {
                labels: [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'SunDay'
                ],
                datasets: [{
                    label: '# Supplies',
                    data: [22, 23, 21, 24, 26, 15, 21],
                    backgroundColor: [
                        createGradient('#FF6384', '#FFB1C1'),      // Lunes
                        createGradient('#36A2EB', '#9BD0F5'),      // Martes
                        createGradient('#FFCE56', '#FFEAA7'),      // Miércoles
                        createGradient('#4BC0C0', '#A6E3E3'),      // Jueves
                        createGradient('#9966FF', '#CCB3FF'),      // Viernes
                        createGradient('#FF9F40', '#FFC988'),      // Sábado
                        createGradient('#C9CBCF', '#403F3E')       // Domingo
                    ],
                    borderColor: [
                        '#ccc',
                        '#ccc',
                        '#ccc',
                        '#ccc',
                        '#ccc',
                        '#ccc',
                        '#ccc'
                    ],
                    borderWidth: 2,
                    hoverBackgroundColor: [
                        createGradient('#FF6384', '#FF6384'),
                        createGradient('#36A2EB', '#36A2EB'),
                        createGradient('#FFCE56', '#FFCE56'),
                        createGradient('#4BC0C0', '#4BC0C0'),
                        createGradient('#9966FF', '#9966FF'),
                        createGradient('#FF9F40', '#FF9F40'),
                        createGradient('#C9CBCF', '#C9CBCF')
                    ],
                    hoverBorderColor: '#FFFFFF',
                    hoverBorderWidth: 3,
                    hoverOffset: 15
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                cutout: '60%', // Controla el tamaño del agujero central
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: "rgba(171, 167, 167,0.9)",
                            font: {
                                size: 11
                            },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} supplies (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 2000
                },
                elements: {
                    arc: {
                        borderWidth: 2,
                        borderJoinStyle: 'round'
                    }
                }
            }
        });
   });
</script>
@endpush
