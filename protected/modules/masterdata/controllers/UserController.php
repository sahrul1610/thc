<?php

namespace app\modules\masterdata\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\Employee;
use app\components\Logic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$karyawan = Employee::findOne($_POST['User']['person_id']);
		
        $model = new User();
		$model->username = $karyawan->employee_id;
		$model->created_by = (string) Yii::$app->user->identity->employee->person_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
		$karyawan = Employee::findOne($_POST['User']['person_id']);
		
        $model = $this->findModel($id);
		$model->username = $karyawan->employee_id;
		$model->created_by = (string) Yii::$app->user->identity->employee->person_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		try {
			$this->findModel($id)->delete();
			return $this->redirect(['index']);
		} catch (\Exception $e) {
			throw new \yii\web\HttpException(500, 'Contraint Integrity, hubungi admin IT jika ingin menghapus data secara permanen.');
		}
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	public function actionShowlistkaryawan(){
        if(Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $key = $_GET['key'];
			if(empty($key)){
				$key = 'X';
			}
			$datacount = Employee::find()
                ->andWhere('LOWER(employee_id::VARCHAR) LIKE :key OR LOWER(person_name) LIKE :key', [':key' => '%'.strtolower($key).'%'])
                ->count();

            $dataall = Employee::find()
                ->andWhere('LOWER(employee_id::VARCHAR) LIKE :key OR LOWER(person_name) LIKE :key', [':key' => '%'.strtolower($key).'%'])
                ->all();
				
            if(!empty($dataall)){
                foreach($dataall as $ddx=>$drow){
                    $result['incomplete_results'] = false;
                    $result['total_count'] = $datacount;
                    $result['items'][$ddx]['id'] = $drow->person_id;
                    $result['items'][$ddx]['text'] = $drow->employee_id.' - '.$drow->person_name;
                }
            }else{
                $result['incomplete_results'] = false;
                $result['total_count'] = 0;
                $result['items'] = [];
            }

            return $result;
        }
    }
	
	public function actionSyncusers()
    {
		if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			$connection = Yii::$app->db;
			$transaction = $connection->beginTransaction();
			try {
				$sqldata_1 = $connection->createCommand('
					INSERT INTO users(person_id, username, password, is_active, is_ldap, last_login, created_by, created_time)
					SELECT a.person_id, a.employee_id username, a.employee_id "password", TRUE is_active, FALSE is_ldap, NULL last_login, 1 created_by, CURRENT_TIMESTAMP created_time
					FROM employee a
					LEFT JOIN users b ON b.person_id = a.person_id
					WHERE b.user_id IS NULL
					RETURNING user_id;
				');
				$execute_1 = $sqldata_1->execute();
				
				$sqldata_2 = $connection->createCommand('
					INSERT INTO auth_assignment(item_name, user_id, created_at)
					SELECT \'usercommon\' item_name, b.user_id, EXTRACT(EPOCH FROM CURRENT_TIMESTAMP)::int created_at
					FROM employee a
					JOIN users b ON b.person_id = a.person_id
					LEFT JOIN auth_assignment c ON c.user_id::int = b.user_id
					WHERE c.user_id IS NULL
					RETURNING user_id, item_name;;
				');
				$execute_2 = $sqldata_2->execute();
				
				if($execute_1 && $execute_2){
					$transaction->commit();
					$data['code'] = 200;
					$data['status'] = 'Success';
					$data['message'] = 'Users has been synchronized';
				}else{
					$transaction->rollback();
					$data['code'] = 200;
					$data['status'] = 'Failed';
					$data['message'] = 'Failed to synchronize users, users is up to date';
				}
				
            } catch (\Exception $e) {
				$transaction->rollback();
				$data['code'] = 404;
				$data['status'] = 'Failed';
				$data['message'] =$e->getMessage();
				// $data['message'] = Logic::Exceptionjson('Terjadi kesalahan! Silahkan hubungi administrator');
            }
			return $data;
		}	
    }
}
