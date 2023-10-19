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
		<h5 class="text-white"><i class="icofont icofont-tick-boxed"></i> Profile Verification</h5>
	</div>
	<div class="card-block tab-content">
		<div class="text-right">
			<button onclick="showannouncement()" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-warning-alt"></i> Pemberitahuan</button>
		</div>

		<ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
			<li class="nav-item"><a class="nav-link active" onclick="showdata(01)" id="top-na-tab" data-toggle="tab" href="#top-na" role="tab" aria-controls="top-na" aria-selected="true" data-original-title="" title=""><i class="icofont icofont-warning-alt"></i>Butuh Persetujuan</a></li>
			<li class="nav-item"><a class="nav-link" onclick="showdata(02)"  id="top-ap-tab" data-toggle="tab" href="#top-ap" role="tab" aria-controls="top-ap" aria-selected="false" data-original-title="" title=""><i class="icofont icofont-checked"></i>Disetujui</a></li>
			<li class="nav-item"><a class="nav-link" onclick="showdata(03)" id="top-rt-tab" data-toggle="tab" href="#top-rt" role="tab" aria-controls="top-rt" aria-selected="false" data-original-title="" title=""><i class="icofont icofont-close-circled"></i>Dikembalikan</a></li>
		</ul>
		
		<div id="loadloader"></div>
		<div id="loadcontent" class="m-t-15"></div>
	</div>
</div>

<script>
	$(document).ready(function() {
		showdata(01);
	});
	
	function showdata(datanav){
		$.ajax({
			url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddataprofile']); ?>',
			type: 'POST',
			dataType: "html",
			data:{
				datanav:datanav
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
	
	function showannouncement() {
        $.ajax({
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showannouncement']); ?>',
            type: 'POST',
            dataType: "html",
            data: {},
            beforeSend: function() {
                $("#loadformloader").html('<div class="loader-box"><div class="loader-39"></div></div>');
                $("#loadformcontent").html('');
            },
            success: function(data) {
                $("#loadformcontent").html(data);
            },
            complete: function() {
                $("#loadformcontent").show();
                $("#loadformloader").html('');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
                $("#loadformcontent").html('<div class="alert alert-danger inverse alert-dismissible fade show m-0" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
            },
        });
    }
</script>