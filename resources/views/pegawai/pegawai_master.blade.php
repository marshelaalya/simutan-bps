<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | SIMUTAN - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

    <!-- Stylesheets -->
    <link href="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/bootstrap.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/app.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/bs-stepper/bs-stepper.css") }}" />

    <link rel="stylesheet" href="{{ asset("vendor/fonts/fontawesome.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/fonts/tabler-icons.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/fonts/flag-icons.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/css/rtl/core.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/css/rtl/theme-default.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/demo.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/perfect-scrollbar/perfect-scrollbar.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/node-waves/node-waves.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/typeahead-js/typeahead.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/bootstrap-select/bootstrap-select.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/select2/select2.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/formvalidation/dist/css/formValidation.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/flatpickr/flatpickr.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css") }}" />
    <link rel="stylesheet" href="{{ asset("vendor/libs/pickr/pickr-themes.css") }}" />

    <!-- Include SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset("vendor/js/helpers.js") }}"></script>
    <script src="{{ asset("js/config.js") }}"></script>

    <style>
        .table-wrapper {
            overflow-x: auto;
        }
        table.dataTable {
            border-collapse: collapse !important;
            width: 100%;
        }
    </style>
</head>

<body data-topbar="dark">

    <div id="layout-wrapper">
        @include('pegawai.body.header')
        @include('pegawai.body.sidebar')

        <div class="main-content">
            @yield('pegawai')
            @include('pegawai.body.footer')
        </div>
    </div>

    <div class="rightbar-overlay"></div>

    <!-- JavaScript -->
    <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify.js/2.0.0/notify.min.js" integrity="sha512-iy8/ErLJUuqWbu30yUSCxXtE3FCDZi3y5op0Duqdp7vtpeh1E6ZyAPnRS+OrJHddh4uP30oYpwNt7TXPbmP5lQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type','info') }}"
        switch(type){
            case 'info':
                toastr.info(" {{ Session::get('message') }} ");
                break;
            case 'success':
                toastr.success(" {{ Session::get('message') }} ");
                break;
            case 'warning':
                toastr.warning(" {{ Session::get('message') }} ");
                break;
            case 'error':
                toastr.error(" {{ Session::get('message') }} ");
                break; 
        }
        @endif 
    </script>

     <!-- Required datatable js -->
     <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
     <script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

     <!-- Datatable init js -->
     <script src="{{ asset('backend/assets/js/pages/datatables.init.js') }}"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/notify.js/2.0.0/notify.min.js" integrity="sha512-iy8/ErLJUuqWbu30yUSCxXtE3FCDZi3y5op0Duqdp7vtpeh1E6ZyAPnRS+OrJHddh4uP30oYpwNt7TXPbmP5lQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>
</html>
