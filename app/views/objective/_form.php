<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Objective */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objective-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'event_id')->textInput() ?>

    <?= $form->field($model, 'obj_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'person_id_created')->textInput() ?>

    <?= $form->field($model, 'app_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'person_id_approval')->textInput() ?>

    <?= $form->field($model, 'objtype_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'key_result')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
