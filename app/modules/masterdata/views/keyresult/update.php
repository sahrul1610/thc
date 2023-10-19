<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KeyResult */

$this->title = Yii::t('app', 'Update Key Result: {name}', [
    'name' => $model->kr_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Key Results'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kr_id, 'url' => ['view', 'id' => $model->kr_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="key-result-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
