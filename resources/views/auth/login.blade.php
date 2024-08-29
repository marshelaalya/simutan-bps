<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | SIMUTAN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css -->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #043277, #2575fc);
            color: #fff;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .wrapper-page {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: #fff;
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .card-body {
            padding: 2rem;
        }

        /* .auth-logo img {
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        } */

        .btn-info {
            background-color: #2575fc;
            border-color: #2575fc;
            color: #fff;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-info:hover {
            background-color: #1b4f91;
            border-color: #1b4f91;
        }

        .form-control {
            border-radius: 25px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);
        }

        .form-group label {
            font-weight: 500;
            color: #333;
        }

        .form-group a {
            color: #2575fc;
            text-decoration: none;
        }

        .form-group a:hover {
            text-decoration: underline;
        }

        .text-center {
            color: #333;
        }

        h1 {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="wrapper-page">
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mt-4">
                        <div class="mb-3">
                            <a href="index.html" class="auth-logo">
                                <img src="{{ asset('backend/assets/images/logo-bps.png') }}" height="60" class="logo-dark mx-auto" alt="">
                                <img src="{{ asset('backend/assets/images/logo-bps.png') }}" height="60" class="logo-light mx-auto" alt="">
                            </a>
                        </div>
                    </div>

                    <h1 class="text-center font-size-34 mb-0"><b>SIMUTAN</b></h1>
                    <h3 class="text-center font-size-34 mt-0">Masuk</h3>

                    <div class="p-3">
                        <form class="form-horizontal mt-3" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input class="form-control" id="username" name="username" type="text" required="" placeholder="Username">
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input class="form-control" id="password" name="password" type="password" required="" placeholder="Password">
                            </div>

                            <div class="form-group mb-3 text-end">
                                <button class="btn btn-info w-auto waves-effect waves-light" type="submit">Masuk</button>
                            </div>

                            {{-- <div class="form-group mb-0 mt-2">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                    <a href="{{ route('register') }}" class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a>
                                </div>
                            </div> --}}
                        </form>
                    </div>
                </div>
                <!-- end cardbody -->
            </div>
            <!-- end card -->
        </div>
        <!-- end container -->
    </div>
    <!-- end -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/app.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type','info') }}"
        switch(type){
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

</body>

</html>
