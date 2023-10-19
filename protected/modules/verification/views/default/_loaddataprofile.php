<?php 
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<style>
	div#profile_filter {
		display: none;
	}
	.select2 {
		width: auto !important;
	}
</style>

<div class="row">
    <div class="col-sm-12 ">
        <div class="table-responsive table-verifikasi">
			<table class="table table-striped text-nowrap" id="profile">
				<thead>
					<tr>
						<?php
							foreach($header as $mdx=>$mrow){
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
	var table_id = $('.table-verifikasi').find('table').attr('id');
	
	$(document).ready(function() {
		var selected = [];
		
		$('#'+table_id).DataTable({
			"dom": '<"toolbox"l><"btnprocess text-right">rtip',
			"processing": true,
			"oLanguage": {
				"sProcessing": "<div class='loader-box'><div class='loader-20'></div></div>"
			},
			"serverSide": true,
			"lengthMenu": [[100, 250, 500, 1000, -1], [100, 250, 500, 1000, "All"]],
			"columns":<?php echo json_encode($key); ?>,
			"columnDefs": [
				{
					"className": "select-checkbox text-center",
					"targets": 0
				}
			],
			"select": {
				"style": 'muti',
				"selector": 'td:not(:nth-child(3), :nth-child(5))'
			},
			"ajax": {
				type: 'POST',
				dataType: "json",
				url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddtprofile']); ?>',
				data: function (d){
					return $.extend({},d,{
						datanav: '<?php echo $datanav; ?>'
					});
				},
				dataSrc: function ( json ) {
					if(json.status == 'Success'){
						window.location = json.url;
						swal(
							'Success',
							''+json.message+'',
							'success'
						);
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
			"rowCallback": function( row, data ) {
				if ($.inArray(data.DT_RowId, selected) !== -1 ) {
					$(row).addClass('selected');
				}
			},
			"ordering": false,
			"paging":true,
			"bInfo":true
		});
		
		<?php if($datanav == 01){ ?>
			$("div.btnprocess").html('<button onclick="showformprocess()" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-primary"><i class="icofont icofont-checked"></i> Proses Pengajuan</button>');
		<?php } ?>
		
		$('#profile thead tr').clone(true).appendTo('#profile thead');
		$('#profile thead tr:eq(1) th').each( function (i){
			var nofilter = $(this).hasClass('nofilter');
			if(nofilter == true){
				$(this).html('');
			}else{		
				if(i == 0){
					$(this).html('<input type="checkbox" class="selectAll"/>');
				}else{					
					var dpdown = $(this).hasClass('dpdown');
					if(dpdown == true){
						if(i == 3){
							$(this).html(<?php echo json_encode($approvaltype); ?>);
						}

						$('select', this).on('change', function(){
							if ($('#'+table_id).DataTable().column(i).search() !== this.value){
								$('#'+table_id).DataTable().column(i).search(this.value, true).draw();
							}
						});					
					}else{				
						$(this).html('<input style="padding:.5rem !important;" type="text" class="form-control form-control-primary"/>');
						$('input', this).on('keyup change clear', function(){
							if ($('#'+table_id).DataTable().column(i).search() !== this.value){
								$('#'+table_id).DataTable().column(i).search(this.value, false).draw();
							}
						});
					}
				}
			}
		});	
		
		$(".selectAll").on( "click", function(e) {
			if ($(this).is( ":checked")) {
				$('#'+table_id).DataTable().rows().select();        
			} else {
				$('#'+table_id).DataTable().rows().deselect(); 
			}
		});

		$("select").select2();
	});	
	
	function showformprocess(approval_id, datanav){
		$('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-warning-alt"></i> Data Pengajuan');
		var dataapproval = [];
		if (typeof(approval_id) != "undefined"){
			dataapproval.push(approval_id);
		}else{
			var id = $('#'+table_id).DataTable().rows('.selected').data();
			$.each(id, function(index, rowId){
				dataapproval.push(rowId.approval_id);
			});
		}
		
		if (typeof(datanav) == "undefined"){
			datanav = '01';	
		}	
		
		$.ajax({
			url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/showformprocess']); ?>',
			type: 'POST',
			dataType: "html",
			data:{
				datanav:datanav,
				approval_id:dataapproval
			},
			beforeSend: function() {
				$("#loadformloader").html('<div class="loader-box"><div class="loader-2"></div></div>');
				$("#loadformcontent").html('');
			},
			success: function(data){
				$("#loadformcontent").html(data);
			},
			complete: function(){
				$("#loadformcontent").show();
				$("#loadformloader").html('');
			},
			error: function (xhr, ajaxOptions, thrownError) {
				var pesan = xhr.status + " " + thrownError + "\n" +  xhr.responseText;
				$("#loadformcontent").html('<div class="alert alert-danger inverse alert-dismissible fade show m-0" role="alert"><i class="icofont icofont-warning-alt"></i> '+pesan+'</div>');
			},
		});
	}
</script>