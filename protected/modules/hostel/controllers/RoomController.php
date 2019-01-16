<?php

class RoomController extends RController
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
				'actions'=>array('index','view','error','roomchange','change','allot','roomsearch','autocomplete','manage','roomlist','roomrequest'),
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
		$model=new Room;
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Room']))
		{
				$flag=0;
				
				$model->attributes=$_POST['Room'];
				$list=$_POST['Room'];
				$cnt=count($list['room_no']);
				
				$errors		= array();
				
				for($i=0;$i<$cnt;$i++)
				{
					
					$model=new Room;
					
					$model->room_no=$list['room_no'][$i];
					$model->no_of_bed=$list['no_of_bed'][$i];
					$model->created=date('Y-m-d');
					$model->floor=$list['floor'];
					$model->hostel_id=$list['hostel_id'];
					$count=$list['no_of_bed'][$i];
					$letter='a';
					
					if(!$model->validate())
					{
						foreach($model->getErrors() as $column=>$error){
							$errors[$column]	= $error[0];
						}
						$flag=1;
					}
					
				}
				
				if($flag==0){
					for($i=0;$i<$cnt;$i++)
					{						
						$model=new Room;						
						$model->room_no=$list['room_no'][$i];
						$model->no_of_bed=$list['no_of_bed'][$i];
						$model->created=date('Y-m-d');
						$model->floor=$list['floor'];
						$model->hostel_id=$list['hostel_id'];
						$count=$list['no_of_bed'][$i];
						$letter='a';
						if($model->save())
						{
							for($j=1;$j<=$count;$j++)
							{
								$model_1=new Allotment;
								$model_1->room_no=$model->id;
								$model_1->bed_no=$letter;
								$model_1->status='C';
								$model_1->hostel_id=$model->hostel_id;
								$model_1->save();
								$letter++;
							}
						}
					}
					
					$this->redirect(array('/hostel/room/manage'));
				}
				
				else{
					$model=new Room;
					foreach($errors as $column=>$error){
						$model->addError($column, $error);
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
		$floor = $model->floor;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Room']))
		{
			$model->attributes=$_POST['Room'];
			$list=$_POST['Room'];
			$cnt=count($list['room_no']);
			if($model->validate()){
				
				for($i=0;$i<$cnt;$i++)
				{
					
					$model=$this->loadModel($id);
					
					$model->room_no=$list['room_no'][$i];
					$model->no_of_bed=$list['no_of_bed'][$i];
					$model->created=date('Y-m-d');
					//$model->floor=$list['floor'];
					$model->floor=$floor;
					$model->hostel_id=$list['hostel_id'];
					$count=$list['no_of_bed'][$i];
					$letter='a';
					$model->save();
					$model_2=Allotment::model()->findAll("room_no=:x",array(':x'=>$model->id));					
					for($j=1;$j<=$count;$j++)
					{
							//$model_2=Allotment::model()->findAll("room_no=:x",array(':x'=>$model->id));
							
							$model_1=new Allotment;			
							$model_1->room_no=$model->id;
							$model_1->bed_no=$letter;
							$model_1->status='C';
							$model_1->hostel_id=$model->hostel_id;
							$model_1->save();
						//var_dump($model_1->attributes);exit;
							$letter++;
					
					}
				
					if($model_2 != NULL)
					{	
						foreach($model_2 as $m)
						{
							$m->delete();
						}
					}
					
				}
			
				$this->redirect(array('/hostel/room/manage'));
			}				
		}
		
		$this->render('update',array(
			'model'=>$model,
		));
		
	}
	
	/*public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$floor = $model->floor;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Room']))
		{
				$model->attributes=$_POST['Room'];
				$list=$_POST['Room'];
				$cnt=count($list['room_no']);
				
					$model=$this->loadModel($id);
					
					$model->room_no=$list['room_no'];
					$model->no_of_bed=$list['no_of_bed'];
					$model->created=date('Y-m-d');
					//$model->floor=$list['floor'];
					$model->floor=$floor;
					$model->hostel_id=$list['hostel_id'];
					$count=$list['no_of_bed'];
					$letter='a';
					//var_dump($model->getErrors());exit;
					if(!$model->validate())
					{
					  $this->render('update',array(
			'model'=>$model,
		));
					}else
					{
					/*else {
					   $this->render('update', array('errors' => $model->getErrors()));
					}*/
					/*$model->save();
					$model_2=Allotment::model()->findAll("room_no=:x",array(':x'=>$model->id));					
					for($j=1;$j<=$count;$j++)
					{
							//$model_2=Allotment::model()->findAll("room_no=:x",array(':x'=>$model->id));
							
							$model_1=new Allotment;			
							$model_1->room_no=$model->id;
							$model_1->bed_no=$letter;
							$model_1->status='C';
							$model_1->hostel_id=$model->hostel_id;
							$model_1->save();
						//var_dump($model_1->attributes);exit;
							$letter++;
					
					}*/
				
					/*if($model_2 != NULL)
					{	
						foreach($model_2 as $m)
						{
							$m->delete();
						}
					}*/
					
				
			
				/*$this->redirect(array('/hostel/room/manage'));
				//$this->redirect(array('/RoomDetails/create/','id'=>$_POST['Room']['room_no']));
			//}
			}
		}
		
		$this->render('update',array(
			'model'=>$model,
		));
		
	}*/

	public function actionManage()
	{
		$model=new Room;
		$criteria = new CDbCriteria;
		$criteria->group = 'room_no';
	

		if(isset($_POST['search']))
		{ 
		
			
			if($_POST['search']=='1')
			{
				$criteria->condition='status =:match or status =:match1';
		 		$criteria->params = array(':match' => 'C',':match1'=>'S');
				
				
			}
			if($_POST['search']=='2')
			{
				
				$criteria->condition='status = :match2';
		 		$criteria->params[':match2'] = 'S';
			}
			if($_POST['search']=='3')
			{
				$criteria->condition='status = :match3';
				$criteria->params[':match3'] = 'C';
		 		
			}
		}
		else if(!isset($_POST['search']))
		{
				$criteria->condition='status = :match4';
				$criteria->params[':match4'] = 'C';
		 	
		}
		else
		{
	    		$this->render('manage',array('model'=>$model,'pages'=>$pages));
		}
	
			
		$criteria->order = 'id ASC';
		
		$total = Allotment::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->applyLimit($criteria);  // the trick is here!
        $pages->setPageSize(Yii::app()->params['listPerPage']);
		$posts = Allotment::model()->findAll($criteria);
		
		$this->render('manage',array(
		'list'=>$posts,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>Yii::app()->params['listPerPage'],
		));	
		
		
		
	}
	
	public function actionRoomlist()
	{
		$model=new Room;
	
		$this->render('roomlist',array('model'=>$model));
		
	}
	public function actionRoomrequest()
	{
		$model=new Roomrequest;
		if(isset($_REQUEST['studentid']) && isset($_REQUEST['allotid']))
		{
			$model->student_id=$_REQUEST['studentid'];
			$model->allot_id=$_REQUEST['allotid'];
			$model->status='C';
			$model->created_at=date('Y-m-d H:i:s');
			$model->save();
		}
		$this->render('roomrequest',array('model'=>$model,'floor'=>$_REQUEST['floor']));
		
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
	public function actionRoomchange()
	{
		$model=new Allotment;
		if(isset($_POST['search']))
		{
			$criteria = new CDbCriteria;
				if(isset($_POST['student_id']) and $_POST['student_id']!=NULL)
				{
					
					
					$criteria->condition='student_id LIKE :match and status=:x';
		 			$criteria->params = array(':match' =>$_POST['student_id'].'%',':x'=>'S');
					
					
			
				}
				else
				{
					$criteria->condition='status=:x';
					$criteria->params = array(':x'=>'S');
				}
			$total = Allotment::model()->count($criteria);
			$pages = new CPagination($total);
      	    $pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria);  // the trick is here!
			$posts = Allotment::model()->findAll($criteria);
			$this->render('roomchange',array('model'=>$model,
			'list'=>$posts,
			'pages' => $pages,
			'item_count'=>$total,
			'page_size'=>Yii::app()->params['listPerPage'],
			));	
		}
		
		else
		{
		$this->render('roomchange',array('model'=>$model));
		}
	}
	public function actionChange()
	{
		$model=new Room;
		$model_1 = new Allotment;
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role 
		if(isset($_POST['search']))
		{
				$criteria = new CDbCriteria;
				if((isset($_POST['floor']) and $_POST['floor']!=NULL) and (isset($_POST['hostel']) and $_POST['hostel']!=NULL))
				{
					  
					$hostel_id	=	$_POST['hostel'];
					$floor_id	=	$_POST['floor'];
				//echo $hostel_id.$floor_id;exit;
					$floor = Floor::model()->findByAttributes(array('id'=>$_POST['floor']));
					
					$room=Room::model()->findAllByAttributes(array('floor'=>$floor->id,'hostel_id'=>$_POST['hostel']));
				//echo count($room);
				if($room!=NULL)
					{
						foreach($room as $room_1)
						{
							
								$criteria->condition='floor=:match';
								$criteria->params[':match'] =$room_1->floor;
						}
					}
					
				}
				else if((isset($_POST['hostel']) and $_POST['hostel']!=NULL))
				{
					$hostel_id	=	$_POST['hostel'];
					$criteria->condition='hostel_id=:hostel_id';
					$criteria->params[':hostel_id'] =$_POST['hostel'];
						
				}
			$total = Room::model()->count($criteria);
			$pages = new CPagination($total);
      	    $pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria);  // the trick is here!
			$posts = Room::model()->findAll($criteria);
			foreach($roles as $role)
			{						  
			   if(sizeof($roles)==1 and $role->name == 'student') //if the current role is student,it render stud_change.php page else it take change.php page
			   {
				   $this->render('stud_change',array('model'=>$model,
					'list'=>$posts,
					'pages' => $pages,
					'item_count'=>$total,
					'page_size'=>Yii::app()->params['listPerPage'],
					));
			   }
			   else
			   {	
					$this->render('change',array('model'=>$model,'floor'=>$floor_id,'hostel'=>$hostel_id,
					'list'=>$posts,
					'pages' => $pages,
					'item_count'=>$total,
					'page_size'=>Yii::app()->params['listPerPage'],
					));	
			   }
			}
		}
		
		else
		{
			foreach($roles as $role)
			{						  
			   if(sizeof($roles)==1 and $role->name == 'student') //if the current role is student,it render stud_change.php page else it take change.php page
			   {
				   $this->render('stud_change',array('model'=>$model,'floor'=>$_POST['Room']['floor']));
			   }
			   else
			   {
					$this->render('change',array('model'=>$model,'floor'=>$_POST['Room']['floor']));
			   }
			}
		}
		
	
	}
	
	public function actionAllot()
	{

		if(isset($_POST['hostel']))
		{
			$criteria = new CDbCriteria;
			$criteria->condition = "hostel_id=:x";
			$criteria->params = array(':x'=>$_POST['hostel']);
			$data=Floor::model()->findAll($criteria);
		}
		
		$data=CHtml::listData($data,'id','floor_no');
		echo CHtml::tag('option',
						 array('value'=>''),CHtml::encode("Select Floor"),true);
		  foreach($data as $value=>$title)
		  {
			  echo CHtml::tag('option',
						 array('value'=>$value),CHtml::encode($title),true);
		  }
	}
	public function actionRoom()
	{
	
		if(isset($_POST['floor']))
		{
			//$floor = Floor::model()->findByAttributes(array('floor_no'=>$_POST['floor']));
			$criteria = new CDbCriteria;
			$criteria->condition = "floor=:x";
			$criteria->params = array(':x'=>$_POST['floor']);
			$data=Room::model()->findAll($criteria);			
		}
		echo CHtml::tag('option', array('value' => 0), CHtml::encode('Select room'), true);
		$data=CHtml::listData($data,'id','room_no');
		
		  foreach($data as $value=>$title)
		  {
			  echo CHtml::tag('option',
						 array('value'=>$value),CHtml::encode($title),true);
		  }
	}
	
	public function actionRoomsearch()
	{
		$model=new Allotment;
		if(isset($_POST['search']))
		{
			$criteria = new CDbCriteria;
			$i=1;
			
			if(isset($_POST['student_id']) and $_POST['student_id']!=NULL)
			{
				
					
					$criteria->condition='student_id=:match';
					$criteria->params[':match'] =$_POST['student_id'];
					
					
					$i=0;	
			}
			
			//if(isset($_POST['Allotment']['floor']) and $_POST['Allotment']['floor']!=NULL)
			//{
				//$room=Room::model()->findByAttributes(array('floor'=>$_POST['Allotment']['floor']));
				//if($i==0)
				//	{
					
				//	$criteria->condition=$criteria->condition.' and floor LIKE :match1';
				//	}
				//	else
				//	{
				///	$criteria->condition='floor LIKE :match1';
				//	}
		 		//	$criteria->params[':match1'] = $room->floor.'%';
				//	$i=0;
			//}
			if(isset($_POST['room_no']) and $_POST['room_no']!=NULL)
			{
				
				if($i==0)
					{
					$criteria->condition=$criteria->condition.' and room_no = :match2';
					}
					else
					{
					$criteria->condition='room_no = :match2';
					}
					
					
					$criteria->params[':match2'] = $_POST['room_no'];
		 		//$criteria->params = array(':match2'=>$_POST['Allotment']['room_no'].'%',':x'=>'S');
					
			}
			
			$total = Allotment::model()->count($criteria);
			$pages = new CPagination($total);
      	    $pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria);  // the trick is here!
			$posts = Allotment::model()->findAll($criteria);
			
			$this->render('roomsearch',array('model'=>$model,
			'list'=>$posts,
			'pages' => $pages,
			'item_count'=>$total,
			'page_size'=>Yii::app()->params['listPerPage'],
			));
			}	
			else
			{
				$this->render('roomsearch',array('model'=>$model));
			}
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Room');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
		public function actionError()
	{
		
		$this->render('error');
		
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Room('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Room']))
			$model->attributes=$_GET['Room'];

		$this->render('admin',array(
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
						'value'=>$hotel->last_name,
						'id'=>$hotel->id,
						);
		}
		echo CJSON::encode($return_array);
	  }
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Room::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='room-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	/* To get floor no for dropdown */
	public function actionFloorlist()
	{		
	
		$data=Floor::model()->findAllByAttributes(array('hostel_id'=>$_POST['hostel']));
		echo CHtml::tag('option', array('value' => 0), CHtml::encode('Select Floor'), true);
		$data=CHtml::listData($data,'floor_no','floor_no');
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
			
		}
	}
}
