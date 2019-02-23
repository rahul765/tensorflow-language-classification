<?php

namespace app\controllers;

use Yii;
use app\models\Flat;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Connector;
use app\models\Landlord;
use yii\filters\AccessControl;
/**
 * FlatController implements the CRUD actions for Flat model.
 */
class FlatController extends Controller
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
     * Lists all Flat models.
     * @return mixed
     */
    public function actionIndex()
    {

	    $dataProvider= Connector::find()->where(['tenant_id' => Yii::$app->user->identity->id ])->all();


		$flatsArray=array();

	    foreach($dataProvider as $flat){
		    array_push($flatsArray , $flat['flat_id']);
	    }


		$flats=array();
        for($x=0;$x<count($flatsArray);$x++){
	        $flat=Flat::find()->where(['id' => $flatsArray[$x]])->one();
	        $landlord=Landlord::find()->where(['id'=> $flat['landlord_id']])->one();
	        $flat['landlord']=array('id'=>$landlord['id'],'name'=>$landlord['name'],'last_name'=>$landlord['last_name']);
	        $flatConnection=Connector::find()->where(['flat_id'=>$flat['id']])->one();
	        $flat['accept']=$flatConnection['accept'];
	        $flat['connection_id']=$flatConnection['id'];
	        array_push($flats , $flat);
        }

        return $this->render('index', [
            'dataProvider' => $flats,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Flat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
