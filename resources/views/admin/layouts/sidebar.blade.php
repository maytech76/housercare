<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                     <div class="sticky">
                        <aside class="app-sidebar sidebar-scroll">

                            {{---------- Logo de Empresa ---------------}}
                            <div class="main-sidebar-header active">
                                <a class="desktop-logo logo-light active"href="{{route('dashboard')}}"><img src="{{asset('admin/img/brand/logo.png')}} " class="main-logo" alt="logo"></a>
                                <a class="desktop-logo logo-dark active" href="{{route('dashboard')}}"><img src="{{asset('admin/img/brand/logo-white.png')}} " class="main-logo" alt="logo"></a>
                                <a class="logo-icon mobile-logo icon-light active" href="{{route('dashboard')}}"><img src="{{asset('admin/img/brand/favicon.png')}} " alt="logo"></a>
                                <a class="logo-icon mobile-logo icon-dark active" href="{{route('dashboard')}} "><img src="{{asset('admin/img/brand/favicon-white.png')}} " alt="logo"></a>
                            </div>

                            @auth
                            <div class="main-sidemenu ">

                                <div class="app-sidebar__user clearfix">
                                    <div class="">
                                       {{--  <div class="main-img-user avatar-xl">
                                            <img alt="user-img" src="{{asset('admin/img/brand/avatar.png')}} "><span class="avatar-status profile-status bg-green"></span>
                                        </div>
                                        <div class="user-info">
                                            <h4 class="fw-semibold mt-3 mb-0">Company</h4>
                                            <span class="mb-0 text-muted">Denver</span>
                                        </div> --}}
                                    </div>
                                </div> 

                               {{--  <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div> --}}

                                {{-- Menu Administrador --}}
                                @if (Auth::user()->rol_id == 2)

                                <ul class="side-menu">

                                    <li class="side-item side-item-category mt-4">Miscellaneous</li>

                                        {{-- Categorias --}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('categories.index')}} "><i class="fa fa-bars"></i><span class="mx-3 side-menu__label">Categories</span></a>
                                        </li>

                                        {{-- Store --}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('stores.index')}} "><i class="fa fa-industry"></i><span class="mx-3 side-menu__label">Stores</span></a>
                                        </li>

                                        {{-- Units --}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('units.index')}} "><i class="fa fa-puzzle-piece"></i><span class="mx-3 side-menu__label">Units</span></a>
                                        </li>


                                        {{-- Productos --}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('products.index')}} "><i class="fa fa-shopping-basket"></i><span class="mx-3 side-menu__label">Products</span></a>
                                        </li>

                                        

                                        {{-- Sectors--}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('sectors.index')}} "><i class="far fa-map"></i><span class="mx-3 side-menu__label">Sectors</span></a>
                                        </li>

                                        {{-- Location--}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('stables.index')}} "><i class="fa fa-map-pin"></i><span class="mx-3 side-menu__label">Location</span></a>
                                        </li>
                                       
                                        {{-- Caballo --}}
                                        <li class="slide">
                                            <a class="side-menu__item"  href="{{route('horses.index')}}"><i class="far fa-star"></i><span class="mx-3 side-menu__label">Horses</span></a>
                                        </li>

                                        {{-- Seleccion de Procesos--}}
                                        <li class="slide">
                                            <a class="side-menu__item"  href="{{route('horses.selection')}}"><i class="fa fa-cogs"></i><span class=" mx-3 side-menu__label">Select Process</span></a>
                                        </li>
                                    </li>

                                       	

                                    <li class="side-item side-item-category">Administrative</li>
                                                
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('movements.index')}} "><i class="fas fa-external-link-alt"></i><span class="mx-3 side-menu__label"> Assign Movements</span></a>
                                        </li>

                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('horse-movements')}} "><i class="fa fa-gavel"></i><span class="mx-3 side-menu__label">Direct Movement</span></a>
                                        </li>

                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('inventory-controls.index')}}"><i class="fa fa-database"></i><span class="mx-3 side-menu__label">Op inventory</span></a>
                                        </li>

                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('supplies.index')}}"><i class="fas fa-utensils"></i><span class="mx-3 side-menu__label">Supplies</span></a>
                                        </li>

                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('services.index')}} "><i class="fa fa-sitemap"></i><span class="mx-3 side-menu__label">Services Esp</span></a>
                                        </li>

                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('assignments.index')}} "><i class="fa fa-check"></i><span class="mx-3 side-menu__label">Assignments Esp</span></a>
                                        </li>

                                               
                                        		
                                                
                                    </li>
                                    
                                </ul>

                                @endif

                                {{-- Menu Manager --}}
                                @if (Auth::user()->rol_id == 3)

                                <li class="side-item side-item-category mt-4">Manager</li>

                                    {{-- Seleccion de Procesos--}}
                                    <li class="slide">
                                        <a class="side-menu__item"  href="{{route('horses.selection')}}"><i class="fa fa-cogs"></i><span class=" mx-3 side-menu__label">Select Process</span></a>
                                    </li>


                                </li>

                                @endif

                                {{-- Menu Property --}}
                                @if (Auth::user()->rol_id == 4)

                                 
                                    
                                @endif

                                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
                            </div>
                            @endauth

                        </aside>
                    </div>