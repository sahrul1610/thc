<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\Logic;
?>

<div class="row m-b-10">
	<div class="col-md-12">
		<div class="form-group">
			<h6>ATASAN</h6>
			<?php if (!empty($atasan)) { ?>
					<div class="h5 txt-info"><?= $atasan->employee_id . ' / ' . $atasan->person_name . ' / ' . $atasan->band_name . ' / ' . $atasan->org_name . ' / ' . $atasan->org_unit_name; ?></div>
			<?php
				} else {
					echo 'Atasan tidak ditemukan';
				}
			?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('org_name');?></h6>
					<div><?= $employee->org_name != '' ? $employee->org_name : '-'; ?></div>
				</div>
			</div>	
			<div class="col-md-4">
				<div class="form-group">
					<h6>MASA KERJA (ADJUSTED)</h6>
					<div><?php echo Logic::getMasaKerja($employee->date_of_adjusted); ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6>MASA KERJA (RIIL)</h6>
					<div><?php echo Logic::getMasaKerja($employee->date_of_work); ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('date_of_position');?></h6>
					<div><?= $employee->date_of_position != '' ? Logic::getIndoDate($employee->date_of_position) : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('date_of_adjusted');?></h6>
					<div><?= $employee->date_of_adjusted != '' ? Logic::getIndoDate($employee->date_of_adjusted) : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('date_of_work');?></h6>
					<div><?= $employee->date_of_work != '' ? Logic::getIndoDate($employee->date_of_work) : '-'; ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('band_name');?></h6>
					<div><?= $employee->band_id != '' ? $employee->band_name: '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('org_unit_name');?></h6>
					<div><?= $employee->org_unit_name != '' ? $employee->org_unit_name : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('date_of_hire');?></h6>
					<div><?= $employee->date_of_hire != '' ? Logic::getIndoDate($employee->date_of_hire) : '-'; ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('date_of_band_position');?></h6>
					<div><?= $employee->date_of_band_position != '' ? Logic::getIndoDate($employee->date_of_band_position) : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('psa_name');?></h6>
					<div><?= $employee->psa_name != '' ? $employee->psa_name : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6>TGL PERKIRAAN PENSIUN (56)</h6>
					<div><?= Logic::getIndoDate(Logic::getPensiun($employee->date_of_birth)); ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('empgroup_name');?></h6>
					<div><?= $employee->empgroup_id != '' ? $employee->empgroup_name : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('jobposition_name');?></h6>
					<div><?= $employee->jobposition_id != '' ? $employee->jobposition_name : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('date_of_dedicated');?></h6>
					<div><?= $employee->date_of_dedicated != '' ? Logic::getIndoDate($employee->date_of_dedicated) : '-'; ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('empsubgroup_name');?></h6>
					<div><?= $employee->empsubgroup_id != '' ? $employee->empsubgroup_name : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('jobfunction_name');?></h6>
					<div><?= $employee->jobfunction_id != '' ? $employee->jobfunction_name : '-'; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<h6><?php echo $employee->getAttributeLabel('date_of_kdmp');?></h6>
					<div><?= $employee->date_of_kdmp != '' ? Logic::getIndoDate($employee->date_of_kdmp) : '-'; ?></div>
				</div>
			</div>
		</div>
	</div>
</div>