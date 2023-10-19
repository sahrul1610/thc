<!DOCTYPE html>
<html class="loading" lang="id" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>Errno</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->theme->baseUrl; ?>/app-assets/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/app-assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->theme->baseUrl; ?>/app-assets/css/style.min.css">

</head>
<body class="horizontal-layout horizontal-menu blank-page navbar-floating footer-static  " data-open="hover" data-menu="horizontal-menu" data-col="blank-page">
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="misc-wrapper">
                    <div class="misc-inner p-2 p-sm-3">
                        <div class="w-100 text-center">
							<?php echo $content; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
    <script src="<?php echo $this->theme->baseUrl; ?>/app-assets/vendors/js/vendors.min.js"></script>
</body>
</html>