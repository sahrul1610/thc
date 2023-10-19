<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\Logic;
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
                        <?php echo Yii::$app->controller->module->id; ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Index</li>
            </ol>
        </div>
    </div>
</div>

<title>My Profile</title>

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
									<div class="description">BP <?= ($model->band_name != '') ? $model->band_name : '-'; ?> / <?= ($model->psa_name != '') ? $model->psa_name : '-' ?></div>
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
								<div class="text-center mt-4">
								<a target="_blank" href="<?php echo Url::toRoute([Yii::$app->controller->id . '/printpdf', 'person_id' => $model->person_id]); ?>" class="btn btn-md btn-pill btn-primary"><i class="icofont icofont-file-pdf"></i> Print Preview</a>
								<!-- <a target="_blank" href="<?php echo Url::toRoute([Yii::$app->controller->id . '/printpdf', 'person_id' => $dataperson]); ?>" class="btn btn-md btn-pill btn-primary"><i class="icofont icofont-file-pdf"></i> Print Preview</a> -->
								</div>
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
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<ul class="nav nav-pills" id="profile-tab" role="tablist" style="overflow-x: auto;overflow-y: hidden;">
									<li class="nav-item">
										<a class="nav-link active" onclick="showdata('general')" id="info-home-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="info-home" aria-selected="true" data-nav="general"><i class="icofont icofont-ui-user"></i>General
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="showdata('jabatan')" id="profile-info-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="info-profile" aria-selected="false" data-nav="jabatan"><i class="icofont icofont-company"></i>Jabatan</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="showdata('pelatihan')" id="contact-info-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="info-contact" aria-selected="false" data-nav="pelatihan"><i class="icofont icofont-list"></i>Pelatihan
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="showdata('pendidikan')" id="contact-info-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="info-contact" aria-selected="false" data-nav="pendidikan"><i class="icofont icofont-contacts"></i>Pendidikan
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="showdata('keluarga')" id="contact-info-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="info-contact" aria-selected="false" data-nav="keluarga"><i class="icofont icofont-users"></i>Keluarga
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="showdata('innovation')" id="contact-info-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="info-contact" aria-selected="false" data-nav="innovation"><i class="icofont icofont-space-shuttle"></i>Innovation
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="showdata('skill')" id="contact-info-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="info-contact" aria-selected="false" data-nav="skill"><i class="icofont icofont-user-suited"></i>Skill
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" onclick="showdata('reward')" id="contact-info-tab" data-toggle="tab" href="javascript:void(0)" role="tab" aria-controls="info-contact" aria-selected="false" data-nav="reward"><i class="icofont icofont-trophy"></i>Penghargaan
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="kolom">	
	<div id="loadloader"></div>
	<div id="loadcontent"></div>
</div>

<script>
	$(document).ready(function() {
		showdata('general');
	});

	function showdata(nav) {
		$.ajax({
			url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/load']); ?>' + nav,
			type: 'POST',
			dataType: "html",
			data: {},
			beforeSend: function() {
				$("#loadloader").html('<div class="loader-box"><div class="loader-39"></div></div>');
				$("#loadcontent").html('');
			},
			success: function(data) {
				$("#loadcontent").html(data);
			},
			complete: function() {
				$("#loadloader").html('');
				// $("html, body").animate({ scrollTop: 0 }, "slow");
			},
			error: function(xhr, ajaxOptions, thrownError) {
				var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
				$("#loadcontent").html('<div class="alert alert-danger inverse alert-dismissible fade show m-0" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
			},
		});
	};

	function showformgeneral(dataid, dataaction){
		$('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> Form ' + dataaction);

		$.ajax({
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showformgn']); ?>',
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