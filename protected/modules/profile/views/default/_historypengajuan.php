<?php
	use app\components\Logic;
?>	

<div class="table-responsive">
	<table class="table table-striped text-nowrap" id="pengajuan">
		<thead>
			<tr>
				<th>NO</th>
				<th>PENGAJU</th>
				<th>PEMROSES</th>
				<th>DATA YANG DIAJUKAN</th>
				<th>KETERANGAN</th>
				<th>JUSTIFIKASI PEMROSES</th>
				<th>STATUS PENGAJUAN</th>
				<th>LAST UPDATED</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$no = 1;
				if(!empty($model)){ 
					foreach($model as $mdx=>$mrow){
						// var_dump($mrow->datapengajuan);exit;
			?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $mrow->personpengaju->employee_id.' / '.$mrow->personpengaju->person_name; ?></td>
							<td><?php echo $mrow->person_id_approval ? $mrow->personpemroses->employee_id.' / '.$mrow->personpemroses->person_name : '-'; ?></td>
							<td><?php echo $mrow->datapengajuan['hasil']; ?></td>
							<td><?php echo $mrow->comment; ?></td>
							<td><?php echo $mrow->justification ? $mrow->justification : '-'; ?></td>
							<td><?php echo $mrow->datapengajuan['status']; ?></td>
							<td><?php echo Logic::getIndoDate($mrow->created_time).' '.date('H:i:s', strtotime($mrow->created_time)); ?></td>
						</tr>
			<?php 
						$no++;
					}
				} 
			?>
		</tbody>	
	</table>
</div>

<script>
	 $(document).ready(function() {
		$('#pengajuan').DataTable();
	 });
</script>