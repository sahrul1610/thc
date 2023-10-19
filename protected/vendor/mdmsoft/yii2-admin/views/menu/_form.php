<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mdm\admin\models\Menu;
use yii\helpers\Json;
use mdm\admin\AutocompleteAsset;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */
/* @var $form yii\widgets\ActiveForm */

foreach(Menu::getMenuSource() as $mdx=>$mrow){
	$arrmenu[$mrow['id'].' / '.$mrow['name']] = $mrow['name'];
}
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin([
		'options' => [
			'class' => 'needs-validation was-validated'
		 ]	
	]); ?>
    
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 128, 'required'=>true]) ?>

			<?php
				$model->parent_name = $model->parent.' / '.$model->menuParent->name;
				echo $form->field($model, 'parent_name')->dropDownList($arrmenu,['prompt'=>'Pilih Parent','required'=>false]);
			?>
            
            <?= $form->field($model, 'route')->dropDownList(Menu::getSavedRoutes(),['prompt'=>'Pilih Route','required'=>true]); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'order')->input('number',['required'=>true]) ?>

            <?= $form->field($model, 'data')->textarea(['rows' => 4, 'required'=>false]) ?>
        </div>
    </div>

    <div class="form-group text-right">
        <?=
        Html::submitButton(Yii::t('rbac-admin', '<i class="icofont icofont-save"></i> Submit'), ['class' => 'btn btn-sm btn-pill btn-primary'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script>
	$(document).ready(function() {
		$('select').select2();
	});
</script>