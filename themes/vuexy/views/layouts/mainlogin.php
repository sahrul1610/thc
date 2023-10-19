
<?php
	use Yii;
	use yii\helpers\Url;
	use app\assets\LoginAsset;

	LoginAsset::register($this);
	$this->beginPage();
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>Login Page</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->theme->baseUrl; ?>/app-assets/images/favicon.ico">
	 
	<?php $this->head() ?>
	<?php $this->registerCsrfMetaTags() ?>
	
	<style>
		html .blank-page .content.app-content {
			padding: 0 !important;
		}
	</style>
</head>


<body class="horizontal-layout horizontal-menu blank-page navbar-floating footer-static  " data-open="hover" data-menu="horizontal-menu" data-col="blank-page">
    <?php $this->beginBody() ?>
	
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-cover">
                    <div class="auth-inner row m-0">
                        <a class="brand-logo" href="<?php echo Url::home(); ?>">
							<img class="img-fluid" style="width:200px;" src="<?php echo $this->theme->baseUrl; ?>/app-assets/images/logo-cbn.png"/>
                        </a>
                        <!-- Left Text-->
                        <div class="d-none d-lg-flex col-lg-8 align-items-center" style="background-image:url('<?php echo $this->theme->baseUrl; ?>/app-assets/images/bglogin.png');background-size: cover;background-position: center center;"></div>
                        <!-- /Left Text-->
                        <!-- Login-->
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
							<div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
								<?php echo $content; ?>
							</div>
                        </div>
                        <!-- /Login-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
	<script>
		$.ajaxSetup({
			data: <?= \yii\helpers\Json::encode([
					\yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
			]) ?>
		});
	</script>
	
	<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>