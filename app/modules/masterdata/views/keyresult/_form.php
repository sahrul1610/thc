<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KeyResult */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="key-result-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'obj_id')->textInput() ?>

    <?= $form->field($model, 'person_id_created')->textInput() ?>

    <?= $form->field($model, 'app_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
