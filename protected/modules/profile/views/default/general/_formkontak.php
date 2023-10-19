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
	
?>
    <div class="row">
        <div class="col-md-12">
            <div id="showerror"></div>
        </div>
    </div>

	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<?php echo $form->field($model, 'contacttype_id')->dropDownList(ArrayHelper::map($listkontak, 'master_id', 'name'), ['class'=>'form-control form-control-primary']); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<?php echo $form->field($model, 'no_contact')->textInput(['maxlength' => true, 'required' => true, 'class' => 'form-control']) ?>
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
		var table_id_kontak = $('.table-kontak').find('table').attr('id');
        $(document).ready(function () {
			$('select').select2();
        });
		
        $('#crud-profile-form').on('submit', function (event) {
            event.preventDefault();
            var data = new FormData($("#" + $(this).attr('id'))[0]);

            data.append('dataid', '<?php echo $dataid; ?>');
            data.append('dataaction', '<?php echo $dataaction; ?>');
            $.ajax({
                type: "POST",
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/crudkontak']); ?>',
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

                        $('#'+table_id_kontak).DataTable().ajax.reload();
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
		var table_id_kontak = $('.table-kontak').find('table').attr('id');
		
        function actiondelete() {
            $.ajax({
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/crudkontak']); ?>',
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

                        $('#'+table_id_identitas).DataTable().ajax.reload();
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