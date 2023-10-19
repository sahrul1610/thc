<?php
namespace app\components;
 
use yii;
use yii\web\UrlRuleInterface;
use yii\helpers\Url;

class GlobalEncryptDecryptSsl implements UrlRuleInterface{

    var $skey = "ASHGARD123456789"; 

    public function createUrl($manager, $route, $params) {
        $paramString = [];
        foreach($params as $key => $value) {
            if(is_array($value)) {
                foreach($value as $key2 => $value2){
                    if($value2 != NULL){
                        $paramString[] = $key2;
                        $paramString[] = $value2;
                    }
                }
            }else{
                if($value != NULL){
                    $paramString[] = $key;
                    $paramString[] = $value;
                }
            }
        }
		
        $urlString = implode(",", $paramString);
        $paramStringEncoded = $urlString ? $this->encode($urlString) : '';
		if(empty($params)){
			return $route.$paramStringEncoded;			
		}else{
			return $route.'?'.$paramStringEncoded;			
		}
    }

    public function parseRequest($manager, $request) {
		$pathInfo = $request->getPathInfo();
		$getPathUrl = $request->getUrl();
		
		if(empty($pathInfo)){
			$pathInfo = Yii::$app->defaultRoute;
			$getPathUrl = $request->getUrl().Yii::$app->defaultRoute;
		}	
		
		$pathUrl = str_replace('??','?',$getPathUrl);
		$pathParams1 = explode("/", $pathUrl);
		unset($pathParams1[0],$pathParams1[1]);
		
		$pathParams2 = explode("?",end($pathParams1));
		$pathParams3 = end($pathParams2);
		$expParams3 = explode('&_csrf', $pathParams3);
		$pathParams4 = [$expParams3[0]];
		
		$var1 = key($pathParams1);
		unset($pathParams1[$var1]);
		
		$pathParams = array_merge($pathParams1, $pathParams4);
		$count = count($pathParams);
		if($count == 2){
			if (isset($pathParams[1])) {
				$paramStringDecoded = $this->decode($pathParams[1]);
				$params = explode(",", $paramStringDecoded);
				for ($i = 0; $i < count($params); $i+= 2) {
					if (count($params) > ($i + 1)) {
						$_GET[$params[$i]] = $params[$i + 1];
						$_REQUEST[$params[$i]] = $params[$i + 1];
					} else {
						$_GET[$params[$i]] = $params[$i];
						$_REQUEST[$params[$i]] = $params[$i];
					}
				}
			}
		}else if($count == 3){
			if (isset($pathParams[2])) {
				$paramStringDecoded = $this->decode($pathParams[2]);
				$params = explode(",", $paramStringDecoded);
				for ($i = 0; $i < count($params); $i+= 2) {
					if (count($params) > ($i + 1)) {
						$_GET[$params[$i]] = $params[$i + 1];
						$_REQUEST[$params[$i]] = $params[$i + 1];
					} else {
						$_GET[$params[$i]] = $params[$i];
						$_REQUEST[$params[$i]] = $params[$i];
					}
				}
			}
		}else if($count == 4){
			if (isset($pathParams[3])) {
				$paramStringDecoded = $this->decode($pathParams[3]);
				$params = explode(",", $paramStringDecoded);
				for ($i = 0; $i < count($params); $i+= 2) {
					if (count($params) > ($i + 1)) {
						$_GET[$params[$i]] = $params[$i + 1];
						$_REQUEST[$params[$i]] = $params[$i + 1];
					} else {
						$_GET[$params[$i]] = $params[$i];
						$_REQUEST[$params[$i]] = $params[$i];
					}
				}
			}
		}else{
			if (isset($pathParams[4])) {
				$paramStringDecoded = $this->decode($pathParams[4]);
				$params = explode(",", $paramStringDecoded);
				for ($i = 0; $i < count($params); $i+= 2) {
					if (count($params) > ($i + 1)) {
						$_GET[$params[$i]] = $params[$i + 1];
						$_REQUEST[$params[$i]] = $params[$i + 1];
					} else {
						$_GET[$params[$i]] = $params[$i];
						$_REQUEST[$params[$i]] = $params[$i];
					}
				}
			}
		}

		$arrPar = [];
		foreach ($params as $key => $val)
        {
            if($key === 0 || $key % 2 === 0) {
                $par = explode('[', $val);
                if(count($par) > 1) {
                    $par2 = explode(']',$par[1]);
                    $keyParam = $par[0];
                    $keyArray = $par2[0];
                    $arrPar[$keyParam][$keyArray] = $params[$key+1];
                }
            }
        }

		return [$pathInfo,$arrPar];
    }

	function encode($string)
    {
        $payload = $this->acakPaylaod($string);
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    }

    /**
     * @param $string
     * @return false|string
     */
    function decode($string)
    {
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $string));
        return $this->unacakPayload($payload);
    }

   
    public function acakPaylaod($string)
    {
        $random = [
            rand(),
            rand(),
            rand(),
            rand()
        ];
        $string = str_replace('{"ct":"', '?' . $random[0] . '?', $string);
        $string = str_replace('","iv":"', '?' . $random[1] . '?', $string);
        $string = str_replace('","s":"', '?' . $random[2] . '?', $string);
        $string = str_replace('"}', '?' . $random[3] . '?', $string);
        $salt = rand(1, 1000);
        $payload = json_encode($random) . '!!' . $string;
        return $salt . '//' . strrev($payload);
    }

    public function unacakPayload($payload)
    {
        $arr = explode('//', $payload);
        $arr = explode('!!', strrev($arr[1]));
        $random = json_decode($arr[0], true);
        $result = $arr[1];
        $result = str_replace('?' . $random[0] . '?', '{"ct":"', $result);
        $result = str_replace('?' . $random[1] . '?', '","iv":"', $result);
        $result = str_replace('?' . $random[2] . '?', '","s":"', $result);
        $result = str_replace('?' . $random[3] . '?', '"}', $result);
        return $result;
    }

}