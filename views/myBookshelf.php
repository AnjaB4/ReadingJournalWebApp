<?php

use app\core\Application;
use app\models\BookModel;
use app\models\BookshelfModel;

/** @var $array $books
 */

//$user = Application::$app->session->get('user');
?>

<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex align-items-center">
            <h6>My Bookshelf</h6>
        </div>
    </div>

    
<!--
    <div class="card-body px-4 pt-4 pb-2 d-flex flex-row justify-content-start">

        <div class="p-2 px-2 pt-0 pb-2 d-flex align-items-center">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-books text-dark text-sm opacity-10"></i>
            </div>
            <span class="ms-1 text-uppercase text-xs text-success font-weight-bolder">Drag to zoom</span>
        </div>
        - TODO fix icons
        <div class="p-2 px-2 pt-0 pb-2">
            <p class="text-uppercase text-xs text-success text-gradient font-weight-bolder opacity-10">Right click to rotate</p>
        </div>
        <div class="p-2 px-2 pt-0 pb-2">
            <p class="text-uppercase text-xs text-success text-gradient font-weight-bolder opacity-10">Click and drag to slide down shelves</p>
        </div>

        <div class="p-2 px-2 pt-0 pb-2 d-flex flex-row align-items-center">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-book-bookmark text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Reading Log</span>
        </div>

    </div>
-->



    <div class="card-body px-0 pt-0 pb-2">

                                                    <!--  mozda OVERFLOW NA scroll-->
        <div id="bookshelf-container" style="width:100%; height:600px; overflow-y: auto; position: relative;">
            
        </div>

        <div id="book-context-menu">
            <button id="examine-book-btn">Examine</button>
        </div>

        <?php if (empty($books)): ?>
            <p class="text-muted mt-3">No finished books yet.</p>
        <?php endif; ?>
        
    </div>
</div>

<script>
    window.BOOKSHELF_DATA = <?= json_encode($books) ?>;
</script>


<script src="/assets/libs/gsap.min.js"></script>

<script type="module" src="/assets/js/bookshelf.js"></script>
