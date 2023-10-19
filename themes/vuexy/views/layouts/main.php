
<?php
	use Yii;
	use yii\helpers\Url;
	use app\components\Logic;
	use mdm\admin\components\MenuHelper;
	use app\assets\BackendAsset;

	BackendAsset::register($this);
	$this->beginPage();
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->theme->baseUrl; ?>/app-assets/images/favicon.ico">
   
	<?php $this->head() ?>
</head>


<body class="pace-done horizontal-layout horizontal-menu footer-static menu-expanded navbar-sticky" data-open="hover" data-menu="horizontal-menu" data-col="blank-page">
    <?php $this->beginBody() ?>
	
    <div class="modal fade" id="loadmodal" tabindex="-1" aria-labelledby="Load Modal" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-transparent">
					<button type="button" id="btn-close-modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div id="loadmodalloader"></div>
					<div id="loadmodalcontent"></div>
				</div>
			</div>
		</div>
	</div>
	
    <nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow">
		<div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav ms-2">
                <li class="nav-item">
					<a class="navbar-brand" href="<?php echo Url::home(); ?>">
                        <img class="img-fluid" style="width:200px;" src="<?php echo $this->theme->baseUrl; ?>/app-assets/images/logo-cbn.png"/>
                    </a>
				</li>
            </ul>
        </div>
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder"> <?= Yii::$app->user->identity->email ?> </span><span class="user-status"> <?= Yii::$app->user->identity->username ?> </span></div>
						<span class="foto-karyawan" style="background-image:url('<?php echo $this->theme->baseUrl; ?>/app-assets/images/avatars/9-small.png')"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
						<a class="dropdown-item" href="<?php echo Url::to(['/site/logout']);?>"><i class="icofont icofont-sign-out"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="horizontal-menu-wrapper">
        <div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-light navbar-shadow menu-border fixed-top" role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav">
            <div class="shadow-bottom"></div>
            <!-- Horizontal menu content-->
			<div class="navbar-container main-menu-content" data-menu="menu-container">
				<ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
					<?php  
						$module = Yii::$app->controller->module->id;
						$controller = Yii::$app->controller->id;
						$action = Yii::$app->controller->action->id;
						$menu = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, null, true); 
						
						foreach ($menu as $mdx => $mrow) {
							if (!empty($mrow['items'])) {
								$url = $mrow['url'][0];
								$data_menu = 'dropdown';
								$data_nav = 'nav-link';
								$data_bs_toggle = 'dropdown';
								$data_toggle = 'toggle';
							} else {
								$url = Url::to([$mrow['url'][0]]);
								$data_menu = '';
								$data_nav = '';
								$data_bs_toggle = '';
								$data_toggle = 'item';
							}
							$label = $mrow['label'];
							$icon = '<i class="'.$mrow['icon'].'"></i>';
							
							$exp = array_reverse(explode('/', $url));
							if ($module == $exp[2]) {
								if($controller == $exp[1] && $action == $exp[0]){
									$active = 'active';
									$title =  '<title>'.$mrow['label'].'</title>';
								}else{
									if($controller == 'default'){
										$active = 'active';
										$dcontroller = $mrow['label'];
									}else{
										$active = 'active';
										$dcontroller = $controller;
									}
									$title =  '<title>'.ucfirst($dcontroller).' > '.ucfirst($action).'</title>';
								}
							} else {
								if($controller == $exp[1] && $action == $exp[0]){
									$active = 'active';
									$title =  '<title>'.$mrow['label'].'</title>';
								}else{
									$active = '';
									$title = '';
								}
							}
							
							echo $title;
					?>
			
						<li class="<?php echo $active; ?>" data-menu="<?php echo $data_menu; ?>">
							<a class="dropdown-<?php echo $data_toggle; ?> <?php echo $data_nav ?> d-flex align-items-center" href="<?php echo $url; ?>" data-bs-toggle="<?php echo $data_bs_toggle ?>"><?php echo $icon.' '.$label; ?></a>
							<?php if (!empty($mrow['items'])) { ?>
								<ul class="dropdown-menu" data-bs-popper="none">
									<?php 
										foreach($mrow['items'] as $idx=>$irow){ 
											if (!empty($irow['items'])) {
												$url = $mrow['url'][0];
												$data_menu = 'dropdown';
												$data_nav = 'nav-link';
												$data_bs_toggle = 'dropdown';
												$data_toggle = 'toggle';
												$data_item = 'item';
												$data_class = 'dropdown dropdown-submenu';
											} else {
												$url = Url::to([$irow['url'][0]]);
												$data_menu = '';
												$data_nav = '';
												$data_bs_toggle = '';
												$data_toggle = 'item';
												$data_item = '';
												$data_class = '';
											}
											
											$label = $irow['label'];
											$icon = '<i class="'.$irow['icon'].'"></i>';
											
											$exp = array_reverse(explode('/', $url));
											if ($controller == $exp[1]) {
												$active = 'active';
												if($action == $exp[0]){
													$title =  '<title>'.$mrow['label'].' > '.$irow['label'].'</title>';
												}else{
													$title =  '<title>'.$mrow['label'].' > '.$irow['label'].' > '.ucfirst($action).'</title>';													
												}
											} else {
												$active = '';
												$title = '';
											}
											echo $title;
									?>
										<li data-menu="" class="<?php echo $active; ?> <?php echo $data_class; ?>">
											<a class="dropdown-<?php echo $data_item; ?> dropdown-<?php echo $data_toggle; ?> d-flex align-items-center" href="<?php echo $url; ?>" data-bs-toggle="<?php echo $data_bs_toggle ?>"><?php echo $icon.' '.$label; ?></a>
											<ul class="dropdown-menu ps" style="max-height: 124px; overflow: hidden auto;" data-bs-popper="none">
												<?php 
													foreach($irow['items'] as $idx=>$jrow){ 
														$url = Url::to([$jrow['url'][0]]);
														$label = $jrow['label'];
														$icon = '<i class="'.$jrow['icon'].'"></i>';
														
														$exp = array_reverse(explode('/', $url));
														if ($controller == $exp[1]) {
															$active = 'active';
															if($action == $exp[0]){
																$title =  '<title>'.$mrow['label'].' > '.$jrow['label'].'</title>';
															}else{
																$title =  '<title>'.$mrow['label'].' > '.$jrow['label'].' > '.ucfirst($action).'</title>';													
															}
														} else {
															$active = '';
															$title = '';
														}
														echo $title;
												?>
													<li data-menu="" class="<?php echo $active; ?>">
														<a class="dropdown-item d-flex align-items-center" href="<?php echo $url; ?>"><?php echo $icon.' '.$label; ?></a>
													</a>
												</li>
												<?php } ?>
											</ul>
										</li>
									<?php } ?>
								</ul>
							<?php } ?>
						</li>
					<?php } ?>
				</ul>
			</div>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
				<?php echo $content; ?>
			</div>	
		</div>	
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light text-center">
        <p class="clearfix mb-0">
			<span class="d-block d-md-inline-block mt-25">
				Copyright &copy; 2022 PT. CYBERS BLITZ NUSANTARA
			</span>
		</p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
	
	<?php $this->endBody(); ?>
	<script>
		$.ajaxSetup({
			data: <?= \yii\helpers\Json::encode([
					\yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
			]) ?>
		});
		
		$(document).on('select2:open', function(event) {
			setTimeout(function(){ document.querySelector('.select2-container--open input.select2-search__field').focus();}, 10);
		});
		
		$(document).ready(function() { 
			hljs.highlightAll();
		});
	</script>
</body>
</html>
<?php $this->endPage(); ?>