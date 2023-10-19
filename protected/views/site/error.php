<?php
	use Yii;
	use yii\helpers\Html;
	use yii\helpers\Url;
	
?>

<title> ERRNO PAGE</title>

<div class="container"><img class="img-100" src="<?php echo $this->theme->baseUrl; ?>/assets/images/other-images/sad.png" alt="">
	<div class="error-heading">
		<h6 class="headline font-primary"><?= $exception->statusCode ?></h6>
	</div>
	<div class="col-md-8 offset-md-2">
		<p class="sub-content">  <?= nl2br(Html::encode($exception->getMessage())) ?></p>
	</div>
	<div><a class="btn btn-primary btn-lg" href="javascript:history.go(-1)">Kembali ke Halaman Sebelumnya</a></div>
</div>