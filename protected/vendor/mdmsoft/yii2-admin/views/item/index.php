<title>MANAGE <?php echo strtoupper($this->context->labels()['Item']); ?></title>

<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use mdm\admin\components\RouteRule;
use mdm\admin\components\Configs;

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
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
		<div class="<?php echo $this->context->labels()['Item']; ?>-index">
			<div class="text-right">
				<?= Html::a('<i class="icofont icofont-plus-square"></i> Create', ['create'], ['class' => 'btn btn-sm btn-pill btn-outline-primary']) ?>
			</div>
			<?=
			GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					[
						'class' => 'yii\grid\ActionColumn',
						'header'=>'Action',
						'template'=>'{view} {update} {delete}',
						'buttons' => [
							'view' => function ($url, $model) {
								return Html::a('<i class="icofont icofont-eye"></i>', $url, [
										'title' => Yii::t('app', 'View'),
										'class'=>'btn btn-xs btn-pill btn-outline-info',                                  
								]);
							},
							'update' => function ($url, $model) {
								return Html::a('<i class="icofont icofont-edit"></i>', $url, [
										'title' => Yii::t('app', 'Update'),
										'class'=>'btn btn-xs btn-pill btn-outline-warning',                                  
								]);
							},
							'delete' => function ($url, $model) {
								return Html::a('<i class="icofont icofont-trash"></i>', $url, [
										'title' => Yii::t('app', 'Delete'),
										'class'=>'btn btn-xs btn-pill btn-outline-danger',                                  
								]);
							},
						],
						'urlCreator' => function ($action, $model, $key, $index) {
							if ($action === 'view') {
								$url =Url::to([Yii::$app->controller->id.'/view', 'id'=>$model->name]);
								return $url;
							}else if ($action === 'update') {
								$url =Url::to([Yii::$app->controller->id.'/update', 'id'=>$model->name]);
								return $url;
							}else if ($action === 'delete') {
								$url =Url::to([Yii::$app->controller->id.'/delete', 'id'=>$model->name]);
								return $url;
							}
						}
					],
					[
						'attribute' => 'name',
						'label' => Yii::t('rbac-admin', 'Name'),
					],
					[
						'attribute' => 'ruleName',
						'label' => Yii::t('rbac-admin', 'Rule Name'),
						'filter' => $rules
					],
					[
						'attribute' => 'description',
						'label' => Yii::t('rbac-admin', 'Description'),
					]
				],
			])
			?>
		</div>
	</div>
</div>
