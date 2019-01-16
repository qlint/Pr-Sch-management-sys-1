<?php

class OnlineRegisterSettingsController extends RController
{
	public function actionIndex()
	{		
		$model= new OnlineRegisterSetting2;				
		if(!empty($_POST))
		{		 
			$model->attributes = $_POST['OnlineRegisterSetting2'];					
			if($model->validate())
			{												
				$posts_1=OnlineRegisterSettings::model()->findByAttributes(array('id'=>2));
				$posts_1->config_value = $model->academic_year;
				$posts_1->save();
												
				$posts_2=OnlineRegisterSettings::model()->findByAttributes(array('id'=>4));
				$posts_2->config_value = $_POST['OnlineRegisterSetting2']['show_link'];
				$posts_2->save();
				
				Yii::app()->user->setFlash('successMessage', Yii::t('app',"Action performed successfully"));
				$this->redirect(array('index'));
			}		 
		}		
		$this->render('index',array('model'=>$model));
	}


	public function filters()
	{
	  return array(
	   'rights', // perform access control for CRUD operations
	  );
	}
}