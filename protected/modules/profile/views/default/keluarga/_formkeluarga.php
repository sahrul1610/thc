<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\Logic;

if ($dataaction != 'Hapus') {

    $form = ActiveForm::begin([
        'method' => 'post',
        'id' => 'crud-profile-form',
        'options' => [
            'class' => 'needs-validation was-validated',
            'enctype' => 'multipart/form-data'
        ]
    ]);
	
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
				<?php echo $form->field($model, 'famtype_id')->dropDownList(ArrayHelper::map($listtype, 'master_id', 'name'), ['required'=>true, 'class'=>'form-control form-control-primary', 'prompt'=>'CHOOSE '.$model->getAttributeLabel('famtype_id').'']); ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $form->field($model, 'famstatus_id')->dropDownList(ArrayHelper::map($liststatus, 'master_id', 'name'), ['required'=>true, 'class'=>'form-control form-control-primary', 'prompt'=>'CHOOSE '.$model->getAttributeLabel('famstatus_id').'']); ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $form->field($model, 'name')->textInput(['maxlength' => true, 'required' => true, 'class' => 'form-control']) ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<div class="form-group">
				<?php echo $form->field($model, 'sex')->dropDownList(['L' => 'LAKI-LAKI', 'P' => 'PEREMPUAN'], ['required'=>true]); ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<?= $form->field($model, 'date_of_birth')->textInput(['class'=>'form-control single_date', 'required'=>false]) ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $form->field($model, 'town_of_birth_id')->dropDownList($dataarea,['required'=>true, 'class'=>'form-control form-control-success btn-square']) ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $form->field($model, 'profession_id')->dropDownList(ArrayHelper::map($listprofession, 'master_id', 'name'), ['required'=>true, 'class'=>'form-control form-control-primary', 'prompt'=>'CHOOSE '.$model->getAttributeLabel('profession_id').'']); ?>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-2">
			<div class="form-group">
				<?= $form->field($model, 'date_of_marital')->textInput(['class'=>'form-control single_date', 'required'=>false]) ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<?= $form->field($model, 'date_of_divorce')->textInput(['class'=>'form-control single_date', 'required'=>false]) ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<?= $form->field($model, 'date_of_dead')->textInput(['class'=>'form-control single_date', 'required'=>false]) ?>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group m-t-50">
				<?= $form->field($model, 'is_still_alive')->checkbox() ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group m-t-50">
				<?= $form->field($model, 'is_dependent')->checkbox() ?>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<?php echo $form->field($model, 'no_marital')->textInput(['maxlength' => true, 'required' => false, 'class' => 'form-control']) ?>
			</div>
		</div>
		<div class="col-md-6 mb-2">
			<div class="form-group">
				<?php echo $form->field($model, 'url_scan_marital')->fileInput(['class' => 'form-control','required'=>false]) ?>
			</div>
			<?php if (file_exists($model->url_scan_marital)) { ?>
				<div class="file-content">
					<div class="files">
						<li class="file-box">
							<a title="Download File"
							   href="<?php echo Url::toRoute(['/masterdata/search/downloadfile', 'path' => $model->url_scan_marital]); ?>">
								<div class="file-top"><i class="icofont icofont-file-document"></i></div>
							</a>
							<div class="file-bottom">
								<h6>
									<?php
									$urlscan = explode('/', $model->url_scan_marital);
									echo end($urlscan); 
									?>
								</h6>
								<p><b>last upload : </b><?php echo Logic::Timeago($model->created_time); ?></p>
							</div>
						</li>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	
    <div class="row">
        <div class="col-md-12 text-right">
            <div class="form-group">
                <button data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-pill btn-outline-primary"><iclass="icofont icofont-ui-close"></i> Cancel
                </button>
                <button class="btn btn-sm btn-pill btn-primary"><i class="icofont icofont-save"></i> Submit</button>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <script>
		var urlmaster = '<?php echo Url::toRoute(['/masterdata/search/index']); ?>';
		var table_id = $('.table-profile').find('table').attr('id');
        $(document).ready(function () {
			$('select').select2();
			searchmaster('employeefamily-town_of_birth_id', urlmaster, 'daerahlahir', '<?php echo $model->getAttributeLabel('town_of_birth_id'); ?>', false);
			
			flatpicker('single_date', false);
        });
		
        $('#crud-profile-form').on('submit', function (event) {
            event.preventDefault();
            var data = new FormData($("#" + $(this).attr('id'))[0]);

            data.append('dataid', '<?php echo $dataid; ?>');
            data.append('dataaction', '<?php echo $dataaction; ?>');
            $.ajax({
                type: "POST",
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/crudkeluarga']); ?>',
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

                        $('#'+table_id).DataTable().ajax.reload();
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
            <button type="button" class="btn btn-sm btn-pill btn-primary" onclick="actiondelete()"><iclass="icofont icofont-checked"></i> Ya</button>
            <button type="button" class="btn btn-sm btn-pill btn-outline-primary" data-dismiss="modal"><iclass="icofont icofont-close"></i> Tidak</button>
        </div>
    </center>

    <script>
		var table_id = $('.table-profile').find('table').attr('id');
		
        function actiondelete() {
            $.ajax({
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/crudkeluarga']); ?>',
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

                        $('#'+table_id).DataTable().ajax.reload();
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