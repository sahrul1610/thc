<title>NOTIFIKASI</title>

<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Logic;

?>

<div class="page-header">
    <div class="row">
        <div class="col-lg-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo Url::to(['/site/index']); ?>">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active">Notifikasi</li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-absolute">
    <div class="card-header bg-danger">
        <h5 class="text-white"><i class="icofont icofont-database"></i> Notifikasi</h5>
    </div>
    <div class="tab-content card-block">
        <div class="notifikasi-index">
            <?php
            $notif = Logic::hasNotif('dataall');
            if (!empty($notif['data'])) {
                foreach ($notif['data'] as $ddx => $drow) {
                    ?>
                    <ul>
                        <li>
                            <p class="f-w-600 font-roboto"><?php echo $drow['jenis']; ?></p>
                        </li>
                        <?php
                        foreach ($drow['hasil']['normalisasi'] as $hdx => $hrow) {
                            ?>
                            <a class="notifikasi" href="<?php echo $hrow['url']; ?>">
                                <li>
                                    <p class="mb-0 text-justify"
                                       style="border-bottom: 1px solid #e5e5e5;"><?php echo $hrow['message']; ?><span
                                                class="pull-right"><?php echo $hrow['timeago']; ?></span></p>
                                </li>
                            </a>
                        <?php } ?>
                    </ul>
                    <?php
                }
            } else {
                ?>
                <ul>
                    <li>
                        <p class="f-w-600 font-roboto">Tidak ada notifikasi</p>
                    </li>
                </ul>
            <?php } ?>

        </div>
    </div>
</div>