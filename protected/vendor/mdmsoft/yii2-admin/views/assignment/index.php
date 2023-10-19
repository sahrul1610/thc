<title>MANAGE ASSIGNMENT</title>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    $usernameField,
];
if (!empty($extraColumns)) {
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
	'class' => 'yii\grid\ActionColumn',
	'header'=>'Action',
	'template'=>'{view}',
	'buttons' => [
		'view' => function ($url, $model) {
			return Html::a('<i class="icofont icofont-eye"></i>', $url, [
					'title' => Yii::t('app', 'View'),
					'class'=>'btn btn-sm btn-pill btn-outline-warning',                                  
			]);
		}
	],
	'urlCreator' => function ($action, $model, $key, $index) {
		if ($action === 'view') {
			$url =Url::to([Yii::$app->controller->id.'/view', 'id'=>$model->user_id]);
			return $url;
		}
	}
];
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
				<a href="<?php echo Url::to(['/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/'.Yii::$app->controller->action->id.'']); ?>">
					Assignment
				</a>
			</li>
			<li class="breadcrumb-item active">Index</li>
		</ol>
		</div>
	</div>
</div>

<div class="card card-absolute">
	<div class="card-header bg-danger">
		<h5 class="text-white"><i class="icofont icofont-database"></i> Manage Assignment</h5>
	</div>
	<div class="tab-content card-block">
		<div class="assignment-index">

		<?php Pjax::begin(); ?>
		<?=
		GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => $columns,
		]);
		?>
		<?php Pjax::end(); ?>
	</div>
</div>
