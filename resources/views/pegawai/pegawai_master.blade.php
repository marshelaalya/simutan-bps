<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | SIMUTAN - Admin & Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables Bootstrap 4 CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">


    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.20.0/tabler-icons.min.css" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('backend/assets/css/bootstrap.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <!-- Icons CSS -->
    <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet" type="text/css" />

    <!-- App CSS -->
    <link href="{{ asset('backend/assets/css/app.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <!-- Toastr CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    <!-- BS Stepper CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/bs-stepper/bs-stepper.css') }}" />

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

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('pegawai.body.header')

        <!-- Left Sidebar -->
        @include('pegawai.body.sidebar')
        <!-- End Left Sidebar -->

        <!-- Main Content -->
        <div class="main-content">
            @yield('pegawai')
            @include('pegawai.body.footer')
        </div>
        <!-- End Main Content -->

    </div>
    <!-- End layout-wrapper -->

    <!-- Right Sidebar -->
    <!-- /Right-bar -->

    <!-- Right bar overlay -->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->

    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- MetisMenu JS -->
    <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>

    <!-- Simplebar JS -->
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>

    <!-- Waves JS -->
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- DataTables JS -->
    {{-- <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
    <script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- ApexCharts JS -->
    <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- jQuery Vector Map JS -->
    <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Dashboard Init JS -->
    <script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>

    <!-- App JS -->
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>

    <!-- Handle Bars JS -->
    <script src="{{ asset('backend/assets/js/handlebars.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch (type) {
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
        @endif
    </script>

    <!-- Notify JS (Optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify.js/2.0.0/notify.min.js" integrity="sha512-iy8/ErLJUuqWbu30yUSCxXtE3FCDZi3y5op0Duqdp7vtpeh1E6ZyAPnRS+OrJHddh4uP30oYpwNt7TXPbmP5lQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>
