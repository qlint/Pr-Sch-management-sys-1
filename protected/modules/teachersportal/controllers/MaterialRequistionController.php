<?php

class MaterialRequistionController extends RController
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=PurchaseMaterialRequistion::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->renderPartial('view',array('id'=>$_REQUEST['id']),false,true);	
		
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new PurchaseMaterialRequistion;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PurchaseMaterialRequistion']))
		{ 
			$model->attributes=$_POST['PurchaseMaterialRequistion'];
			$model->employee_id=Yii::app()->User->Id;
			
			if($model->save())
				$this->redirect(array('index'));
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PurchaseMaterialRequistion']))
		{
			$model->attributes=$_POST['PurchaseMaterialRequistion'];
			$model->status=0;
			$model->status=0;
			$model->is_issued=0;
			if($model->save())
			{
			Yii::app()->user->setFlash('successMessage', Yii::t('app','Material Request Updated Successfully'));
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
			$model = PurchaseMaterialRequistion::model()->findByPk($id);
			if($model){				
				if($model->delete())
				Yii::app()->user->setFlash('successMessage', Yii::t('app','Material Request Deleted Successfully'));
			}	
				//$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
				$this->redirect(array('index'));	
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria 				= 	new CDbCriteria;
		$criteria->condition	=   'employee_id=:employee_id';
		$criteria->params 		= 	array(':employee_id'=>Yii::app()->User->Id);		
		$criteria->order 		= 	'id DESC';
		
		$total = PurchaseMaterialRequistion::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria); 
		
		$materials = PurchaseMaterialRequistion::model()->findAll($criteria);
		$this->render('index', array('materials'=>$materials, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new PurchaseMaterialRequistion('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PurchaseMaterialRequistion']))
			$model->attributes=$_GET['PurchaseMaterialRequistion'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='material-requistion-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	public function actionApprove()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->status_pm =1;
			if($model->save())
			{
				$this->redirect(array('index'));
			}
		}
		
	}
	
	public function actionDisapprove()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->status_pm =2;
			if($model->save())
			{
				$this->redirect(array('index'));
			}
		}
		
	}
	
	public function actionIssue()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->is_issued =1;
			if($model->save())
			{
				$this->render('issue_item');
			}
		}
		$this->render('issue_item');
		
		
	}
	public function actionPurchase()
	{
		$roles = Rights::getAssignedRoles(Yii::app()->user->Id);
		if(key($roles) == 'teacher')
		{
			$batch_id =array();
		 $employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		 $batches=Batches::model()->findAll("employee_id=:x AND is_active=:y AND is_deleted=:z", array(':x'=>$employee->id,':y'=>1,':z'=>0));
		 foreach($batches as $batch)
		 {
			 $batch_id[] = $batch->id;
		 }
			$criteria 			= new CDbCriteria;		
			$criteria->order 	= 'id DESC';
			$criteria->join	 =	 'JOIN `students` AS `p` ON t.employee_id = p.uid';
			$criteria->addInCondition('p.batch_id',$batch_id);
			$students    	 =    PurchaseMaterialRequistion::model()->findAll($criteria);
			$total			 = 	  PurchaseMaterialRequistion::model()->count($criteria);
			$pages 			 = 	  new CPagination($total);
			$pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria); 
			$material_requests = PurchaseMaterialRequistion::model()->findAll($criteria);
			$this->render('student_request', array('material_requests'=>$material_requests, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
			
		}
		
	}
	public function actionRequestApprove()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->status_tchr =1;
			
			if($model->save())
			{
				$this->redirect(array('purchase'));
			}
		}
		
	}
	public function actionRequestDisapprove()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->status_tchr =2;
			if($model->save())
			{
				$this->redirect(array('purchase'));
			}
		}
		
	}
	public function actionSendRequest()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->is_send =1;
			if($model->save())
			{
				$this->redirect(array('purchase'));
			}
		}
		
	}

}
