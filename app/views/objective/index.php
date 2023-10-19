<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Objectives');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objective-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Objective'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'obj_id',
            'event_id',
            'obj_title',
            'person_id_created',
            'app_status',
            //'person_id_approval',
            //'objtype_id',
            //'key_result',
            //'point_kompleksitas',
            //'created_by',
            //'created_time',
            //'updated_by',
            //'updated_time',
            //'band_id',
            //'object_id',
            //'object_abbr',
            //'object_name',
            //'psa_id',
            //'objectunit_id',
            //'objectunit_name',
            //'object_parent',
            //'obj_description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
