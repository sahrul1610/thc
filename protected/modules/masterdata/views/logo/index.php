<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

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
                        <?php echo Yii::$app->controller->id; ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Index</li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-absolute">
    <div class="card-header bg-primary">
        <h5 class="text-white"><i class="icofont icofont-database"></i> Manage <?php echo Yii::$app->controller->id; ?></h5>
    </div>
    <div class="tab-content card-block">
		<div class="card-options text-right">
			<button onclick='showformdr("", "Tambah")' data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-plus-square"></i> Create <?php echo ucfirst(Yii::$app->controller->id); ?></button>
		</div>
		<div class="table-responsive mt-3 ">
			<table class="table table-striped text-nowrap" id="data_repository">
				<thead>
					<tr>
						<?php
							foreach($model->customAttributeLabels() as $mdx=>$mrow){
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

<script>
	$(document).ready(function() {
		var table_id = $('table').attr('id');
		
		$('#'+table_id+' thead tr').clone(true).appendTo('#'+table_id+' thead');
		$('#'+table_id+' thead tr:eq(1) th').each( function (i){
			var nofilter = $(this).hasClass('nofilter');
			if(nofilter == true){
				$(this).html('');
			}else{					
				var dpdown = $(this).hasClass('dpdown');
				if(dpdown == true){
					$(this).html('<select class="form-control form-control-primary" style="padding:.5rem !important;"><option value="">Choose<option value=1>Yes</option><option value=0>No</option></select>');
					
					$('select', this).on('change', function(){
						if ($('#'+table_id+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id+'').DataTable().column(i).search(this.value, true).draw();
						}
					});					
				}else{				
					$(this).html('<input style="padding:.5rem !important;" type="text" class="form-control form-control-primary"/>');
					$('input', this).on('keyup change clear', function(){
						if ($('#'+table_id+'').DataTable().column(i).search() !== this.value){
							$('#'+table_id+'').DataTable().column(i).search(this.value, false).draw();
						}
					});
				}
			}
		});	
		
		$('#'+table_id+'').DataTable({
			"dom":"lrt",
			"processing": true,
			"oLanguage": {
				"sProcessing": "<div class='loader-box'><div class='loader-20'></div></div>"
			},
			"serverSide": true,
			"lengthMenu": [[10, 100, 250, 500, 1000, -1], [10, 100, 250, 500, 1000, "All"]],
			"columns":<?php echo json_encode($key); ?>,
			columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap' style='width:400px'>" + data + "</div>";
                    },
                    targets: 4
                }
             ],
			"ajax": {
				type: 'POST',
				dataType: "json",
				url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddtdatarepository']); ?>',
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
	});	
	
	 function showformdr(dataid, dataaction) {
        $('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> Form ' + dataaction);

        $.ajax({
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showformdr']); ?>',
            type: 'POST',
            dataType: "html",
            data: {
                dataid: dataid,
                dataaction: dataaction
            },
            beforeSend: function () {
                $("#loadformloader").html('<div class="loader-box"><div class="loader-2"></div></div>');
                $("#loadformcontent").html('');
            },
            success: function (data) {
                $("#loadformcontent").html(data);
            },
            complete: function () {
                $("#loadformcontent").show();
                $("#loadformloader").html('');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
                $("#loadformcontent").html('<div class="alert alert-danger inverse alert-dismissible fade show m-0" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
            },
        });
    }
</script>
