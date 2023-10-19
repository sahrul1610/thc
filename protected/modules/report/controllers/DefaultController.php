<?php

namespace app\modules\report\controllers;

use yii\web\Controller;
use app\models\EmployeeIdentity;
use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Logic;
use app\models\MMaster;
use app\models\Project;
use app\models\ProjectMember;
/**
 * Default controller for the `report` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
