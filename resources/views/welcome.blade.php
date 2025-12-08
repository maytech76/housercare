<!DOCTYPE html>
<html lang="es">

	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="Description" content="Sistema de gestion para el cuidado y entrenamiento de Caballos">
        <meta name="Author" content="[Marco Antonio Yanez, Jesus Boves] - CEO & Especialista en Tecnología Web">
        <meta name="Keywords" content="Solutions, innovación web, desarrollo aplicaciones, arquitectura software, liderazgo tech, soluciones digitales, transformación empresarial">
        <meta property="og:image" content="https://horsecare.maydev.tech/images/avatar.png">
        <meta property="og:url" content="https://horsecare.maydev.tech">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="[Maydev.Tech]">
		<!-- Title -->
		<title> Horse|Care </title>

		<!--- Favicon -->
		<link rel="icon" href="{{asset('admin/img/brand/favicon.png')}}" type="image/x-icon">

		<!--- Icons css -->
		<link href="{{asset('admin/css/icons.css')}}" rel="stylesheet">

		<!-- Bootstrap css -->
		<link id="style" href="{{asset('admin/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet'">

		<!-- style css -->
		<link href="{{asset('admin/css/style.css')}}" rel="stylesheet">

	</head>

	<body class="app sidebar-mini ltr landing-page horizontalmenu">



    <!-- Page -->
    <div class="page">
        <div class="page-main">

            <!-- Main Header-->
            <div class="main-header side-header">
                <div class="main-container container-fluid">
                    <div class="main-header-left">
                        <a class="main-header-menu-icon" href="javascript:void(0)"
                            id="mainSidebarToggle"><span></span></a>
                        <div class="hor-logo">
                            <a class="main-logo" href="index.html">
                                <img src="{{asset('admin/img/brand/logo-white.png')}}" class="header-brand-img desktop-logo"
                                    alt="logo">
                                <img src="{{asset('admin/img/brand/logo.png')}}" class="header-brand-img desktop-logo-dark"
                                    alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="main-header-center">
                        <div class="responsive-logo">
                            <a href="index.html"><img src="{{asset('admin/img/brand/logo.png')}}" class="mobile-logo" alt="logo"></a>
                            <a href="index.html"><img src="{{asset('admin/img/brand/logo-white.png')}}" class="mobile-logo-dark" alt="logo"></a>
                        </div>
                    </div>
                    <div class="main-header-right">
                        <button class="navbar-toggler navresponsive-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
                        </button><!-- Navresponsive closed -->
                        <div
                            class="navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark  ">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <div class="d-flex order-lg-2 ms-auto">
                                    <!-- SEARCH -->
                                    <div class="header-nav-right p-3">
                                        <a href="signup.html" class="btn ripple btn-min w-sm btn-primary me-2"
                                            target="_blank">Logins
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Main Header-->

            <div class="landing-top-header overflow-hidden">
                <div class="top sticky">
                    <!--APP-SIDEBAR-->
                    <div class="landing-app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                    <div class="landing-app-sidebar bg-transparent">
                        <div class="container">
                            <div class="row">
                                <div class="main-sidemenu navbar px-0">
                                    <a class="main-logo" href="index.html">
                                        <img src="{{asset('admin/img/brand/logo-white.png')}}" class="header-brand-img desktop-logo"
                                            alt="logo">
                                        <img src="{{asset('admin/img/brand/logo.png')}}"
                                            class="header-brand-img desktop-logo-dark" alt="logo">
                                    </a>
                                    <div class="slide-left disabled" id="slide-left"><svg
                                            xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                                        </svg></div>
                                    <ul class="side-menu">
                                        <li class="slide">
                                            <a class="side-menu__item active" data-bs-toggle="slide" href="#home"><span
                                                    class="side-menu__label">Inicio</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#Features"><span
                                                    class="side-menu__label">Servicios</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#Tecnologies"><span
                                                    class="side-menu__label">Técnologias</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#About"><span
                                                    class="side-menu__label">Nosotros</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#faqs-2"><span
                                                    class="side-menu__label">Noticias</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#sectionfaq"><span
                                                    class="side-menu__label">Preguntas</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#Clients"><span
                                                    class="side-menu__label">Clientes</span></a>
                                        </li>
                                        <li class="slide">
                                            <a class="side-menu__item" data-bs-toggle="slide" href="#Contact"><span
                                                    class="side-menu__label">Contacto</span></a>
                                        </li>
                                    </ul>
                                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg"
                                            fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                            <path
                                                d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                                        </svg></div>
                                    <div class="header-nav-right d-none d-lg-block">
                                        <a href="{{ route('login') }}" class="btn ripple btn-min w-sm btn-primary me-2">
                                            Sistema
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/APP-SIDEBAR-->
                </div>
                <div class="demo-screen-headline main-demo main-demo-1 spacing-top overflow-hidden reveal" id="home">
                    <div class="container px-sm-0">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 animation-zidex pos-relative">
                                <h4 class="fw-semibold mt-7">Manage Your Business</h4>
                                <h1 class="text-start fw-bold">We Help to Build Your Dream Project with Valex !</h1>
                                <h6 class="pb-3">
                                    Valex - Now you can use this admin template to design stunning dashboards
                                    that will wow your target viewers or users to no end. To create a good and
                                    well-structured dashboard,
                                    you need to start from scratch with HTML, SCSS, CSS, and JS and with lots of coding,
                                    but by using this Valex-Admin template.</h6>

                                <a href="index.html"
                                    target="_blank" class="btn ripple btn-min w-lg mb-3 me-2 btn-primary"><i
                                        class="fe fe-play me-2"></i> Get Started
                                </a>
                                <a href="https://themeforest.net/user/spruko/portfolio"
                                    class="btn ripple btn-min w-lg btn-outline-primary mb-3 me-2" target="_blank"><i
                                        class="fe fe-eye me-2"></i>Discover More
                                </a>
                            </div>
                            {{-- --- IMAGEN DE PORTADA ---- --}}
                            <div class="col-xl-6 col-lg-6 my-auto">
                                <img src="{{asset('admin/img/pngs/14.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--app-content open-->
            <div class="main-content mt-0 ms-0">
                <div class="side-app ">

                    <!-- ROW-2 OPEN -->
                    <div class="sptb section bg-white-2" id="Features">
                        <div class="container">
                            <div class="row">
                                <h4 class="text-center fw-semibold landing-card-header">Features</h4>
                                <span class="landing-title"></span>
                                <h2 class="fw-semibold text-center">Valex Main Features</h2>
                                <p class="text-default mb-5 text-center">The Valex admin template comes with
                                    ready-to-use features that are completely easy-to-use for any user, even for
                                    a beginner.</p>
                                    <div class="row mt-7">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card features main-features main-features-4 wow fadeInUp reveal revealleft active p-4" data-wow-delay="0.1s">
                                                <div class="bg-img mb-2 text-left">
                                                    <svg width="50" height="50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><circle cx="64" cy="64" r="64" fill="#42A3DB"/><path fill="#347CBE" d="M85.5 26.6 66.1 61 33.3 98.6 62.6 128H64c33.7 0 61.3-26 63.8-59.1L85.5 26.6z"/><path fill="#CD2F30" d="M73.1 57.7h-16c3.6 18.7 11.1 36.6 22.1 52.5.3-5 1-9.8 1.8-14.5 4.6 1.3 9.2 2.3 13.7 3-9.7-12.2-17-26.1-21.6-41z"/><path fill="#F04D45" d="M54.9 57.7c-4.6 15-11.9 28.9-21.6 40.9 4.5-.7 9.1-1.7 13.7-3 .9 4.7 1.5 9.5 1.8 14.5 11-15.9 18.4-33.8 22.1-52.5h-16z"/><path fill="#FFF" d="M93.5 52c1.8-1.8 1.8-4.7 0-6.5-1.3-1.3-1.7-3.3-1-5 1-2.4-.1-5-2.5-6-1.7-.7-2.8-2.4-2.8-4.3 0-2.5-2.1-4.6-4.6-4.6-1.9 0-3.5-1.1-4.3-2.8-1-2.4-3.7-3.5-6-2.5-1.7.7-3.7.3-5-1-1.8-1.8-4.7-1.8-6.5 0-1.3 1.3-3.3 1.7-5 1-2.4-1-5 .1-6 2.5-.7 1.7-2.4 2.8-4.3 2.8-2.5 0-4.6 2.1-4.6 4.6 0 1.9-1.1 3.5-2.8 4.3-2.4 1-3.5 3.7-2.5 6 .7 1.7.3 3.7-1 5-1.8 1.8-1.8 4.7 0 6.5 1.3 1.3 1.7 3.3 1 5-1 2.4.1 5 2.5 6 1.7.7 2.8 2.4 2.8 4.3 0 2.5 2.1 4.6 4.6 4.6 1.9 0 3.5 1.1 4.3 2.8 1 2.4 3.7 3.5 6 2.5 1.7-.7 3.7-.3 5 1 1.8 1.8 4.7 1.8 6.5 0 1.3-1.3 3.3-1.7 5-1 2.4 1 5-.1 6-2.5.7-1.7 2.4-2.8 4.3-2.8 2.5 0 4.6-2.1 4.6-4.6 0-1.9 1.1-3.5 2.8-4.3 2.4-1 3.5-3.7 2.5-6-.7-1.7-.3-3.7 1-5z"/><path fill="#FFCD0A" d="M64 70.8c-12.2 0-22.1-9.9-22.1-22.1 0-12.2 9.9-22.1 22.1-22.1 12.2 0 22.1 9.9 22.1 22.1 0 12.2-9.9 22.1-22.1 22.1z"/><path fill="#FFF" d="M59.9 61c-.6 0-1.1-.2-1.5-.7l-8.3-9.2c-.7-.8-.7-2.1.1-2.8.8-.7 2.1-.7 2.8.1l6.7 7.5 15.1-18.8c.7-.9 2-1 2.8-.3.9.7 1 2 .3 2.8L61.4 60.2c-.3.5-.9.8-1.5.8z"/></svg>
                                                </div>
                                                <div class="text-left">
                                                    <h4 class="fw-bold">Quality &amp; Clean Code</h4>
                                                    <p class="mb-0">The Valex admin code is maintained very cleanly
                                                        and well-structured with proper comments.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card features main-features main-features-2 wow fadeInUp reveal revealleft active p-4" data-wow-delay="0.1s">
                                                <div class="bg-img mb-2 text-left">
                                                    <svg width="50" height="50" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 128 128" viewBox="0 0 128 128"><circle cx="64" cy="64" r="63.5" fill="#54C0EB"/><path fill="#84DBFF" d="M19.2,109c11.5,11.4,27.3,18.5,44.8,18.5c17.5,0,33.3-7.1,44.8-18.5H19.2z"/><rect width="19.6" height="10.4" x="54.2" y="92.7" fill="#FFF"/><rect width="19.6" height="2.3" x="54.2" y="92.7" fill="#E6E9EE"/><path fill="#E6E9EE" d="M82.2,109H45.8l0,0c0-3.3,2.7-6,6-6h24.4C79.5,103.1,82.2,105.7,82.2,109L82.2,109z"/><path fill="#324A5E" d="M103,92.7H25c-2.4,0-4.4-2-4.4-4.4V34.7c0-2.4,2-4.4,4.4-4.4h78c2.4,0,4.4,2,4.4,4.4v53.7   C107.4,90.7,105.4,92.7,103,92.7z"/><path fill="#FFF" d="M20.6,84v4.4c0,2.4,1.9,4.3,4.3,4.3H103c2.4,0,4.3-1.9,4.3-4.3V84H20.6z"/><rect width="80.3" height="46.9" x="23.9" y="33.4" fill="#FFF"/><circle cx="100.3" cy="88.3" r="2" fill="#FF7058"/><circle cx="94.7" cy="88.3" r="2" fill="#4CDBC4"/><circle cx="89.1" cy="88.3" r="2" fill="#54C0EB"/><rect width="9.7" height="27.7" x="32.3" y="46.7" fill="#ACB3BA"/><rect width="9.7" height="15.8" x="45.7" y="58.7" fill="#4CDBC4"/><rect width="9.7" height="23.1" x="59.1" y="51.3" fill="#FFD05B"/><rect width="9.7" height="35.7" x="72.6" y="38.7" fill="#84DBFF"/><rect width="9.7" height="8.1" x="86" y="66.3" fill="#FF7058"/></svg>
                                                </div>
                                                <div class="text-left">
                                                    <h4 class="fw-bold">Multiple Demos</h4>
                                                    <p class="mb-0">
                                                        We included multiple demos, preview video, and screen shots
                                                        to give a quick overview of our Valex admin template.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card features main-features main-features-3 wow fadeInUp reveal revealleft active p-4" data-wow-delay="0.1s">
                                                <div class="bg-img mb-2 text-left">
                                                    <svg width="50" height="50" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 128 128" viewBox="0 0 128 128"><circle cx="64" cy="64" r="63.5" fill="#54C0EB"/><path fill="#FFF" d="M42.2,96H23.6c-1.6,0-2.8-1.3-2.8-2.8V34.8c0-1.6,1.3-2.8,2.8-2.8h18.6c1.6,0,2.8,1.3,2.8,2.8v58.3   C45.1,94.7,43.8,96,42.2,96z"/><rect width="18.7" height="36.8" x="23.6" y="35.8" fill="#4CDBC4"/><circle cx="32.9" cy="83.9" r="7.2" fill="#E6E9EE"/><circle cx="32.9" cy="83.9" r="5" fill="#324A5E"/><path fill="#FFF" d="M68.8,96H50.2c-1.6,0-2.8-1.3-2.8-2.8V34.8c0-1.6,1.3-2.8,2.8-2.8h18.6c1.6,0,2.8,1.3,2.8,2.8v58.3   C71.6,94.7,70.4,96,68.8,96z"/><rect width="18.7" height="36.8" x="50.1" y="35.8" fill="#FF7058"/><circle cx="59.5" cy="83.9" r="7.2" fill="#E6E9EE"/><circle cx="59.5" cy="83.9" r="5" fill="#324A5E"/><path fill="#FFF" d="M109,92.7l-18,4.6c-1.5,0.4-3.1-0.5-3.5-2.1L73.2,38.7c-0.4-1.5,0.5-3.1,2.1-3.5l18-4.6   c1.5-0.4,3.1,0.5,3.5,2.1l14.3,56.5C111.5,90.8,110.6,92.4,109,92.7z"/><rect width="18.7" height="36.8" x="80.4" y="36.1" fill="#FFD05B" transform="rotate(-14.193 89.778 54.551)"/><circle cx="97" cy="83.2" r="7.2" fill="#E6E9EE"/><circle cx="97" cy="83.2" r="5" fill="#324A5E"/></svg>
                                                </div>
                                                <div class="text-left">
                                                    <h4 class="fw-bold">Widgets</h4>
                                                    <p class="mb-0">
                                                        30+ widgets are included in this template. Please check out
                                                        the best option that suits you and implement it in your
                                                        projects.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card features main-features main-features-4 wow fadeInUp reveal revealleft active p-4" data-wow-delay="0.1s">
                                                <div class="bg-img mb-2 text-left">
                                                    <svg width="50" height="50" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 128 128" viewBox="0 0 128 128"><circle cx="64" cy="64" r="63.5" fill="#FFD05B"/><path fill="#FFF" d="M30,103.8l0-79.7c0-1.8,1.5-3.3,3.3-3.3h50.1l0,11.4c0,1.8,1.5,3.3,3.3,3.3H98l0,68.3   c0,1.8-1.5,3.3-3.3,3.3H33.3C31.5,107.1,30,105.6,30,103.8z"/><path fill="#E6E9EE" d="M83.3,20.9h11.4c1.8,0,3.3,1.5,3.3,3.3l0,11.4H86.6c-1.8,0-3.3-1.5-3.3-3.3L83.3,20.9z"/><path fill="#CED5E0" d="M83.3,20.9h11.4c1.8,0,3.3,1.5,3.3,3.3l0,11.4L83.3,20.9z"/><rect width="54.6" height="2.4" x="36.7" y="50.7" fill="#E6E9EE"/><rect width="54.6" height="2.4" x="36.7" y="58.2" fill="#E6E9EE"/><rect width="54.6" height="2.4" x="36.7" y="65.8" fill="#E6E9EE"/><rect width="54.6" height="2.4" x="36.7" y="73.4" fill="#E6E9EE"/><rect width="23.5" height="2.4" x="67.8" y="80.9" fill="#84DBFF"/><rect width="23.5" height="2.4" x="67.8" y="88.5" fill="#84DBFF"/><rect width="54.6" height="2.4" x="36.7" y="43.1" fill="#E6E9EE"/><rect width="29.6" height="2.4" x="36.7" y="35.6" fill="#84DBFF"/><path fill="#FF7058" d="M41.1,83.3c-4.4,4.4-4.4,11.5,0,15.9s11.5,4.4,15.9,0c4.4-4.4,4.4-11.5,0-15.9   C52.6,78.9,45.5,78.9,41.1,83.3z M41.9,84.1c3.4-3.4,8.7-3.8,12.6-1.3l-1.6,1.6c-3-1.7-6.9-1.3-9.5,1.2c-2.6,2.6-3,6.5-1.2,9.5   l-1.6,1.6C38.1,92.8,38.5,87.5,41.9,84.1z M43.1,94.3c-1.3-2.5-0.9-5.7,1.2-7.7c2.1-2.1,5.2-2.5,7.7-1.2L43.1,94.3z M54.9,88.2   c1.3,2.5,0.9,5.7-1.2,7.7c-2.1,2.1-5.2,2.5-7.7,1.2L54.9,88.2z M56.1,98.3c-3.4,3.4-8.7,3.8-12.6,1.3l1.6-1.6   c3,1.7,6.9,1.3,9.5-1.2c2.6-2.6,3-6.5,1.2-9.5l1.6-1.6C60,89.6,59.5,94.9,56.1,98.3z"/></svg>
                                                </div>
                                                <div class="text-left">
                                                    <h4 class="fw-bold">Validation Forms</h4>
                                                    <p class="mb-0">
                                                        Different types of “Form Validation” are implemented in this
                                                        Valex admin template and used strict validation rules.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card features main-features main-features-5 wow fadeInUp reveal revealleft p-4" data-wow-delay="0.1s">
                                                <div class="bg-img mb-2 text-left">
                                                    <svg width="50" height="50" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 128 128" viewBox="0 0 128 128"><circle cx="64" cy="64" r="63.5" fill="#90DFAA"/><path fill="#FFF" d="M95.8,19.4H32.2c-1.9,0-3.4,1.5-3.4,3.4v82.5c0,1.9,1.5,3.4,3.4,3.4h63.7c1.9,0,3.4-1.5,3.4-3.4V22.7   C99.2,20.9,97.7,19.4,95.8,19.4z"/><polygon fill="#324A5E" points="88.5 90.2 43 90.2 43 34.2 39.5 34.2 39.5 93.8 88.5 93.8"/><rect width="4.9" height="44.8" x="48" y="41.3" fill="#FF7058"/><rect width="4.9" height="38.3" x="58.1" y="47.8" fill="#84DBFF"/><rect width="4.9" height="31.2" x="68.3" y="54.9" fill="#FFD05B"/><rect width="4.9" height="22.7" x="78.4" y="63.3" fill="#CED5E0"/></svg>
                                                </div>
                                                <div class="text-left">
                                                    <h4 class="fw-bold">9 Types of Charts</h4>
                                                    <p class="mb-0">
                                                        We included nine (9) types of the best possible chart
                                                        options for your project. You can customize with your
                                                        requirement.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card features main-features main-features-6 mb-4 wow fadeInUp reveal revealleft p-4" data-wow-delay="0.1s">
                                                <div class="bg-img mb-2 text-left">
                                                    <svg width="50" height="50" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 128 128" viewBox="0 0 128 128"><circle cx="64" cy="64" r="63.5" fill="#54C0EB"/><path fill="#FFF" d="M28.8,22.7v82.5c0,1.9,1.5,3.4,3.4,3.4H84V96.8c0-1.9,1.5-3.4,3.4-3.4h11.8V22.7c0-1.9-1.5-3.4-3.4-3.4   H32.2C30.3,19.4,28.8,20.9,28.8,22.7z"/><path fill="#E6E9EE" d="M84,108.6h11.8c1.9,0,3.4-1.5,3.4-3.4V93.5H87.4c-1.9,0-3.4,1.5-3.4,3.4V108.6z"/><path fill="#CED5E0" d="M84,108.6h11.8c1.9,0,3.4-1.5,3.4-3.4V93.5L84,108.6z"/><rect width="6.3" height="11.8" x="47.6" y="39.6" fill="#CED5E0"/><rect width="6.3" height="21.1" x="56.4" y="30.3" fill="#FFD05B"/><rect width="6.3" height="17.1" x="65.3" y="34.2" fill="#84DBFF"/><rect width="6.3" height="24.5" x="74.2" y="26.8" fill="#FF7058"/><rect width="37.4" height="1.8" x="45.3" y="51.4" fill="#324A5E"/><rect width="52.7" height="2.6" x="37.7" y="73.5" fill="#E6E9EE"/><rect width="52.7" height="2.6" x="37.7" y="67.2" fill="#E6E9EE"/><rect width="52.7" height="2.6" x="37.7" y="60.9" fill="#E6E9EE"/><rect width="52.7" height="2.6" x="37.7" y="79.7" fill="#E6E9EE"/><rect width="38" height="2.6" x="37.7" y="86" fill="#E6E9EE"/></svg>
                                                </div>
                                                <div class="text-left">
                                                    <h4 class="fw-bold">Documentation</h4>
                                                    <p class="mb-0">
                                                        The documentation provides clear-cut material for the Valex
                                                        admin template. The documentation is explained in such a way that every user can understand.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-2 CLOSED -->

                    <!-- ROW-2 OPEN -->
                    <div class="section testimonial-owl-landing feature-section" id="Tecnologies">
                        <div class="container">
                            <div class="row">
                                <div class="card bg-transparent mb-0">
                                    <h4 class="text-center fw-semibold text-white landing-card-header">Features</h4>
                                    <span class="landing-title"></span>
                                    <div class="demo-screen-skin code-quality pt-2" >
                                        <div class="text-center p-0">
                                            <h2 class="text-center fw-semibold text-white">Features Used in
                                                Valex Admin Template</h2>
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-12 px-0">
                                                        <div class="feature-logos mt-5">
                                                            <div class="slide">
                                                                <img src="{{asset('admin/landing/web/1.png')}}" alt="img">
                                                                <h5 class="mt-3 text-white">Bootstrap5</h5>
                                                            </div>
                                                            <div class="slide">
                                                                <img src="{{asset('admin/landing/web/2.png')}}" alt="img">
                                                                <h5 class="mt-3 text-white">HTML5</h5>
                                                            </div>
                                                            <div class="slide">
                                                                <img src="{{asset('admin/landing/web/3.png')}}" alt="img">
                                                                <h5 class="mt-3 text-white">JQuery</h5>
                                                            </div>
                                                            <div class="slide">
                                                                <img src="{{asset('admin/landing/web/4.png')}}" alt="img">
                                                                <h5 class="mt-3 text-white">Sass</h5>
                                                            </div>
                                                            <div class="slide">
                                                                <img src="{{asset('admin/landing/web/5.png')}}" alt="img">
                                                                <h5 class="mt-3 text-white">Gulp</h5>
                                                            </div>
                                                            <div class="slide">
                                                                <img src="{{asset('admin/landing/web/6.png')}}" alt="img">
                                                                <h5 class="mt-3 text-white">NPM</h5>
                                                            </div>
                                                            <div class="slide">
                                                                <img src="{{asset('admin/landing/web/1.png')}}" alt="img">
                                                                <h5 class="mt-3 text-white">Bootstrap5</h5>
                                                            </div>
                                                            <div class="slide">
                                                                <img src="{{asset('admin/landing/web/2.png')}}" alt="img">
                                                                <h5 class="mt-3 text-white">HTML5</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-2 CLOSED -->

                    <!-- ROW-3 OPEN -->
                    <div class="section bg-landing working-section  bg-white-2" id="About">
                        <div class="container">
                            <div class="row">
                                <h4 class="text-center fw-semibold landing-card-header">Our Mission</h4>
                                <span class="landing-title"></span>
                                <div class="text-center">
                                    <h2 class="text-center fw-semibold">Our mission is to make work meaningful.
                                    </h2>
                                </div>
                                <div class="col-lg-12">
                                    <div class="card bg-transparent">
                                        <div class="card-body text-dark">
                                            <div class="statistics-info">
                                                <div class="row">
                                                    <div class="col-xl-6 col-lg-6 pe-0 my-auto">
                                                        <div class="ps-5">
                                                            <h2 class="text-start fw-semibold fs-25 mb-4">We are
                                                                a creative agency with a passion for design.
                                                            </h2>
                                                            <div class="d-flex reveal revealleft">
                                                                <span><svg style="width:20px;height:20px"
                                                                        viewBox="0 0 24 24">
                                                                        <path fill="#0162e8"
                                                                            d="M23,12L20.56,9.22L20.9,5.54L17.29,4.72L15.4,1.54L12,3L8.6,1.54L6.71,4.72L3.1,5.53L3.44,9.21L1,12L3.44,14.78L3.1,18.47L6.71,19.29L8.6,22.47L12,21L15.4,22.46L17.29,19.28L20.9,18.46L20.56,14.78L23,12M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9L10,17Z" />
                                                                    </svg></span>
                                                                <div class="ms-4 mb-4">
                                                                    <h5 class="fw-bold">Quality & Clean Code
                                                                    </h5>
                                                                    <p>The Valex admin code is maintained very
                                                                        cleanly and well-structured with proper
                                                                        comments.</p>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex reveal revealleft">
                                                                <span><svg style="width:20px;height:20px"
                                                                        viewBox="0 0 24 24">
                                                                        <path fill="#0162e8"
                                                                            d="M23,12L20.56,9.22L20.9,5.54L17.29,4.72L15.4,1.54L12,3L8.6,1.54L6.71,4.72L3.1,5.53L3.44,9.21L1,12L3.44,14.78L3.1,18.47L6.71,19.29L8.6,22.47L12,21L15.4,22.46L17.29,19.28L20.9,18.46L20.56,14.78L23,12M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9L10,17Z" />
                                                                    </svg></span>
                                                                <div class="ms-4 mb-4">
                                                                    <h5 class="fw-bold">Well Documented</h5>
                                                                    <p>
                                                                        The documentation provides clear-cut
                                                                        material for the Valex admin template.
                                                                        The documentation is explained or
                                                                        instructed in such a way that every user
                                                                        can understand.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6">
                                                        <div class="text-center reveal revealleft mb-3">
                                                            <img src="{{asset('admin/img/pngs/16.png')}}"
                                                                alt="" class="br-5">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-3 CLOSED -->


                    <!-- ROW-4 OPEN -->
                    <div class="testimonial-owl-landing">
                        <div class="container">
                            <div class="row">
                                <div class="card bg-transparent mb-0">
                                    <div class="demo-screen-skin code-quality" id="dependencies">
                                        <div class="text-center p-0">
                                            <div class="row justify-content-center">
                                                <div class="row text-center services-statistics landing-statistics">
                                                    <div class="col-xl-12">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="row">
                                                                        <div class="col-xl-3 col-md-6 col-lg-3">
                                                                            <div class=" reveal">
                                                                                <div class="bg-transparent">
                                                                                    <div class="counter-status">
                                                                                        <div class="counter-icon">
                                                                                            <i class="fe fe-server"></i>
                                                                                        </div>
                                                                                        <div
                                                                                            class="test-body text-center">
                                                                                            <h1 class=" mb-0">
                                                                                                <span
                                                                                                    class="counter fw-semibold counter">100</span>
                                                                                                <span class="">+</span>
                                                                                            </h1>
                                                                                            <div class="counter-text">
                                                                                                <h5
                                                                                                    class="font-weight-normal mb-0">
                                                                                                    HTML Pages</h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xl-3 col-md-6 col-lg-3">
                                                                            <div class=" reveal">
                                                                                <div class="bg-transparent">
                                                                                    <div class="counter-status">
                                                                                        <div class="counter-icon">
                                                                                            <i
                                                                                                class="fe fe-life-buoy"></i>
                                                                                        </div>
                                                                                        <div
                                                                                            class="test-body text-center">
                                                                                            <h1 class=" mb-0">
                                                                                                <span
                                                                                                    class="counter fw-semibold counter">60</span>
                                                                                                <span class="">+</span>
                                                                                            </h1>
                                                                                            <div class="counter-text">
                                                                                                <h5
                                                                                                    class="font-weight-normal mb-0">
                                                                                                    Plugins</h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xl-3 col-md-6 col-lg-3">
                                                                            <div class=" reveal">
                                                                                <div class="bg-transparent">
                                                                                    <div class="counter-status">
                                                                                        <div class="counter-icon">
                                                                                            <i
                                                                                                class="fe fe-file"></i>
                                                                                        </div>
                                                                                        <div
                                                                                            class="test-body text-center">
                                                                                            <h1 class=" mb-0">
                                                                                                <span
                                                                                                    class="counter fw-semibold counter">10</span>
                                                                                                <span class="">+</span>
                                                                                            </h1>
                                                                                            <div class="counter-text">
                                                                                                <h5
                                                                                                    class="font-weight-normal mb-0">
                                                                                                    Form Elements</h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xl-3 col-md-6 col-lg-3">
                                                                            <div class=" reveal">
                                                                                <div class="bg-transparent">
                                                                                    <div class="counter-status">
                                                                                        <div class="counter-icon">
                                                                                            <i class="fe fe-grid"></i>
                                                                                        </div>
                                                                                        <div
                                                                                            class="test-body text-center">
                                                                                            <h1 class=" mb-0">
                                                                                                <span
                                                                                                    class="counter fw-semibold counter">30</span>
                                                                                                <span class="">+</span>
                                                                                            </h1>
                                                                                            <div class="counter-text">
                                                                                                <h5
                                                                                                    class="font-weight-normal mb-0">
                                                                                                    Widgets</h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-4 CLOSED -->


                    <!-- ROW-5 OPEN -->
                    <div class="section">
                        <div class="container">
                            <div class="row">
                                <section class="sptb demo-screen-demo faqs" id="faqs-2">
                                    <div class="container">
                                        <div class="row align-items-center">
                                            <h4 class="text-center fw-semibold">Highlights</h4>
                                            <span class="landing-title"></span>
                                            <h2 class="text-center fw-semibold">Template Highlights</h2>
                                            <div class="col-lg-12">
                                                <div class="row justify-content-center">
                                                    <p class="col-lg-9 text-default sub-text mb-7">
                                                        The Valex admin template is one of the modern dashboard
                                                        templates.
                                                        It is also a premium admin dashboard with high-end features,
                                                        where users can easily customize
                                                        or change their projects according to their choice. Please take
                                                        a quick look at our template highlights.
                                                    </p>
                                                </div>
                                                <div class="row" id="grid">
                                                    <div class="col-md-6">
                                                        <div class="col-md-12 grid-item  p-0">
                                                            <div class="card card-collapsed bg-primary-transparent text-primary p-0 highlights  reveal revealleft">
                                                                <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                                    <a href="#" class="card-options-collapse  h5 font-weight-bold  text-primary card-title mb-0"><i class="fe fe-chevron-up me-3"></i>Switch Easily From Vertical to Horizontal </a>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <p>
                                                                        The Slica - Bootstrap Responsive Flat Admin Dashboard HTML5 Template is available in both vertical and horizontal menus.
                                                                        Both menus are managed by single assets. Where users can easily switch from vertical to horizontal menus.
                                                                    </p>
                                                                    <p class="mt-2 mb-3">
                                                                        <span class="font-weight-bold">Note: </span>Please Refer full Documentation
                                                                        for more details.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 grid-item  p-0">
                                                            <div class="card card-collapsed bg-success-transparent p-0  highlights  reveal revealleft">
                                                                <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                                    <a href="#" class="card-options-collapse  h5 font-weight-bold card-title text-success mb-0"><i class="fe fe-chevron-up me-3"></i>Switch Easily From LTR to RTL Version</a>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <p class="mb-3">
                                                                        The Slica - Bootstrap Responsive Flat Admin Dashboard HTML5 Template is available in LRT &amp; RTL versions with single assets.
                                                                        Using those single assets, it’s very easy to switch from one version to another version.
                                                                    </p>
                                                                    <p class="mt-2 mb-3">
                                                                        <span class="font-weight-bold">Note: </span>Please Refer full Documentation
                                                                        for more details.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 grid-item  p-0">
                                                            <div class="card card-collapsed bg-warning-transparent p-0  reveal revealleft">
                                                                <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                                    <a href="#" class="card-options-collapse  h5 font-weight-bold text-warning card-title mb-0"><i class="fe fe-chevron-up me-3"></i>Change Easily Side Menu Styles</a>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <p>
                                                                        The Slica - Bootstrap Responsive Flat Admin Dashboard HTML5 Template is also available in different types of Side Menu Styles.
                                                                        Where the users can change their Side Menu styles by using single assets.
                                                                    </p>
                                                                    <p class="mt-2 mb-3">
                                                                        <span class="font-weight-bold">Note: </span>Please Refer full Documentation
                                                                        for more details.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-12 grid-item  p-0">
                                                            <div class="card card-collapsed bg-info-transparent p-0 reveal revealleft">
                                                                <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                                    <a href="#" class="card-options-collapse  h5 font-weight-bold text-info card-title mb-0"><i class="fe fe-chevron-up me-3"></i>Switch Easily From One Color to Another Color </a>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <p class="mb-3">
                                                                        The Slica - Bootstrap Responsive Flat Admin Dashboard HTML5 Template is available in different types of color styles.
                                                                        Where the users can change their template completely with those color styles.
                                                                    </p>
                                                                    <p class="mt-2 mb-3">
                                                                        <span class="font-weight-bold">Note: </span>Please Refer full Documentation
                                                                        for more details.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 grid-item  p-0">
                                                            <div class="card card-collapsed bg-danger-transparent p-0 reveal revealleft">
                                                                <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                                    <a href="#" class="card-options-collapse  h5 font-weight-bold text-danger card-title mb-0"><i class="fe fe-chevron-up me-3"></i>Switch Easily From Fixed to Scrollable Layout</a>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <p>
                                                                        The Slica - Bootstrap Responsive Flat Admin Dashboard HTML5 Template is also available in two
                                                                        different types of layouts "Fixed Layout" and "Scrollable Layout". Here users
                                                                        can switch their Template from one layout to another layout easily.
                                                                    </p>
                                                                    <p class="mt-2 mb-3">
                                                                        <span class="font-weight-bold">Note: </span>Please Refer full Documentation
                                                                        for more details.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 grid-item  p-0">
                                                            <div class="card card-collapsed bg-secondary-transparent p-0 reveal revealleft">
                                                                <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                                    <a href="#" class="card-options-collapse  h5 font-weight-bold text-secondary card-title mb-0"><i class="fe fe-chevron-up me-3"></i>Switch Easily From Full Width to Boxed Layout</a>
                                                                </div>
                                                                <div class="card-body pt-0">
                                                                    <p>
                                                                        The Slica - Bootstrap Responsive Flat Admin Dashboard HTML5 Template is also available in two different types of layouts
                                                                        “Full Width” and “Boxed” Layouts. So that user can switch their dashboard from one layout to another
                                                                        layout effortlessly.
                                                                    </p>
                                                                    <p class="mt-2 mb-3">
                                                                        <span class="font-weight-bold">Note: </span>Please Refer full Documentation
                                                                        for more details.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-6">
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-5 CLOSED -->


                    <!-- ROW-6 OPEN -->
                    <div class="bg-landing section  bg-white-2">
                        <div class="container">
                            <div class="row">
                                <h4 class="text-center fw-semibold landing-card-header">Choose a plan </h4>
                                <span class="landing-title"></span>
                                <h2 class="text-center fw-semibold">Find the <span class="text-primary">Perfect
                                        Plan</span> for your Business.</h2>
                                <div class="pricing-tabs text-center">
                                    <div class="pri-tabs-heading text-center mt-3">
                                        <ul class="nav nav-price">
                                            <li><a data-bs-toggle="tab" href="#month">Monthly</a></li>
                                            <li><a class="active show" data-bs-toggle="tab"
                                                    href="#annualyear">Annual</a></li>
                                        </ul>
                                    </div>
                                    <div class="tab-content text-start">
                                        <div class="tab-pane pb-0 active show" id="annualyear">
                                            <div class="row d-flex align-items-center justify-content-center">
                                                <div class="col-lg-4 col-xl-4 col-md-8 col-sm-12">
                                                    <div class="card p-3 border  border-primary pricing-card advanced reveal revealrotate">
                                                        <div class="p-4 bg-primary rounded-5 d-block text-justified pt-2 bd-white-2">
                                                            <p class="fs-18 fw-semibold mb-1 pe-0">Advanced<span
                                                                    class="badge bg-white text-primary float-end font-weight-normal">Limited
                                                                    Deal</span></p>
                                                            <p class="text-justify fw-semibold mb-1"> <span
                                                                    class="fs-30 me-2">$</span><span
                                                                    class="fs-30 me-1">1,299</span><span
                                                                    class="fs-25"><span
                                                                        class="op-0-5 text-muted text-20">/</span>
                                                                    year</span></p>
                                                            <p class="fs-13 mb-2">Lorem ipsum dolor sit amet
                                                                consectetur adipisicing elit. Iure quos debitis
                                                                aliquam .</p>
                                                            <p class="fs-13 mb-1 text-primary">Billed monthly on
                                                                regular basis!</p>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <ul class="text-justify pricing-body ps-0 my-4">
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i>
                                                                    <strong> 5 Free</strong> Domain Name
                                                                </li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>5
                                                                    </strong> One-Click Apps</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>
                                                                        3 </strong> Databases</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>
                                                                        Unlimited </strong> Cloud Storage</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>
                                                                        Money </strong> BackGuarantee</li>
                                                                <li class=""><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>
                                                                        24/7</strong> support</li>
                                                            </ul>
                                                        </div>
                                                        <div class="card-footer text-center border-top-0 py-2">
                                                            <button class="btn btn-lg btn-primary">
                                                                <span class="ms-4 me-4">Select</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-xl-4 col-md-8 col-sm-12">
                                                    <div class="card  border  p-3 pricing-card reveal revealrotate">
                                                        <div class="card-header d-block text-justified pt-2">
                                                            <p class="fs-18 fw-semibold mb-1">Basic</p>
                                                            <p class="text-justify fw-semibold mb-1"> <span
                                                                    class="fs-30 me-2">$</span><span
                                                                    class="fs-30 me-1">399</span><span
                                                                    class="fs-25"><span
                                                                        class="op-0-5 text-muted text-20">/</span>
                                                                    year</span></p>
                                                            <p class="fs-13 mb-1">Lorem ipsum dolor sit amet
                                                                consectetur adipisicing elit. Iure quos debitis
                                                                aliquam .</p>
                                                            <p class="fs-13 mb-3 text-secondary">Billed monthly
                                                                on regular basis!</p>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <ul class="text-justify pricing-body ps-0 mb-0">
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline p-2 fs-16 text-secondary"></i><strong>
                                                                        2 Free</strong> Domain Name</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline p-2 fs-16 text-secondary"></i>
                                                                    <strong>3 </strong> One-Click Apps
                                                                </li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline p-2 fs-16 text-gray"></i><strong>
                                                                        1 </strong> Databases</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline p-2 fs-16 text-gray"></i><strong>
                                                                        Unlimited </strong> Cloud Storage</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline p-2 fs-16 text-gray"></i><strong>
                                                                        Money </strong> BackGuarantee</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline p-2 fs-16 text-gray"></i><strong>
                                                                        24/7</strong> support</li>
                                                            </ul>
                                                        </div>
                                                        <div class="card-footer text-center border-top-0 py-2">
                                                            <button class="btn btn-lg btn-outline-secondary ">
                                                                <span class="ms-4 me-4">Select</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-xl-4 col-md-8 col-sm-12">
                                                    <div class="card border  p-3 pricing-card reveal revealrotate">
                                                        <div class="card-header d-block text-justified pt-2">
                                                            <p class="fs-18 fw-semibold mb-1">Regular</p>
                                                            <p class="text-justify fw-semibold mb-1"> <span
                                                                    class="fs-30 me-2">$</span><span
                                                                    class="fs-30 me-1">899</span><span
                                                                    class="fs-25"><span
                                                                        class="op-0-5 text-muted text-20">/</span>
                                                                    year</span></p>
                                                            <p class="fs-13 mb-1">Lorem ipsum dolor sit amet
                                                                consectetur adipisicing elit. Iure quos debitis
                                                                aliquam .</p>
                                                            <p class="fs-13 mb-3  text-danger">Billed monthly on
                                                                regular basis!</p>
                                                        </div>
                                                        <div class="card-body py-2">
                                                            <ul class="text-justify pricing-body ps-0 mb-0">
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-danger p-2 fs-16"></i><strong>
                                                                        1 Free</strong> Domain Name</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-danger p-2 fs-16"></i><strong>4
                                                                    </strong> One-Click Apps</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-danger p-2 fs-16"></i><strong>
                                                                        2 </strong> Databases</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline text-gray p-2 fs-16"></i><strong>
                                                                        Unlimited </strong> Cloud Storage</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline text-gray p-2 fs-16"></i><strong>
                                                                        Money </strong> BackGuarantee</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline text-gray p-2 fs-16"></i><strong>
                                                                        24/7</strong> support</li>
                                                            </ul>
                                                        </div>
                                                        <div class="card-footer text-center border-top-0 py-2">
                                                            <button class="btn btn-lg btn-outline-secondary ">
                                                                <span class="ms-4 me-4">Select</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="month">
                                            <div class="row d-flex align-items-center justify-content-center">
                                                <div class="col-lg-4 col-xl-4 col-md-8 col-sm-12">
                                                    <div class="card p-3 border-primary pricing-card advanced">
                                                        <div class="card-header d-block text-justified pt-2">
                                                            <p class="fs-18 fw-semibold mb-1 pe-0">Advanced<span
                                                                    class="tag bg-primary text-white float-end">Limited
                                                                    Deal</span></p>
                                                            <p class="text-justify fw-semibold mb-1"> <span
                                                                    class="fs-30 me-2">$</span><span
                                                                    class="fs-30 me-1">199</span><span
                                                                    class="fs-25"><span
                                                                        class="op-0-5 text-muted text-20">/</span>
                                                                    month</span></p>
                                                            <p class="fs-13 mb-2">Lorem ipsum dolor sit amet
                                                                consectetur adipisicing elit. Iure quos debitis
                                                                aliquam .</p>
                                                            <p class="fs-13 mb-1 text-primary">Billed monthly on
                                                                regular basis!</p>
                                                        </div>
                                                        <div class="card-body pt-2">
                                                            <ul class="text-justify pricing-body ps-0">
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i>
                                                                    <strong> 5 Free</strong> Domain Name
                                                                </li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>5
                                                                    </strong> One-Click Apps</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>
                                                                        3 </strong> Databases</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>
                                                                        Unlimited </strong> Cloud Storage</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>
                                                                        Money </strong> BackGuarantee</li>
                                                                <li class="mb-5"><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-primary p-2 fs-16"></i><strong>
                                                                        24/7</strong> support</li>
                                                            </ul>
                                                        </div>
                                                        <div class="card-footer text-center border-top-0 py-2">
                                                            <button
                                                                class="btn btn-lg btn-primary text-white">
                                                                <span class="ms-4 me-4">Select</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-xl-4 col-md-8 col-sm-12">
                                                    <div class="card p-3 pricing-card">
                                                        <div class="card-header d-block text-justified pt-2">
                                                            <p class="fs-18 fw-semibold mb-1">Basic</p>
                                                            <p class="text-justify fw-semibold mb-1"> <span
                                                                    class="fs-30 me-2">$</span><span
                                                                    class="fs-30 me-1">39</span><span
                                                                    class="fs-25"><span
                                                                        class="op-0-5 text-muted text-20">/</span>
                                                                    month</span></p>
                                                            <p class="fs-13 mb-1">Lorem ipsum dolor sit amet
                                                                consectetur adipisicing elit. Iure quos debitis
                                                                aliquam .</p>
                                                            <p class="fs-13 mb-1 text-secondary">Billed monthly
                                                                on regular basis!</p>
                                                        </div>
                                                        <div class="card-body pt-2">
                                                            <ul class="text-justify pricing-body ps-0">
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline p-2 fs-16 text-secondary"></i><strong>
                                                                        2 Free</strong> Domain Name</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline p-2 fs-16 text-secondary"></i>
                                                                    <strong>3 </strong> One-Click Apps
                                                                </li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline p-2 fs-16 text-gray"></i><strong>
                                                                        1 </strong> Databases</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline p-2 fs-16 text-gray"></i><strong>
                                                                        Unlimited </strong> Cloud Storage</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline p-2 fs-16 text-gray"></i><strong>
                                                                        Money </strong> BackGuarantee</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline p-2 fs-16 text-gray"></i><strong>
                                                                        24/7</strong> support</li>
                                                            </ul>
                                                        </div>
                                                        <div class="card-footer text-center border-top-0 py-2">
                                                            <button class="btn btn-lg btn-outline-secondary ">
                                                                <span class="ms-4 me-4">Select</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-xl-4 col-md-8 col-sm-12">
                                                    <div class="card p-3 pricing-card">
                                                        <div class="card-header d-block text-justified pt-2">
                                                            <p class="fs-18 fw-semibold mb-1">Regular</p>
                                                            <p class="text-justify fw-semibold mb-1"> <span
                                                                    class="fs-30 me-2">$</span><span
                                                                    class="fs-30 me-1">69</span><span
                                                                    class="fs-25"><span
                                                                        class="op-0-5 text-muted text-20">/</span>
                                                                    month</span></p>
                                                            <p class="fs-13 mb-1">Lorem ipsum dolor sit amet
                                                                consectetur adipisicing elit. Iure quos debitis
                                                                aliquam .</p>
                                                            <p class="fs-13 mb-1  text-danger">Billed monthly on
                                                                regular basis!</p>
                                                        </div>
                                                        <div class="card-body pt-2">
                                                            <ul class="text-justify pricing-body ps-0">
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-danger p-2 fs-16"></i><strong>
                                                                        1 Free</strong> Domain Name</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-danger p-2 fs-16"></i><strong>4
                                                                    </strong> One-Click Apps</li>
                                                                <li><i
                                                                        class="mdi mdi-checkbox-marked-circle-outline text-danger p-2 fs-16"></i><strong>
                                                                        2 </strong> Databases</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline text-gray p-2 fs-16"></i><strong>
                                                                        Unlimited </strong> Cloud Storage</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline text-gray p-2 fs-16"></i><strong>
                                                                        Money </strong> BackGuarantee</li>
                                                                <li class="text-muted"><i
                                                                        class="mdi mdi-close-circle-outline text-gray p-2 fs-16"></i><strong>
                                                                        24/7</strong> support</li>
                                                            </ul>
                                                        </div>
                                                        <div class="card-footer text-center border-top-0 py-2">
                                                            <button class="btn btn-lg btn-outline-secondary ">
                                                                <span class="ms-4 me-4">Select</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-6 CLOSED -->

                    <!-- ROW-7 OPEN -->
                    <div class="section" id="sectionfaq">
                        <div class="container">
                            <div class="row">
                                <h4 class="text-center fw-semibold landing-card-header">FAQ'S ?</h4>
                                <span class="landing-title"></span>
                                <h2 class="text-center fw-semibold">We are here to help you</h2>
                                <div class="row justify-content-center">
                                    <p class="col-xl-9 wow fadeInUp text-default sub-text mb-5" data-wow-delay="0s">
                                        The Valex admin template is one of the modern dashboard templates.
                                        It is also a premium admin dashboard with high-end features, where users
                                        can easily customize
                                        or change their projects according to their choice. Please take a quick
                                        look at our template highlights.
                                    </p>
                                </div>
                                <section class="sptb demo-screen-demo faqs">
                                    <h2 class="d-none">FAQ's</h2>
                                    <div class="row align-items-center">
                                        <div class="col-md-12 col-lg-6 reveal revealright">
                                            <img src="../assets/img/pngs/15.png" alt="">
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="col-md-12 grid-item  px-0">
                                                <div
                                                    class="card card-collapsed border  p-0 reveal revealleft">
                                                    <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                        <a href="javascript:void(0);"
                                                            class="card-options-collapse h5 fw-bold card-title mb-0  text-primary"><span
                                                                class="me-3 fs-18 fw-bold">01.</span>Can
                                                            i get a free trial before purchase ?</a>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <p>
                                                            Lorem ipsum dolor sit amet consectetur adipisicing
                                                            elit. Iure quos debitis aliquam .
                                                        </p>
                                                        <p class="mt-2 mb-3">
                                                            <span class="fw-bold">Note: </span>Please Refer
                                                            support section for more information.
                                                        </p>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-primary tx-13">Click here</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 grid-item  px-0">
                                                <div
                                                    class="card card-collapsed border p-0 reveal revealleft">
                                                    <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                        <a href="javascript:void(0);"
                                                            class="card-options-collapse  h5 fw-bold card-title mb-0 text-success"><span
                                                                class="me-3 fs-18 fw-bold">02.</span>What
                                                            type of files i will get after purchase ?</a>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <p>
                                                            Lorem ipsum dolor sit amet consectetur adipisicing
                                                            elit. Iure quos debitis aliquam.
                                                        </p>
                                                        <p class="mt-2 mb-3">
                                                            <span class="fw-bold">Note: </span>Please Refer
                                                            support section for more information.
                                                        </p>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-success tx-13">Click here</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 grid-item  px-0">
                                                <div
                                                    class="card card-collapsed border  p-0 reveal revealleft">
                                                    <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                        <a href="javascript:void(0);"
                                                            class="card-options-collapse  h5 fw-bold card-title mb-0 text-secondary"><span
                                                                class="me-3 fs-18 fw-bold">03.</span>What
                                                            is a single Application</a>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <p>
                                                            Lorem ipsum dolor sit amet consectetur adipisicing
                                                            elit. Iure quos debitis aliquam.
                                                        </p>
                                                        <p class="mt-2 mb-3">
                                                            <span class="fw-bold">Note: </span>Please Refer
                                                            support section for more information.
                                                        </p>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-secondary tx-13">Click
                                                            here</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 grid-item  px-0">
                                                <div
                                                    class="card card-collapsed border p-0 reveal revealleft">
                                                    <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                        <a href="javascript:void(0);"
                                                            class="card-options-collapse  h5 fw-bold card-title mb-0 text-warning"><span
                                                                class="me-3 fs-18 fw-bold">04.</span>How
                                                            to get future updates ?</a>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <p>
                                                            Lorem ipsum dolor sit amet consectetur adipisicing
                                                            elit. Iure quos debitis aliquam.
                                                        </p>
                                                        <p class="mt-2 mb-3">
                                                            <span class="fw-bold">Note: </span>Please Refer
                                                            support section for more information.
                                                        </p>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-warning tx-13">Click here</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 grid-item  px-0">
                                                <div
                                                    class="card card-collapsed border p-0 reveal revealleft">
                                                    <div class="card-header grid-link" data-bs-toggle="card-collapse">
                                                        <a href="javascript:void(0);"
                                                            class="card-options-collapse  h5 fw-bold card-title mb-0 text-danger"><span
                                                                class="me-3 fs-18 fw-bold">05.</span>Do
                                                            you provide support ?</a>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <p>
                                                            Lorem ipsum dolor sit amet consectetur adipisicing
                                                            elit. Iure quos debitis aliquam.
                                                        </p>
                                                        <p class="mt-2 mb-3">
                                                            <span class="fw-bold">Note: </span>Please Refer
                                                            support section for more information.
                                                        </p>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-danger tx-13">Click here</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-7 CLOSED -->

                    <!-- ROW-9 OPEN -->
                    <div class="testimonial-owl-landing section pb-0" id="Clients">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bg-transparent">
                                        <div class="card-body">
                                            <h4 class="text-center fw-semibold">Testimonials </h4>
                                            <span class="landing-title"></span>
                                            <h2 class="text-center fw-semibold text-white mb-5">What People Are
                                                Saying About Our Product.</h2>
                                            <div class="testimonial-carousel">
                                                <div class="slide text-center">
                                                    <div class="row">
                                                        <div class="col-xl-8 col-md-12 d-block mx-auto">
                                                            <div class="testimonia">
                                                                <p class="text-white-80">
                                                                    <i class="fa fa-quote-left fs-20 text-white-80"></i>
                                                                    Lorem ipsum dolor sit amet,
                                                                    consectetur adipisicing elit. Quod eos id
                                                                    officiis hic tenetur quae quaerat
                                                                    ad velit ab. Lorem ipsum dolor sit amet,
                                                                    consectetur adipisicing elit.
                                                                    Dolore cum accusamus eveniet molestias
                                                                    voluptatum inventore laboriosam
                                                                    labore sit, aspernatur praesentium iste
                                                                    impedit quidem dolor veniam.
                                                                </p>
                                                                <h3 class="title">Elizabeth</h3>
                                                                <span class="post">Web Developer</span>
                                                                <div class="rating-stars block my-rating-5 mb-3 d-flex justify-content-center"
                                                                    data-rating="4"></div>
                                                                <div class="owl-controls clickable">
                                                                    <div class="owl-pagination">
                                                                        <div class="owl-page active">
                                                                            <span class=""></span>
                                                                        </div>
                                                                        <div class="owl-page ">
                                                                            <span class=""></span>
                                                                        </div>
                                                                        <div class="owl-page">
                                                                            <span class=""></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="slide text-center">
                                                    <div class="row">
                                                        <div class="col-xl-8 col-md-12 d-block mx-auto">
                                                            <div class="testimonia">
                                                                <p class="text-white-80"><i
                                                                        class="fa fa-quote-left fs-20"></i> Nemo
                                                                    enim ipsam
                                                                    voluptatem quia voluptas sit aspernatur aut
                                                                    odit aut fugit, sed quia
                                                                    consequuntur magni dolores eos qui ratione
                                                                    voluptatem sequi nesciunt. Neque
                                                                    porro quisquam est, qui dolorem ipsum quia
                                                                    dolor sit amet, consectetur,
                                                                    adipisci velit, sed quia non numquam eius
                                                                    modi tempora incidunt ut labore.
                                                                </p>
                                                                <div class="testimonia-data">
                                                                    <h3 class="title">williamson</h3>
                                                                    <span class="post">Web Developer</span>
                                                                        <div class="rating-stars block my-rating-5 mb-3 d-flex justify-content-center"
                                                                            data-rating="5"></div>
                                                                        <div class="owl-controls clickable">
                                                                            <div class="owl-pagination">
                                                                                <div class="owl-page ">
                                                                                    <span class=""></span>
                                                                                </div>
                                                                                <div class="owl-page active">
                                                                                    <span class=""></span>
                                                                                </div>
                                                                                <div class="owl-page">
                                                                                    <span class=""></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="slide text-center">
                                                    <div class="row">
                                                        <div class="col-xl-8 col-md-12 d-block mx-auto">
                                                            <div class="testimonia">
                                                                <p class="text-white-80"><i
                                                                        class="fa fa-quote-left fs-20"></i> Duis
                                                                    aute irure dolor
                                                                    in reprehenderit in voluptate velit esse
                                                                    cillum dolore eu fugiat nulla
                                                                    pariatur. Excepteur sint occaecat cupidatat
                                                                    non proident, sunt in culpa qui
                                                                    officia deserunt mollit anim id est laborum.
                                                                    Sed ut perspiciatis unde omnis
                                                                    iste natus error sit voluptatem accusantium
                                                                    doloremque laudantium.</p>
                                                                <div class="testimonia-data">
                                                                    <h3 class="title">Sophie Carr</h3>
                                                                    <span class="post">Web Developer</span>
                                                                        <div class="rating-stars block my-rating-5 mb-3 d-flex justify-content-center"
                                                                            data-rating="5"></div>
                                                                        <div class="owl-controls clickable">
                                                                            <div class="owl-pagination">
                                                                                <div class="owl-page ">
                                                                                    <span class=""></span>
                                                                                </div>
                                                                                <div class="owl-page">
                                                                                    <span class=""></span>
                                                                                </div>
                                                                                <div class="owl-page active">
                                                                                    <span class=""></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-9 CLOSED -->

                    <!-- ROW-10 OPEN -->
                    <div class="section bg-white-2 pb-4" id="Contact">
                        <div class="container">
                            <div class="">
                                <div class="card reveal p-5 pb-2 mb-0 ">
                                    <h4 class="text-center fw-semibold landing-card-header ">Contact</h4>
                                    <span class="landing-title"></span>
                                    <h2 class="text-center fw-semibold mb-0 px-2">Get in Touch with <span
                                            class="text-primary">US.</span></h2>
                                    <div class="card-body text-dark pb-0">
                                        <div class="statistics-info">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-12">
                                                    <div class="mt-3">
                                                        <div class="text-dark">
                                                            <div class="services-statistics reveal my-5">
                                                                <div class="row text-center">
                                                                    <div class="col-xl-3 col-md-6 col-lg-6">
                                                                        <div class="card">
                                                                            <div class="card-body p-0">
                                                                                <div class="counter-status d-flex">
                                                                                    <div
                                                                                        class="counter-icon border border-primary bg-primary-transparent m-0">
                                                                                        <i
                                                                                            class="fe fe-map-pin text-primary fs-23"></i>
                                                                                    </div>
                                                                                    <div class="ms-3 text-start">
                                                                                        <h5 class="mb-1 fw-semibold">Main Branch</h5>
                                                                                        <p>San Francisco, CA </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-md-6 col-lg-6">
                                                                        <div class="card">
                                                                            <div class="card-body p-0">
                                                                                <div class="counter-status d-flex">
                                                                                    <div
                                                                                        class="counter-icon border border-danger bg-danger-transparent">
                                                                                        <i
                                                                                            class="fe fe-mail text-danger fs-23"></i>
                                                                                    </div>
                                                                                    <div class="ms-3 text-start">
                                                                                        <h5 class="mb-1 fw-semibold">
                                                                                            Email</h5>
                                                                                        <p>georgeme@abc.com</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-md-6 col-lg-6">
                                                                        <div class="card">
                                                                            <div class="card-body p-0">
                                                                                <div class="counter-status d-flex">
                                                                                    <div
                                                                                        class="counter-icon border border-success bg-success-transparent">
                                                                                        <i
                                                                                            class="fe fe-headphones text-success fs-23"></i>
                                                                                    </div>
                                                                                    <div class="ms-3 text-start">
                                                                                        <h5 class="mb-1 fw-semibold">
                                                                                            Contact</h5>
                                                                                        <p>+125 254
                                                                                            3562 </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-md-6 col-lg-6">
                                                                        <div class="card">
                                                                            <div class="card-body p-0">
                                                                                <div class="counter-status d-flex">
                                                                                    <div
                                                                                        class="counter-icon border border-warning bg-warning-transparent">
                                                                                        <i
                                                                                            class="fe fe-airplay text-warning fs-23"></i>
                                                                                    </div>
                                                                                    <div class="ms-3 text-start">
                                                                                        <h5 class="mb-1 fw-semibold">
                                                                                            Working Hours</h5>
                                                                                        <p class="mb-0">Mon -
                                                                                            Fri: 9am - 6pm</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="">
                                                        <form class="reveal">
                                                            <div class="row">
                                                                <div class="col-xl-6">
                                                                    <div class="form-group ">
                                                                        <label for="cusName" class="form-label">Name
                                                                            <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control"
                                                                            id="cusName" placeholder="Enter your name">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-6">
                                                                    <div class="form-group">
                                                                        <label for="cusEmail" class="form-label">Email
                                                                            <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control"
                                                                            id="cusEmail"
                                                                            placeholder="Enter your email">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cusSubject"
                                                                    class="form-label">Subject</label>
                                                                <input type="text" class="form-control" id="cusSubject"
                                                                    placeholder="Enter your subject">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cusMessage" class="form-label">Message <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea rows="5" class="form-control" id="cusMessage"
                                                                    placeholder="Type your message here..."></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <button class="btn btn-primary">Send Message</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW-10 CLOSED -->
                </div>
            </div>
            <!--app-content closed-->
            
        </div>
        <!-- FOOTER OPEN -->
        <div class="demo-footer">
            <div class="container">
                <div class="row">
                    <div class="card mb-0 bg-transparent">
                        <div class="card-body p-0">
                            <div class="top-footer">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-12 col-md-12 reveal revealleft">
                                        <h6>About</h6>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                            doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore
                                            veritatis et quasi architecto beatae vitae dicta sunt
                                            explicabo.
                                        </p>
                                        <p class="mb-5 mb-lg-2">Duis aute irure dolor in reprehenderit in voluptate
                                            velit esse cillum dolore eu fugiat nulla pariatur Excepteur sint occaecat .
                                        </p>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-md-4 reveal revealleft">
                                        <h6>Pages</h6>
                                        <ul class="mb-5 mb-lg-0 ps-0">
                                            <li><a href="index.html">Dashboard</a></li>
                                            <li><a href="alerts.html">Elements</a></li>
                                            <li><a href="form-elements.html">Forms</a></li>
                                            <li><a href="charts.html">Charts</a></li>
                                            <li><a href="table-data.html">Tables</a></li>
                                            <li><a href="file-attachments.html">Other Pages</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-md-4 reveal revealleft">
                                        <h6>Information</h6>
                                        <ul class="mb-5 mb-lg-0 ps-0">
                                            <li><a href="about.html">Our Team</a></li>
                                            <li><a href="about.html">Contact US</a></li>
                                            <li><a href="about.html">About</a></li>
                                            <li><a href="about.html">Services</a></li>
                                            <li><a href="blog.html">Blog</a></li>
                                            <li><a href="about.html">Terms and Services</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 col-md-4 reveal revealleft">
                                        <div class="">
                                            <a href="index.html"><img loading="lazy" alt="" class="logo mb-3"
                                                    src="../assets/img/brand/logo-white.png"></a>
                                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                                                dolore eu fugiat nulla pariatur Excepteur sint occaecat.</p>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter your email"
                                                        aria-label="Example text with button addon"
                                                        aria-describedby="button-addon2">
                                                    <button class="btn btn-primary" type="button"
                                                        id="button-addon2">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-list mt-6">
                                            <button type="button" class="btn btn-icon rounded-pill"><i
                                                    class="fe fe-facebook"></i></button>
                                            <button type="button" class="btn btn-icon rounded-pill"><i
                                                    class="fe fe-github"></i></button>
                                            <button type="button" class="btn btn-icon rounded-pill"><i
                                                    class="fe fe-twitter"></i></button>
                                            <button type="button" class="btn btn-icon rounded-pill"><i
                                                    class="fe fe-instagram"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <footer class="main-footer px-0 text-center border-top-0 mt-5">
                                <div class="row ">
                                    <div class="col-md-12 col-sm-12">
                                        Copyright © <span id="year"></span> <a href="javascript:void(0)">Valex</a>.
                                        Designed with <span class="fa fa-heart text-danger"></span> by <a
                                            href="javascript:void(0)"> Spruko </a> All rights reserved.
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER CLOSED -->
    </div>

    <!-- End Page -->

		<!-- Back-to-top -->
		<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>

		<!-- JQuery min js -->
		{{-- <script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script> --}}

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	    <!-- Plugin de jQuery Mask -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

		<!-- Bootstrap Bundle js -->
	    <script src="{{asset('admin/plugins/bootstrap/js/popper.min.js')}}"></script>
		<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

		<!-- Moment js -->
		<script src="{{asset('admin/plugins/moment/moment.js')}}"></script>

		<!--- Perfect-scrollbar js -->
		<script src="{{asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

		<!-- Owl-carousel JS-->
		<script src="{{asset('admin/plugins/owl-carousel/owl.carousel.js')}}"></script>
		<script src="{{asset('admin/landing/lib/company-slider/slider.js')}}"></script>

		<!-- COUNTERS JS-->
		<script src="{{asset('admin/plugins/counters/counterup.min.js')}}"></script>
		<script src="{{asset('admin/plugins/counters/waypoints.min.js')}}"></script>
		<script src="{{asset('admin/plugins/counters/counters-1.js')}}"></script>

		<!--- TABS JS -->
		<script src="{{asset('admin/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
		<script src="{{asset('admin/plugins/tabs/tab-content.js')}}"></script>

		<!-- Star Rating Js-->
		<script src="{{asset('admin/plugins/rating/jquery-rate-picker.js')}}"></script>
		<script src="{{asset('admin/plugins/rating/rating-picker.js')}}"></script>

		<!-- Star Rating-1 Js-->
		<script src="{{asset('admin/plugins/ratings-2/jquery.star-rating.js')}}"></script>
		<script src="{{asset('admin/plugins/ratings-2/star-rating.js')}}"></script>

		<!-- SIDE-MENU JS -->
		<script src="{{asset('admin/landing/js/sidemenu.js')}}"></script>

		<!-- Sticky js -->
		<script src="{{asset('admin/js/sticky.js')}}"></script>

		<!--themecolor js-->
		<script src="{{asset('admin/js/themecolor.js')}}"></script>

		<!-- CUSTOM JS -->
		<script src="{{asset('admin/landing/js/landing.js')}}"></script>

	</body>

</html>