<?php

//echo "<pre>";
//var_dump(Application::$app->session->get('user'));

use app\core\Application;
$user = Application::$app->session->get('user');
$roleLibrarian = Application::$app->session->isInRole('Librarian');
//moze i bez te druge varijable pa onda sa in_array f-jom
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/myimg/books-pixel-logo.jpg">
    <title>
        Reading Journal
    </title>
    <!--     Fonts and icons     -->
    <link href="../assets/css/font.css" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->

    <!--    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>-->
    <!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- TODO pravi problem za bookshelf
   <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
-->
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
    <!-- custom CSS za žanrove -->
    <link href="../assets/css/custom-genres.css" rel="stylesheet">
    <!-- custom CSS za checkbox -->
    <link href="../assets/css/custom-checkbox.css" rel="stylesheet">
    
    <!-- custom CSS za level progression-->
    <link href="../assets/css/custom-levelbox.css" rel="stylesheet">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="../assets/js/plugins/toastr/toastr.min.css" />
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/toastr/toastr.min.js"></script>
    <script src="../assets/js/plugins/toastr/toastr-options.js"></script>
    <script src="../assets//js/plugins/chartjs.min.js"></script>
</head>


<body class="g-sidenav-show   bg-gray-100">
<div class="min-height-300 bg-gradient-success position-absolute w-100"></div>
<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0">
            <img src="../assets/myimg/books-pixel-logo.jpg" width="36px" height="36px" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Reading Journal</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link " href="/home">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-camera-compact text-dark text-sm opacity-10"></i>
                    </div>       <!--ni-tv-2-->
                    <span class="nav-link-text ms-1"><?=$user[0]['first_name'] . "'s Profile"?></span>
                </a>
            </li>

<!--            --><?php //if (in_array('Librarian', array_column($user, 'role'))): ?>
            <?php if ($roleLibrarian): ?>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Librarian Overview</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/users">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/librarianRequests">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-badge text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Librarian Requests</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/librarianReports">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-chart-bar-32 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Librarian Reports</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Library pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/myBookshelf">
                <!--     <div class="icon icon-shape icon-m myBookshelf-icon border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 32 32"
                                fill="currentColor"
                                class="text-dark opacity-10"
                            >
                        <rect x="11" y="11" width="2" height="18"></rect>
                        <path d="m26,2H6c-2.206,0-4,1.794-4,4v20c0,2.206,1.794,4,4,4h20c2.206,0,4-1.794,4-4V6c0-2.206-1.794-4-4-4Zm0,26H6c-1.103,0-2-.897-2-2v-14h24v14c0,1.103-.897,2-2,2Z"></path>
                    </svg>
                    </div>  
                    // TODO nadji bolju ikonicu  -->
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-align-left-2 text-dark text-sm opacity-10"></i>
                    </div>
                    
                    <span class="nav-link-text ms-1">My Bookshelf</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="/readingLog">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-book-bookmark text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Reading Log</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="/books">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-books text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Books</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="/achievements">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-trophy text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Achievements</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="/myReports">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-chart-pie-35 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Graphs</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="/processLogout">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-user-run text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidenav-footer mx-3 mt-5">
        <div class="card card-plain shadow-none" id="sidenavCard">

            <div class="w-50 mx-auto text-center">
                <i class="ni ni-books text-success text-gradient" style="font-size: 4rem;"></i>
            </div>

        </div>
    </div>
</aside>


<main class="main-content position-relative border-radius-lg ">
    <div class="container-fluid py-4">
        {{ RENDER_SECTION }}
    </div>
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
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

<?php
Application::$app->session->showSuccessNotification();
Application::$app->session->showErrorNotification();
?>

</html>