<?php

use app\models\RegistrationModel;

/** @var $params RegistrationModel
 */
?>

<div class="card card-plain">
    <div class="card-header pb-0 text-start">
        <h4 class="font-weight-bolder">Sign Up</h4>
        <p class="mb-0">Enter your email and password to sign up</p>
    </div>
    <div class="card-body">
        <form role="form" method="post" action="/processRegistration">
            <div class="mb-2">
                <input type="text" name="first_name" class="form-control form-control-lg" placeholder="First Name" aria-label="Name" value="<?= $params->first_name ?>">
                <?php
                if ($params != null && $params->errors != null) {
                    foreach ($params->errors as $attribute => $error) {
                        if ($attribute == 'first_name') {
                            echo "<span class='text-danger text-sm'>$error[0]</span>";
                        }
                    }
                }
                ?>
            </div>
            <div class="mb-2">
                <input type="text" name="last_name" class="form-control form-control-lg" placeholder="Last Name" aria-label="LastName" value="<?= $params->last_name ?>">
                <?php
                if ($params != null && $params->errors != null) {
                    foreach ($params->errors as $attribute => $error) {
                        if ($attribute == 'last_name') {
                            echo "<span class='text-danger text-sm'>$error[0]</span>";
                        }
                    }
                }
                ?>
            </div>
            <div class="mb-2">
                <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" aria-label="Email" value="<?= $params->email ?>">
                <?php
                if ($params != null && $params->errors != null) {
                    foreach ($params->errors as $attribute => $error) {
                        if ($attribute == 'email') {
                            echo "<span class='text-danger text-sm'>$error[0]</span>";
                        }
                    }
                }
                ?>
            </div>
            <div class="mb-2">
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" value="<?= $params->password ?>">
                <?php
                if ($params != null && $params->errors != null) {
                    foreach ($params->errors as $attribute => $error) {
                        if ($attribute == 'password') {
                            echo "<span class='text-danger text-sm'>$error[0]</span>";
                        }
                    }
                }
                ?>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-lg btn-info btn-lg w-100 mt-4 mb-0">Sign Up</button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center pt-0 px-lg-2 px-1">
        <p class="mb-4 text-sm mx-auto">
            Already have an account?
            <a href="/login" class="text-info text-gradient font-weight-bold">Sign In</a>
        </p>
    </div>
</div>