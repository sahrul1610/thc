<style>
.numInputWrapper {
 display: none;
}
</style>

<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\Logic;
use app\models\MMaster;

if ($dataaction != 'Hapus') {
    $form = ActiveForm::begin([
        'method' => 'post',
        'id' => 'dr-form',
        'options' => [
            'class' => 'needs-validation was-validated',
            'enctype' => 'multipart/form-data'
        ]
    ]);
	$dataorg = $model->org_id ? [$model->org_id=>$model->org_code.' / '.$model->org_name.' / '.$model->band_name.' / '.$model->org_unit_code.' / '.$model->org_unit_name] : NULL;
	$dataempgroup = $model->empgroup_id ? [$model->empgroup_id=>$model->empgroup_code.' / '.$model->empgroup_name] : NULL;
	$dataempsubgroup = $model->empsubgroup_id ? [$model->empsubgroup_id=>$model->empsubgroup_code.' / '.$model->empsubgroup_name] : NULL;
	$databand = $model->band_id ? [$model->band_id=>$model->band_code.' / '.$model->band_name] : NULL;
	$datapsa = $model->psa_id ? [$model->psa_id=>$model->psa_code.' / '.$model->psa_name] : NULL;
	$datajobposition = $model->jobposition_id ? [$model->jobposition_id=>$model->jobposition_code.' / '.$model->jobposition_name] : NULL;
	$datajobfunction = $model->jobfunction_id ? [$model->jobfunction_id=>$model->jobfunction_code.' / '.$model->jobfunction_name] : NULL;
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
			<div class="col-md-4">
				<div class="form-group">
					<?php echo $form->field($model, 'employee_id')->textInput(['maxlength' => true, 'required'=>true]) ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<?php echo $form->field($model, 'person_name')->textInput(['maxlength' => true, 'required'=>true]) ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<?php echo $form->field($model, 'sex')->dropDownList(['L' => 'LAKI-LAKI', 'P' => 'PEREMPUAN']); ?>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<?php echo $form->field($model, 'org_id')->dropDownList($dataorg,['class'=>'form-control form-control-success btn-square']) ?>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-3">
				<?php echo $form->field($model, 'empgroup_id')->dropDownList($dataempgroup,['class'=>'form-control form-control-success btn-square']) ?>
			</div>
			<div class="col-md-3">
				<?php echo $form->field($model, 'empsubgroup_id')->dropDownList($dataempsubgroup,['class'=>'form-control form-control-success btn-square']) ?>
			</div>
			<div class="col-md-3">
				<?php echo $form->field($model, 'town_of_birth_id')->dropDownList($dataarea,['class'=>'form-control form-control-success btn-square']) ?>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_birth')->textInput(['class'=>'form-control single_date']) ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-3">
				<?php echo $form->field($model, 'ethnic_id')->dropDownList($dataethnic,['class'=>'form-control form-control-success btn-square']) ?>
			</div>
			<div class="col-md-3">
				<?php echo $form->field($model, 'religion_id')->dropDownList($datareligion,['class'=>'form-control form-control-success btn-square']) ?>
			</div>
			<div class="col-md-3">
				<?php echo $form->field($model, 'payroll_id')->dropDownList($datapayroll,['class'=>'form-control form-control-success btn-square']) ?>
			</div>
			<div class="col-md-3">
				<?php echo $form->field($model, 'marital_id')->dropDownList($datamarital,['class'=>'form-control form-control-success btn-square']) ?>
			</div>
		</div>	
			
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_hire')->textInput(['class'=>'form-control single_date']) ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_work')->textInput(['class'=>'form-control single_date']) ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_adjusted')->textInput(['class'=>'form-control single_date']) ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_kdmp')->textInput(['class'=>'form-control single_date']) ?>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_position')->textInput(['class'=>'form-control single_date']) ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_band_position')->textInput(['class'=>'form-control single_date']) ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_retire')->textInput(['class'=>'form-control single_date']) ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<?= $form->field($model, 'date_of_dedicated')->textInput(['class'=>'form-control single_date']) ?>
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
			
			searchmaster('employee-org_id', urlmaster, 'organisasi', '<?php echo $model->getAttributeLabel('org_id'); ?>', false);
			searchmaster('employee-empgroup_id', urlmaster, 'employee_group', '<?php echo $model->getAttributeLabel('empgroup_id'); ?>', false);
			searchmaster('employee-empsubgroup_id', urlmaster, 'employee_sub_group', '<?php echo $model->getAttributeLabel('empsubgroup_id'); ?>', false);
			searchmaster('employee-ethnic_id', urlmaster, 'ethnic_group', '<?php echo $model->getAttributeLabel('ethnic_id'); ?>', false);
			searchmaster('employee-religion_id', urlmaster, 'religion', '<?php echo $model->getAttributeLabel('religion_id'); ?>', false);
			searchmaster('employee-payroll_id', urlmaster, 'payroll_area', '<?php echo $model->getAttributeLabel('payroll_id'); ?>', false);
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
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/cruddr']); ?>',
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

                        $('#'+table_id+'').DataTable().ajax.reload();
                    } else {
                        $("#showerror").html('<div class="alert alert-danger dark" role="alert"><i class="icofont icofont-warning-alt"></i> Please fix the following errors ' + data.message + '</div>');
                    }
                },
                complete: function () {
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
    <?php
} else {
    ?>
    <center>
        <div id="showerror"></div>
        <div>Apakah anda yakin ingin melakukan penghapusan data ?</div>
        <div>Mohon jangan tutup browser atau melakukan reload halaman selama proses penghapusan data berlangsung</div>

        <div class="form-actions">
            <button type="button" class="btn btn-sm btn-pill btn-info" onclick="actiondelete()"><i
                        class="icofont icofont-checked"></i> Ya
            </button>
            <button type="button" class="btn btn-sm btn-pill btn-danger" data-dismiss="modal"><i
                        class="icofont icofont-close"></i> Tidak
            </button>
        </div>
    </center>

    <script>
        function actiondelete() {
			var table_id = $('table').attr('id');
            $.ajax({
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/cruddr']); ?>',
                type: 'POST',
                dataType: "json",
                data: {
                    dataid:<?php echo $dataid;?>,
                    dataaction: '<?php echo $dataaction;?>'
                },
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

                        $('#'+table_id+'').DataTable().ajax.reload();
                    } else {
                        $("#showerror").html('<div class="alert alert-danger dark" role="alert"><i class="icofont icofont-warning-alt"></i> ' + data.message + '</div>');
                    }
                },
                complete: function () {
                    $("#loadformloader").html('');
                    $("#loadformcontent").show();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
                    $("#showerror").html('<div class="alert alert-danger dark" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
                },
            });
        }
    </script>
    <?php
}
?>	