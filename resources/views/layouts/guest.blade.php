<!DOCTYPE html>
<html lang="es">

<head>

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="hosercare">
	<meta name="Author" content="MAYDEV SPA">
	<meta name="Keywords"
		content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4">

	<!-- Title -->
	<title> Login </title>

	<!-- Favicon -->
	<link rel="icon" href="{{asset('admin/img/brand/favicon.png')}}" type="image/x-icon">

	<!-- Icons css -->
	<link href="{{asset('admin/css/icons.css')}}" rel="stylesheet">

	<!-- Bootstrap css -->
	<link id="style" href="{{asset('admin/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

	<!-- style css -->
	<link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
	<link href="{{asset('admin/css/plugins.css')}}" rel="stylesheet">

	<!--- Animations css-->
	<link href="{{asset('admin/css/animate.css')}}" rel="stylesheet">

	<!-- Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])

	<!-- Styles -->
	{{-- @livewireStyles --}}

</head>

<body class="ltr error-page1 main-body bg-light text-dark error-3 login-img">


	<!-- Loader -->
	<div id="global-loader">
		<img src="{{asset('admin/img/svgicons/loader.svg')}}" class="loader-img" alt="Loader">
	</div>
	<!-- /Loader -->

        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
		
@stack('scripts')


<!-- JQuery min js -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>

<!-- Bootstrap Bundle js -->
<script src="{{asset('admin/plugins/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- Moment js -->
<script src="{{asset('admin/plugins/moment/moment.js')}}"></script>

<!-- P-scroll js -->
<script src="{{asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

<!-- eva-icons js -->
<script src="{{asset('admin/js/eva-icons.min.js')}}"></script>

<!--themecolor js-->
<script src="{{asset('admin/js/themecolor.js')}}"></script>

<!-- custom js -->
<script src="{{asset('admin/js/custom.js')}}"></script>

<!-- switcher-styles js -->
<script src="{{asset('admin/js/swither-styles.js')}}"></script>

</body>

</html>