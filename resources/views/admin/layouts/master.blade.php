<!doctype html>
<html lang="es" dir="ltr">

	<head>

		<meta charset="UTF-8">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
		<meta name="Author" content="Spruko Technologies Private Limited">
		<meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4">

		<!-- Title -->
		<title>MAYdev | HorseCare</title>

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

		{{-- DropzoneJs --}}
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

        @livewireStyles

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
				@include('admin.layouts.header')
				 <!-- FINAL DEL HEADER-->

				{{------ INICIO MENU SIDEBAR ------}}
                @include('admin.layouts.sidebar')
	            {{------ FINAL MENU SIDEBAR ------}}

				
			</div>

        
        
            {{------ PAGINA CENTRAL PRINCIPAL ------}}

			<!-- main-content -->
			<div class="main-content app-content justify-content-center mt-6" style="margin-top: 60px">

				<!-- container -->
            <main>
				
                @yield('content') {{-- Extiende contenido de cada vista blade --}}

				@isset($slot)
				  {{ $slot }} {{-- Extiende contenido de cada vista Livewire --}}
			    @endisset
                
            </main>
               

				<!-- Container closed -->
			</div>
			<!-- main-content closed -->

           {{------ FINAL PAGINA CENTRAL PRINCIPAL ------}}



			

        <!-- Footer opened -->
         @include('admin.layouts.footer')
        <!-- Footer closed -->

    </div>
 <!-- End Page -->

		<!-- Back-to-top -->
		<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>

        @livewireScripts

		<script src="{{asset('admin/plugins/jquery/jquery.min.js')}} "></script>
	    

	

	<!-- Bootstrap Bundle js -->
	<script src="{{asset('admin/plugins/bootstrap/js/popper.min.js')}} "></script>
	<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.min.js')}} "></script>

	<!-- FULL CALENDAR JS -->
	{{-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script> --}}
	<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>



	<!-- Moment js -->
	<script src="{{asset('admin/plugins/moment/moment.js')}} "></script>

	{{-- Select-2 --}}
	 <script src="{{asset('admin/plugins/select2/js/select2.min.js')}}"></script> 

	<!-- P-scroll js -->
	 <script src="{{asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}} "></script>
	 <script src="{{asset('admin/plugins/perfect-scrollbar/p-scroll.js')}} "></script> 

	<!-- eva-icons js -->
	<script src="{{asset('admin/js/eva-icons.min.js')}}"></script>

	<!-- Sidebar js -->
	 <script src="{{asset('admin/plugins/side-menu/sidemenu.js')}} "></script>

	<!-- Internal Modal js-->
	<script src="{{asset('admin/js/modal.js')}}"></script>

	<!-- Right-sidebar js -->
    <script src="{{asset('admin/plugins/sidebar/sidebar.js')}} "></script>
	<script src="{{asset('admin/plugins/sidebar/sidebar-custom.js')}} "></script> 

	<!-- switcher-styles js changer theme ligth dark-->
	<script src="{{asset('admin/js/swither-styles.js')}} "></script>

	<!-- eva-icons js -->
	<script src="{{asset('admin/js/eva-icons.min.js')}} "></script>

	<!--themecolor js-->
	<script src="{{asset('admin/js/themecolor.js')}} "></script>

	<!-- custom js -->
	<script src="{{asset('admin/js/custom.js')}} "></script>

	<script src="{{asset('admin/js/swither-styles.js')}} "></script>


	<!-- DATA TABLE JS-->
	<script src="{{asset('admin/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('admin/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
	<script src="{{asset('admin/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{asset('admin/plugins/datatable/js/buttons.bootstrap5.min.js')}}"></script>
	<script src="{{asset('admin/plugins/datatable/js/jszip.min.js')}}"></script>
	
	{{-- <script src="{{asset('admin/plugins/datatable/pdfmake/vfs_fonts.js')}}"></script> --}}
	<script src="{{asset('admin/plugins/datatable/js/buttons.html5.min.js')}}"></script>
	<script src="{{asset('admin/plugins/datatable/js/buttons.print.min.js')}}"></script>
	<script src="{{asset('admin/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
	<script src="{{asset('admin/plugins/datatable/dataTables.responsive.min.js')}}"></script>
	<script src="{{asset('admin/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

	<!--Internal  Datatable js -->
     <script src="{{asset('admin/js/table-data.js')}}"></script> 

	<!-- Internal Select2.min js -->
	<script src="{{asset('admin/plugins/select2/js/select2.min.js')}}"></script> 
	<script src="{{asset('admin/plugins/sumoselect/jquery.sumoselect.js')}}"></script>

	<!--Internal Ion.rangeSlider.min js -->
	<script src="{{asset('admin/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>

	<!--Internal  jquery-simple-datetimepicker js -->
	<script src="{{asset('admin/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>

	<!-- Ionicons js -->
	<script src="{{asset('admin/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>

	<!--Internal  pickerjs js -->
	{{-- <script src="{{asset('admin/plugins/pickerjs/picker.min.js')}}"></script> --}}

	<!--internal color picker js-->
	<script src="{{asset('admin/plugins/colorpicker/pickr.es5.min.js')}}"></script>
	{{-- <script src="{{asset('admin/js/colorpicker.js')}}"></script> --}}

	<!--Dropzone-js-->

	<!--Internal  Form-wizard js -->
	{{-- <script src="{{asset('admin/js/form-wizard.js')}}"></script>


	<script src="{{asset('admin/plugins/jquery-steps/jquery.steps.min.js')}}"></script>
	<script src="{{asset('admin/plugins/parsleyjs/parsley.min.js')}}"></script> --}}
	
	

	<!--Internal  Datepicker js -->
	{{-- <script src="{{asset('admin/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>  --}}

    

	<!-- Internal form-elements js -->
	{{-- <script src="{{asset('admin/js/form-elements.js')}}"></script> --}}

		
	@stack('scripts')
	</body>
</html>
