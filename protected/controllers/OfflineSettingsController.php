<?php

class OfflineSettingsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','users','activate'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        public function getStatus($data,$row)
        {
            
            $settins_model= UserSettings::model()->findByAttributes(array('user_id'=>1));
            if($settins_model)
            {
                $zone_id= $settins_model->timezone;
                $timezone_model= Timezone::model()->findByPk($zone_id);
                if($timezone_model)
                {
                    date_default_timezone_set($timezone_model->timezone);
                }
            }
                                    
            $end_time= $data->end_time;            
            $current_time= date('Y-m-d H:i:s');
            $model= SystemOfflineSettings::model()->findByPk($data->id);
            if($end_time<$current_time)
            {        
                $model= SystemOfflineSettings::model()->findByPk($data->id);
                $model->status=2;
                $model->save();                
            }
            
            if($model->status==0)
            {
               return Yii::t('app','Inactive');
            }
            if($model->status==1)
            {                
               return Yii::t('app','Active');
            }
            if($model->status==2)
            {
               return Yii::t('app','Complete');
            }
        }
        
        //for activate inactive schedules
        public function actionActivate($id)
        {
            $model=SystemOfflineSettings::model()->findByPk($id);
            if($model)
            {
                $model->saveAttributes(array('status'=>1));
                $this->redirect(Yii::app()->request->urlReferrer);
            }
            $this->redirect(array('index'));
        }



        public function actionUsers()
        {
            $array_list=array();
            $data=  AuthAssignment::model()->findAll('itemname=:id', array(':id'=>$_POST['user_type']));
            foreach($data as $value)
            {               
                $name= User::model()->findByPk($value->userid);
                $array_list[]= ($name->username); 
                
            }           
            $data=$array_list;
            foreach($data as $value=>$name)
            {
                echo CHtml::tag('option',
                           array('value'=>$value),CHtml::encode($name),true);
            }
        }

	public function actionCreate()
	{
		$model=new SystemOfflineSettings;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SystemOfflineSettings']))
		{
                    
			$model->attributes=$_POST['SystemOfflineSettings'];                        
                        if($_POST['SystemOfflineSettings']['start_time'][0]!="")
                        {
                            $model->start_time=$new_date = date('Y-m-d H:i:s', strtotime($_POST['SystemOfflineSettings']['start_time'][0]));
                        }
                        else { $model->start_time = ""; }
                        
                        if($_POST['SystemOfflineSettings']['end_time'][0]!="")
                        {
                        $model->end_time= $new_date = date('Y-m-d H:i:s', strtotime($_POST['SystemOfflineSettings']['end_time'][0]));
                        }
                        else
                        {
                            $model->end_time="";
                        }
                        $model->created_at= date('Y-m-d H:i:s');  
                        if($model->validate())
                        {
                            
                            if($model->end_time<$model->created_at)
                            {
                                $model->status=2;
                            }
                            if($model->save())

                                    $this->redirect(array('/offlineSettings'));
                        }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SystemOfflineSettings']))
		{
			$model->attributes=$_POST['SystemOfflineSettings'];
                        if($_POST['SystemOfflineSettings']['start_time'][0]!="")
                        {
                            $model->start_time=$new_date = date('Y-m-d H:i:s', strtotime($_POST['SystemOfflineSettings']['start_time'][0]));
                        }
                        else { $model->start_time = ""; }
                        
                        if($_POST['SystemOfflineSettings']['end_time'][0]!="")
                        {
                        $model->end_time= $new_date = date('Y-m-d H:i:s', strtotime($_POST['SystemOfflineSettings']['end_time'][0]));
                        }
                        else
                        {
                            $model->end_time="";
                        }
                        
                        $model->created_at= date('Y-m-d H:i:s'); 
                        if($model->validate())
                        {
                            if($model->end_time<$model->created_at)
                            {
                                $model->status=2;
                            }
                            if($model->save())
                                    $this->redirect(array('/offlineSettings'));
                        }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
        
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	
	public function actionIndex()
	{
		$model=new SystemOfflineSettings('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SystemOfflineSettings']))
			$model->attributes=$_GET['SystemOfflineSettings'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	
	public function loadModel($id)
	{
		$model=SystemOfflineSettings::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='system-offline-settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
