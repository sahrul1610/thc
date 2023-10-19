<title>MANAGE ROUTES</title>

<?php

use mdm\admin\AnimateAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $routes [] */

$this->title = Yii::t('rbac-admin', 'Routes');
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'routes' => $routes,
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="icofont icofont-plus-square"></i>';
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
                        <?php echo Yii::$app->controller->id; ?>
                    </a>
                </li>
                <li class="breadcrumb-item active">Index</li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-absolute">
    <div class="card-header bg-primary">
        <h5 class="text-white"><i class="icofont icofont-database"></i> Manage <?php echo Yii::$app->controller->id; ?></h5>
    </div>
	<div class="tab-content card-block">
		<div class="row">
			<div class="col-sm-10">
				<div class="form-group">
					<input id="inp-route" type="text" class="form-control" placeholder="<?=Yii::t('rbac-admin', 'New route(s)');?>">
				</div>
			</div>
			<div class="col-sm-2 text-right">
				<span class="input-group-btn">
					<?=Html::a(Yii::t('rbac-admin', ''.$animateIcon.' Create'), ['create'], [
						'class' => 'btn btn-sm btn-pill btn-outline-primary',
						'id' => 'btn-new',
					]);?>
				</span>
			</div>
		</div>
		<p>&nbsp;</p>
		<div class="row">
			<div class="col-sm-5">
				<div class="form-group">
					<div class="input-group">
						<input class="form-control search" data-target="available" placeholder="<?=Yii::t('rbac-admin', 'Search for routes available');?>">
						<span class="input-group-btn">
							<?=Html::a('<i class="icofont icofont-refresh"></i>', ['refresh'], [
								'class' => 'btn btn-outline-warning',
								'id' => 'btn-refresh',
							]);?>
						</span>
					</div>
				</div>
				<select multiple size="20" class="form-control list" data-target="available"></select>
			</div>
			<div class="col-sm-2 text-center">
				<br><br>
				<?=Html::a('&gt;&gt;', ['assign'], [
					'class' => 'btn btn-sm btn-outline-success btn-assign',
					'data-target' => 'available',
					'title' => Yii::t('rbac-admin', 'Assign'),
				]);?><br><br>
						<?=Html::a('&lt;&lt;', ['remove'], [
					'class' => 'btn btn-sm btn-outline-danger btn-assign',
					'data-target' => 'assigned',
					'title' => Yii::t('rbac-admin', 'Remove'),
				]);?>
					</div>
			<div class="col-sm-5">
				<div class="form-group">
					<input class="form-control search" data-target="assigned" placeholder="<?=Yii::t('rbac-admin', 'Search for routes assigned');?>">
					<select multiple size="20" class="form-control list" data-target="assigned"></select>
				</div>
			</div>
		</div>
	</div>
</div>
