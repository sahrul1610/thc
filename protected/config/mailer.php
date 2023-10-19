<?php
	return [
		'class' => 'yii\swiftmailer\Mailer',
		'useFileTransport' => false,
		'transport' => [
			'class' => 'Swift_SmtpTransport',
			'encryption' => MAIL_ENCRYPTION,
			'host' => MAIL_HOST,
			'port' => MAIL_PORT,
			'username' => MAIL_USERNAME,
			'password' => MAIL_PASSWORD,
		]        
	];