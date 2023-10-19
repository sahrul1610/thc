<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KeyResult */

$this->title = Yii::t('app', 'Create Key Result');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Key Results'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="key-result-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
