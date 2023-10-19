<?php

namespace app\modules\masterdata\controllers;

use Yii;
use app\models\MMaster;
use app\models\Employee;
use app\models\Organization;
use app\models\VListDaerah;
use app\components\Logic;
use yii\helpers\VarDumper;

class SearchController extends \yii\web\Controller
{
    public function actionIndex()
    {
        try {
			if (Yii::$app->request->isAjax) {
			   \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;	
			   
				$q = $_POST['q']['term'];
                $type = strip_tags($_POST['type']);
                $arrdata = [];                   
				
				if($type == 'employee'){
					$datawhere = Employee::find();
					$datawhere->andWhere(['company_id'=>Yii::$app->user->identity->company_id]);
					
					$dataall = $datawhere->andWhere('LOWER(employee_id) LIKE :param OR LOWER(person_name) LIKE :param', [':param'=>'%'.strtolower($q).'%'])->orderBy(['org_id'=>SORT_ASC])->limit(10)->all();
					foreach($dataall as $index=>$value){
						$arrdata[$index]['id']= $value['person_id'];
						$arrdata[$index]['text']= $value['employee_id'].' / '.$value['person_name'];
					}
				}else if($type == 'organisasi'){
					$datawhere = Organization::find();
					$datawhere->andWhere(['company_id'=>Yii::$app->user->identity->company_id, 'is_active'=>Logic::statusActive()]);
					
					$dataall = $datawhere->andWhere('LOWER(org_code) LIKE :param OR LOWER(org_name) LIKE :param OR LOWER(unit_code) LIKE :param OR LOWER(unit_name) LIKE :param', [':param'=>'%'.strtolower($q).'%'])->orderBy(['org_id'=>SORT_ASC])->limit(10)->all();
					foreach($dataall as $index=>$value){
						$arrdata[$index]['id']= $value['org_id'];
						$arrdata[$index]['text']= $value['org_code'].' / '.$value['org_name'].' / '.$value['band_name'].' / '.$value['unit_code'].' / '.$value['unit_name'].' / '.$value['psa_name'];
					}
				}else if($type == 'daerah'){
					$datawhere = VListDaerah::find();
					// $all = Select * from employee_address; => seluruh data
					// Foreach $all as $item
					// $data1= MMaster::findOne($item['master_id']);
					// MMaster::find()->andWhere(['master_id' => $data1->parent_id])->one();
					$dataall = $datawhere->andWhere('LOWER(code) LIKE :param OR LOWER(name) LIKE :param OR LOWER(description) LIKE :param', [':param'=>'%'.strtolower($q).'%'])->orderBy(['order'=>SORT_ASC])->limit(10)->all();
					foreach($dataall as $index=>$value){
						$address = MMaster::findOne($value['master_id']);
						$address1 = MMaster::find()->andWhere(['master_id' => $address->parent_id])->one();
						//$address2 = MMaster::find()->andWhere(['master_id' => $address1->parent_id])->one();
						$arrdata[$index]['id']= $value['master_id'];
						$arrdata[$index]['text']= $value['name'] . ' - '. $address1->name;
					}
				}else if($type == 'daerahlahir'){
					$datawhere = VListDaerah::find();
					$dataall = $datawhere->andWhere('LOWER(code) LIKE :param OR LOWER(name) LIKE :param OR LOWER(description) LIKE :param', [':param'=>'%'.strtolower($q).'%'])->orderBy(['order'=>SORT_ASC])->limit(10)->all();
					foreach($dataall as $index=>$value){
						$address = MMaster::findOne($value['master_id']);
						$address1 = MMaster::find()->andWhere(['master_id' => $address->parent_id])->one();
						$arrdata[$index]['id']= $value['master_id'];
						$arrdata[$index]['text']= $value['name'] . ' - '. $address1->name;
					}
				}else{
					$datawhere = MMaster::find();
					$datawhere->andWhere(['key'=>$type, 'is_active'=>Logic::statusActive()]);
					$datawhere->andWhere(['or', ['master_id'=>Yii::$app->user->identity->company_id], ['parent_id'=>Yii::$app->user->identity->company_id]]);

					$dataall = $datawhere->andWhere('LOWER(code) LIKE :param OR LOWER(name) LIKE :param OR LOWER(description) LIKE :param', [':param'=>'%'.strtolower($q).'%'])->orderBy(['order'=>SORT_ASC])->limit(10)->all();
					foreach($dataall as $index=>$value){
						$arrdata[$index]['id']= $value['master_id'];
						$arrdata[$index]['text']= $value['code'].' / '.$value['name'];
					}
				}
                $arrdata = array_map("unserialize", array_unique(array_map("serialize", $arrdata)));

                return $arrdata;
			}	
		} catch (\Exception $e) {
			return Logic::_sendResponse(500, 'Failed', Logic::Exception($e->getMessage()), null, 'application/html');
		}		
    }
	
	public function actionDownloadfile($path) {
		return Logic::Downloadfile(\Yii::getAlias('@webroot').$path);
    }
}
