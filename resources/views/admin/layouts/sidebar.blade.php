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
                            <div class="main-sidemenu">

                                <div class="app-sidebar__user clearfix">
                                    <div class="dropdown user-pro-body">
                                        <div class="main-img-user avatar-xl">
                                            <img alt="user-img" src="{{asset('admin/img/brand/avatar.png')}} "><span class="avatar-status profile-status bg-green"></span>
                                        </div>
                                        <div class="user-info">
                                            <h4 class="fw-semibold mt-3 mb-0">Company</h4>
                                            <span class="mb-0 text-muted">Denver</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>

                                {{-- Menu Administrador --}}
                                @if (Auth::user()->rol_id == 1)
                                <ul class="side-menu">

                                    <li class="side-item side-item-category">Miscellaneous</li>

                                        {{-- Categorias --}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('categories.index')}} "><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Categories</span></a>
                                        </li>

                                        {{-- Store --}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('stores.index')}} "><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Stores</span></a>
                                        </li>

                                        {{-- Units --}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('units.index')}} "><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Units</span></a>
                                        </li>


                                        {{-- Productos --}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('products.index')}} "><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Products - Services</span></a>
                                        </li>

                                        

                                        {{-- Location--}}
                                        <li class="slide">
                                            <a class="side-menu__item" href="{{route('stables.index')}} "><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Location</span></a>
                                        </li>
                                       
                                        {{-- Caballo --}}
                                        <li class="slide">
                                            <a class="side-menu__item"  href="{{route('horses.index')}}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Horses</span></a>
                                        </li>

                                        {{-- Seleccion de Procesos--}}
                                        <li class="slide">
                                            <a class="side-menu__item"  href="{{route('horses.selection')}}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Select Process</span></a>
                                        </li>

                                       	

                                    <li class="side-item side-item-category">Administrative</li>

                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><span class="side-menu__label">Procesos</span><i class="angle fe fe-chevron-down"></i></a>
                                            <ul class="slide-menu">
                                                {{-- Sectores --}}
                                                <li class="slide">
                                                    <a class="slide-item" href="{{route('sectors.index')}} "><span class="side-menu__label">Sectors</span></a>
                                                </li>

                                                <li class="slide">
                                                    <a class="slide-item" href="{{route('horse-movements')}} "><span class="side-menu__label">Movements</span></a>
                                                </li>

                                                <li><a class="slide-item" href="{{route('inventory-controls.index')}}">Op inventory</a></li>
                                                <li><a class="slide-item" href="{{route('supplies.index')}}">Suministros</a></li>
                                                <li><a class="slide-item" href="{{route('services.index')}} ">Services</a></li>
                                                <li><a class="slide-item" href="{{route('assignments.index')}} ">Assignments</a></li>

                                            </ul>
                                        </li>			
                                                
                                </ul>
                                @endif

                                {{-- Menu Asesor --}}
                                @if (Auth::user()->rol_id == 2)
                                <ul class="side-menu">
                                    <li class="side-item side-item-category">Asesor</li>
                                    <li class="slide">
                                        <a class="side-menu__item" href="# "><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Calendario</span></a>
                                    </li>
                                </ul>
                                @endif

                                @if (Auth::user()->rol_id == 3)

                                 <ul class="side-menu">
                                    <li class="side-item side-item-category">Cliente</li>
                                        
                                        <li class="slide mb-2">
                                            <a class="side-menu__item" href="{{route('cliente.reservas')}} "><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Reserva</span></a>
                                        </li>
                                        <li class="slide mb-2">
                                            <a class="side-menu__item" href="{{route('cliente.calendario')}} "><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Calendario</span></a>
                                        </li>
                                       
                                    </li>
                                 </ul>
                                    
                                @endif

                                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
                            </div>
                            @endauth

                        </aside>
                    </div>