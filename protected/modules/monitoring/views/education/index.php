<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\EmployeeEducation;
use app\components\Logic;
use app\models\MMaster;

$listpendidikan = MMaster::find()->andWhere(['key'=>'education_level', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->orderBy(['order'=>SORT_ASC])->all();	
$datalevelpendidikan = Html::dropDownList('trg_id', null,ArrayHelper::map($listpendidikan, 'master_id', 'code'), ['class'=>'form-control form-control-primary', 'prompt'=>'CHOOSE '.$model->getAttributeLabel('level_id').'']);
?>

<title>Monitoring Education</title>

<div class="page-header">
    <div class="row">
        <div class="col-lg-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo Url::to(['/site/index']); ?>">
                        <i data-feather="home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo Url::to(['/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id . '']); ?>">
                        <?php echo Yii::$app->controller->module->id; ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Index</li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-absolute">
	<div class="tab-content card-block">
		<div class="card-header bg-primary">
			<h5 class="text-white"><i class="icofont icofont-contacts"></i> Monitoring Education</h5>
		</div>
		<div class="row mt-1">
			<div class="col-md-12 text-right">
				<div class="form-group">
					<button id="buttonzip" class="btn btn-sm btn-pill btn-outline-primary"><em class="icofont icofont-file-zip"></em> Export to Zip</button>
					<button id="buttonexcel" class="btn btn-sm btn-pill btn-primary"><em class="icofont icofont-file-excel"></em> Export to Excel</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 ">
				<div class="table-responsive table-education">
					<div id="getbuttoncheck"></div>
					<table class="table table-striped text-nowrap" id="education">
						<thead>
							<tr>
								<?php
									foreach($model->customAttributeLabelsMonitoring() as $mdx=>$mrow){
										$keyeducation[]['name'] = array_keys($mrow['name'])[0];
										$labeleducation = array_values($mrow['name'])[0];
										$classeducation = $mrow['class'];
								?>
										<th class="<?php echo $classeducation; ?>" id="<?php echo $mdx; ?>"><?php echo $labeleducation; ?></th>
								<?php	
									}
								?>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
		var table_id_education = $('.table-education').find('table').attr('id');
		$('#'+table_id_education+' thead tr').clone(true).appendTo('#'+table_id_education+' thead');
		$('#'+table_id_education+' thead tr:eq(1) th').each( function (i){
			var nofilter = $(this).hasClass('nofilter');
			if(nofilter == true){
				$(this).html('');
			}else{					
				var dpdown = $(this).hasClass('dpdown');
				if(dpdown == true){
					if(i == 2){
						$(this).html(<?php echo json_encode($datalevelpendidikan); ?>);
					}
					$('select', this).on('change', function(){
						if ($('#'+table_id_education+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id_education+'').DataTable().column(i).search(this.value, true).draw();
						}
					});					
				}else{				
					$(this).html('<input style="padding:.5rem !important;" type="text" class="form-control form-control-primary"/>');
					$('input', this).on('keyup change clear', function(){
						if ($('#'+table_id_education+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id_education+'').DataTable().column(i).search(this.value, false).draw();
						}
					});
				}
			}
		});	
		
		$('#'+table_id_education+'').DataTable({
			"dom": '<"toolbox"l><"btnprocessidentitas text-right">rtip',
			"processing": true,
			"oLanguage": {
				"sProcessing": "<div class='loader-box'><div class='loader-20'></div></div>"
			},
			"serverSide": true,
			"lengthMenu": [[10, 100, 250, 500, 1000, -1], [10, 100, 250, 500, 1000, "All"]],
			"columns":<?php echo json_encode($keyeducation); ?>,
			"ajax": {
				type: 'POST',
				dataType: "json",
				url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddtpendidikan']); ?>',
				data: function (d){
					return $.extend({},d,{
						custom_search:$('#getbuttoncheck').val(),
					});
				},
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
		
		$("#buttonexcel").click(function(ebent) {
			event.stopImmediatePropagation();
			event.preventDefault();
			
			$('#getbuttoncheck').val('buttonexcel');
			$('#'+table_id_education).DataTable().ajax.reload();
			$('#getbuttoncheck').val('');
		});	
		
		$("#buttonzip").click(function(ebent) {
			event.stopImmediatePropagation();
			event.preventDefault();
			
			$('#getbuttoncheck').val('buttonzip');
			$('#'+table_id_education).DataTable().ajax.reload();
			$('#getbuttoncheck').val('');
		});	
    });
</script>