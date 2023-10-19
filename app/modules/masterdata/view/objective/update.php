<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Objective */

$this->title = Yii::t('app', 'Update Objective: {name}', [
    'name' => $model->obj_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Objectives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->obj_id, 'url' => ['view', 'id' => $model->obj_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="objective-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
