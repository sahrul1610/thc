<!DOCTYPE html>
<?php
	use yii\helpers\Html;
	use app\components\Logic;
	use app\models\MMaster;
?>

<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="author" content="cbn" />
		<?php echo Html::csrfMetaTags(); ?>
        <?php 
			$faviconget = Yii::$app->session->get('favicon'); 
			if(empty($faviconget)){
				$dataconfig = MMaster::find()->andWhere(['key'=>'config_app', 'name'=>$_SERVER['HTTP_HOST'], 'is_active'=>Logic::statusActive()])->one();
				$datafavicon = MMaster::find()->andWhere(['key'=>'favicon', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>$dataconfig->parent_id], ['parent_id'=>$dataconfig->parent_id]])->one();
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
        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/bootstrap.css" />
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/style.css" />
        <link id="color" rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/assets/css/color-1.css" media="screen" />
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/responsive.css" />
		
		<style>
			.img:after{
				background: #072f75 !important;
			}
			.btn-primary {
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
		</style>
    </head>
    <body style="position: relative;height: 100%;background: url('<?php echo $this->theme->baseUrl; ?>/assets/images/bglogin.png') no-repeat center / cover">
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
        <!-- Loader ends-->
        <!-- page-wrapper Start-->
        <div class="page-wrapper">
            <div class="container-fluid p-0">
                <!-- login page with video background start-->
                <div class="auth-bg-video">
					<?= $content ?>
                </div>
                <!-- login page with video background end-->
            </div>
        </div>
        <!-- latest jquery-->
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/jquery-3.5.1.min.js"></script>
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
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/login.js"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/script.js"></script>
        <!-- login js-->
        <!-- Plugin used-->
		
		<script>
		$.ajaxSetup({
			data: <?= \yii\helpers\Json::encode([
				\yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
			]) ?>
		});
	</script>
	</body>
</html>
