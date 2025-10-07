<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Dashboard - Login</title>

        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
        <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
        <link rel="shortcut icon" href="favicon.ico">

        <!-- FontAwesome JS-->
        <script defer src="{{ asset('assets/plugins/fontawesome/js/all.min.js') }}"></script>

        <!-- App CSS -->
        <link id="theme-style" rel="stylesheet" href="{{ asset('assets/css/portal.css') }}">

    </head>

    <body class="app app-login p-0">
        <div class="row g-0 app-auth-wrapper">
            <div class="col-12 text-center p-5">
                <div class="d-flex flex-column align-content-end">
                    <div class="app-auth-body mx-auto">
                        <div class="app-auth-branding mb-4"><a class="app-logo" href="index.html"><img
                                    class="logo-icon me-2" src="{{ asset('assets/images/app-logo.jpg') }}"
                                    alt="logo"></a></div>
                        <h2 class="auth-heading text-center mb-5">Login As Adminstrator</h2>

                        <div class="auth-form-container text-start">
                            <form class="auth-form login-form" action="{{ route('login.submit') }}" method="POST">
                                @csrf
                                <div class="email mb-3">
                                    <label class="sr-only" for="signin-email">Email</label>
                                    <input id="signin-email" name="email" type="email"
                                        class="form-control signin-email" placeholder="Email address"
                                        required="required">
                                </div>
                                <div class="password mb-3">
                                    <label class="sr-only" for="signin-password">Password</label>
                                    <input id="signin-password" name="password" type="password"
                                        class="form-control signin-password" placeholder="Password" required="required">

                                </div>
                                @if ($errors->any())
                                    <div style="color: red;">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="text-center my-5">
                                    <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Log
                                        In</button>
                                </div>
                            </form>
                        </div>

                    </div>


                </div>
            </div>


        </div>


    </body>

</html>
