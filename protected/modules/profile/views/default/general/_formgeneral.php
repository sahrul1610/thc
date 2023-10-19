<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\Logic;
use app\models\MMaster;

$form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'dr-form',
    'options' => [
        'class' => 'needs-validation was-validated',
        'enctype' => 'multipart/form-data'
    ]
]);
$dataethnic = $model->ethnic_id ? [$model->ethnic_id=>$model->ethnic_code.' / '.$model->ethnic_name] : NULL;
$datareligion = $model->religion_id ? [$model->religion_id=>$model->religion_code.' / '.$model->religion_name] : NULL;
$datapayroll = $model->payroll_id ? [$model->payroll_id=>$model->payroll_code.' / '.$model->payroll_name] : NULL;
$datamarital = $model->marital_id ? [$model->marital_id=>$model->marital_code.' / '.$model->marital_name] : NULL;
$dataarea = $model->town_of_birth_id ? [$model->town_of_birth_id=>$model->town_of_birth_name] : NULL;
?>
	<div class="row">
		<div class="col-md-12">
			<div id="showerror"></div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>NIK</label>
				<div><?= $model->employee_id; ?></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>NAMA KARYAWAN</label>
				<div><?= $model->person_name; ?></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<?php echo $form->field($model, 'sex')->dropDownList(['L' => 'LAKI-LAKI', 'P' => 'PEREMPUAN']); ?>
			</div>
		</div>
		<div class="col-md-3">
			<?php echo $form->field($model, 'ethnic_id')->dropDownList($dataethnic,['class'=>'form-control form-control-success btn-square']) ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->field($model, 'religion_id')->dropDownList($datareligion,['class'=>'form-control form-control-success btn-square']) ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->field($model, 'marital_id')->dropDownList($datamarital,['class'=>'form-control form-control-success btn-square']) ?>
		</div>
	</div>	
	
	<div class="row">
		<div class="col-md-3">
			<?php echo $form->field($model, 'town_of_birth_id')->dropDownList($dataarea,['class'=>'form-control form-control-success btn-square']) ?>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<?= $form->field($model, 'date_of_birth')->textInput(['class'=>'form-control single_date']) ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<?= $form->field($model, 'url_photo')->fileInput(['class' => 'form-control','required'=>false]) ?>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12 text-right">
			<div class="form-group">
				<button data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-ui-close"></i> Cancel</button>
				<button class="btn btn-sm btn-pill btn-primary"><i class="icofont icofont-save"></i> Submit</button>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>

<script>
	var urlmaster = '<?php echo Url::toRoute(['/masterdata/search/index']); ?>';
	$(document).ready(function() {
		$('select').select2();	
		
		searchmaster('employee-ethnic_id', urlmaster, 'ethnic_group', '<?php echo $model->getAttributeLabel('ethnic_id'); ?>', false);
		searchmaster('employee-religion_id', urlmaster, 'religion', '<?php echo $model->getAttributeLabel('religion_id'); ?>', false);
		searchmaster('employee-marital_id', urlmaster, 'marital_status', '<?php echo $model->getAttributeLabel('marital_id'); ?>', false);
		searchmaster('employee-town_of_birth_id', urlmaster, 'daerah', '<?php echo $model->getAttributeLabel('town_of_birth_id'); ?>', false);
		
		flatpicker('single_date', false);
	});
	
    $('#dr-form').on('submit', function (event) {
		var table_id = $('table').attr('id');
		event.stopImmediatePropagation();
		event.preventDefault();	
		
        var data = new FormData($("#" + $(this).attr('id'))[0]);

        data.append('dataid', '<?php echo $dataid; ?>');
        data.append('dataaction', '<?php echo $dataaction; ?>');
        $.ajax({
            type: "POST",
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/updategeneral']); ?>',
            processData: false,
            contentType: false,
            dataType: "json",
            data: data,
            beforeSend: function () {
                $("#loadformloader").html('<div class="loader-box"><div class="loader-2"></div></div>');
                $("#showerror").html('');
                $("#loadformcontent").hide();
            },
            success: function (data) {
                if (data.status == 'Success') {
                    $('#loadmodal').modal('hide');

                    swal(
                        'Success',
                        '' + data.message + '',
                        'success'
                    );

                    showdata('general');
                } else {
                    $("#showerror").html('<div class="alert alert-danger dark" role="alert"><i class="icofont icofont-warning-alt"></i> Please fix the following errors ' + data.message + '</div>');
                }
            },
            complete: function () {
            	showdata('general');
                $("#loadformloader").html('');
                $("#loadformcontent").show();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
                $("#showerror").html('<div class="alert alert-danger dark" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
            },
        });
    });
</script>