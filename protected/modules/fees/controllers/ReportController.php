<?php
class ReportController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','reportpdf'),
				'users'=>array('*'),
			),
			
		);
	}
	public function actionIndex(){
		if($_POST['submit_button_form'] and $_POST['submit_button_form']!=NULL){
			$start_date 	=	date("Y-m-d",strtotime($_REQUEST['star_date']));
			$end_date		=	date("Y-m-d",strtotime($_REQUEST['end_date']));
				$criteria=new CDbCriteria;
				$criteria->condition	= 'is_deleted = 0 AND status = 1';        
				$criteria->addBetweenCondition("date",$start_date,$end_date,'AND');
				$model   = FeeTransactions::model()-> findAll($criteria);
				$this->render('index',array('model'=>$model,'start_date'=>$start_date,'end_date'=>$end_date));
		}
		else
		{
			$this->render('index');
		}
	}
	public function actionReportPdf()
    {        
		$start_date 	=	date("Y-m-d",strtotime($_REQUEST['startdate']));
		$end_date		=	date("Y-m-d",strtotime($_REQUEST['enddate']));
		if($start_date!=NULL){
			if($end_date >= $start_date){
				$criteria=new CDbCriteria;
				$criteria->condition	= 'is_deleted = 0 AND status = 1';        
				$criteria->addBetweenCondition("date",$start_date,$end_date,'AND');
				$model   = FeeTransactions::model()-> findAll($criteria);
				$filename	= 'Fees Collction Report';
					Yii::app()->osPdf->generate("application.modules.fees.views.report.reportpdf", $filename, array('model'=>$model,'start_date'=>$start_date,'end_date'=>$end_date), 1);
				}
				else{
					Yii::app()->user->setFlash('notification',Yii::t('app','Mention the End Date correctly!'));
				}
		}
		else
		{
			Yii::app()->user->setFlash('notification',Yii::t('app','Enter Start Date!'));
		}
		 
	}
	 public function actionExcel()
        {
            if(isset($_GET['from_date']) and $_GET['from_date']!=NULL)
            {
				$start_date 	=	date("Y-m-d",strtotime($_REQUEST['from_date']));
				$end_date		=	date("Y-m-d",strtotime($_REQUEST['to_date']));
				$criteria=new CDbCriteria;
				$criteria->condition	= 'is_deleted = 0 AND status = 1';        
				$criteria->addBetweenCondition("date",$start_date,$end_date,'AND');
				$model   = FeeTransactions::model()-> findAll($criteria); 
				//var_dump($model);exit;                   
                Yii::app()->request->sendFile('Daily Collection Report.xls',
                            $this->renderPartial('reportExcel',array('model'=>$model,'start_date'=>$start_date,'end_date'=>$end_date),true)
                    );													
			}
        }
	public function actionDueReport(){
		if($_POST['submit'] and $_POST['submit']!=NULL)
		{ 
			$course_id   = $_REQUEST['course_id'];
			$batch_id	 = $_REQUEST['batch_id'];
			$students    = Students::model()->findAllByAttributes(array('batch_id'=>$batch_id,'is_active'=>'1','is_deleted'=>'0'));
			$this->render('duereport',array('model'=>$students,'batch_id'=>$batch_id,'course_id'=>$course_id));
		}
		else
		{
		$this->render('duereport');
		}
	}
	public function actionReportCourse()
	{		
		if($_REQUEST['course'])
		{   
		
		$data=Batches::model()->findAll('is_active=:x AND is_deleted=:y AND course_id=:z',array(':x'=>1,':y'=>0,':z'=>$_REQUEST['course']));
		}
		echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select Batch')), true);
		$data=CHtml::listData($data,'id','name');
		
		foreach($data as $value=>$name)
		{
		echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
		}		
	}
	public function actionReportDuePdf()
    {        
		$course_id   = $_REQUEST['course_id'];
		$batch_id	 = $_REQUEST['batch_id'];
		$students    = Students::model()->findAllByAttributes(array('batch_id'=>$batch_id,'is_active'=>'1','is_deleted'=>'0'));
		$filename	= 'Fees Collction Report';
		Yii::app()->osPdf->generate("application.modules.fees.views.report.duereportpdf", $filename, array('model'=>$students,'batch_id'=>$batch_id,'course_id'=>$course_id), 1);
	}
	public function actionDueExcel()
        {
            if(isset($_GET['batch_id']) and $_GET['batch_id']!=NULL)
            {
				$course_id   = $_REQUEST['course_id'];
				$batch_id	 = $_REQUEST['batch_id'];
				$students    = Students::model()->findAllByAttributes(array('batch_id'=>$batch_id,'is_active'=>'1','is_deleted'=>'0'));
                Yii::app()->request->sendFile('Due Report.xls',
                            $this->renderPartial('duereportExcel',array('model'=>$students,'course_id'=>$course_id,'batch_id'=>$batch_id),true)
                    );													
			}
        }
	
 }
