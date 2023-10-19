<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
?>

<div class="table-responsive">
	<?php 
		foreach($datapengajuan as $ddx=>$drow){ 
		$no = 1;
	?>
			<table class="table table-sm table-striped text-nowrap mb-4" id="table_pengajuan_<?php echo $ddx; ?>">
				<thead>
					<tr>
						<th>NO</th>
						<?php foreach($drow[0]['card_body']['header'] as $hrow){ ?>
							<th><?php echo $hrow; ?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
	<?php
					foreach($drow as $edx=>$erow){ 
	?>
							<tr>
								<td><?php echo $no; ?></td>
								<?php foreach($erow['card_body']['body'] as $brow){ ?>
									<td><?php echo $brow; ?></td>
								<?php } ?>
							</tr>
	<?php 
						$no++;
					} 
	?>
				</tbody>
			</table>
	<?php
		} 
	?>
</div>

<?php
	if($datanav == '01'){
		$form = ActiveForm::begin([
			'method' => 'post',
			'id'=>'approval-form',
			'options' => [
				'class' => 'needs-validation was-validated'
			 ]
		]); 
?>
			<div class="row m-t-15">
				<div class="col-md-12">
					<div id="showerrormodal"></div>
				</div>
			</div>
			
			<input type="hidden" id="getbuttoncheck">
			<div class="form-group">
				<textarea id="approval-justification" name="Approval[justification]" placeholder="Masukkan komentar anda disini" class="form-control" rows="5" cols="10" required></textarea>
			</div>	
			
			<div class="form-group text-right">
				<button id="RETURN" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-close-circled"></i> Kembalikan</button>
				<button id="APPROVED" class="btn btn-sm btn-pill btn-primary"><i class="icofont icofont-checked"></i> Setujui</button>
			</div>
			
			<script>
				CKEDITOR.timestamp = new Date;
				CKEDITOR.replace('approval-justification', {
					toolbar: [
						['base64image', 'Preview', 'Templates'],
						['Bold', 'Italic', 'Underline'],
						['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'BlockQuote', 'CreateDiv', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Link', 'Table'],
						['Styles', 'Format', 'Font', 'FontSize'],
						['TextColor', 'BGColor']
					]
				});
		
				var table_id = $('.table-verifikasi').find('table').attr('id');
		
				$('button').click(function() {
					$('#getbuttoncheck').val('');
					buttonpressed = $(this).attr('id')
				});
				
				$('#approval-form').on('submit', function(event) {
					event.preventDefault();
					event.stopImmediatePropagation();
					
					for (instance in CKEDITOR.instances) {
						CKEDITOR.instances[instance].updateElement();
					}
			
					$('#getbuttoncheck').val(buttonpressed);
					var data = new FormData($("#"+$(this).attr('id'))[0]);
					
					data.append('approval_id','<?php echo json_encode($approval_id); ?>');
					data.append('dataaction',buttonpressed);
					
					$.ajax({
						type: "POST",
						url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/processapproval']); ?>',
						processData: false,
						contentType: false,
						dataType: "json",
						data:data,
						beforeSend: function() {
							$("#loadformloader").html('<div class="loader-box"><div class="loader-2"></div></div>');
							$("#showerrormodal").html('');
							$("#loadformcontent").hide();
						},
						success: function(data){
							if(data.status == 'Success'){
								$('#loadmodal').modal('hide');
								
								swal(
									'Success',
									''+data.message+'',
									'success'
								);
								
								$('#'+table_id).DataTable().ajax.reload();
							}else{
								$("#showerrormodal").html('<div class="alert alert-danger dark" role="alert"><i class="icofont icofont-warning-alt"></i> Please fix the following errors '+data.message+'</div>');
							}
						},
						complete: function(){
							$("#loadformloader").html('');
							$("#loadformcontent").show();					
						},
						error: function (xhr, ajaxOptions, thrownError) {
							var pesan = xhr.status + " " + thrownError + "\n" +  xhr.responseText;
							$("#showerrormodal").html('<div class="alert alert-danger dark" role="alert"><i class="icofont icofont-warning-alt"></i> '+pesan+'</div>');
						},
					});
				});
			</script>
	<?php ActiveForm::end(); ?>
<?php } ?>