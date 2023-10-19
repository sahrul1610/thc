<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

$YII_ENV = getenv('YII_ENV') ? getenv('YII_ENV') : YII_ENV;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
		'options' => [
			'class' => 'auth-login-form'
		 ],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-form-label'],
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback'],
        ],
    ]); ?>

	<div class="row">
		<div class="col-md-12 mb-1">
			<div class="input-group input-group-merge border-login">
				<span class="input-group-text cursor-pointer border-none"><i class="icofont icofont-user icofont-1-5x color-green"></i></span>
				<input type="text" id="loginform-username" class="form-control form-control-merge border-none" name="LoginForm[username]" autofocus="" placeholder="Username" aria-required="true" autocomplete="off">
			</div>
		</div>
		
		<div class="col-md-12 mb-1">
			<div class="input-group input-group-merge form-password-toggle border-login">
				<span class="input-group-text cursor-pointer border-none"><i class="icofont icofont-lock icofont-1-5x color-green"></i></span>
				<input type="password" id="loginform-password" class="form-control form-control-merge border-none" name="LoginForm[password]" placeholder="Password..." aria-required="true" autocomplete="off">
				<span class="input-group-text input-group-text-right cursor-pointer border-none"><i data-feather="eye" class="color-green"></i></span>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12 d-grid">
			<?= Html::submitButton('<i class="icofont icofont-sign-in"></i> Login', ['class' => 'btn btn-primary waves-effect waves-float waves-light g-recaptcha', 'data-sitekey'=>'6Ld6JCQhAAAAAOke52qggfx_l34rsuMr0aS26jas', 'data-callback'=>'onSubmit', 'data-action'=>'submit', 'name' => 'login-button']) ?>
		</div>
	</div>

    <?php ActiveForm::end(); ?>
	
	<!-- <?php if($YII_ENV == 'prod'){ ?>
		<script src="https://www.google.com/recaptcha/api.js"></script>
	<?php } ?> -->
</div>

<script>
	function onSubmit(token) {
		document.getElementById("login-form").submit();
	}
 </script>