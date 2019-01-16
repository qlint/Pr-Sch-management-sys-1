<?php

class TransportationController extends RController
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
				'actions'=>array('index','view','autocomplete','routes','studentsearch','autocomplete1','settings','error','reallot','remove','allotstudent'),
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
	public function actiofnreallot($id)
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
		$model=new Transportation;
				
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
	
		if(isset($_POST['Transportation']))
		{
			
			$trans=Transportation::model()->findByAttributes(array('student_id'=>$_POST['student_id']));
			$model->attributes=$_POST['Transportation'];
			$model->student_id=$_POST['student_id'];
			if($trans!=NULL and $_POST['student_id'])
			{
				$this->redirect(array('error'));
			}
			else
			{ 
			     if(isset($_POST['student_id']) and $_POST['student_id'] != NULL)
				 { 
				$allot=Allotment::model()->findByAttributes(array('student_id'=>$_POST['student_id']));
					if($allot->student_id == $_POST['student_id'])
					{
						
					  $this->redirect(array('warning','transportation'=>$_POST['Transportation']['stop_id'],'student_id'=>$_POST['student_id']));
					}
				 }
			        if(isset($_POST['Transportation']['stop_id']) and $_POST['Transportation']['stop_id'] != NULL)
					{
						if($_POST['Transportation']['stop_id']!='0')
						{
					$stop=StopDetails::model()->findByAttributes(array('id'=>$_POST['Transportation']['stop_id']));
					$route=RouteDetails::model()->findByAttributes(array('id'=>$stop->route_id));
					$vehicle=VehicleDetails::model()->findByAttributes(array('id'=>$route->vehicle_id));
					$connection = Yii::app()->db;
					$sql="SELECT t2.id FROM transportation AS t2 JOIN stop_details AS t1   WHERE t2.stop_id = t1.id AND t1.route_id= ".$stop->route_id;
					$command = $connection->createCommand($sql);
					$stops = $command->queryAll();
					
		      			if($vehicle->maximum_capacity > count($stops))
			 				{
			
								if($model->save())
								{
									$this->redirect(array('view','id'=>$model->id));
								}
							}
					
							else
							{
								Yii::app()->user->setFlash('errorMessage',Yii::t("app","No seat available in the vehicle!"));
								$this->redirect(array('create'));
							}
						}
				
				}
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

		if(isset($_POST['Transportation']))
		{
			$model->attributes=$_POST['Transportation'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	public function actionAutocomplete() 
	 {
	  if (isset($_GET['term'])) {
		$criteria=new CDbCriteria;
		$criteria->alias = "last_name";
		$criteria->condition = "last_name   like '%" . $_GET['term'] . "%'";
		 $userArray = Students::model()->findAll($criteria);
		
		$hotels = Students::model()->findAll($criteria);;
	
		$return_array = array();
		foreach($hotels as $hotel) {
		  $return_array[] = array(
						'label'=>$hotel->last_name.' '.$hotel->first_name  ,
						'id'=>$hotel->id,
						);
		}
		echo CJSON::encode($return_array);
	  }
	}
	public function actionRoutes()
	{
	
		if(isset($_POST['route']))
		{
			
			$data=StopDetails::model()->findAll('route_id=:x',array(':x'=>$_POST['route']));
		}
		echo CHtml::tag('option', array('value' =>''), CHtml::encode(Yii::t('app','Select Stop')), true);
		//$data=CHtml::listData($data,'id','stop_name');
		  foreach($data as $value)
		  {
			  echo CHtml::tag('option',
						 array('value'=>$value->id,),CHtml::encode($value->stop_name),true);
		  }
	}
	public function actionStudentsearch()
	{
		$model= new Transportation;
		if(isset($_POST['search']))
		{
			$criteria = new CDbCriteria;
			$i=1;
			if($_POST['student_id']=="" &&  $_POST['Transportation']['stop_id']=="")
			{
				$this->render('studentsearch',array('model'=>$model));
			}
			else{
			if(isset($_POST['student_id']) and $_POST['student_id']!=NULL)
			{
					
					$criteria->condition='student_id LIKE :match';
					$criteria->params[':match'] = $_POST['student_id'];
				
					$i=0;
						
			}
				if(isset($_POST['route']) and $_POST['route']!=NULL)
				{
						$criteria->join ='JOIN `stop_details` ON `stop_details`.id =t.stop_id';
						$criteria->condition = '`stop_details`.route_id=:a';
						$criteria->params = array(':a'=>$_POST['route']);
						
				}

			if(isset($_POST['Transportation']['stop_id']) and $_POST['Transportation']['stop_id']!=0)
			{
					
					if($i==0)
					{
						
						$criteria->condition=$criteria->condition.' and stop_id LIKE :match2';
						
					}
					else
					{
						$criteria->condition=$criteria->condition.' and t.stop_id LIKE :match2';

						
						//$criteria->condition='stop_id LIKE :match2';
						
					}
						$criteria->params[':match2'] = $_POST['Transportation']['stop_id'];
						//$criteria->params = array(':match2' => $_POST['stop_id'].'%');
					//var_dump($criteria);
			}
			
		
			$total = Transportation::model()->count($criteria);
			$pages = new CPagination($total);
      	    $pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria);  // the trick is here!
			$posts = Transportation::model()->findAll($criteria);
			
			$this->render('studentsearch',array('model'=>$model,
			'list'=>$posts,
			'pages' => $pages,
			'item_count'=>$total,
			'page_size'=>Yii::app()->params['listPerPage'],
			));
	}
		}
	else
	{
		$this->render('studentsearch',array('model'=>$model));
	}
	}
	
	//attendace log
	public function actionAttendanceLog(){
            
            if(isset($_REQUEST['route_id']) && $_REQUEST['route_id']!=NULL)
            {
                $route_id= $_REQUEST['route_id'];                                
                $stop_ids= CHtml::listData(StopDetails::model()->findAllByAttributes(array('route_id'=>$route_id)),'id','id');
                $criteria= new CDbCriteria;
                $criteria->addInCondition('stop_id',$stop_ids);
                $model= Transportation::model()->findAll($criteria);                
                
                $total = Transportation::model()->count($criteria);
                $pages = new CPagination($total);
                $pages->setPageSize(Yii::app()->params['listPerPage']);
                $pages->applyLimit($criteria);  // the trick is here!
                $posts = Transportation::model()->findAll($criteria);

                if(isset($_GET['print'])){
			
			
                        $filename= "Route Attendance.pdf";
                        Yii::app()->osPdf->generate("application.modules.transport.views.transportation.route_attendancepdf", $filename, array('model'=>$model));
		}
                
                
                $this->render('attendanceLog',array('model'=>$model,
                                            'list'=>$posts,
                                            'pages' => $pages,
                                            'item_count'=>$total,
                                            'page_size'=>Yii::app()->params['listPerPage'],)) ;
                
            }
		$this->render('attendanceLog');
	}
        
        public function actionAttendancePdf(){
            if(isset($_REQUEST['route_id']) && $_REQUEST['route_id']!=NULL)
            {
                $route_id= $_REQUEST['route_id'];                                
                $stop_ids= CHtml::listData(StopDetails::model()->findAllByAttributes(array('route_id'=>$route_id)),'id','id');
                $criteria= new CDbCriteria;
                $criteria->addInCondition('stop_id',$stop_ids);
                $model= Transportation::model()->findAll($criteria);                
                                               					
                $filename= "Route Attendance.pdf";
                Yii::app()->osPdf->generate("application.modules.transport.views.transportation.route_attendancepdf", $filename, array('model'=>$model));
            }
        }
	

	public function actionAutocomplete1() 
	 {
	  if (isset($_GET['term'])) {
		$criteria=new CDbCriteria;
		$criteria->alias = "last_name";
		$criteria->condition = "last_name   like '%" . $_GET['term'] . "%'";
		 $userArray = Students::model()->findAll($criteria);
		
		$hotels = Students::model()->findAll($criteria);;
	
		$return_array = array();
		foreach($hotels as $hotel) {
		  $return_array[] = array(
						'label'=>$hotel->last_name.' '.$hotel->first_name  ,
						'value'=>$hotel->last_name,
						'id'=>$hotel->id,
						);
		}
		echo CJSON::encode($return_array);
	  }
	}
	public function actionSettings()
	{
		$model=new Transportation;
		
		$this->render('settings',array('model'=>$model));
		
	}
	public function actionViewall()
	{
	 $this->render('transview',array('model'=>$model));	
	}
	public function actionPayfees()
	{
		if(Yii::app()->request->isPostRequest){
			$model=new Transportation;
			$list  = Transportation::model()->findByAttributes(array('student_id'=>$_REQUEST['id']));
			$list->is_paid = 1;
			$list->save();
			$this->render('transdetails',array('model'=>$model,'studentid'=>$_REQUEST['id']));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	public function actionPrint()
	{
	    $model=new Transportation;
            $list  = Transportation::model()->findByAttributes(array('student_id'=>$_REQUEST['id']));       
        
            $filename= 'Transport.pdf';
            Yii::app()->osPdf->generate("application.modules.transport.views.transportation.print", $filename, array('model'=>$model,'studentid'=>$_REQUEST['id']));
		
	}
	public function actionRemove($id)
	{
		if(Yii::app()->request->isPostRequest){
			Transportation::model()->deleteAllByAttributes(array('id'=>$id));
			$this->redirect(array('studentsearch'));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
		
	}
	public function actionReallot($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Transportation']))
		{
			 $model->student_id	=	$_POST['Transportation']['student_id'];
			 
			        if(isset($_POST['Transportation']['stop_id']) and $_POST['Transportation']['stop_id'] != NULL)
					{
						
						if($_POST['Transportation']['stop_id']!='0')
						{
							 $model->stop_id = $_POST['Transportation']['stop_id'];
					
								if($model->save())
								{
									$this->redirect(array('view','id'=>$model->id));
								}
						}
				
						else
						{
							Yii::app()->user->setFlash('errorMessage',Yii::t("app","No seat available in the vehicle!"));
							//$this->redirect(array('reallot','id'=>$id));
						}
						
					}
		}

		
		
		/*$allot =Transportation::model()->findByAttributes(array('id'=>$id)); 
		//$stopdetails=StopDetails::model()->findByAttributes(array('id'=>$list_1->stop_id));
		//$routedetails=RouteDetails::model()->findByAttributes(array('id'=>$stopdetails->route_id));
		if(isset($_POST['Transportation']))
		{
			
			
			if(@$allot)
			{
		
			 $allot->saveAttributes(array('student_id'=>$_POST['Transportation']['student_id'],'stop_id'=>$_POST['Transportation']['stop_id']));
			
			 $this->redirect(array('view','id'=>$model->id));
			}
			
				
		}
*/
		$this->render('reallot',array(
			'model'=>$model,
		));
		
		
	}
	public function actionAllotStudent()
	{
		
		$model=new Transportation;
				
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_REQUEST['student_id']) and ($_REQUEST['student_id']!=NULL) and isset($_REQUEST['transportation']) and ($_REQUEST['transportation']!=NULL))
		
		{
				
					
					
					$stop=StopDetails::model()->findByAttributes(array('id'=>$_REQUEST['transportation']));
					$route=RouteDetails::model()->findByAttributes(array('id'=>$stop->route_id));
					$vehicle=VehicleDetails::model()->findByAttributes(array('id'=>$route->vehicle_id));
					$connection = Yii::app()->db;
					$sql="SELECT t2.id FROM transportation AS t2 JOIN stop_details AS t1   WHERE t2.stop_id = t1.id AND t1.route_id= ".$stop->route_id;
					
					$command = $connection->createCommand($sql);
					$stops = $command->queryAll();
					if($vehicle->maximum_capacity > count($stops))
			  		{
						
						$model->student_id=$_REQUEST['student_id'];
						$model->stop_id=$_REQUEST['transportation'];
							
						if($model->save())
						{
							$this->redirect(array('view','id'=>$model->id));
						}
					}
					
					else
					{
						Yii::app()->user->setFlash('errorMessage',Yii::t("app","No seat available in the vehicle!"));
						$this->redirect(array('create'));
					}
			
			}
		
		
	
		
	}
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
	public function actionError()
	{
		
		$this->render('error');
		
	}
	public function actionWarning()
	{
		
		$this->render('warning');
		
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Transportation');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Transportation('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Transportation']))
			$model->attributes=$_GET['Transportation'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Transportation::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='transportation-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionView($id)

	{

		$this->render('view',array(

			'model'=>$this->loadModel($id),

		));

	}
}
