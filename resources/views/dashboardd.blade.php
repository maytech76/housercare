<!doctype html>
<html lang="es" dir="ltr">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
		<meta name="Author" content="Spruko Technologies Private Limited">
		<meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4">

		<!-- Title -->
		<title>Admin</title>

		<!-- Favicon -->
		<link rel="icon" href="{{asset('admin/img/brand/favicon.png')}} " type="image/x-icon">

		<!-- Icons css -->
		<link href="{{asset('admin/css/icons.css')}} " rel="stylesheet">

		<!-- Bootstrap css -->
		<link id="style" href="{{('admin/plugins/bootstrap/css/bootstrap.min.css')}} " rel="stylesheet">

		<!-- style css -->
		<link href="{{asset('admin/css/style.css')}} " rel="stylesheet">
		<link href="{{asset('admin/css/plugins.css')}} " rel="stylesheet">

		<!--- Animations css-->
		<link href="{{asset('admin/css/animate.css')}} " rel="stylesheet">

	</head>

	<body class="ltr main-body app sidebar-mini">

		<!-- Loader -->
		<div id="global-loader">
			<img src="{{asset('admin/img/svgicons/loader.svg')}} " class="loader-img" alt="Loader">
		</div>
		<!-- /Loader -->

    <!-- Page -->
    <div class="page">
			<div>

				<!-- INICIO DEL HEADER-->
				<div class="main-header side-header sticky nav nav-item">
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



                                    {{-------------- INICIO MENU MENSAJES ------------------}}
										{{-- <li class="dropdown nav-item main-header-message ">
											<a class="new nav-link" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg><span class=" pulse-danger"></span></a>
											<div class="dropdown-menu">
												<div class="menu-header-content bg-primary text-start">
													<div class="d-flex">
														<h6 class="dropdown-title mb-1 tx-15 text-white fw-semibold">Messages</h6>
														<a href="javascript:void(0);" class="badge rounded-pill bg-warning ms-auto my-auto float-end">Mark All Read</a>
													</div>
													<p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">You have 4 unread messages</p>
												</div>
												<div class="main-message-list chat-scroll">
													<div class="p-3 d-flex border-bottom messages">
														<div class="  drop-img  cover-image  " data-bs-image-src="{{asset('admin/img/users/3.jpg')}} ">
															<span class="avatar-status bg-teal"></span>
														</div>
														<div class="wd-90p">
															<div class="d-flex">
																<a href="chat.html"><h5 class="mb-1 name">Petey Cruiser</h5></a>
															</div>
															<p class="mb-0 desc">I'm sorry but i'm not sure how to help you with that......</p>
															<p class="time mb-0 text-start float-start ms-2 mt-2">Mar 15 3:55 PM</p>
														</div>
													</div>
													<div class="p-3 d-flex border-bottom messages">
														<div class="drop-img cover-image" data-bs-image-src="{{asset('admin/img/users/2.jpg')}} ">
															<span class="avatar-status bg-teal"></span>
														</div>
														<div class="wd-90p">
															<div class="d-flex">
																<a href="chat.html"><h5 class="mb-1 name">Jimmy Changa</h5></a>
															</div>
															<p class="mb-0 desc">All set ! Now, time to get to you now......</p>
															<p class="time mb-0 text-start float-start ms-2 mt-2">Mar 06 01:12 AM</p>
														</div>
													</div>
													<div class="p-3 d-flex border-bottom messages">
														<div class="drop-img cover-image" data-bs-image-src="{{asset('admin/img/users/9.jpg')}} ">
															<span class="avatar-status bg-teal"></span>
														</div>
														<div class="wd-90p">
															<div class="d-flex">
																<a href="chat.html"><h5 class="mb-1 name">Graham Cracker</h5></a>
															</div>
															<p class="mb-0 desc">Are you ready to pickup your Delivery...</p>
															<p class="time mb-0 text-start float-start ms-2 mt-2">Feb 25 10:35 AM</p>
														</div>
													</div>
													<div class="p-3 d-flex border-bottom messages">
														<div class="drop-img cover-image" data-bs-image-src="{{asset('admin/img/users/12.jpg')}} ">
															<span class="avatar-status bg-teal"></span>
														</div>
														<div class="wd-90p">
															<div class="d-flex">
																<a href="chat.html"><h5 class="mb-1 name">Donatella Nobatti</h5></a>
															</div>
															<p class="mb-0 desc">Here are some products ...</p>
															<p class="time mb-0 text-start float-start ms-2 mt-2">Feb 12 05:12 PM</p>
														</div>
													</div>
													<div class="p-3 d-flex border-bottom messages">
														<div class="drop-img cover-image" data-bs-image-src="{{asset('admin/img/users/5.jpg')}} ">
															<span class="avatar-status bg-teal"></span>
														</div>
														<div class="wd-90p">
															<div class="d-flex">
																<a href="chat.html"><h5 class="mb-1 name">Anne Fibbiyon</h5></a>
															</div>
															<p class="mb-0 desc">I'm sorry but i'm not sure how...</p>
															<p class="time mb-0 text-start float-start ms-2 mt-2">Jan 29 03:16 PM</p>
														</div>
													</div>
												</div>
												<div class="text-center dropdown-footer">
													<a href="chat.html">VIEW ALL</a>
												</div>
											</div>
										</li> --}}
                                    {{-------------- INICIO MENU MENSAJES ------------------}}


                                        
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
															<h6>Maydev</h6><span>Admin</span>
														</div>
													</div>
												</div>
												<a class="dropdown-item" href="profile.html"><i class="bx bx-user-circle"></i>Perfil</a>
												<a class="dropdown-item" href="editprofile.html"><i class="bx bx-cog"></i>Editar Perfil</a>
												{{-- <a class="dropdown-item" href="mail.html"><i class="bx bxs-inbox"></i>Inbox</a> --}}
												{{-- <a class="dropdown-item" href="chat.html"><i class="bx bx-envelope"></i>Messages</a> --}}
												{{-- <a class="dropdown-item" href="editprofile.html"><i class="bx bx-slider-alt"></i> Account Settings</a> --}}
												<a class="dropdown-item" href="signin.html"><i class="bx bx-log-out"></i> Salir</a>
											</div>
										</li>

                                    {{-------- FINAL MENU PERFIL DE USUARIO --------}}
										<li class="dropdown main-header-message right-toggle">
											<a class="nav-link pe-0" data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right">
												<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- FINAL DEL HEADER-->

				{{------ INICIO MENU SIDEBAR ------}}
                    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                    <div class="sticky">
                        <aside class="app-sidebar sidebar-scroll">

                            {{---------- Logo de Empresa ---------------}}
                            <div class="main-sidebar-header active">
                                <a class="desktop-logo logo-light active" href="index.html"><img src="{{asset('admin/img/brand/logo.png')}} " class="main-logo" alt="logo"></a>
                                <a class="desktop-logo logo-dark active" href="index.html"><img src="{{asset('admin/img/brand/logo-white.png')}} " class="main-logo" alt="logo"></a>
                                <a class="logo-icon mobile-logo icon-light active" href="index.html"><img src="{{asset('admin/img/brand/favicon.png')}} " alt="logo"></a>
                                <a class="logo-icon mobile-logo icon-dark active" href="index.html"><img src="{{asset('admin/img/brand/favicon-white.png')}} " alt="logo"></a>
                            </div>

                            <div class="main-sidemenu">

                                <div class="app-sidebar__user clearfix">
                                    <div class="dropdown user-pro-body">
                                        <div class="main-img-user avatar-xl">
                                            <img alt="user-img" src="{{asset('admin/img/users/6.jpg')}} "><span class="avatar-status profile-status bg-green"></span>
                                        </div>
                                        <div class="user-info">
                                            <h4 class="fw-semibold mt-3 mb-0">name Usuario</h4>
                                            <span class="mb-0 text-muted">Rol de Usuario</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
                                <ul class="side-menu">
                                    <li class="side-item side-item-category">Administrativo</li>
                                        <li class="slide">
                                            <a class="side-menu__item" href="index.html"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Categorias</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" href="index.html"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Productos</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" href="index.html"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Clientes</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" href="index.html"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Proveedores</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" href="index.html"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Inventario</span></a>
                                        </li>

                                    <li class="side-item side-item-category">General</li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"  viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 4c-4.42 0-8 3.58-8 8s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm3.5 4c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5zm-7 0c.83 0 1.5.67 1.5 1.5S9.33 11 8.5 11 7 10.33 7 9.5 7.67 8 8.5 8zm3.5 9.5c-2.33 0-4.32-1.45-5.12-3.5h1.67c.7 1.19 1.97 2 3.45 2s2.76-.81 3.45-2h1.67c-.8 2.05-2.79 3.5-5.12 3.5z" opacity=".3"/><circle cx="15.5" cy="9.5" r="1.5"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="M12 16c-1.48 0-2.75-.81-3.45-2H6.88c.8 2.05 2.79 3.5 5.12 3.5s4.32-1.45 5.12-3.5h-1.67c-.69 1.19-1.97 2-3.45 2zm-.01-14C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg><span class="side-menu__label">Icons</span><i class="angle fe fe-chevron-down"></i></a>
                                            <ul class="slide-menu">
                                                <li class="side-menu__label1"><a href="javascript:void(0);">Icons</a></li>
                                                <li><a class="slide-item" href="icons.html">Font Awesome </a></li>
                                                <li><a class="slide-item" href="icons-2.html">Material Design Icons</a></li>
                                                <li><a class="slide-item" href="icons-3.html">Simple Line Icons</a></li>
                                                <li><a class="slide-item" href="icons-4.html">Feather Icons</a></li>
                                                <li><a class="slide-item" href="icons-5.html">Ionic Icons</a></li>
                                                <li><a class="slide-item" href="icons-6.html">Flag Icons</a></li>
                                                <li><a class="slide-item" href="icons-7.html">Pe7 Icons</a></li>
                                                <li><a class="slide-item" href="icons-8.html">Themify Icons</a></li>
                                                <li><a class="slide-item" href="icons-9.html">Typicons Icons</a></li>
                                                <li><a class="slide-item" href="icons-10.html">Weather Icons</a></li>
                                                <li><a class="slide-item" href="icons-11.html">Material Icons</a></li>
                                                <li><a class="slide-item" href="icons-12.html">Bootstrap Icons</a></li>
                                            </ul>
                                        </li>			
                                                
                                </ul>
                                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
                            </div>
                        </aside>
                    </div>
	            {{------ FINAL MENU SIDEBAR ------}}
			</div>

        
        
            {{------ PAGINA CENTRAL PRINCIPAL ------}}

			<!-- main-content -->
			<div class="main-content app-content">

				<!-- container -->
				<div class="main-container container-fluid">


                 {{------------------------------------------------------------}}
                 
                    <!-- breadcrumb -->
                    <div class="breadcrumb-header justify-content-between">
                        <div class="my-auto">
                            <h4 class="page-title">Administración</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Usuarios</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Gestion de Usuario</li>
                            </ol>
                        </div>
                        <div class="d-flex my-xl-auto right-content align-items-center">
                            
                            <div class="mb-xl-0">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false">
                                        14 Aug 2019
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- breadcrumb -->

                    {{-----Star Body-----}}



                    {{-----End Body------}}

                 {{------------------------------------------------------------}}


				</div>
				<!-- Container closed -->
			</div>
			<!-- main-content closed -->

           {{------ FINAL PAGINA CENTRAL PRINCIPAL ------}}

			
		

			<!-- Footer opened -->
			<div class="main-footer">
				<div class="container-fluid pd-t-0 ht-100p">
					<span> Copyright © 2024 <a href="https://maydev.tech" class="text-success">Admin</a></span> de <a href="https://maydev.tech">Maydev Spa </a> All rights reserved.</span>
				</div>
			</div>
			<!-- Footer closed -->

    </div>
    <!-- End Page -->

		<!-- Back-to-top -->
		<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>

		<!-- JQuery min js -->
		<script src="{{asset('admin/plugins/jquery/jquery.min.js')}} "></script>

		<!-- Bootstrap Bundle js -->
		<script src="{{asset('admin/plugins/bootstrap/js/popper.min.js')}} "></script>
		<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.min.js')}} "></script>

		<!-- Moment js -->
		<script src="{{asset('admin/plugins/moment/moment.js')}} "></script>

		<!-- P-scroll js -->
		<script src="{{asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}} "></script>
		<script src="{{asset('admin/plugins/perfect-scrollbar/p-scroll.js')}} "></script>

		<!-- Sidebar js -->
		<script src="{{asset('admin/plugins/side-menu/sidemenu.js')}} "></script>

		<!-- Right-sidebar js -->
		<script src="{{asset('admin/plugins/sidebar/sidebar.js')}} "></script>
		<script src="{{asset('admin/plugins/sidebar/sidebar-custom.js')}} "></script>

		<!-- Sticky js -->
		<script src="{{asset('admin/js/sticky.js')}} "></script>

		<!-- eva-icons js -->
		<script src="{{asset('admin/js/eva-icons.min.js')}} "></script>

		<!--themecolor js-->
		<script src="{{asset('admin/js/themecolor.js')}} "></script>

		<!-- custom js -->
		<script src="{{asset('admin/js/custom.js')}} "></script>

		<!-- switcher-styles js -->
		<script src="{{asset('admin/js/swither-styles.js')}} "></script>

	</body>
</html>
