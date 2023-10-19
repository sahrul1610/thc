<title>UPDATE USER</title>

<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<div class="page-header">
	<div class="row">
		<div class="col-lg-6">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?php echo Url::to(['/site/index']); ?>">
					<i data-feather="home"></i>
				</a>
			</li>
			<li class="breadcrumb-item">
				<a href="<?php echo Url::to(['/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/index']);?>">
					User
				</a>
			</li>
			<li class="breadcrumb-item active">Update</li>
		</ol>
		</div>
	</div>
</div>

<div class="card card-absolute">
	<div class="card-header bg-danger">
		<h5 class="text-white"><i class="icofont icofont-database"></i> Update User</h5>
	</div>
	<div class="tab-content card-block">
		<div class="user-update">
			<?= $this->render('_form', [
				'model' => $model,
			]) ?>
		</div>
	</div>
</div>
