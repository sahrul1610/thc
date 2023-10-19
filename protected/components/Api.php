<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use Yii;
use yii\helpers\Html;

class Api
{
	function curlpost($url, $payload){
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Gravitee-Api-Key: '.Yii::$app->params['api_token'], 'Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$postToken = curl_exec($ch); 
		curl_close($ch);
		
		return json_decode($postToken, true);						
	}

	function listpayslip($nik, $periode_awal, $periode_akhir){
		$data = [
			'nik'=>$nik,
			'periode_awal'=>$periode_awal,
			'periode_akhir'=>$periode_akhir
		];
		
		$payload = http_build_query($data, '&');
		
		$url = Yii::$app->params['api_host'].'/payslip';
		$response = Api::curlpost($url, $payload);
		
		return $response;
	}	
}
