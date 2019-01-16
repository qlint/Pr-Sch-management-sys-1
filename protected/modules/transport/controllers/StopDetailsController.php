<?php
class StopDetailsController extends RController
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
			'rights', // perform access control for CRUD operations
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
				'actions'=>array('index','view','manage'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new StopDetails;
		//$err_flag = 0;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['StopDetails']))
		{
			$add	=	false;
			$model->attributes=$_POST['StopDetails'];
			$route=RouteDetails::model()->findByAttributes(array('id'=>$_POST['StopDetails']['route_id']));
			$list=$_POST['StopDetails'];
			
			if(isset($_REQUEST['stops'])){
				$cnt=$_REQUEST['stops'];
				$add	=	true;
			}
			else{
				$cnt=$route['no_of_stops'];
			}
			
			$errors		= array();
	        for($i=0;$i<$cnt;$i++)
			{
				$model=new StopDetails;
				$model->route_id=$_POST['StopDetails']['route_id'];
				$model->stop_name=$list['stop_name'][$i];
				$model->fare=$list['fare'][$i];
				$model->arrival_mrng=$_POST['arrival_mrng'][$i];
				$model->arrival_evng=$_POST['arrival_evng'][$i];
				
				if(!$model->validate()){
					$err_flag = 1;
					foreach($model->getErrors() as $column=>$error){
						$errors[$column]	= $error[0];
					}
				}
			}
				if($err_flag==0){				
				for($i=0;$i<$cnt;$i++)
				{
					$model=new StopDetails;
					$model->route_id=$_POST['StopDetails']['route_id'];
					$model->stop_name=$list['stop_name'][$i];
					$model->fare=$list['fare'][$i];
					$model->arrival_mrng=$_POST['arrival_mrng'][$i];
					$model->arrival_evng=$_POST['arrival_evng'][$i];
					if($model->save()){
						$id	=	$model->route_id;
						$croute	=	RouteDetails::model()->findByPk($id);
					}
				}
				
				$this->redirect(array('manage','id'=>$model->route_id));
			}
			else{
				$model=new StopDetails;
				foreach($errors as $column=>$error){
					$model->addError($column, $error);
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	 public function actionUpdate($id)
	 {		 
		$model		= StopDetails::model()->findAllByAttributes(array('route_id'=>$id));
		$model_1 	= new StopDetails;
		if(isset($_POST['StopDetails'])){			
			if(count($_POST['StopDetails']) > 0){
				$errors		= array();
				$err_flag 	= 0;
				for($i = 0; $i < count($_POST['StopDetails']); $i++){
					$model_1 = StopDetails::model()->findByPk($_POST['StopDetails'][$i]['id']);					
					
					$model_1->stop_name			=	$_POST['StopDetails'][$i]['stop_name'];
					$model_1->fare				=	$_POST['StopDetails'][$i]['fare'];
					$model_1->arrival_mrng		=	$_POST['StopDetails'][$i]['arrival_mrng'];
					$model_1->arrival_evng		=	$_POST['StopDetails'][$i]['arrival_evng'];	
								
					if(!$model_1->validate()){
						$err_flag = 1;
						foreach($model_1->getErrors() as $column=>$error){
							$errors[$column]	= $error[0];
						}					
					}					
				}
				
				if($err_flag == 0){				
					for($i = 0; $i < count($_POST['StopDetails']); $i++){					
						$stop_details = StopDetails::model()->findByPk($_POST['StopDetails'][$i]['id']);
						if($stop_details){
							$stop_details->stop_name		=	$_POST['StopDetails'][$i]['stop_name'];
							$stop_details->fare				=	$_POST['StopDetails'][$i]['fare'];
							$stop_details->arrival_mrng		=	$_POST['StopDetails'][$i]['arrival_mrng'];
							$stop_details->arrival_evng		=	$_POST['StopDetails'][$i]['arrival_evng'];
							$stop_details->save();
						}
					}
					$this->redirect(array('manage','id'=>$_REQUEST['id']));
				}
				else{					
					foreach($errors as $column=>$error){
						$model_1->addError($column, $error);
					}
				}
			}						
		}
		$this->render('update',array(
			'model'=>$model,'model_1'=>$model_1
		));
	}
	 
	/*public function actionUpdate($id)
	{
		$model	=	StopDetails::model()->findAllByAttributes(array('route_id'=>$id));
		if(isset($_POST['StopDetails'])){
			$allStops	=	$_POST['StopDetails'];
			var_dump($_POST['StopDetails']);exit;
			foreach($allStops as $stop){
				$current=$this->loadModel($id);
				
				$current->stop_name		=	$stop['stop_name'];
				$current->fare			=	$stop['fare'];
				$current->arrival_mrng	=	$stop['arrival_mrng'];
				$current->arrival_evng	=	$stop['arrival_mrng'];
				$current->save();
			}
			
			$this->redirect(array('manage','id'=>$_REQUEST['id']));
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}*/
	
	/*?>public function actionUpdate()
	{
		$model	=	StopDetails::model()->findAllByAttributes(array('route_id'=>$id));
		
		if(isset($_POST['StopDetails']))
		{
			$add	=	false;
			$model->attributes=$_POST['StopDetails'];
			$route=RouteDetails::model()->findByAttributes(array('id'=>$_POST['StopDetails']['route_id']));
			$list=$_POST['StopDetails'];
			
			if(isset($_REQUEST['stops'])){
				$cnt=$_REQUEST['stops'];
				$add	=	true;
			}
			else{
				$cnt=$route['no_of_stops'];
			}
			
			$errors		= array();
	        for($i=0;$i<$cnt;$i++)
			{
				$model=new StopDetails;
				$model->route_id=$_POST['StopDetails']['route_id'];
				$model->stop_name=$list['stop_name'][$i];
				$model->fare=$list['fare'][$i];
				$model->arrival_mrng=$_POST['arrival_mrng'][$i];
				$model->arrival_evng=$_POST['arrival_evng'][$i];
				
				if(!$model->validate()){
					$err_flag = 1;
					foreach($model->getErrors() as $column=>$error){
						$errors[$column]	= $error[0];
					}
				}
			}
				if($err_flag==0){				
				for($i=0;$i<$cnt;$i++)
				{
					$model=new StopDetails;
					$model->route_id=$_POST['StopDetails']['route_id'];
					$model->stop_name=$list['stop_name'][$i];
					$model->fare=$list['fare'][$i];
					$model->arrival_mrng=$_POST['arrival_mrng'][$i];
					$model->arrival_evng=$_POST['arrival_evng'][$i];
					if($model->save()){
						$id	=	$model->route_id;
						$croute	=	RouteDetails::model()->findByPk($id);
					}
				}
				
				$this->redirect(array('manage','id'=>$model->route_id));
			}
			else{
				$model=new StopDetails;
				foreach($errors as $column=>$error){
					$model->addError($column, $error);
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

<?php */
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
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
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	
	public function actionRemove($id)
	{
		if(Yii::app()->request->isPostRequest)
		{	
			if(is_numeric($id)){
				$model	=	$this->loadModel($id);
				$id	=	$model->route_id;
				if($model->delete()){
					$route	=	RouteDetails::model()->findByPk($id);
					$route->saveAttributes(array('no_of_stops'=>$route->no_of_stops-1));
				}
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage','id'=>$model->route_id));
			}
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
		
		
	}
	
	public function actionRemoveAll($id){
		if(Yii::app()->request->isPostRequest){
			if(is_numeric($id)){
				StopDetails::model()->deleteAllbyAttributes(array('route_id'=>$id));
				$route	=	RouteDetails::model()->findByPk($id);
				$route->saveAttributes(array('no_of_stops'=>0));
				$this->redirect(array('stopDetails/manage','id'=>$id));
			}
			else{
				$this->redirect(array('RouteDetails/manage'));
			}
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('StopDetails');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionManage()
	{
		$model=new StopDetails;
		
		$this->render('manage',array('model'=>$model));
		
		
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new StopDetails('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StopDetails']))
			$model->attributes=$_GET['StopDetails'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionEdit($id){
		$model=$this->loadModel($id);
		if(isset($_POST['StopDetails']))
		{ 
			$model->attributes=$_POST['StopDetails'];
			if($model->save())
				$this->redirect(array('stopDetails/manage','id'=>$model->route_id));
		}
		$this->render('edit',array('model'=>$model));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=StopDetails::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stop-details-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
