<?php

class EmployeePayslipController extends RController
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
				'actions'=>array('index','view','pdf','Remove','CheckRecord','CheckDepartment'),
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
		$model=new EmployeePayslip;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['EmployeePayslip']))
		{
			$model->attributes=$_POST['EmployeePayslip'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	
	 
	 public function actionAdmin()
	 {
		 
		
		$criteria = new CDbCriteria;
		$criteria->compare('is_deleted',0);
		if(isset($_REQUEST['deptid']) && $_REQUEST['deptid']!='0')
		{
			$criteria->compare('employee_department_id',$_REQUEST['deptid']);
		}
		$model = Employees::model()->findAll($criteria);
		
		 
		$this->render('admin',array('model'=>$model
		));
	 }
	 
	 
	 
	public function actionPdf()
    {
		
			
	 	$html2pdf = Yii::app()->ePdf->HTML2PDF();
		$html2pdf->WriteHTML($this->renderPartial('print', array('model'=>$this->loadModel($_REQUEST['id'])), true));
        $html2pdf->Output($employee);
			
			
		
	}
	public function actionPrintpdf()
    {
		
			
	 	$html2pdf = Yii::app()->ePdf->HTML2PDF();
		$html2pdf->WriteHTML($this->renderPartial('print', array(), true));
        $html2pdf->Output();
			
			
		
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

		if(isset($_POST['EmployeePayslip']))
		{
			$model->attributes=$_POST['EmployeePayslip'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		$criteria = new CDbCriteria;
		$criteria->condition = 'employee_id=:val_1 AND DATE_FORMAT(salary_date,"%Y-%m")=:val_2';
		$criteria->params = array(':val_1'=>$id,':val_2'=>date('Y-m'));
		MonthlyPayslips::model()->deleteAll($criteria);
		$this->redirect(array('admin','deptid'=>$_REQUEST['deptid']));
	}
	public function actionApprove($id)
	{
		$employee=Employees::model()->findByPk($id);
		$amount=($employee->basic_pay + $employee->HRA + $employee->DA + $employee->others1)-($employee->TDS + $employee->PF + $employee->others2);
		$model=new MonthlyPayslips;
		if(isset($_REQUEST['salarydate']))
		{
			$model->salary_date=$_REQUEST['salarydate'];
		}else
		{
			$model->salary_date=date('Y-m-d');
		}
		$model->employee_id=$id;
		$model->amount=$amount;
		$model->is_approved=1;
		$model->approver_id=Yii::app()->user->id;
		$model->save();
		$this->redirect(array('admin','deptid'=>$_REQUEST['deptid']));
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('EmployeePayslip');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionCheckRecord(){
		$ret = array(
			     'employee'=>'0'
			     );
				 
		$model = new Employees;
		$exist = $model->find(array('condition'=>"id=:emp_id ",'params'=>array(':emp_id'=>$_POST['id'])));
		$record = '0';
		if($exist!=null){
		$record = $exist;
		}
		echo CJSON::encode($ret = array(
				'employee' => $record,
				    ));
	}
	
	public function actionCheckDepartment(){
		$ret = array(
			     'list'=>'0'
			     );
		$record = '0';
		
		$model = new Employees;
		$data = $model->findAll(array('condition'=>"employee_department_id=:dep_id ",'params'=>array(':dep_id'=>$_POST['dept'])));
		$options[] = 'Select';
        $options = array_merge($options, CHtml::listData($data,'id','first_name'));
        $htmlOptions = array();
        $options = CHtml::listOptions('', $options, $htmlOptions);
		 
		echo CJSON::encode($ret = array(
				'list' => $options,
				    ));
	}

	/**
	 * Manages all models.
	 */
	/*public function actionAdmin()
	{
		$model=new EmployeePayslip('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['EmployeePayslip']))
			$model->attributes=$_GET['EmployeePayslip'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
*/
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=EmployeePayslip::model()->findByPk($id);
		
		if($model==null)
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		
			return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='employee-payslip-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}
