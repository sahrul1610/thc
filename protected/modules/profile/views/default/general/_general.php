<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\Logic;
?>


<div class="card card-absolute">
	<div class="tab-content card-block">
		<div class="userinfodetail">
			<div class="card-header bg-primary">
				<h5 class="text-white"><i class="icofont icofont-ui-user"></i> Informasi Identitas</h5>
			</div>

			<?php echo Yii::$app->controller->renderPartial('general/_loadidentitas', ['identitas'=>$identitas]); ?>	
		</div>
	</div>
</div>

<div class="card card-absolute">
	<div class="tab-content card-block">
		<div class="userinfodetail">
			<div class="card-header bg-primary">
				<h5 class="text-white"><i class="icofont icofont-ui-user"></i> Informasi Kontak</h5>
			</div>

			<?php echo Yii::$app->controller->renderPartial('general/_loadkontak', ['kontak'=>$kontak]); ?>	
		</div>
	</div>
</div>

<div class="card card-absolute">
	<div class="tab-content card-block">
		<div class="userinfodetail">
			<div class="card-header bg-primary">
				<h5 class="text-white"><i class="icofont icofont-ui-user"></i> Informasi Alamat</h5>
			</div>

			<?php echo Yii::$app->controller->renderPartial('general/_loadalamat', ['alamat'=>$alamat]); ?>	
		</div>
	</div>
</div>

<div class="card card-absolute">
	<div class="tab-content card-block">
		<div class="userinfodetail">
			<div class="row">
				<div class="card-header bg-primary">
					<h5 class="text-white"><i class="icofont icofont-ui-user"></i> Informasi Pekerjaan</h5>
				</div>

				<div class="card-block">
					<?php echo Yii::$app->controller->renderPartial('general/_loadgeneral', ['atasan'=>$atasan, 'employee'=>$employee]); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
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
	
	function showpengajuan(dataid, dataaction) {
		$('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> History Pengajuan');
		
        $.ajax({
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showpengajuan']); ?>',
            type: 'POST',
            dataType: "html",
            data: {
				dataid:dataid,
				dataaction:dataaction
			},
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