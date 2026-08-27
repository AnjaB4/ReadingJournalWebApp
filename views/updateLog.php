<?php

use app\models\ReadingLogModel;

/** @var $params ReadingLogModel
 */
use app\models\BookModel;

/** @var $books BookModel */
?>

<div class="card">
    <form action="/processUpdateLog" method="post">
        <input type="hidden" name="id" value="<?php echo $params->id ?>">
        <div class="card-header pb-0">
            <div class="d-flex align-items-center">
                <p class="mb-0">Edit log</p>
                <button class="btn btn-primary btn-sm ms-auto" type="submit">Save</button>
            </div>
        </div>
        <div class="card-body">
            <p class="text-uppercase text-sm">Reading Log Information</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id_book" class="form-control-label">Book</label>
                        <select class="form-control" name="id_book">
                            <option value="">-- Select a book --</option>
                            <?php foreach ($books as $book): ?>
                                <option value="<?= $book['id'] ?>" <?= ($params->id_book ?? '') == $book['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php
                        if ($params != null && $params->errors != null) {
                            foreach ($params->errors as $attribute => $error) {
                                if ($attribute == 'id_book') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-control-label">Status</label>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusTbr" value="tbr"
                                    <?= ($params->status ?? 'tbr') == 'tbr' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusTbr">tbr</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusReading"
                                       value="reading"
                                    <?= ($params->status ?? '') == 'reading' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusReading">reading</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusCompleted"
                                       value="completed"
                                    <?= ($params->status ?? '') == 'completed' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusCompleted">completed</label>
                            </div>
                        </div>
                        <?php
                        if ($params != null && $params->errors != null) {
                            foreach ($params->errors as $attribute => $error) {
                                if ($attribute == 'status') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="start_date" class="form-control-label">Start Date</label>
                        <input class="form-control" type="date" name="start_date" value="<?= $params->start_date ?? '' ?>">
                        <?php
                        if ($params != null && $params->errors != null) {
                            foreach ($params->errors as $attribute => $error) {
                                if ($attribute == 'start_date') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="form-control-label">End Date</label>
                        <input class="form-control" type="date" name="end_date" value="<?= $params->end_date ?? '' ?>">
                        <?php
                        if ($params != null && $params->errors != null) {
                            foreach ($params->errors as $attribute => $error) {
                                if ($attribute == 'end_date') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>