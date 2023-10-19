<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Objective */

$this->title = $model->obj_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Objectives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="objective-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->obj_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->obj_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'obj_id',
            'event_id',
            'obj_title',
            'person_id_created',
            'app_status',
            'person_id_approval',
            'objtype_id',
            'key_result',
            'point_kompleksitas',
            'created_by',
            'created_time',
            'updated_by',
            'updated_time',
            'band_id',
            'object_id',
            'object_abbr',
            'object_name',
            'psa_id',
            'objectunit_id',
            'objectunit_name',
            'object_parent',
            'obj_description:ntext',
        ],
    ]) ?>

</div>
