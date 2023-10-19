<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\Logic;
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
                    <a href="<?php echo Url::to(['/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id . '']); ?>">
                        <?php echo Yii::$app->controller->module->id; ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Index</li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-absolute ">
	<div class="card-header bg-primary">
		<h5 class="text-white"><i class="icofont icofont-papers"></i> SK Tools</h5>
	</div>
	<div class="card-body">
		<?php echo Yii::$app->controller->renderPartial('/../../../views/site/comingsoon'); ?>
	</div>
</div>