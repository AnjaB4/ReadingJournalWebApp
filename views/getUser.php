<?php
use app\models\UserModel;

/** @var $params UserModel
 */
?>

<div class="card">
    <div class="card-body">
        <h1>
            <?php echo $params->first_name ?? "NOT FOUND" ?> <br>
            <?php echo $params->last_name ?? "NOT FOUND" ?> <br>
            <?php echo $params->email ?? "NOT FOUND" ?> <br>
<!--            izbrisi ako pravi problem img-->
            <img src="<?= "../assets/pfps/" . ($params->profile_picture ?? "default.jpg") ?>" width="200" height="200" alt="profile picture" class="img-thumbnail rounded-circle img-fluid img-thumbnail">
        </h1>
    </div>
</div>

