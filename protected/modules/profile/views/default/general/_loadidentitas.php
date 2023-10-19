<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\Logic;
use app\models\MMaster;

$listjenisidentitas = MMaster::find()->andWhere(['key'=>'identity_type', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
$datajenisidentitas = Html::dropDownList('identitytype_id', null,ArrayHelper::map($listjenisidentitas, 'master_id', 'name'), ['class'=>'form-control form-control-primary', 'prompt'=>'CHOOSE '.$identitas->getAttributeLabel('identitytype_id').'']);

?>

<div class="row">
	<div class="col-sm-12 ">
		<div class="table-responsive table-identitas">
			<table class="table table-striped text-nowrap" id="identitas">
				<thead>
					<tr>
						<?php
							foreach($identitas->customAttributeLabels() as $mdx=>$mrow){
								$keyidentitas[]['name'] = array_keys($mrow['name'])[0];
								$labelidentitas = array_values($mrow['name'])[0];
								$classidentitas = $mrow['class'];
						?>
								<th class="<?php echo $classidentitas; ?>" id="<?php echo $mdx; ?>"><?php echo $labelidentitas; ?></th>
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
		var table_id_identitas = $('.table-identitas').find('table').attr('id');
		$('#'+table_id_identitas+' thead tr').clone(true).appendTo('#'+table_id_identitas+' thead');
		$('#'+table_id_identitas+' thead tr:eq(1) th').each( function (i){
			var nofilter = $(this).hasClass('nofilter');
			if(nofilter == true){
				$(this).html('');
			}else{					
				var dpdown = $(this).hasClass('dpdown');
				if(dpdown == true){
					if(i == 3){
						$(this).html(<?php echo json_encode($datajenisidentitas); ?>);
					}
					$('select', this).on('change', function(){
						if ($('#'+table_id_identitas+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id_identitas+'').DataTable().column(i).search(this.value, true).draw();
						}
					});					
				}else{				
					$(this).html('<input style="padding:.5rem !important;" type="text" class="form-control form-control-primary"/>');
					$('input', this).on('keyup change clear', function(){
						if ($('#'+table_id_identitas+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id_identitas+'').DataTable().column(i).search(this.value, false).draw();
						}
					});
				}
			}
		});	
		
		$('#'+table_id_identitas+'').DataTable({
			"dom": '<"toolbox"l><"btnprocessidentitas text-right">rtip',
			"processing": true,
			"oLanguage": {
				"sProcessing": "<div class='loader-box'><div class='loader-20'></div></div>"
			},
			"serverSide": true,
			"lengthMenu": [[10, 100, 250, 500, 1000, -1], [10, 100, 250, 500, 1000, "All"]],
			"columns":<?php echo json_encode($keyidentitas); ?>,
			"ajax": {
				type: 'POST',
				dataType: "json",
				url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddtidentitas']); ?>',
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
		
		$("div.btnprocessidentitas").html('<button onclick="showannouncement()" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-warning-alt"></i> Pemberitahuan</button> <button onclick="showformidentitas(\'\', \'Tambah\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-primary"><i class="icofont icofont-plus-square"></i> Tambah</button>');
    });
	
	function showformidentitas(dataid, dataaction) {
		$('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> Form ' + dataaction + ' Identitas');

		$.ajax({
			url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showformidentitas']); ?>',
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