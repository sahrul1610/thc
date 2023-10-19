<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\Logic;
use app\models\MMaster;
//$listalamat = MMaster::find()->andWhere(['key'=>'kabupaten', 'is_active'=>Logic::statusActive()])->orderBy(['order'=>SORT_ASC])->all();	
if ($dataaction != 'Hapus') {

    $form = ActiveForm::begin([
        'method' => 'post',
        'id' => 'crud-profile-form',
        'options' => [
            'class' => 'needs-validation was-validated',
            'enctype' => 'multipart/form-data'
        ]
    ]);
	$dataarea = $model->location_id ? [$model->location_id=>$model->location_name] : NULL;
?>
    <div class="row">
        <div class="col-md-12">
            <div id="showerror"></div>
        </div>
    </div>

	<div class="row">
		<div class="col-md-6">
            <!-- <?php echo $form->field($model, 'location_id')->dropDownList(ArrayHelper::map($listalamat, 'master_id', 'name'), ['class'=>'form-control form-control-primary', 'prompt'=>'CHOOSE '.$model->getAttributeLabel('location_id').'']); ?> -->

			<?php echo $form->field($model, 'location_id')->dropDownList($dataarea,['class'=>'form-control form-control-success btn-square']) ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
			</div>
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
		CKEDITOR.timestamp = new Date;
		CKEDITOR.replace('employeeaddress-address', {
			toolbar: [
				['base64image', 'Preview', 'Templates'],
				['Bold', 'Italic', 'Underline'],
				['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'BlockQuote', 'CreateDiv', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Link', 'Table'],
				['Styles', 'Format', 'Font', 'FontSize'],
				['TextColor', 'BGColor']
			]
		});
		
		var urlmaster = '<?php echo Url::toRoute(['/masterdata/search/index']); ?>';
		var table_id_alamat = $('.table-alamat').find('table').attr('id');
        $(document).ready(function () {
            searchmaster('employeeaddress-location_id', urlmaster, 'daerah', '<?php echo $model->getAttributeLabel('location_id'); ?>', false);
        });
		
        $('#crud-profile-form').on('submit', function (event) {
            event.preventDefault();
			
			for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
			}
			
            var data = new FormData($("#" + $(this).attr('id'))[0]);
			
            data.append('dataid', '<?php echo $dataid; ?>');
            data.append('dataaction', '<?php echo $dataaction; ?>');
            $.ajax({
                type: "POST",
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/crudalamat']); ?>',
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

                        $('#'+table_id_alamat).DataTable().ajax.reload();
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
		var table_id_alamat = $('.table-alamat').find('table').attr('id');
		
        function actiondelete() {
            $.ajax({
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/crudalamat']); ?>',
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

                        $('#'+table_id_alamat).DataTable().ajax.reload();
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