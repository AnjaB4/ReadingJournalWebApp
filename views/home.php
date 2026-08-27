
<?php
use app\core\Application;

$user = Application::$app->session->get('user');
$userRoles = array_column($user, 'role');
/** @var string $profile_picture */

?>


<body class="g-sidenav-show bg-gray-100">

<div>
    <div class="card-body">
        <h1 class='text-md-start text-info-emphasis mb-0 ms-3 px-2'><?php echo $user[0]['first_name']; ?>, welcome to your library!</h1>
    </div>
</div>

<div class="main-content position-relative max-height-vh-100 h-100">

    <!-- End Navbar -->
    <div class="card shadow-lg mx-4 card-profile">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <?php
                        echo "<img src='../assets/pfps/$profile_picture' alt='profile_image' class='w-100 border-radius-lg shadow-sm'>"
                        ?>
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            <?php echo htmlspecialchars($user[0]['first_name']); ?>
                            <?php echo htmlspecialchars($user[0]['last_name']); ?>
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            <?php
                            if (in_array('Librarian', $userRoles)):
                                echo "$userRoles[1]";
                            else:
                                echo "$userRoles[0]";
                            endif; ?>
                        </p>
                    </div>
                </div>

                <div class="col-auto align-content-end">
                <?php if (!in_array('Librarian', $userRoles)): ?>
                    <?php if (isset($librarianRequestStatus) && $librarianRequestStatus === 'pending'): ?>
                        <h6 class="text-sm text-warning mb-0 border rounded-1 px-2 mb-4">Request Pending...</h6>
                    <?php elseif (isset($librarianRequestStatus) && $librarianRequestStatus === 'rejected'): ?>
                        <h6 class="text-sm text-danger mb-0 border rounded-1 px-2">Request Rejected</h6>
                        <form method="post" action="/user/requestLibrarian" class="mt-2">
                            <button type="submit" class="btn btn-sm btn-primary">Request Again</button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="/user/requestLibrarian">
                            <button type="submit" class="btn btn-sm btn-info">Become a Librarian</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
                </div>

                



                







                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <div class="xp-container d-flex align-items-center justify-content-end gap-3">
                            <!-- LEVEL-->
                            <div class="xp-level-circle">
                                <?= $level ?> 
                            </div>
                            <!-- PROGRESS-->
                            <div class="xp-bar-wrapper">
                                <div class="xp-bar">
                                    <div class="xp-progress" style="width: <?= $progress ?>%;"></div>
                                </div>
                                <small class="text-muted xp-text-small">
                                    <?= $xp ?> XP | <?= $xpToNextLevel?> XP to next level
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-uppercase text-sm">User Information</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Role(s)</label>
                                    <div class="form-group  border rounded-1">
                                    <?php foreach ($userRoles as $role):?>
                                        <?php echo "<p class='text-xs text-info-emphasis mb-0 px-2'>$role</p>"; ?>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email address</label>
                                    <h6 class='text-sm text-info-emphasis mb-0 border rounded-1 px-2'><?php echo $user[0]['email']; ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">First name</label>
                                    <h6 class='text-sm text-info-emphasis mb-0 border rounded-1 px-2'><?php echo $user[0]['first_name']; ?></h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Last name</label>
                                    <h6 class='text-sm text-info-emphasis mb-0 border rounded-1 px-2'><?php echo $user[0]['last_name']; ?></h6>
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">

<!--                        <hr class="horizontal dark">-->
<!--                        <p class="text-uppercase text-sm">About me</p>-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-12">-->
<!--                                <div class="form-group">-->
<!--                                    <label for="example-text-input" class="form-control-label">About me</label>-->
<!--                                    <input class="form-control" type="text"-->
<!--                                           value="--><?php //echo htmlspecialchars($about ?? 'Nothing yet.'); ?><!--">-->
<!---->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
