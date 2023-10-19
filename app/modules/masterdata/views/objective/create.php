<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Objective */

$this->title = Yii::t('app', 'Create Objective');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Objectives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objective-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
