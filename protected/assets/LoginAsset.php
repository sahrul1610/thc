<?php
namespace app\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $baseUrl = '@web/themes/vuexy/app-assets';
    public $css = [
        'vendors/css/vendors.min.css',
        'vendors/css/icofont/icofont.css',
        'css/bootstrap.css',
        'css/bootstrap-extended.css',
        'css/colors.css',
        'css/components.css',
        'css/core/menu\menu-types/horizontal-menu',
        'css/plugins/forms/form-validation.css',
        'css/pages/authentication.css',
        '../assets/css/style.css'
    ];
    public $js = [
		["vendors/js/jquery/jquery.min.js", "position" => \yii\web\View::POS_HEAD],
		'vendors/js/vendors.min.js',
		'vendors/js/forms/validation/jquery.validate.min.js',
		'js/core/app-menu.js',
		'js/core/app.js',
		'js/scripts/pages/auth-login.js'
    ];
    public $depends = [];
}