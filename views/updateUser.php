<?php

use app\models\UserModel;

/** @var $params UserModel
 */
?>

<div class="card">
    <form action="/processUpdateUser" method="post">
        <input type="hidden" name="id" value="<?php echo $params->id ?>">
        <div class="card-header pb-0">
            <div class="d-flex align-items-center">
                <p class="mb-0">Edit Profile</p>
                <button class="btn btn-primary btn-sm ms-auto" type="submit">Save</button>
            </div>
        </div>
        <div class="card-body">
            <p class="text-uppercase text-sm">User Information</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Email address</label>
                        <input class="form-control" type="email" name="email" value="<?= $params->email ?>" onfocus="focused(this)"
                               onfocusout="defocused(this)">
                        <?php
                        if ($params != null && $params->errors != null) {
                            foreach ($params->errors as $attribute => $error) {
                                if ($attribute == 'email') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">First name</label>
                        <input class="form-control" type="text" name="first_name" value="<?= $params->first_name ?>"
                               onfocus="focused(this)" onfocusout="defocused(this)">
                        <?php
                        if ($params != null && $params->errors != null) {
                            foreach ($params->errors as $attribute => $error) {
                                if ($attribute == 'first_name') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Last name</label>
                        <input class="form-control" type="text" name="last_name" value="<?= $params->last_name ?>"
                               onfocus="focused(this)" onfocusout="defocused(this)">
                        <?php
                        if ($params != null && $params->errors != null) {
                            foreach ($params->errors as $attribute => $error) {
                                if ($attribute == 'last_name') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Vrati ako ti treba neki description za profil -->
            <!--        <hr class="horizontal dark">-->
            <!--        <p class="text-uppercase text-sm">About me</p>-->
            <!--        <div class="row">-->
            <!--            <div class="col-md-12">-->
            <!--                <div class="form-group">-->
            <!--                    <label for="example-text-input" class="form-control-label">About me</label>-->
            <!--                    <input class="form-control" type="text"-->
            <!--                           value="A beautiful Dashboard for Bootstrap 5. It is Free and Open Source.">-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->
        </div>
    </form>
</div>