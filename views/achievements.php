<?php

use app\core\Application;

/** @var $params array */

$user = Application::$app->session->get('user');

$achievements = $params['achievements'];
$books = $params['books'];
$userTasks = $params['userTasks'];

?>

<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex align-items-center">
            <h6>Achievements</h6>
        </div>
    </div>

    <div class="card-body">

        <?php foreach ($achievements as $param): ?>

            <?php
            /* koliko zavrsenih taskova ima; prikazi badge ili sakrij*/
            $completedTasks = 0;

            foreach ($param['tasks'] as $task) {
                foreach ($userTasks as $userTask) {
                    if ($userTask['id_task'] == $task['id']) {
                        $completedTasks++;
                        break;
                    }
                }
            }

            $allTasksCompleted = count($param['tasks']) > 0
                && $completedTasks == count($param['tasks']);
            ?>

            <div class="card mb-3">
                <div class="card-body">

                     <!-- prostor za badge-->
                    <div class="d-flex align-items-center">
                        <img src="../assets/achievements/<?= $allTasksCompleted
                            ? htmlspecialchars($param['icon'])
                            : 'questionmark.png' ?>"
                            alt="achievement badge"
                            class="me-2"
                            style="width: 35px; height: 35px; object-fit: cover;">

                        <!-- title achievementa-->
                        <h5 class="mb-0">
                            <?= htmlspecialchars($param['title']) ?>
                        </h5>
                    </div>

                    <p class="text-sm text-secondary mb-2">
                        <?= htmlspecialchars($param['description']) ?>
                    </p>


                    <div class="mt-3">

                        <p class="text-uppercase text-xs font-weight-bolder">
                            Tasks
                        </p>

                        <form action="/saveAchievementTask" method="post">

                            <?php foreach ($param['tasks'] as $task): ?>

                                <?php
                                // da li task vec ima sacuvanu knjigu
                                $selectedBookId = null;
                                $selectedBook = null;

                                foreach ($userTasks as $userTask) {
                                    if ($userTask['id_task'] == $task['id']) {
                                        $selectedBookId = $userTask['id_book'];

                                        foreach ($books as $book) {
                                            if ($book['id'] == $selectedBookId) {
                                                $selectedBook = $book;
                                                break;
                                            }
                                        }

                                        break;
                                    }
                                }
                                ?>

                                <div class="mb-3">

                                    <!-- CHECKBOX -->
                                    <div class="custom">
                                        <input type="checkbox"
                                            id="task_<?= $task['id'] ?>"
                                            <?= $selectedBook ? 'checked' : '' ?>
                                            disabled>

                                        <label for="task_<?= $task['id'] ?>"
                                               class="<?= $selectedBook ? 'text-decoration-line-through' : '' ?>">
                                            <?= htmlspecialchars($task['title']) ?>
                                        </label>
                                    </div>

                                    <?php if (!empty($task['description'])): ?>
                                        <p class="text-xs text-secondary mb-2">
                                            <?= htmlspecialchars($task['description']) ?>
                                        </p>
                                    <?php endif; ?>


                                    <?php if ($selectedBook): ?>

                                        <!-- TASK JE ZAVRSEN -->

                                        <div class="d-flex align-items-center">

                                            <img src="../assets/covers/<?= htmlspecialchars($selectedBook['cover_image']) ?>"
                                                class="me-2"
                                                style="width: 40px; height: 60px; object-fit: cover;"
                                                alt="book cover">

                                            <span class="text-sm text-success ms-3 opacity-75">
                                                <?= htmlspecialchars($selectedBook['title']) ?>
                                                by <?= htmlspecialchars($selectedBook['author']) ?>
                                            </span>


                                            <span class="text-secondary mx-1"></span>

                                            <button type="submit"
                                                    name="task_id"
                                                    value="<?= $task['id'] ?>"
                                                    formaction="/deleteAchievementTask"
                                                    formmethod="post"
                                                    class="btn btn-link p-0 text-danger font-weight-bold text-xs"
                                                    onclick="return confirm('Are you sure you want to reset this task?')">
                                                Delete
                                            </button>

                                        </div>


                                    <?php else: ?>

                                        <!-- TASK NIJE ZAVRSEN ILI SE EDITUJE -->
                                        <select class="form-select form-select-sm"
                                                name="task_<?= $task['id'] ?>">

                                            <option value="">-- Select a book --</option>

                                            <?php foreach ($books as $book): ?>

                                                <option value="<?= $book['id'] ?>">
                                                    <?= htmlspecialchars($book['title']) ?>
                                                    by <?= htmlspecialchars($book['author']) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    <?php endif; ?>

                                </div>

                            <?php endforeach; ?>

                            <button type="submit" class="btn btn-sm btn-info">
                                Save
                            </button>

                        </form>

                    </div>


                    <p class="text-xs text-info-emphasis mb-0">
                        Reward: <?= $param['xp_reward'] ?> XP
                    </p>

                </div>
            </div>

        <?php endforeach; ?>

    </div>
</div>