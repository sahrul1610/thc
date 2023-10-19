<title>MANAGE USER</title>

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
				<a href="<?php echo Url::to(['/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/'.Yii::$app->controller->action->id.'']); ?>">
					User
				</a>
			</li>
			<li class="breadcrumb-item active">Index</li>
		</ol>
		</div>
	</div>
</div>

<div class="card card-absolute">
	<div class="card-header bg-danger">
		<h5 class="text-white"><i class="icofont icofont-database"></i> Manage User</h5>
	</div>
	<div class="tab-content card-block">
		<div class="user-index">
			<div class="text-right">
				<a onclick="syncusers()" href="javascript::void(0)" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-refresh"></i> Synchronize Users</a>
				<?= Html::a('<i class="icofont icofont-plus-square"></i> Create', ['create'], ['class' => 'btn btn-sm btn-pill btn-success']) ?>
			</div>
			
			<div class="table-responsive">

				<?php Pjax::begin([
						'id'=>'pjax-users',
						'enablePushState'=>FALSE
					]); 
				?>
				<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'filterModel' => $searchModel,
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],
						'username',
						'is_active:boolean',
						'is_ldap:boolean',
						'last_login',
						[
							'attribute' => 'created_time', 
							'value' => function ($model, $key, $index, $widget) { 
								return date('Y-m-d H:i:s', strtotime($model->created_time));
							}
						],
						[
							'class' => 'yii\grid\ActionColumn',
							'header'=>'Action',
							'template'=>'{update} {delete}',
							'buttons' => [
								'update' => function ($url, $model) {
									return Html::a('<i class="icofont icofont-edit"></i>', $url, [
											'title' => Yii::t('app', 'Update'),
											'class'=>'btn btn-sm btn-pill btn-outline-warning',                                  
									]);
								},
								'delete' => function ($url, $model) {
									return Html::a('<i class="icofont icofont-trash"></i>', $url, [
											'title' => Yii::t('app', 'Delete'),
											'class'=>'btn btn-sm btn-pill btn-outline-danger',                                  
									]);
								},
							],
							'urlCreator' => function ($action, $model, $key, $index) {
								if ($action === 'update') {
									$url =Url::to([Yii::$app->controller->id.'/update', 'id'=>$model->user_id]);
									return $url;
								}else if ($action === 'delete') {
									$url =Url::to([Yii::$app->controller->id.'/delete', 'id'=>$model->user_id]);
									return $url;
								}
							}
						],
					],
				]); ?>

				<?php Pjax::end(); ?>

			</div>
		</div>
	</div>
</div>

<script>
	function syncusers(){
		$.ajax({
			type: "POST",
			url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/syncusers']); ?>',
			dataType: "json",
			data:{
			},
			beforeSend: function() {
				swal({
					title : '<div class="loader-box"><div class="loader-39"></div></div><div class="text-center" style="font-size:14px;">Please wait, synchronizing users on process, please dont close or refresh the browser</div>',
					showConfirmButton:false,
					allowOutsideClick:false
				});
			},
			success: function(data){
				if(data.status == 'Success'){
					swal(
						'Success',
						''+data.message+'',
						'success'
					);
					
					$.pjax.reload({container: '#pjax-users'});
				}else{
					swal(
						'Failed',
						'<div class="alert alert-danger mt-1 alert-validation-msg" role="alert"><div class="alert-body align-items-center">'+data.message+'</div></div>',
						'error'
					);
				}
			},
			complete: function(){},
			error: function (xhr, ajaxOptions, thrownError) {
				var pesan = xhr.status + " " + thrownError + "\n" +  xhr.responseText;
				swal(
					'Failed',
					'<div class="alert alert-danger mt-1 alert-validation-msg" role="alert"><div class="alert-body align-items-center">'+pesan+'</div></div>',
					'error'
				);
			},
		});
	}
</script>
