<?php

use app\models\StatisticsModel;

/** @var $params StatisticsModel
 */


$allEmpty = true;
foreach ($params as $statData) {
    if (!empty($statData)) {
        $allEmpty = false;
        break;
    }
}

?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
        <h6>My Statistics</h6>
        </div>
        <div class="card-body px-4 pt-0 pb-2">
        </div>

        <div class="card-body px-0 pt-0 pb-2">
            <div class="col-md-12">

                <div class="card-body px-4 pt-0 pb-2">

                    <?php if ($allEmpty): ?>
                        <h5 class="mb-0 text-info text-gradient text-sm">
                            No data found. Add some books to your Reading Log to see your stats!
                        </h5>
                    <?php else: ?>

                        <?php foreach ($params as $statName => $statData): ?>
                            <?php if (empty($statData)) continue;?>

                                <h5 class="text-uppercase text-xs font-weight-bolder opacity-10 text-decoration-underline" style="color: #138f9a;"><?php echo $statName ?></h5>
                                <ul>
                                    <?php foreach ($statData as $row): ?>
                                        <li>

                                            <?php foreach ($row as $key => $value): ?>
                                                <?php
                                                    if ($key == 'month') continue;?>
                                                <span class="text-sm font-weight-bold"> <?php echo $value . '  ';?> </span>

                                            <?php endforeach; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

