<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Sash – Bootstrap 5  Admin &amp; Dashboard Template </title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
	<meta name="keywords" content="admin dashboard,dashboard design htmlbootstrap admin template,html admin panel,admin dashboard html,admin panel html template,bootstrap dashboard,html admin template,html dashboard,html admin dashboard template,bootstrap dashboard template,dashboard html template,bootstrap admin panel,dashboard admin bootstrap,bootstrap admin dashboard">

    <!-- Favicon -->
    <link rel="icon" href="views/template/sash/assets/images/brand-logos/favicon.ico" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="views/template/sash/assets/js/authentication-main.js"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="views/template/sash/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

    <!-- Style Css -->
    <link href="views/template/sash/assets/css/styles.min.css" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="views/template/sash/assets/css/icons.min.css" rel="stylesheet" >


</head>

<body>


    <!-- Loader -->
    <div id="loader" >
        <img src="views/template/sash/assets/images/media/loader.svg" alt="">
    </div>
    <!-- Loader -->

    <div class="autentication-bg">

        <div class="container-lg">
            <div class="row justify-content-center authentication authentication-basic align-items-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                    <div class="my-4 d-flex justify-content-center">
                        <a href="index.html">
                            <img src="views/template/sash/assets/images/brand-logos/desktop-white.png" alt="logo">
                        </a>
                    </div>
                    <div class="card custom-card">
                        <div class="card-body p-5">
                            <form id="member_login">
                                <p class="h5 fw-semibold mb-2 text-center">Sign In</p>
                                <!-- <p class="mb-4 text-muted op-7 fw-normal text-center">Welcome back Jhon !</p> -->
                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <label for="signin-username" class="form-label text-default">User Name</label>
                                        <input name="email" type="text" class="form-control form-control-lg" id="signin-username" placeholder="user name">
                                        <div class="invalid-feedback" id="feedback-email-input"></div>
                                    </div>
                                    <div class="col-xl-12 mb-2">
                                        <label for="signin-password" class="form-label text-default d-block">Password<a href="reset-password.html" class="float-end text-danger">Forget password ?</a></label>
                                        <div class="input-group">
                                            <input name="password" type="password" class="form-control form-control-lg" id="signin-password" placeholder="password">
                                            <button class="btn btn-light" type="button" onclick="createpassword('signin-password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                                            <div class="invalid-feedback" id="feedback-password-input"></div>
                                        </div>
                                        <div class="mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                                <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                                                    Remember password ?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 d-grid mt-2">
                                        <button id="normal_login" type="submit" class="btn btn-lg btn-primary">
                                            Login
                                        </button>
                                        <!-- <a href="index.html" class="btn btn-lg btn-primary">Sign In</a> -->
                                    </div>
                                </div>
                                <!-- <div class="text-center">
                                    <p class="text-muted mt-3">Dont have an account? <a href="sign-up.html" class="text-primary">Sign Up</a></p>
                                </div>
                                <div class="text-center my-3 authentication-barrier">
                                    <span>OR</span>
                                </div>
                                <div class="btn-list text-center">
                                    <button type="button" aria-label="button" class="btn btn-icon btn-primary-transparent">
                                        <i class="ri-facebook-fill"></i>
                                    </button>
                                    <button type="button" aria-label="button" class="btn btn-icon btn-primary-transparent">
                                        <i class="ri-google-fill"></i>
                                    </button>
                                    <button type="button" aria-label="button" class="btn btn-icon btn-primary-transparent">
                                        <i class="ri-twitter-fill"></i>
                                    </button>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="views/template/sash/assets/js/jquery.min.js"></script>
    <!-- Custom-Switcher JS -->
    <script src="views/template/sash/assets/js/custom-switcher.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="views/template/sash/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Show Password JS -->
    <script src="views/template/sash/assets/js/show-password.js"></script>

    <!-- CUSTOM JS -->
    <script src="views/template/sash/assets/js/custom.js"></script>
    <script src="assets/javascripts/login/login.js"></script>

</body>

</html>