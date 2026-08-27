<?php

use app\models\UserModel;

/** @var $params UserModel
 */

?>

<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex align-items-center">
            <h6>Users</h6>
            <a class="btn btn-success btn-sm ms-auto" href="/createUser" ">Create</a>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">

        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                    <th class="text-secondary opacity-7"></th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach ($params as $user) {
                    echo "<tr>";
                    echo "<td>";
                    echo "<div class='d-flex px-2 py-1'>";
                    echo "<div>";
                    echo "<img src='../assets/pfps/$user[profile_picture]' class='avatar avatar-sm me-3' alt='user1'>";
                    echo "</div>";
                    echo "<div class='d-flex flex-column justify-content-center'>";
                    echo "<h6 class='mb-0 text-sm'>$user[first_name] $user[last_name]</h6>";
                    echo "<p class='text-xs text-secondary mb-0'>$user[email]</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</td>";

                    // ROLES 
                    echo "<td>";
                    if (!empty($user['roles'])) {
                        foreach ($user['roles'] as $role) {
                            echo "<span class='badge bg-fantasy bg-gradient me-1' style='text-transform: none;'>$role</span>";
//                            echo "<p class='text-xs font-weight-bold mb-0'>$role</p>";
                        }
                    } else {
                        echo "<p class='text-xs text-secondary mb-0'>No roles</p>";
                    }
                    echo "</td>";


                    echo "<td class='align-middle'>";
                    echo "<a href='/updateUser?id=$user[id]' target='_blank' class='text-secondary font-weight-bold text-xs' 
                            data-toggle='tooltip' data-original-title='Edit user'>Edit</a>";
                    echo " | ";
                    echo "<a href='/deleteUser?id=$user[id]' onclick='return confirm(\"Are you sure?\")' 
                            class='text-danger font-weight-bold text-xs'>Delete</a>";
                    echo "</td>";

                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<?php //ako popravis all() u BaseModel/php ?>
<!-- OVO bismo koristili da smo mapirali all
   kao objekte u all() f-ji u BaseModel.php
   (na samom vrhu sa definisanim params
   kao @var iz UserModela unutar php tagova:
        use app\models\UserModel;
        /** @var $params UserModel
        */
   isto kao sto smo i u getUser.php) -->
<?php /*
<!-- prepravi DIVs
<div class="card">
    <div class="card-body">
        <h1>USERS:</h1>
        <p>
           <?php foreach ($params as $user): ?>

            <?php echo $user->first_name ?>
            <?php echo $user->last_name ?>
            <?php echo $user->email ?>

            <?php endforeach; ?>
        </p>
    </div>
</div>
-->*/ ?>
