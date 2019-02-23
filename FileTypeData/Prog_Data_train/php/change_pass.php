<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Landlord */

$this->title = 'Ustawienia';

?>
<div class="landlord-update">


    <div class="page-title">
	     <div class="row">
	     	<div class="col-md-12">
      	  		<h2><i class="icon-wrench color"></i><?= Html::encode($this->title) ?></h2>
	  		</div>
	     </div>
		<hr />
    </div>

						 <div class="landlord-form">
							 <ul id="myTab" class="nav nav-tabs">
                               <li><a href="/settings">Profil</a></li>
                               <li class="active"><a href="/settings/password">Hasło</a></li>
                             </ul>
                               <div class="tab-pane fade in active" id="pass">
                                 <div class="awidget">
		                           <div class="awidget-body">
		                              <div class="row">
										 <div class="col-md-12 col-sm-12">

										    <?php $form = ActiveForm::begin(); ?>

										    <?= $form->field($model, 'oldpassword')->passwordInput(['value'=>'']) ?>

										    <?= $form->field($model, 'password')->passwordInput(['value'=>'']) ?>

										    <?= $form->field($model, 'repassword')->passwordInput(['value'=>'']) ?>

										    <div class="form-group">
										        <?= Html::submitButton('Zapisz' , ['class' =>'btn btn-success']) ?>
										    </div>

										    <?php ActiveForm::end(); ?>
										    </div>
		                              </div>
		                           </div>
		                        </div>
                               </div>
                             </div>
</div>


