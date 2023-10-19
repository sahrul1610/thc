<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\Logic;
use app\models\MMaster;

$listjeniskontak = MMaster::find()->andWhere(['key'=>'contact_person', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
$datajeniskontak = Html::dropDownList('contacttype_id', null,ArrayHelper::map($listjeniskontak, 'master_id', 'name'), ['class'=>'form-control form-control-primary', 'prompt'=>'CHOOSE '.$kontak->getAttributeLabel('contacttype_id').'']);

?>

<div class="row">
	<div class="col-sm-12 ">
		<div class="table-responsive table-kontak">
			<table class="table table-striped text-nowrap" id="kontak">
				<thead>
					<tr>
						<?php
							foreach($kontak->customAttributeLabels() as $mdx=>$mrow){
								$keykontak[]['name'] = array_keys($mrow['name'])[0];
								$labelkontak = array_values($mrow['name'])[0];
								$classkontak = $mrow['class'];
						?>
								<th class="<?php echo $classkontak; ?>" id="<?php echo $mdx; ?>"><?php echo $labelkontak; ?></th>
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
		var table_id_kontak = $('.table-kontak').find('table').attr('id');
		$('#'+table_id_kontak+' thead tr').clone(true).appendTo('#'+table_id_kontak+' thead');
		$('#'+table_id_kontak+' thead tr:eq(1) th').each( function (i){
			var nofilter = $(this).hasClass('nofilter');
			if(nofilter == true){
				$(this).html('');
			}else{					
				var dpdown = $(this).hasClass('dpdown');
				if(dpdown == true){
					if(i == 3){
						$(this).html(<?php echo json_encode($datajeniskontak); ?>);
					}
					$('select', this).on('change', function(){
						if ($('#'+table_id_kontak+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id_kontak+'').DataTable().column(i).search(this.value, true).draw();
						}
					});					
				}else{				
					$(this).html('<input style="padding:.5rem !important;" type="text" class="form-control form-control-primary"/>');
					$('input', this).on('keyup change clear', function(){
						if ($('#'+table_id_kontak+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id_kontak+'').DataTable().column(i).search(this.value, false).draw();
						}
					});
				}
			}
		});	
		
		$('#'+table_id_kontak+'').DataTable({
			"dom": '<"toolbox"l><"btnprocesskontak text-right">rtip',
			"processing": true,
			"oLanguage": {
				"sProcessing": "<div class='loader-box'><div class='loader-20'></div></div>"
			},
			"serverSide": true,
			"lengthMenu": [[10, 100, 250, 500, 1000, -1], [10, 100, 250, 500, 1000, "All"]],
			"columns":<?php echo json_encode($keykontak); ?>,
			"ajax": {
				type: 'POST',
				dataType: "json",
				url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddtkontak']); ?>',
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
		
		$("div.btnprocesskontak").html('<button onclick="showannouncement()" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-warning-alt"></i> Pemberitahuan</button> <button onclick="showformkontak(\'\', \'Tambah\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-primary"><i class="icofont icofont-plus-square"></i> Tambah</button>');
    });
	
	function showformkontak(dataid, dataaction) {
		$('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> Form ' + dataaction + ' Kontak');

		$.ajax({
			url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showformkontak']); ?>',
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