<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Logic;
use app\models\ProjectMember;
?>

<style>
	.table td{
		color :#fff;
	}
	.table-kontrak{
		white-space:nowrap;
	}
</style>

<?php if($datanav == 01){ ?>
	<div class="card-options text-right">
		<button onclick='showformproject("", "Tambah")' data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-plus-square"></i> Create Project</button>
	</div>
<?php } ?>

<?php if(!empty($model)) { ?>
	<div class="row">
		<?php 
			foreach($model as $mdx=>$mrow){ 
				$totalmember = ProjectMember::find()->andWhere(['company_id'=>$mrow->company_id, 'project_id'=>$mrow->project_id, 'is_active'=>true])->count();
		?>
			<div class="col-md-6">
				<div class="card bg-primary">
					<div class="card-block tab-content">
						<div style="display:flex;justify-content: space-between;">
							<p class="mb-0 bg-outline-primary"><?php echo $mrow->groupproject_name; ?></p>
							<div style="display:flex;">
								<a class="bg-outline-primary" data-placement="top" data-toggle="tooltip" title="" data-original-title="Click to see detail of <?php echo $mrow->name; ?>" href="<?php echo Url::to(['/'.Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/projectdetail', 'project_id'=>$mrow->project_id]); ?>"><i class="icofont icofont-eye"></i></a>&nbsp;
								<div data-placement="top" data-toggle="tooltip" title="" data-original-title="Delete project of <?php echo $mrow->name; ?>" >
									<a onclick='showformproject(<?php echo $mrow->project_id; ?>, "Hapus")' data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" href="javascript:void(0)" class="bg-outline-primary"><i class="icofont icofont-trash"></i></a>
								</div>
							</div>
						</div>
						<p class="mb-2 text-justify"><?php echo $mrow->name; ?></p>
						<div class="table-responsive">
							<table class="table text-white table-kontrak mb-0">
								<tbody>
									<tr>
										<td>NO KONTRAK</td>
										<td><?php echo $mrow->no_contract != '' ? $mrow->no_contract : '-'; ?></td>
									</tr>
									<tr>
										<td>NO AMANDEMEN</td>
										<td><?php echo $mrow->amendment != '' ? $mrow->amendment : '-'; ?></td>
									</tr>
									<tr>
										<td>NO BAST 1 & 2</td>
										<td><?php echo $mrow->bast_1 != '' ? $mrow->bast_1.' & '.$mrow->bast_2 : '-'; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-md-6">
								<p class="mb-0"><i class="icofont icofont-company"></i> <?php echo $mrow->client_company != '' ? $mrow->client_company : ''; ?></p>
								<p class="mb-0"><i class="icofont icofont-id-card"></i> <?php echo $mrow->client_unit != '' ? $mrow->client_unit : '-'; ?></p>
								<p class="mb-0">
									<i class="icofont icofont-calendar"></i> 
									<?php echo Logic::getIndoDate($mrow->start_date) != NULL ? Logic::getIndoDate($mrow->start_date).' - '.Logic::getIndoDate($mrow->end_date) : '-'; ?>
								</p>
								<p class="mb-0"><i class="icofont icofont-users"></i> <?php echo $totalmember; ?> Members of project</p>
							</div>
							<div class="col-md-6">
								<p class="mb-0">PM : -</p>
								<p class="mb-0">TL &nbsp;: -</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
<?php } ?>

<script>
	function showformproject(dataid, dataaction) {
        $('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> Form ' + dataaction + ' Project');

        $.ajax({
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showformproject']); ?>',
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
	
	$(document).ready(function() {
		$("a").tooltip();
		$("div").tooltip();
	});
</script>