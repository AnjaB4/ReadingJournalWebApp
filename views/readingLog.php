<!--TODO ispravi view-->
<?php

use app\core\Application;
use app\models\ReadingLogModel;

/** @var $params ReadingLogModel
 */
$user = Application::$app->session->get('user');

?>

<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex align-items-center">
            <h6>My Reading Log</h6>
            <a class="btn btn-success btn-sm ms-auto" href="/createLog">Create</a>
        </div>
    </div>
    <div class="card-body px-4 pt-0 pb-2">
    <?php if (empty($params) || empty($params[0]['id'])): ?>
        <h5 class='mb-0 text-success text-gradient text-sm'>Add some books to see your progress here!</h5>
    <?php endif; ?>
    </div>
    <div class="card-body px-0 pt-0 pb-2">

    <!-- TODO Sort and Filter-->
        <div class="mb-3 px-3 d-flex align-items-center">
            <form method="get" action="/readingLog" class="d-flex align-items-center">
                
                <label for="sort_by" class="me-2 text-sm font-weight-bold">Sort by:</label>
                <select name="sortBy" id="sortBy" class="form-select me-2" style="width:auto;">
                    <option value="title" <?= ($_GET['sort_by'] ?? '') === 'title' ? 'selected' : '' ?>>Title</option>
                    <option value="author" <?= ($_GET['sort_by'] ?? '') === 'author' ? 'selected' : '' ?>>Author</option>
                    <option value="status" <?= ($_GET['sort_by'] ?? '') === 'status' ? 'selected' : '' ?>>Status</option>
                    <option value="page_count" <?= ($_GET['sort_by'] ?? '') === 'page_count' ? 'selected' : '' ?>>Page Count</option>
                    <option value="end_date" <?= ($_GET['sort_by'] ?? '') === 'end_date' ? 'selected' : '' ?>>End Date</option>
                </select>

                <select name="order" class="form-select me-3" style="width:auto;">
                    <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Ascending</option>
                    <option value="desc" <?= ($_GET['order'] ?? '') === 'desc' ? 'selected' : '' ?>>Descending</option>
                </select>

                <button type="submit" class="btn btn-info btn-sm align-self-center">Apply</button>
                
                
            </form>
        </div>
    
        

        <div class="table-responsive p-0">

            <div class="overflow-y-scroll pt-2" style="max-height: 100vh">

                <table class="table align-items-center mb-0">
                    <thead>
                    <tr>
                        <th style="width: 400px;"
                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Book
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start
                            Date
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Date
                        </th>
                        <th class="text-secondary opacity-7"></th>
                    </tr>
                    </thead>
                    <tbody>

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
                        echo "<p class='text-xs text-secondary mb-0'>$param[author]</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</td>";
                        echo "<td>";
                        echo "<p class='text-xs font-weight-bold mb-0'>$param[status]</p>";
                        echo "</td>";

                        echo "<td>";
                        if (!empty($param['start_date']) && $param['start_date'] !== '0000-00-00') {
                            echo "<p class='text-xs text-secondary mb-0 text-wrap'>{$param['start_date']}</p>";
                        }
                        echo "</td>";
                        echo "<td>";
                        if (!empty($param['end_date']) && $param['end_date'] !== '0000-00-00') {
                            echo "<p class='text-xs text-secondary mb-0 text-wrap'>{$param['end_date']}</p>";
                        }
                        echo "</td>";

                        echo "<td class='align-middle'>";
                        echo "<a href='/updateLog?id=$param[id]' target='_blank' class='text-secondary font-weight-bold text-xs' data-toggle='tooltip' data-original-title='Edit user'>";
                        echo "Edit";
                        echo " | ";
                        echo "<a href='/deleteLog?id=$param[id]' onclick='return confirm(\"Are you sure?\")' class='text-danger font-weight-bold text-xs'>Delete</a>";
                        echo "</a>";
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


