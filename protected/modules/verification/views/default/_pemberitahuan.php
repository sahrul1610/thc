<?php echo $model->description; ?>

<script>
	$(document).ready(function() {
		$('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-warning-alt"></i> <?php echo $model->name; ?>');
	});
</script>