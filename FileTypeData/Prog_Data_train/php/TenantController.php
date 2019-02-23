<?php

namespace app\controllers;

use Yii;
use app\models\Tenant;
use app\models\ChangePass;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Register;
/**
 * TenantController implements the CRUD actions for Tenant model.
 */
class TenantController extends Controller
{
    public function behaviors()
    {
        return [
	        'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
	                'actions' => ['create','thanks'],
                    'allow' => true,
                    'roles' => ['?'],
					],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tenant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Register();

        if(!empty(Yii::$app->user->identity->id))
        	$this->redirect('/');

        if($model->load(Yii::$app->request->post()) && $model->validate()){
	        $model->activate=1;
	        $model->signup_date=date("Y-m-d H:i:s");
	        $model->password=hash('sha256',$model->password);
	        $model->repassword=hash('sha256',$model->repassword);

	        if ($model->save())
            	return $this->redirect('/thanks');
            else
            	return $this->redirect('/register');
        }else
        	return $this->render('create', [
	                'model' => $model,
	            ]);
    }


    public function actionThanks()
    {
        return $this->render('thanks');
    }


    public function actionUpdate()
    {
        $model = $this->findModel(Yii::$app->user->identity->id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('update_ok', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionChangePass()
	{
        $model = new ChangePass();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $landlord=$this->findModel(Yii::$app->user->identity->id);
			$landlord->password=hash('sha256',$model->password);
			$landlord->save();

            return $this->render('update_ok', [
                'model' => $model,
            ]);
        } else {
            return $this->render('change_pass', [
                'model' => $model,
            ]);
        }
    }

/*
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
*/

    /**
     * Finds the Tenant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tenant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tenant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
