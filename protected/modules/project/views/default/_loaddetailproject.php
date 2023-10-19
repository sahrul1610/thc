<?php
	use yii\helpers\Url;
	use app\components\Logic;
?>

<div class="float-right" data-placement="top" data-toggle="tooltip" title="" data-original-title="Update project of <?php echo $model->name; ?>" >
	<a onclick='showformproject(<?php echo $model->project_id; ?>, "Edit")' data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" href="javascript:void(0)" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-edit"></i> Project</a>
</div>
<div style="clear:both"></div>			
<div class="row">
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('no_contract'); ?></p>
		<p><?php echo $model->no_contract != '' ? $model->no_contract : '-'; ?></p>
	</div>
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('amendment'); ?></p>
		<p><?php echo $model->amendment != '' ? $model->amendment : '-'; ?></p>
	</div>
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('bast_1'); ?></p>
		<p><?php echo $model->bast_1 != '' ? $model->bast_1 : '-'; ?></p>
	</div>
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('bast_2'); ?></p>
		<p><?php echo $model->bast_2 != '' ? $model->bast_2 : '-'; ?></p>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('groupproject_name'); ?></p>
		<p><?php echo $model->groupproject_name; ?></p>
	</div>
	<div class="col-md-6">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('name'); ?></p>
		<p><?php echo $model->name; ?></p>
	</div>
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('client_company'); ?></p>
		<p><?php echo $model->client_company != '' ? $model->client_company : '-'; ?> / <?php echo $model->client_unit != '' ? $model->client_unit : '-'; ?></p>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('range_date'); ?></p>
		<p><?php echo Logic::getIndoDate($model->start_date); ?> to <?php echo Logic::getIndoDate($model->end_date); ?></p>
	</div>
	<div class="col-md-3">
		<p class="text-muted mb-0">Members of Project</p>
		<p><?php echo $totalmember; ?></p>
	</div>
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('pm_project'); ?></p>
		<p>-</p>
	</div>
	<div class="col-md-3">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('technical_leader'); ?></p>
		<p>-</p>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<p class="text-muted mb-0"><?php echo $model->getAttributeLabel('description'); ?></p>
		<p><?php echo $model->description; ?></p>
	</div>
</div>

<script>
	$(document).ready(function() {
		$("div").tooltip();
	});
	
	function showformproject(dataid, dataaction) {
        $('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> Form ' + dataaction + ' Project');

        $.ajax({
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showformproject']); ?>',
            type: 'POST',
            dataType: "html",
            data: {
                dataid: dataid,
                dataaction: dataaction
            },
            beforeSend: function () {
                $("#loadformloader").html('<div class="loader-box"><div class="loader-2"></div></div>');
                $("#loadformcontent").html('');
            },
            success: function (data) {
                $("#loadformcontent").html(data);
            },
            complete: function () {
                $("#loadformcontent").show();
                $("#loadformloader").html('');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
                $("#loadformcontent").html('<div class="alert alert-danger inverse alert-dismissible fade show m-0" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
            },
        });
    }
</script>