<title>CREATE <?php echo strtoupper($this->context->labels()['Item']); ?></title>

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
                    <a href="<?php echo Url::to(['/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/index']); ?>">
                        <?php echo Yii::$app->controller->id; ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-absolute">
    <div class="card-header bg-primary">
        <h5 class="text-white"><i class="icofont icofont-database"></i> Create <?php echo Yii::$app->controller->id; ?></h5>
    </div>
	<div class="tab-content card-block">
		<div class="<?php echo $this->context->labels()['Item']; ?>-create">
			<?= $this->render('_form', [
				'model' => $model,
			]) ?>
		</div>
	</div>
</div>
