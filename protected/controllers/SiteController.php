<?php

namespace app\controllers;

use app\models\News;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\components\Logic;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
	public function beforeAction($action){
		if($action->id =='login'){
			$this->enableCsrfValidation = false;
		}
		return parent::beforeAction($action);
	}
	
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAllnotifikasi()
    {
        return $this->render('allnotifikasi');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
		Yii::$app->view->theme = new \yii\base\Theme([
			'pathMap' => [
				'@app' => [
					'@app/../themes/vuexy'
				]
			],
			'baseUrl' => '@web/themes/vuexy',
		]);
			
		$session = Yii::$app->session;
		$session->destroy();
		
        $this->layout = '/mainlogin';
        // if (!Yii::$app->user->isGuest) {
            // return $this->redirect(['site/index']);
        // }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->redirect(['site/index']);				
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
	
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	// public function actionError(){
		// $this->layout = '/mainerror';
		// $exception = Yii::$app->errorHandler->exception;
		// if (Yii::$app->request->isAjax) {
			// return nl2br(Html::encode($exception->getMessage()));
		// }else{	
			// return $this->render('error', [
				// 'exception' => $exception
			// ]);
		// }
	// }
}
