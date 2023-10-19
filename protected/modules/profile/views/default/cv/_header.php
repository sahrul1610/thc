<?php
	use Yii;
	use app\components\Logic;
?>

<div class="user-profile">
	<div class="row">
		<div class="col tabs-responsive-side">
			<div class="user-profile">
				<div class="card card-absolute">
					<div class="card-block">
						<div class="row">
							<?php if($model->employeegeneral->app_status != "ON PROGRESS"){ ?>
								<div class="col-md-12 m-b-10 text-right">
									<button onclick='showformgeneral("<?php echo $model->person_id; ?>", "Edit")' data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-warning"><i class="icofont icofont-edit"></i>
									</button>
								</div>
							<?php } ?>
							
							<div class="col-md-2">
								<div class="text-center mb-4">
									<img class="img-rounded" style="border-radius:10%" width="120" src="<?php echo Logic::getFile($model->url_photo); ?>" alt="" data-original-title="" title="">
								</div>
							</div>
							<div class="col-md-4">
								<div class="title">
									<h4><?php echo (empty($model->person_name)) ? '-' : $model->person_name; ?></h4>
									<div class="description"><?php echo (empty($model->employee_id)) ? '-' : $model->employee_id; ?></div>
									<div class="description"><?php echo (empty($model->org_name)) ? '-' : $model->org_name; ?></div>
									<div class="description"><?php echo (empty($model->org_unit_code)) ? '-' : $model->org_unit_name; ?></div>
									<div class="description">BP <?= ($model->band_id != '') ? $model->band_name : '-'; ?> / <?= ($model->psa_id != '') ? $model->psa_name : '-' ?></div>
								</div>

								<!-- tabel report  -->
								<table class="m-t-10">
									<tbody>
										<tr>
											<td>Position Date</td>
											<td>:</td>
											<td><?php echo Logic::getIndoDate($model->date_of_position); ?></td>
										</tr>
										<tr>
											<td>Band Position Date</td>
											<td>:</td>
											<td><?php echo Logic::getIndoDate($model->date_of_band_position); ?></td>
										</tr>
										<tr>
											<td>Retirement Date</td>
											<td>:</td>
											<td><?= Logic::getIndoDate(Logic::getPensiun($model->date_of_birth)); ?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<br><br>
							<div class="col-md-6">
								<div class="employeeinfo">
									<div class="mb-0 row">
										<label for="nama" class="col-sm-4 col-form-label"><?php echo $model->getAttributeLabel('person_name');?></label>
										<div class="col-sm-8">
											<div class="form-control-plaintext">
												<?= $model->person_name != '' ? $model->person_name : '-'; ?>
											</div>
										</div>
									</div>
									<div class="mb-0 row">
										<label for="nama" class="col-sm-4 col-form-label"><?php echo $model->getAttributeLabel('employee_id');?></label>
										<div class="col-sm-8">
											<div class="form-control-plaintext">
												<?= $model->employee_id != '' ? $model->employee_id : '-'; ?>
											</div>
										</div>
									</div>
									<div class="mb-0 row">
										<label for="nama" class="col-sm-4 col-form-label"><?php echo $model->getAttributeLabel('sex');?></label>
										<div class="col-sm-8">
											<div class="form-control-plaintext">
												<?= $model->sex == 'L' ? 'Pria' : 'Wanita'; ?>
											</div>
										</div>
									</div>
									<div class="mb-0 row">
										<label for="nama" class="col-sm-4 col-form-label"><?php echo $model->getAttributeLabel('ethnic_name');?></label>
										<div class="col-sm-8">
											<div class="form-control-plaintext">
												<?= $model->ethnic_id != '' ? $model->ethnic_name : '-'; ?>
											</div>
										</div>
									</div>
									<div class="mb-0 row">
										<label for="nama" class="col-sm-4 col-form-label"><?php echo $model->getAttributeLabel('religion_name');?></label>
										<div class="col-sm-8">
											<div class="form-control-plaintext">
												<?= $model->religion_id != '' ? $model->religion_name : '-'; ?>
											</div>
										</div>
									</div>
									<div class="mb-0 row">
										<label for="nama" class="col-sm-4 col-form-label">TEMPAT, TANGGAL LAHIR</label>
										<div class="col-sm-8">
											<div class="form-control-plaintext">
												<?= $model->town_of_birth_id != '' ? $model->town_of_birth_name : '-'; ?>, <?= Logic::getIndoDate($model->date_of_birth) == '' ? '-' : Logic::getIndoDate($model->date_of_birth); ?>
											</div>
										</div>
									</div>
									<div class="mb-0 row">
										<label for="nama" class="col-sm-4 col-form-label"><?php echo $model->getAttributeLabel('marital_name');?></label>
										<div class="col-sm-8">
											<div class="form-control-plaintext">
												<?= $model->marital_id != '' ? $model->marital_name : '-'; ?>
											</div>
										</div>
									</div>

									<?php 
									if($model->employeegeneral->app_status == 'ON PROGRESS'){ ?>
										<div class="mb-0 row">
											<label for="nama" class="col-sm-4 col-form-label">STATUS APPROVAL</label>
											<div class="col-sm-8">
												<div class="form-control-plaintext">
													<button onclick="showpengajuan('<?= $model->employeegeneral->persongen_id ?>', 'general')" data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-xs btn-pill btn-primary"><?= strip_tags($model->employeegeneral->app_status); ?></button>
												</div>
											</div>
										</div>
									<?php }
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>