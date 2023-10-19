<!DOCTYPE html>

<?php

use yii\helpers\Url;
use mdm\admin\components\MenuHelper;
use app\components\Logic;
use app\models\MMaster;

?>
<?php $this->beginPage() ?>
<html lang="en">
<head>
    <?php $this->head() ?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="cbn"/>
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
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900"
          rel="stylesheet"/>
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/fontawesome.css"/>
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/icofont.css"/>
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/themify.css"/>
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/flag-icon.css"/>
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/feather-icon.css"/>
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/animate.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/chartist.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/date-picker.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/datatables.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/datatable-select.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/date-picker.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/select2.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/rating.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/jquery.filer/css/jquery.filer.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/flatpickr/flatpickr.min.css">
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/assets/ckeditor/ckeditor.js"></script>
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/bootstrap.css"/>
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/style.css"/>
    <link id="color" rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/assets/css/color-1.css"
          media="screen"/>
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/assets/css/responsive.css"/>
    <!-- latest jquery-->
    <script src="<?php echo $this->theme->baseUrl; ?>/assets/js/jquery-3.5.1.min.js"></script>
    <style>
        .page-main-header .main-header-right {
            padding: 10px 35px;
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
<?php $this->beginBody() ?>
<div id="loadmodal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title=""
                        title=""><span aria-hidden="true">×</span></button>
            </div>
            <div id="loadformloader"></div>
            <div class="modal-body" id="loadformcontent"></div>
        </div>
    </div>
</div>
<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="loader-index"><span></span></div>
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
<div class="page-wrapper horizontal-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
    <div class="page-main-header">
        <div class="main-header-right row m-0">
            <div class="main-header-left">
                <div class="logo-wrapper">
                    <a href="<?php echo Url::to(['/site/index']); ?>">
						<?php 
							$logoget = Yii::$app->session->get('logo'); 
							if(empty($logoget)){
								$datalogo = MMaster::find()->andWhere(['key'=>'logo', 'is_active'=>Logic::statusActive()])->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]])->one();
								if(!empty($datalogo)){
									$logoset = Yii::$app->session->set('logo', $datalogo->name); 
									$logoget = Yii::$app->session->get('logo'); 
								}
							}
							
							if(!empty($logoget)){
						?>
							<img class="img-fluid login-logo" style="width:45px;" src="<?php echo Logic::getFile($logoget); ?>"/>
						<?php }else{ ?>
							<img class="img-fluid login-logo" src="<?php echo $this->theme->baseUrl; ?>/assets/images/logo/logo_default.png"/>
						<?php } ?>
                    </a>
                </div>
            </div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="grid" id="sidebar-toggle"></i>
            </div>
            <?php $notif = Logic::hasNotif('datalimit'); ?>
            <div class="nav-right col pull-right right-menu">
                <ul class="nav-menus">
                    <li class="onhover-dropdown">
                        <?php
                        $totalinbox = 0;
                        if (!empty($notif['data'])) {
                            foreach ($notif['data'] as $ddx => $drow) {
                                $totalinbox += $drow['total_data'];
                            }
                        }
                        ?>
                        <div class="notification-box"><i data-feather="bell"></i><span
                                    class="badge badge-pill badge-secondary"><?php echo $totalinbox; ?></span>
                        </div>
                        <?php
                        if (!empty($notif['data'])) {
                        echo '<ul class="notification-dropdown onhover-show-div" style="max-height: 500px;overflow: auto;">';
                        foreach ($notif['data'] as $ddx => $drow) {
                        ?>
                    <li>
                        <p class="f-w-600 font-roboto"><?php echo $drow['jenis']; ?></p>
                    </li>
                    <?php
                    foreach ($drow['hasil']['normalisasi'] as $hdx => $hrow) {
                        ?>
                        <a class="notifikasi" href="<?php echo $hrow['url']; ?>">
                            <li>
                                <p class="mb-0 text-justify"
                                   style="border-bottom: 1px solid #e5e5e5;"><?php echo $hrow['message']; ?>
                                    <span class="pull-right"><?php echo $hrow['timeago']; ?></span></p>
                            </li>
                        </a>
                    <?php } ?>
                    <?php
                    }
                    echo '<li class="text-center m-t-10">';
                    echo '<a href="' . Url::to(["/site/allnotifikasi"]) . '" class="btn btn-sm btn-pill btn-warning"><i class="icofont icofont-eye"></i> Lihat Semua</a>';
                    echo '</li>';
                    echo '</ul>';
                    } else {
                        ?>
                        <ul class="notification-dropdown onhover-show-div">
                            <li>
                                <p class="f-w-600 font-roboto">Tidak ada notifikasi</p>
                            </li>
                        </ul>
                    <?php } ?>
                    </li>
                    <li>
                        <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i
                                    data-feather="maximize"></i></a>
                    </li>
                    <li class="onhover-dropdown p-0">
                        <div class="media profile-media">
                            <img style="width: 37px;" class="b-r-10"
                                 src="<?php echo Logic::getFile(Yii::$app->user->identity->employee->url_photo); ?>"
                                 alt=""/>
                            <div class="media-body">
                                <span><?= Yii::$app->user->identity->employee->person_name; ?></span>
                                <p class="mb-0 font-roboto"><?= Yii::$app->user->identity->employee->employee_id; ?> <i
                                            class="middle fa fa-angle-down"></i></p>
                            </div>
                        </div>
                        <ul class="profile-dropdown onhover-show-div">
                            <li><i data-feather="user"></i><span><a
                                            href="<?php echo Url::to(['/profile/default/index']); ?>">Profile </a></span>
                            </li>
                            <li><i data-feather="log-in"> </i><span><a href="<?php echo Url::to(['/site/logout']); ?>">Log out</a></span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
        </div>
    </div>
    <!-- Page Header Ends                              -->
    <!-- Page Body Start-->
    <div class="page-body-wrapper horizontal-menu">
        <!-- Page Sidebar Start-->
        <header class="main-nav">
            <div class="logo-wrapper">
                <a href="<?php echo Url::to(['/site/index']); ?>">
                    <img class="img-fluid m-b-10 login-logo"
                         src="<?php echo $this->theme->baseUrl; ?>/assets/images/logo-cbn.png"/>
                </a>
            </div>
            <div class="logo-icon-wrapper">
                <a href="<?php echo Url::to(['/site/index']); ?>"><img class="img-fluid login-icon"
                                                                       src="<?php echo $this->theme->baseUrl; ?>/assets/images/favicon.png"
                                                                       alt=""/></a>
            </div>
            <?php
            $menu = MenuHelper::getAssignedMenu(Yii::$app->user->id);
            ?>
            <nav>
                <div class="main-navbar">
                    <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                    <div id="mainnav">
                        <ul class="nav-menu custom-scrollbar">
                            <li class="back-btn">
                                <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2"
                                                                                        aria-hidden="true"></i></div>
                            </li>
                            <?php
                            foreach ($menu as $mdx => $mrow) {
                                if (!empty($mrow['items'])) {
                                    $urlmenu = $mrow['url'][0];
                                } else {
                                    $urlmenu = Url::to([$mrow['url'][0]]);
                                }
                                ?>
                                <li class="dropdown">
                                    <a class="nav-link menu-title link-nav" href="<?php echo $urlmenu; ?>"><i
                                                class="<?php echo $mrow['icon']; ?>"></i><span> <?php echo $mrow['label']; ?></span></a>
                                    <?php if (!empty($mrow['items'])) { ?>
                                        <ul class="nav-submenu menu-content">
                                            <?php
                                            foreach ($mrow['items'] as $ndx => $nrow) {
                                                if (!empty($nrow['items'])) {
                                                    $urlsubmenu = $nrow['url'][0];
                                                    $urlsubmenuarrow = '<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>';
                                                } else {
                                                    $urlsubmenu = Url::to([$nrow['url'][0]]);
                                                    $urlsubmenuarrow = '';
                                                }
                                                ?>
                                                <li>
                                                    <a class="submenu-title"
                                                       href="<?php echo $urlsubmenu; ?>"><?php echo $nrow['label'] . ' ' . $urlsubmenuarrow; ?> </a>
                                                    <ul class="nav-sub-childmenu submenu-content">
                                                        <?php foreach ($nrow['items'] as $odx => $orow) { ?>
                                                            <li>
                                                                <a href="<?php echo Url::to([$orow['url'][0]]); ?>"><?php echo $orow['label']; ?></a>
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
            </nav>
        </header>
        <!-- Page Sidebar Ends-->
        <div class="page-body">
            <!-- Container-fluid starts-->
            <div class="container-fluid">
                <?= $content; ?>
            </div>
            <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 footer-copyright text-center">
                        <p class="mb-0">Copyright &copy <?php echo date('Y'); ?> PT. CYBERS BLITZ NUSANTARA.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

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
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/modernizr.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/countdown.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/sweet-alert/sweetalert.min.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/helpers.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/datepicker/date-picker/datepicker.en.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/rating/jquery.barrating.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/ckeditor/ckeditor.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/ckeditor/config.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/ckeditor/styles.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/jquery.filer/js/jquery.filer.min.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/clipboard/clipboard.min.js"></script>
<script src="<?php echo $this->theme->baseUrl; ?>/assets/flatpickr/flatpickr.min.js"></script>
<!--<script src="--><?php //echo $this->theme->baseUrl; ?><!--/assets/js/select2/select2-custom.js"></script>-->
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="<?php echo $this->theme->baseUrl; ?>/assets/js/tooltip-init.js"></script>
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