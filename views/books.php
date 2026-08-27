<?php

use app\core\Application;
use app\models\BookModel;

/** @var $params BookModel
 */

$user = Application::$app->session->get('user');
$roleLibrarian = Application::$app->session->isInRole('Librarian');
?>

<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex align-items-center">
            <h6>Books</h6>
            <?php if ($roleLibrarian): ?>
                <a class="btn btn-success btn-sm ms-auto" href="/createBook">Create</a>
            <?php endif; ?>
        </div>

        <form method="get" action="/books" class="ms-auto">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" 
                        placeholder="Search by title or author..." 
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" >
                <button type="submit" class="btn btn-info btn-sm mb-0">Search</button>
            </div>
        </form>
    </div>


    <div class="card-body px-0 pt-0 pb-2">

        <div class="table-responsive p-0">
            <div class="overflow-y-scroll pt-2" style="max-height: 100vh">

                <table class="table align-items-center mb-0">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Book</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Synopsis</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Author</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Genre</th>
                        <th class="text-secondary opacity-7"></th>
                    </tr>
                    </thead>

                    <tbody class="overflow-scroll pt-5" style="max-height: 100vh">
                    <?php

                    foreach ($params as $param) {
                        echo "<tr>";
                        echo "<td>";
                        echo "<div class='d-flex px-2 py-1'>";
                        echo "<div>";
                        echo "<img src='../assets/covers/$param[cover_image]' class='img-fluid me-3' style='max-width:80px; max-height:120px;' alt='book cover'>";
                        echo "</div>";
                        echo "<div class='d-flex flex-column justify-content-center'>";
                        echo "<h6 class='mb-0 text-sm'>$param[title]</h6>";
                        echo "<p class='text-xs text-secondary mb-0'>$param[page_count] pages</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</td>";
                        echo "<td>";
                        echo "<p class='text-xs text-secondary mb-0 text-wrap'>$param[synopsis]</p>";
                        echo "</td>";

                        echo "<td>";
                        echo "<p class='text-xs font-weight-bold mb-0'>$param[author]</p>";
//                    echo "<p class='text-xs text-secondary mb-0'>izaberi jedan od paragrafa</p>";
                        echo "</td>";

                        //ZANROVI //
                        echo "<td>";
                        if (!empty($param['genres'])) {
                            foreach ($param['genres'] as $genre) {
                                $colorClass = $genre['color_class'] ?? 'bg-secondary';
                                echo "<span class='badge $colorClass me-1' style='text-transform: none;'>{$genre['name']}</span>";
                            }
                        } else {
                            echo "<p class='text-xs text-secondary mb-0'>No genres</p>";
                        }
                        echo "</td>";
                        //////

                        echo "<td class='align-middle'>";
//                        if (in_array('Librarian', array_column($user, 'role')))
                        if ($roleLibrarian)
                        {
                            echo "<a href='/updateBook?id=$param[id]' target='_blank' class='text-secondary font-weight-bold text-xs' data-toggle='tooltip' data-original-title='Edit user'>Edit</a>";
                            echo " | ";
                            echo "<a href='/deleteBook?id=$param[id]' onclick='return confirm(\"Are you sure?\")' class='text-danger font-weight-bold text-xs'>Delete</a>";
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                    ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>
