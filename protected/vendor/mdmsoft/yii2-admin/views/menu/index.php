<title>MANAGE MENU</title>

<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\widgets\Pjax;
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
		<div class="family-type-index">
			<div class="text-right">
				<?= Html::a('<i class="icofont icofont-plus-square"></i> Create', ['create'], ['class' => 'btn btn-sm btn-pill btn-outline-primary']) ?>
			</div>

			<?php Pjax::begin(); ?>
			<?=
			GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					[
						'class' => 'yii\grid\ActionColumn',
						'header'=>'Action',
						'template'=>'{update} {delete}',
						'buttons' => [
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
							if ($action === 'update') {
								$url =Url::to([Yii::$app->controller->id.'/update', 'id'=>$model->id]);
								return $url;
							}else if ($action === 'delete') {
								$url =Url::to([Yii::$app->controller->id.'/delete', 'id'=>$model->id]);
								return $url;
							}
						}
					],
					[
						'attribute' => 'menuParent.name',
						'filter' => Html::activeTextInput($searchModel, 'parent_name', [
							'class' => 'form-control', 'id' => null
						]),
						'label' => Yii::t('rbac-admin', 'Parent'),
					],
					'name',
					'route',
					'order'
				]
			]);
			?>
		<?php Pjax::end(); ?>
		</div>
	</div>
</div>
