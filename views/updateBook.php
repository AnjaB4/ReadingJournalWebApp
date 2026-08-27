<?php

use app\models\BookModel;
use app\models\GenreModel;

/** @var $book BookModel
 */

/** @var $genres GenreModel
 */
?>


<div class="card">
    <form action="/processUpdateBook" method="post">
        <input type="hidden" name="id" value="<?php echo $book->id ?>">
        <div class="card-header pb-0">
            <div class="d-flex align-items-center">
                <p class="mb-0">Edit Book</p>
                <button class="btn bg-fantasy btn-sm ms-auto" type="submit">Save</button>
            </div>
        </div>
        <div class="card-body">
            <p class="text-uppercase text-sm">Book Information</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Title</label>
                        <input class="form-control" type="text" name="title" value="<?= $book->title ?>" onfocus="focused(this)"
                               onfocusout="defocused(this)">
                        <?php
                        if ($book != null && $book->errors != null) {
                            foreach ($book->errors as $attribute => $error) {
                                if ($attribute == 'title') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="synopsis" class="form-control-label">Synopsis</label>
                        <textarea class="form-control" name="synopsis" rows="5"
                               onfocus="focused(this)" onfocusout="defocused(this)"><?= $book->synopsis ?>
                        </textarea>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Author</label>
                        <input class="form-control" type="text" name="author" value="<?= $book->author ?>"
                               onfocus="focused(this)" onfocusout="defocused(this)">
                        <?php
                        if ($book != null && $book->errors != null) {
                            foreach ($book->errors as $attribute => $error) {
                                if ($attribute == 'author') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Page Count</label>
                        <input class="form-control" type="text" name="page_count" value="<?= $book->page_count ?>"
                               onfocus="focused(this)" onfocusout="defocused(this)">
                        <?php
                        if ($book != null && $book->errors != null) {
                            foreach ($book->errors as $attribute => $error) {
                                if ($attribute == 'page_count') {
                                    echo "<span class='text-danger'>$error[0]</span>";
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!--zanrovi-->

            <div class="form-group">
                <label for="example-text-input" class="form-control-label">Select genres:</label><br>
                <?php foreach ($genres as $genre): ?>
                    <?php
                    $checked = '';
                    if (!empty($book->genres)) {
                        foreach ($book->genres as $bg) {
                            if ($bg['id'] == $genre['id']) {
                                $checked = 'checked';
                                break;
                            }
                        }
                    }
                    ?>
                    <div class='custom form-check-inline'>
                        <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" id="genre-<?= $genre['id'] ?>" <?= $checked ?>>
                        <label for="genre-<?= $genre['id'] ?>">
                            <?php echo $genre['name'];?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </form>
</div>