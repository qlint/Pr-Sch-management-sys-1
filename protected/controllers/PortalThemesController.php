<?php

class PortalThemesController extends Controller
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete','set'),
				'users'=>array('@'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

        
        //action for set admin theme to user theme settings
        public function actionSet()
        {
            $themes= PortalThemes::model()->findByAttributes(array('user_id'=> 0));
            if($themes)
            {
                $userModel= PortalThemes::model()->findByAttributes(array('user_id'=>  Yii::app()->user->id));
                if($userModel)
                {
                    $userModel->attributes= $themes->attributes;
                    $userModel->user_id= Yii::app()->user->id;
                    $userModel->save();
                    $this->redirect(array('/portalThemes'));
                }
                else
                {
                    $model= new PortalThemes;
                    $model->attributes= $themes->attributes;
                    $model->user_id= Yii::app()->user->id;
                    $model->save();
                    $this->redirect(array('/portalThemes'));
                }
            }
            else
            {
                $userModel= PortalThemes::model()->findByAttributes(array('user_id'=>  Yii::app()->user->id));
                if($userModel)
                {        
                    $user_id= $userModel->id;  
                    $userModel->unsetAttributes();
                    
                    $userModel->id= $user_id;
                    $userModel->user_id= Yii::app()->user->id;
                    $userModel->save();
                    $this->redirect(array('/portalThemes'));
                }
                else
                {
                    $model= new PortalThemes;                    
                    $model->user_id= Yii::app()->user->id;
                    $model->save();
                    $this->redirect(array('/portalThemes'));
                }
            }
            
            $this->redirect(array('/portalThemes'));
        }




        public function actionCreate()
	{
		$model=new PortalThemes;
                $role= $this->getrole();
                if($role=='Admin')
                {
                    $model->user_id= 0;
                }
                else
                {
                    $model->user_id= Yii::app()->user->id;	
                }
		if(isset($_POST['PortalThemes']))
		{
			$model->attributes=$_POST['PortalThemes'];
                        
			if($model->save())
				$this->redirect(array('/portalThemes'));
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}
        
	public function actionUpdate()
	{
                
                $role= $this->getrole();
                if($role=='Admin')
                {
                    $user_id= 0;
                }
                else
                {
                    $user_id= Yii::app()->user->id;	
                }
                
                $themes_model= PortalThemes::model()->findByAttributes(array('user_id'=>$user_id));
                if($themes_model)
                {
                    $id= $themes_model->id;
                }
		$model=$this->loadModel($id);
		if(isset($_POST['PortalThemes']))
		{
			$model->attributes=$_POST['PortalThemes'];
			if($model->save())
				$this->redirect(array('/portalThemes'));
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		$model= PortalThemes::model()->findByPk($id);
                if($model)
                {
                    $user_id= $model->user_id;  
                    $model->unsetAttributes();
                    
                    $model->id= $id;
                    $model->user_id= $user_id;                                        
                    if($model->save())
                    {
                        $this->redirect(array('/portalThemes'));
                    }
                    else 
                        throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
                }
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionIndex()
	{
            $role= Rights::getAssignedRoles(Yii::app()->user->id);
            if(sizeof($role)==1 and key($role)=="student")
            {
                $this->layout= '/portallayouts/studentmain';
            }
            if(sizeof($role)==1 && key($role)=="parent")
            {
                $this->layout= '/portallayouts/none';
            }
            if(sizeof($role)==1 && key($role)=="teacher")
            {
                $this->layout= '/portallayouts/teachers';
            }
		$this->render('index');     
	}

		
	public function loadModel($id)
	{
		$model=PortalThemes::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        public function getrole()
        {
            $roles=Rights::getAssignedRoles(Yii::app()->user->Id);
                foreach($roles as $data)
                {
                $role= $data->name;
                }
                return $role;
                
        }
	
}
