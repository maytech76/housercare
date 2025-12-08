<div class="main-header side-header sticky nav nav-item mb-10">
    <div class="container-fluid main-container ">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="index.html" class="header-logo">
                    <img src="{{asset('admin/img/brand/logo.png')}} " class="logo-1" alt="logo">
                    <img src="{{asset('admin/img/brand/logo-white.png')}} " class="dark-logo-1" alt="logo">
                </a>
            </div>
            <div class="app-sidebar__toggle" data-bs-toggle="sidebar">
                <a class="open-toggle" href="javascript:void(0);"><i class="header-icon fe fe-align-left" ></i></a>
                <a class="close-toggle" href="javascript:void(0);"><i class="header-icons fe fe-x"></i></a>
            </div>
            <div class="main-header-center ms-3 d-sm-none d-md-none d-lg-block">
                
            </div>
        </div>
        <div class="main-header-right">
            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon fe fe-more-vertical "></span>
            </button>

            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                    <ul class="nav nav-item  navbar-nav-right ms-auto">

                    {{-------------- INICIO BOTON TEMA CLARO-OSCURO ------------------}}
                        <li class="dropdown nav-item main-layout">
                            <a class="new nav-link theme-layout nav-link-bg layout-setting" >
                                <span class="dark-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z"/></svg></span>
                                <span class="light-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z"/></svg></span>
                            </a>
                        </li>
                    {{-------------- FINAL BOTON TEMA CLARO-OSCURO ------------------}}

                        
                    {{-------- BOTON FULL SCREEN --------}}
                    
                        <li class="nav-item full-screen fullscreen-button">
                            <a class="new nav-link full-screen-link" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg></a>
                        </li> 

                    {{--------FINAL BOTON FULL SCREEN --------}}


                    {{-------- MENU PERFIL DE USUARIO --------}}
                        <li class="dropdown main-profile-menu nav nav-item nav-link">
                            <a class="profile-user d-flex" href=""><img alt="" src="{{asset('admin/img/users/6.jpg')}} "></a>
                            <div class="dropdown-menu">
                                <div class="main-header-profile bg-primary p-3">
                                    <div class="d-flex wd-100p">
                                        <div class="main-img-user"><img alt="" src="{{asset('admin/img/users/6.jpg')}} " class=""></div>
                                        <div class="ms-3 my-auto">
                                            @auth

                                            <h6>{{Auth::user()->name}} </h6><span>{{Auth::user()->role->name}} </span>
                                                
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                                <a class="dropdown-item" href="#"><i class="bx bx-user-circle"></i>Perfil</a>
                                <a class="dropdown-item" href="#"><i class="bx bx-cog"></i>Editar Perfil</a>
                                
                               
                                   
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                   <a class="dropdown-item" href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                       <i class="bx bx-log-out"></i>
                                     Salir
                                   </a>
                                 </form>
                                    
                                
                                
                            </div>
                        </li>
                    {{-------- FINAL MENU PERFIL DE USUARIO --------}}

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>