<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\Logic;
use app\models\MMaster;

?>

<div class="row">
	<div class="col-sm-12 ">
		<div class="table-responsive table-alamat">
			<table class="table table-striped text-nowrap" id="alamat">
				<thead>
					<tr>
						<?php
							foreach($alamat->customAttributeLabels() as $mdx=>$mrow){
								$keyalamat[]['name'] = array_keys($mrow['name'])[0];
								$labelalamat = array_values($mrow['name'])[0];
								$classalamat = $mrow['class'];
						?>
								<th class="<?php echo $classalamat; ?>" id="<?php echo $mdx; ?>"><?php echo $labelalamat; ?></th>
						<?php	
							}
						?>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var table_id_alamat = $('.table-alamat').find('table').attr('id');
		$('#'+table_id_alamat+' thead tr').clone(true).appendTo('#'+table_id_alamat+' thead');
		$('#'+table_id_alamat+' thead tr:eq(1) th').each( function (i){
			var nofilter = $(this).hasClass('nofilter');
			if(nofilter == true){
				$(this).html('');
			}else{							
				$(this).html('<input style="padding:.5rem !important;" type="text" class="form-control form-control-primary"/>');
				$('input', this).on('keyup change clear', function(){
					if ($('#'+table_id_alamat+'').DataTable().column(i).search() !== this.value){
						$('#'+table_id_alamat+'').DataTable().column(i).search(this.value, false).draw();
					}
				});
			}
		});	
		
		$('#'+table_id_alamat+'').DataTable({
			"dom": '<"toolbox"l><"btnprocessalamat text-right">rtip',
			"processing": true,
			"oLanguage": {
				"sProcessing": "<div class='loader-box'><div class='loader-20'></div></div>"
			},
			"serverSide": true,
			"lengthMenu": [[10, 100, 250, 500, 1000, -1], [10, 100, 250, 500, 1000, "All"]],
			"columns":<?php echo json_encode($keyalamat); ?>,
			"ajax": {
				type: 'POST',
				dataType: "json",
				url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddtalamat']); ?>',
				dataSrc: function (json) {
					if(json.status == 'Success'){
						window.location = json.url;
						swal(
							'Success',
							''+json.message+'',
							'success'
						);
						
						return json.data;
					}else{
						return json.data;
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					var pesan = xhr.status + " " + thrownError + "\n" +  xhr.responseText;
					swal(
						'Failed',
						''+pesan+'',
						'error'
					);
				}
			},
			"ordering": false,
			"paging":true,
			"bInfo":true
		});
		
        $("select").select2();
		
		$("div.btnprocessalamat").html('<button onclick="showannouncement()" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-warning-alt"></i> Pemberitahuan</button> <button onclick="showformalamat(\'\', \'Tambah\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-primary"><i class="icofont icofont-plus-square"></i> Tambah</button>');
    });
	
	function showformalamat(dataid, dataaction) {
		$('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> Form ' + dataaction + ' Alamat');

		$.ajax({
			url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showformalamat']); ?>',
			type: 'POST',
			dataType: "html",
			data: {
				dataid: dataid,
				dataaction: dataaction
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