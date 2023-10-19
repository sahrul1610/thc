<!DOCTYPE html>

<?php 
	use yii\helpers\Url;
?>
<?php $this->beginPage() ?>
<html lang="en">
    <head>
		<?php $this->head() ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="author" content="cbn" />
        <link rel="icon" href="<?php echo $this->theme->baseUrl; ?>/assets/images/favicon.png" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo $this->theme->baseUrl; ?>/assets/images/favicon.png" type="image/x-icon" />
        <!-- Google font-->
        <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet" />
        <!-- Font Awesome-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/fontawesome.css" />
        <!-- ico-font-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/icofont.css" />
        <!-- Themify icon-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/themify.css" />
        <!-- Flag icon-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/flag-icon.css" />
        <!-- Feather icon-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/feather-icon.css" />
        <!-- Plugins css start-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/chartist.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/date-picker.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/datatables.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/datatable-select.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/datatable-extension.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/sweetalert2.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/date-picker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/select2.css">
        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/bootstrap.css" />
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/style.css" />
        <link id="color" rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/assets/css/color-1.css" media="screen" />
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/responsive.css" />
		<!-- latest jquery-->
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/jquery-3.5.1.min.js"></script>
		<style>
			.page-wrapper .page-body-wrapper .page-body{
				margin-top:20px;
			}
			div#pageWrapper {
				background: #2c323f;
			}
		</style>
    </head>
    <body>
		<?php $this->beginBody() ?>
		
			<?= $content; ?>
        
        <!-- Bootstrap js-->
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/bootstrap/popper.min.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/bootstrap/bootstrap.js"></script>
        <!-- feather icon js-->
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/icons/feather-icon/feather.min.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/icons/feather-icon/feather-icon.js"></script>
        <!-- Sidebar jquery-->
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/sidebar-menu.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/config.js"></script>
        <!-- Plugins JS start-->
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datepicker/date-picker/datepicker.en.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datepicker/date-picker/datepicker.custom.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/tooltip-init.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/timeline/timeline-v-1/main.js"></script>
		<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datatable/datatable-extension/dataTables.select.min.js"></script>
		<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/modernizr.js"></script>
		<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/countdown.js"></script>
		<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/sweet-alert/sweetalert.min.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/helpers.js"></script>
		<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
		<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datepicker/date-picker/datepicker.en.js"></script>
		<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/select2/select2.full.min.js"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/script.js"></script>
		
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/theme-customizer/customizer.js"></script>
        <!-- login js-->
        <!-- Plugin used-->
		
		<script>
			$.ajaxSetup({
				data: <?= \yii\helpers\Json::encode([
					\yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
				]) ?>
			});
		</script>
		
		<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>