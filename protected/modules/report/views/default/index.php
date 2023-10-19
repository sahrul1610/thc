<!-- <div class="report-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div> -->
<?php
/* @var $this yii\web\View */
?>
<!-- <h1>identitas/index</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p> -->
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
<div class="card card-absolute">
    <div class="card-header bg-primary">
        <h5 class="text-white"><i class="icofont icofont-database"></i> <?php echo Yii::$app->controller->id; ?></h5>
    </div>
    <div class="tab-content card-block">
		<!-- <div class="card-options text-right">
			<button onclick='showformdr("", "Tambah")' data-toggle="modal" data-target="#loadmodal" data-keyboard="false" data-backdrop="static" class="btn btn-sm btn-pill btn-outline-primary"><i class="icofont icofont-plus-square"></i> Create <?php echo ucfirst(Yii::$app->controller->id); ?></button>
		</div> -->
		<div class="table-responsive mt-3 ">
			<table class="table table-striped text-nowrap" id="data_repository">
				<thead>
                    <tr>
                        <?php
							foreach($model->customAttributeLabels2() as $mdx=>$mrow){
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
		var table_id = $('.table-profile').find('table').attr('id');
		$('#'+table_id+' thead tr').clone(true).appendTo('#'+table_id+' thead');
		$('#'+table_id+' thead tr:eq(1) th').each( function (i){
			var nofilter = $(this).hasClass('nofilter');
			if(nofilter == true){
				$(this).html('');
			}else{					
				var dpdown = $(this).hasClass('dpdown');
				if(dpdown == true){
					if(i == 3){
						$(this).html(<?php echo json_encode($datalevelpendidikan); ?>);
					}
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
			"ajax": {
				type: 'POST',
				dataType: "json",
				url: '<?php echo Url::toRoute([Yii::$app->controller->id.'/loaddtpendidikan']); ?>',
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

    // function showformpendidikan(dataid, dataaction) {
    //     $('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> Form ' + dataaction + ' Pendidikan');

    //     $.ajax({
    //         url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showformpendidikan']); ?>',
    //         type: 'POST',
    //         dataType: "html",
    //         data: {
    //             dataid: dataid,
    //             dataaction: dataaction
    //         },
    //         beforeSend: function() {
    //             $("#loadformloader").html('<div class="loader-box"><div class="loader-39"></div></div>');
    //             $("#loadformcontent").html('');
    //         },
    //         success: function(data) {
    //             $("#loadformcontent").html(data);
    //         },
    //         complete: function() {
    //             $("#loadformcontent").show();
    //             $("#loadformloader").html('');
    //         },
    //         error: function(xhr, ajaxOptions, thrownError) {
    //             var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
    //             $("#loadformcontent").html('<div class="alert alert-danger inverse alert-dismissible fade show m-0" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
    //         },
    //     });
    // }

    function showannouncement() {
        $.ajax({
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showannouncement']); ?>',
            type: 'POST',
            dataType: "html",
            data: {},
            beforeSend: function() {
                $("#loadformloader").html('<div class="loader-box"><div class="loader-39"></div></div>');
                $("#loadformcontent").html('');
            },
            success: function(data) {
                $("#loadformcontent").html(data);
            },
            complete: function() {
                $("#loadformcontent").show();
                $("#loadformloader").html('');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
                $("#loadformcontent").html('<div class="alert alert-danger inverse alert-dismissible fade show m-0" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
            },
        });
    }
	
	function showpengajuan(dataid, dataaction) {
		$('#loadmodal').find('.modal-header .modal-title').html('<i class="icofont icofont-database"></i> History Pengajuan');
		
        $.ajax({
            url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showpengajuan']); ?>',
            type: 'POST',
            dataType: "html",
            data: {
				dataid:dataid,
				dataaction:dataaction
			},
            beforeSend: function() {
                $("#loadformloader").html('<div class="loader-box"><div class="loader-39"></div></div>');
                $("#loadformcontent").html('');
            },
            success: function(data) {
                $("#loadformcontent").html(data);
            },
            complete: function() {
                $("#loadformcontent").show();
                $("#loadformloader").html('');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                var pesan = xhr.status + " " + thrownError + "\n" + xhr.responseText;
                $("#loadformcontent").html('<div class="alert alert-danger inverse alert-dismissible fade show m-0" role="alert"><i class="icofont icofont-warning-alt"></i> ' + pesan + '</div>');
            },
        });
    }
</script>