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
                    <a href="<?php echo Url::to(['/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/index']); ?>">
                        <?php echo Yii::$app->controller->module->id; ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Project Detail of : <?php echo $model->name; ?></li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-absolute ">
	<div class="card-header bg-primary">
		<h5 class="text-white"><i class="icofont icofont-document-search"></i> Project Detail</h5>
	</div>
	<div class="card-block tab-content">
		<ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
			<li class="nav-item"><a class="nav-link active" onclick="showdatadetail('project')" id="active-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-selected="true" data-original-title="" title="">Project</a></li>
			<li class="nav-item"><a class="nav-link" onclick="showdatadetail('members')"  id="completed-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-selected="false" data-original-title="" title="">Members</a></li>
			<li class="nav-item"><a class="nav-link" onclick="showdatadetail('applications')"  id="completed-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-selected="false" data-original-title="" title="">Applications</a></li>
			<li class="nav-item"><a class="nav-link" onclick="showdatadetail('documents')"  id="completed-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-selected="false" data-original-title="" title="">Documents</a></li>
		</ul>
		
		<div id="loadloader"></div>
		<div id="loadcontent" class="m-t-15"></div>
	</div>
</div>

<script>
	$(document).ready(function() {
		showdatadetail('project');
	});
	
	function showdatadetail(datanav){
		$.ajax({
			url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddetail']); ?>'+datanav,
			type: 'POST',
			dataType: "html",
			data:{
				project_id:<?php echo $model->project_id; ?>
			},
			beforeSend: function() {
				$("#loadloader").html('<div class="loader-box"><div class="loader-2"></div></div>');
				$("#loadcontent").html('');
			},
			success: function(data){
				$("#loadcontent").html(data);
			},
			complete: function(){
				$("#loadloader").html('');
			},
			error: function (xhr, ajaxOptions, thrownError) {
				var pesan = xhr.status + " " + thrownError + "\n" +  xhr.responseText;
				$("#loadcontent").html('<div class="alert alert-danger dark" role="alert"><i class="icofont icofont-warning-alt"></i> '+pesan+'</div>');
			},
		});
	}
</script>