<!-- <?php
/* @var $this yii\web\View */
?>
<h1>cv/index</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p> -->
<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\Logic;
	use app\models\MMaster;
	use yii\helpers\ArrayHelper;

    
?>
<title>Monitoring CV</title>

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
			<h5 class="text-white"><i class="icofont icofont-files"></i> cv</h5>
		</div>

		<!-- <div class="card-options text-right">
			<button id="buttonexcel" src class="btn btn-sm btn-pill btn-primary"><em class="icofont icofont-file-excel"></em> Export to Excel</button>
		</div> -->


		<div class="row">
			<div class="col-sm-12 ">
				<div class="table-responsive table-cv">
					<div id="getbuttoncheck"></div> 
					<table class="table table-striped text-nowrap" id="cv">
						<thead>
							<tr>
								<?php
									foreach($model->customAttributeLabels2() as $mdx=>$mrow){
										$key[]['name'] = array_keys($mrow['name'])[0];
										$label = array_values($mrow['name'])[0];
										$class = $mrow['class'];
								?>
										<th class="<?php echo $class; ?>" id="<?php echo $mdx; ?>"><?php echo $label; ?></th>
                                        
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
		var table_id_cv = $('.table-cv').find('table').attr('id');
		console.log(table_id_cv);
		$('#'+table_id_cv+' thead tr').clone(true).appendTo('#'+table_id_cv+' thead');
		
		$('#'+table_id_cv+' thead tr:eq(1) th').each( function (i){
			console.log(table_id_cv);
			var nofilter = $(this).hasClass('nofilter');
			if(nofilter == true){
				$(this).html('');
			}else{					
				var dpdown = $(this).hasClass('dpdown');
				if(dpdown == true){
					if(i == 4){
						$(this).html(<?php echo json_encode($datajenisidentitas); ?>);
					}
					$('select', this).on('change', function(){
						if ($('#'+table_id_cv+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id_cv+'').DataTable().column(i).search(this.value, true).draw();
						}
					});					
				}else{				
					$(this).html('<input style="padding:.5rem !important;" type="text" class="form-control form-control-primary"/>');
					$('input', this).on('keyup change clear', function(){
						if ($('#'+table_id_cv+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id_cv+'').DataTable().column(i).search(this.value, false).draw();
						}
					});
				}
			}
		});	
		
		$('#'+table_id_cv+'').DataTable({
			"dom": '<"toolbox"l><"btnprocessidentitas text-right">rtip',
			"processing": true,
			"oLanguage": {
				"sProcessing": "<div class='loader-box'><div class='loader-20'></div></div>"
			},
			"serverSide": true,
			"lengthMenu": [[10, 100, 250, 500, 1000, -1], [10, 100, 250, 500, 1000, "All"]],
			"columns":<?php echo json_encode($key); ?>,
			"ajax": {
				type: 'POST',
				dataType: "json",
				url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddtcv']); ?>',
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
			$('#'+table_id_cv).DataTable().ajax.reload();
			$('#getbuttoncheck').val('');
		});	
		//$("div.btnprocessidentitas").html('<button onclick="showannouncement()" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-warning-alt"></i> Pemberitahuan</button> <button onclick="showformidentitas(\'\', \'Tambah\')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-primary"><i class="icofont icofont-plus-square"></i> Tambah</button>');
    });
</script>