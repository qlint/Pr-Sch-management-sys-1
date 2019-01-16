<?php
class ConfigController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex()
	{
		$model 	= FeeConfigurations::model()->find();
		if($model==NULL)
			$model 	= new FeeConfigurations;
		
		if(isset($_POST['FeeConfigurations'])){
			$model->attributes 	= $_POST['FeeConfigurations'];
			if($model->discount_in_fee==0){
				$model->discount_in_invoice	= 0;
			}
			
			if($model->save()){
				Yii::app()->user->setFlash('success', Yii::t('app', 'Configurations saved successfully'));
				$this->redirect(array('index'));
			}
		}

		$this->render('index', array('model'=>$model));
	}
}