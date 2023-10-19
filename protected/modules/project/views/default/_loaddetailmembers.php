<?php
	use app\components\Logic;
	use yii\helpers\Url;
 
	if(!empty($model)){
		echo '<div class="row">';
			foreach($model as $mdx=>$mrow){
?>
				<div class="col-md-3">
					<div class="card bg-primary">
						<div class="card-block tab-content">
							<div style="display:flex;justify-content:space-between;">
								<div style="display:flex;">
									<div>
										<img style="width: 37px;" class="b-r-10" src="<?php echo Logic::getFile($mrow->person->url_photo);?>" alt="" data-original-title="" title="">
									</div>
									<div class="pl-2">
										<p class="mb-0"><?php echo $mrow->person_name; ?></p>
										<p class="mb-0"><?php echo $mrow->employee_id; ?></p>
										<p class="mb-0 badge badge-default bg-outline-primary"><?php echo $mrow->is_pm ? 'PM ('.$mrow->pm_label.')' : 'Member'; ?></p>
									</div>
								</div>
								<div style="display:flex;justify-content: flex-start;flex-direction: column;align-items: flex-end;">
									<div class="mb-1" data-placement="top" data-toggle="tooltip" title="" data-original-title="Edit <?php echo $mrow->person_name; ?>" >
										<a onclick='showformmember(<?php echo $mrow->member_id; ?>, "Edit")' data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" href="javascript:void(0)" class="bg-outline-primary"><i class="icofont icofont-edit"></i></a>
									</div>
									<div data-placement="top" data-toggle="tooltip" title="" data-original-title="Delete <?php echo $mrow->person_name; ?>" >
										<a onclick='showformmember(<?php echo $mrow->member_id; ?>, "Hapus")' data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" href="javascript:void(0)" class="bg-outline-primary"><i class="icofont icofont-trash"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
<?php 
			}
		echo '</div>';
	} 
?>

<script>
	$(document).ready(function() {
		$("a").tooltip();
		$("div").tooltip();
	});
</script>