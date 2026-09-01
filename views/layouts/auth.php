<?php
use app\core\Application;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">-->
<!--    <link rel="icon" type="image/png" href="../assets/img/favicon.png">-->

    <title>
        Reading Journal
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
<!--    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>-->
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
    <!-- Toastr -->
    <link rel="stylesheet" href="../assets/js/plugins/toastr/toastr.min.css" />
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/toastr/toastr.min.js"></script>
    <script src="../assets/js/plugins/toastr/toastr-options.js"></script>

</head>

<body class="">
<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                <div class="container-fluid">
                    <i class="ni ni-books text-info text-gradient"></i>
                    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="/login">
<!--                        <img src="../assets/myimg/kofi.jpg" alt="brand" height="50">-->
                        READING JOURNAL
                    </a>
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="navbar-nav">
                            <?php
                                if (Application::$app->session->get('user')) {
                                    echo '<li class="nav-item me-0">';
                                    echo '<a class="nav-link me-2" href="/processLogout">';
                                    echo '<i class="ni ni-user-run opacity-6 text-dark me-1"></i>';
                                    echo 'Logout';
                                    echo '</a>';
                                    echo '</li>';
                                }else {
                                    echo '<li class="nav-item me-0">';
                                    echo '<a class="nav-link me-2" href="/login">';
                                    echo '<i class="fas fa-key opacity-6 text-dark me-1"></i>';
                                    echo 'Sign In';
                                    echo '</a>';
                                    echo '</li>';
                                }
                            ?>

                            <li class="nav-item me-0">
                                <a class="nav-link me-2" href="/registration">
                                    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                                    Sign Up
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                        {{ RENDER_SECTION }}
                    </div>
                    <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">    <!--podesi public dir na Resource Root da ti ovo nestane(necu sad da se zezam s tim zbog ostalih urlova)-->
                        <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('/assets/myimg/bg.png');
          background-size: cover;">
                            <span class="mask bg-gradient-info opacity-3"></span>
                            <p class="text-info-emphasis position-relative"><q>We always need a story, even when we think we don't</q></p>
                            <h4 class="mt-5 text-info-emphasis font-weight-bolder position-relative">~Requiem: A Hallucination</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons 
<script async defer src="https://buttons.github.io/buttons.js"></script> -->

<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

<?php
Application::$app->session->showSuccessNotification();
Application::$app->session->showErrorNotification();
?>

</html>