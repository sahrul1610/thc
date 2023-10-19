<title>VIEW ASSIGNMENT</title>

<?php

use mdm\admin\AnimateAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Assignment */
/* @var $fullnameField string */

$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = Yii::t('rbac-admin', 'Assignment') . ' : ' . $userName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
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
				<a href="<?php echo Url::to(['/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/index']); ?>">
					Assignment
				</a>
			</li>
			<li class="breadcrumb-item active">View</li>
		</ol>
		</div>
	</div>
</div>

<div class="card card-absolute">
	<div class="card-header bg-danger">
		<h5 class="text-white"><i class="icofont icofont-database"></i> View Assignment</h5>
	</div>
	<div class="tab-content card-block">
		<div class="assignment-index">
				<h1>Assignment <?=$this->title;?></h1>

			<div class="row">
				<div class="col-sm-5">
					<input class="form-control search" data-target="available"
						   placeholder="<?=Yii::t('rbac-admin', 'Search for available');?>">
					<select multiple size="20" class="form-control list" data-target="available">
					</select>
				</div>
				<div class="col-sm-2 text-center">
					<br><br>
					<?=Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => (string) $model->id], [
						'class' => 'btn btn-success btn-assign',
						'data-target' => 'available',
						'title' => Yii::t('rbac-admin', 'Assign'),
					]);?>
					<br><br>
					<?=Html::a('&lt;&lt;' . $animateIcon, ['revoke', 'id' => (string) $model->id], [
						'class' => 'btn btn-danger btn-assign',
						'data-target' => 'assigned',
						'title' => Yii::t('rbac-admin', 'Remove'),
					]);?>
				</div>
				<div class="col-sm-5">
					<input class="form-control search" data-target="assigned"
						   placeholder="<?=Yii::t('rbac-admin', 'Search for assigned');?>">
					<select multiple size="20" class="form-control list" data-target="assigned">
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
