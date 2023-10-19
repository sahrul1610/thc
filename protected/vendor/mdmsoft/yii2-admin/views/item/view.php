<title>VIEW <?php echo strtoupper($this->context->labels()['Item']); ?></title>

<?php

use mdm\admin\AnimateAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

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
                    <a href="<?php echo Url::to(['/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/index']); ?>">
                        <?php echo Yii::$app->controller->id; ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Index</li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-absolute">
    <div class="card-header bg-primary">
        <h5 class="text-white"><i class="icofont icofont-database"></i> Manage <?php echo Yii::$app->controller->id; ?></h5>
    </div>
	<div class="tab-content card-block">
		<div class="auth-item-view">
			<h1><?php echo $this->context->labels()['Item']; ?> <?=Html::encode($this->title);?></h1>
			<p>
				<?=Html::a(Yii::t('rbac-admin', '<i class="icofont icofont-edit"></i> Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-sm btn-pill btn-outline-warning']);?>
				<?=Html::a(Yii::t('rbac-admin', '<i class="icofont icofont-trash"></i> Delete'), ['delete', 'id' => $model->name], [
					'class' => 'btn btn-sm btn-pill btn-outline-danger',
					'data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'),
					'data-method' => 'post',
				]);?>
				<?=Html::a(Yii::t('rbac-admin', '<i class="icofont icofont-plus-square"></i> Create'), ['create'], ['class' => 'btn btn-sm btn-pill btn-outline-primary']);?>
			</p>
			<div class="row">
				<div class="col-sm-12">
					<?=
					DetailView::widget([
						'model' => $model,
						'attributes' => [
							'name',
							'description:ntext',
							'ruleName',
							'data:ntext',
						],
						'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
					]);
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-5">
					<input class="form-control search" data-target="available" placeholder="<?=Yii::t('rbac-admin', 'Search for available');?>">
					<select multiple size="20" class="form-control list" data-target="available"></select>
				</div>
				<div class="col-sm-2 text-center">
					<br><br>
					<?=Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => $model->name], [
						'class' => 'btn btn-sm btn-outline-success btn-assign',
						'data-target' => 'available',
						'title' => Yii::t('rbac-admin', 'Assign'),
					]);?><br><br>
								<?=Html::a('&lt;&lt;' . $animateIcon, ['remove', 'id' => $model->name], [
						'class' => 'btn btn-sm btn-outline-danger btn-assign',
						'data-target' => 'assigned',
						'title' => Yii::t('rbac-admin', 'Remove'),
					]);?>
				</div>
				<div class="col-sm-5">
					<input class="form-control search" data-target="assigned" placeholder="<?=Yii::t('rbac-admin', 'Search for assigned');?>">
					<select multiple size="20" class="form-control list" data-target="assigned"></select>
				</div>
			</div>
		</div>
	</div>
</div>