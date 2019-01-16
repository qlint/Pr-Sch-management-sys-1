<?php

class PaymentTypesController extends RController
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new FeePaymentTypes;
		$model->created_by	= Yii::app()->user->id;
		$model->created_at	= date("Y-m-d H:i:s");

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FeePaymentTypes']))
		{
			$model->attributes=$_POST['FeePaymentTypes'];
			
			if($model->save())
				$this->redirect(array('admin'));
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
		if($model->is_editable){	
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);
	
			if(isset($_POST['FeePaymentTypes']))
			{
				$model->attributes=$_POST['FeePaymentTypes'];
				$model->created_by	= Yii::app()->user->id;
				if($model->save())
					$this->redirect(array('admin'));
			}
	
			$this->render('update',array(
				'model'=>$model,
			));
		}
		else{
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
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
			$model	= $this->loadModel($id);
			if($model->is_editable){
				$model->delete();
				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if(!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
			else{
				throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
			}
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$criteria	= new CDbCriteria;
		$criteria->condition	= '`is_gateway`=:is_gateway';
		$criteria->params		= array(':is_gateway'=>0);
		$types=FeePaymentTypes::model()->findAll($criteria);
		$this->render('admin',array(
			'types'=>$types,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=FeePaymentTypes::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='fee-payment-types-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
