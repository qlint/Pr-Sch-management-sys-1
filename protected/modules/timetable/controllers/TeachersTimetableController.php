<?php

class TeachersTimetableController extends RController
{
	public function beforeAction(){
		$action				= Yii::app()->controller->action->id;
		$redirect_actions	= array('index', 'fullpdf', 'fullteacherpdf');
		if(in_array(strtolower($action), $redirect_actions)){
			if(Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL)==2){ // timetable format is not selected as course level					
				$params	= array('/timetable/flexibleTeachersTimetable/'.$action);
				foreach($_REQUEST as $key=>$param){
					if($key!="r"){
						$params[$key]	= $param;
					}
				}
				$this->redirect($params);
			}
		}
		
		return true;
	}
	
	public function actionIndex()
	{
		if(isset($_REQUEST['dep_id']) and isset($_REQUEST['employee_id']) and isset($_REQUEST['day_id']))
		
			$this->render('index',array('department_id'=>$_REQUEST['dep_id'],'employee_id'=>$_REQUEST['employee_id'],'day_id'=>$_REQUEST['day_id']));
		else
			$this->render('index',array());
	}

		
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'rights', // perform access control for CRUD operations
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	public function actionEmployeename()
	{			
		$data=Employees::model()->findAll('employee_department_id=:id AND is_deleted=:y',array(':id'=>(int) $_POST['department_id'],':y'=>0));
		
		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Teacher')), true);
		echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','All Teacher')), true);
		$data=CHtml::listData($data,'id','fullname');
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
		}
	 }
	 
	 public function actionFullPdf()
     {
		
				$batch_name = ' Teacher Timetable.pdf';                        
                $filename= ' Teacher Timetable.pdf';
                Yii::app()->osPdf->generate("application.modules.timetable.views.teachersTimetable.exportpdf", $filename, array(),1);     
	}
	
	public function actionFullTeacherPdf()
     {
		//$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
				$batch_name = ' Teacher Timetable.pdf';                        
                $filename= ' Teacher Timetable.pdf';
                Yii::app()->osPdf->generate("application.modules.timetable.views.teachersTimetable.exportfullpdf", $filename, array(),1);     
                
        ////////////////////////////////////////////////////////////////////////////////////
	}
	
}