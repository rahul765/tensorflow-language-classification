<?php

namespace app\controllers;

use Yii;
use app\models\Flatcontact;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Connector;
use app\models\Flat;


/**
 * FlatcontactController implements the CRUD actions for Flatcontact model.
 */
class FlatcontactController extends Controller
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

    /**
     * Lists all Flatcontact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $connections=Connector::find()->where(['tenant_id'=>Yii::$app->user->identity->id,'accept'=>'yes'])->all();
	    $flatsArray=array();

		foreach($connections as $connection){
			$flat=Flat::find()->where(['id' => $connection['flat_id'], 'delete'=>0])->one();
		    array_push($flatsArray , $flat['id']);
	    }

        $dataProvider = new ActiveDataProvider([
            'query' => FlatContact::find()->where(['flat_id'=>$flatsArray]),
        ]);

        $dataProvider=$dataProvider->getModels();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Finds the Flatcontact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Flatcontact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Flatcontact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
