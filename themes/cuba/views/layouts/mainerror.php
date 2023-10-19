<!DOCTYPE html>
<?php
	use app\components\Logic;
?>	

<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="author" content="cbn" />
        <?php 
			$faviconget = Yii::$app->session->get('favicon'); 
			if(empty($faviconget)){
				$datafavicon = MMaster::find()->andWhere(['key'=>'favicon', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->one();
				if(!empty($datafavicon)){
					$faviconset = Yii::$app->session->set('favicon', $datafavicon->name); 
					$faviconget = Yii::$app->session->get('favicon'); 
				}
			}
			
			if(!empty($faviconget)){
		?>
			<link rel="icon" href="<?php echo Logic::getFile($faviconget);?>" type="image/x-icon"/>
			<link rel="shortcut icon" href="<?php echo Logic::getFile($faviconget);?>"type="image/x-icon"/>
		<?php } ?>
        
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/bootstrap.css" />
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/style.css" />
        <link id="color" rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/assets/css/color-1.css" media="screen" />
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/responsive.css" />
		
		<style>
			.page-main-header .main-header-right {
				padding: 10px 35px;
			}
			.font-primary {
				color: #072f75 !important;
			}
			.btn-primary {
				color: #fff !important;
				background-color: #072f75 !important;
				border-color: #072f75 !important;
			}
			.btn-outline-primary {
				border-color: #072f75 !important;
				color: #072f75 !important;
			}
			.btn-outline-primary:hover, .btn-outline-primary:active, .btn-outline-primary.active {
				background-color: #072f75 !important;
				border-color: #072f75 !important;
				color:#fff !important;
			}
			.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active {
				background-color: #072f75 !important;
				border-color: #072f75 !important;
			}
			.bg-primary {
				background-color: #072f75 !important;
			}
			a.bg-primary:hover, a.bg-primary:focus, button.bg-primary:hover, button.bg-primary:focus{
				background-color: #072f75 !important;
			}
			.border-tab.nav-tabs .nav-item .nav-link.active, .border-tab.nav-tabs .nav-item .nav-link.show, .border-tab.nav-tabs .nav-item .nav-link:focus{
				border-bottom: 2px solid #072f75 !important;
			}
			.border-tab.nav-tabs .nav-item .nav-link.active, .border-tab.nav-tabs .nav-item .nav-link.show, .border-tab.nav-tabs .nav-item .nav-link:focus, .border-tab.nav-tabs .nav-item .nav-link:hover{
				color: #072f75 !important;
			}
			.page-wrapper .page-body-wrapper .page-header .breadcrumb .breadcrumb-item a{
				color: #072f75 !important;
			}
			.dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button:active{
				background-color: #072f75 !important;
			}
			.select2-container--default .select2-selection--multiple .select2-selection__choice{
				background-color: #072f75 !important;
				border-color: #072f75 !important;
			}
		</style>
    </head>
    <body>
        <!-- Loader starts-->
        <div class="loader-wrapper">
            <div class="loader-index">
                <span></span>
            </div>
            <svg>
                <defs></defs>
                <filter id="goo">
                    <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                    <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"></fecolormatrix>
                </filter>
            </svg>
        </div>
		<div class="page-wrapper compact-wrapper" id="pageWrapper">
			<!-- error-400 start-->
			<div class="error-wrapper">
				<?= $content; ?>
			</div>
			<!-- error-400 end-->
		</div>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/jquery-3.5.1.min.js"></script>
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/script.js"></script>
		
		<script>
		$.ajaxSetup({
			data: <?= \yii\helpers\Json::encode([
				\yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
			]) ?>
		});
	</script>
	</body>
</html>
