<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\Logic;
use app\models\ProjectMember;

if ($dataaction != 'Hapus') {
    $form = ActiveForm::begin([
        'method' => 'post',
        'id' => 'project-form',
        'options' => [
            'class' => 'needs-validation was-validated',
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>
		<div class="row">
			<div class="col-md-12">
				<div id="showerror"></div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<?php echo $form->field($model, 'code')->textInput(['maxlength' => true, 'required'=>true]) ?>
						</div>
					</div>
					
					<div class="col-md-8">
						<div class="form-group">
							<?php echo $form->field($model, 'name')->textInput(['maxlength' => true, 'required'=>true]) ?>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<?php echo $form->field($model, 'groupproject_id')->dropDownList(ArrayHelper::map($listgroup, 'master_id', 'name'), ['required'=>true, 'class'=>'form-control form-control-primary']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?= $form->field($model, 'client_company')->textInput(['maxlength' => true, 'required'=>false]) ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?= $form->field($model, 'client_unit')->textInput(['maxlength' => true, 'required'=>false]) ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?= $form->field($model, 'no_contract')->textInput(['maxlength' => true, 'required'=>false]) ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?= $form->field($model, 'amendment')->textInput(['maxlength' => true, 'required'=>false]) ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?= $form->field($model, 'bast_1')->textInput(['maxlength' => true, 'required'=>false]) ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?= $form->field($model, 'bast_2')->textInput(['maxlength' => true, 'required'=>false]) ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?= $form->field($model, 'start_date')->textInput(['class'=>'form-control single_date']) ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?= $form->field($model, 'end_date')->textInput(['class'=>'form-control single_date']) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
				
		<div class="row mb-4">
			<div class="col-md-12">
				<label class="control-label" for="project-project_member"><?php echo $model->getAttributeLabel('project_member');?></label>
				<?php
					$listmember = ProjectMember::find()->andWhere(['project_id'=>$model->project_id, 'is_active'=>true])->all();
					$listselectmember = '<select id="project-project_member" name="Project[project_member][]" multiple=true class="form-control">';
						foreach($listmember as $ldx=>$lrow){
							$listselectmember .='<option value="'.$lrow->person_id.'" selected>'.$lrow->person_name.'</option>';
						} 
					$listselectmember .= '</select>';
					echo $listselectmember;
				?>
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
		CKEDITOR.timestamp = new Date;
		CKEDITOR.replace('project-description', {
			toolbar: [
				['base64image', 'Preview', 'Templates'],
				['Bold', 'Italic', 'Underline'],
				['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'BlockQuote', 'CreateDiv', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Link', 'Table'],
				['Styles', 'Format', 'Font', 'FontSize'],
				['TextColor', 'BGColor']
			]
		});
		
		var urlmaster = '<?php echo Url::toRoute(['/masterdata/search/index']); ?>';
		$(document).ready(function () {
			$('select').select2();
			searchmaster('project-project_member', urlmaster, 'employee', '<?php echo $model->getAttributeLabel('project_member'); ?>', true);
			flatpicker('single_date', false);
		});
		
        $('#project-form').on('submit', function (event) {
			event.stopImmediatePropagation();
			event.preventDefault();
			
			for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
			}
			
            var data = new FormData($("#" + $(this).attr('id'))[0]);

            data.append('dataid', '<?php echo $dataid; ?>');
            data.append('dataaction', '<?php echo $dataaction; ?>');
            $.ajax({
                type: "POST",
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/crudproject']); ?>',
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

						if (typeof showdata !== 'undefined' && $.isFunction(showdata)) {
							showdata(01);
						}
						if (typeof showdatadetail !== 'undefined' && $.isFunction(showdatadetail)) {
							showdatadetail('project');
						}
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
            $.ajax({
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/crudproject']); ?>',
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

                        showdata(01);
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