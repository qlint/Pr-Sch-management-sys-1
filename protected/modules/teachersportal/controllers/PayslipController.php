<?php

class PayslipController extends RController
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
	
	public function actionIndex(){
		$employee				= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		if($employee!=NULL){
			$criteria				= new CDbCriteria;
			$criteria->condition 	= 'employee_id=:eid';
			$criteria->params 		= array(":eid"=>$employee->id);
			
			$total 		= SalaryDetails::model()->count($criteria);			
			$page_size	= 20;
			$pages 		= new CPagination($total);
			$pages->setPageSize($page_size);
			$pages->applyLimit($criteria);  // the trick is here
			$payslips	= SalaryDetails::model()->findAll($criteria);
			
			$this->render('index',array(
				'payslips'=> $payslips,
				'pages' => $pages,
				'item_count' => $total,
				'page_size' => $page_size
			));
		}
	}
	
	public function actionDownload($id){
		$payslip	=	SalaryDetails::model()->findByPk($id);
		$employee	= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		if($employee!=NULL and $employee->id==$payslip->employee_id){
			$pathname 	= 	Yii::getPathOfAlias('webroot')."/payslips/".$payslip->filename;	
			Yii::app()->getRequest()->sendFile($payslip->filename, file_get_contents($pathname));
		}
		else
			throw new CHttpException(404, Yii::t('app', 'You are not allowed to access this document.'));
	}
}
