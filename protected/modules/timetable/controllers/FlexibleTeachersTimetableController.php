<?php

class FlexibleTeachersTimetableController extends RController
{
	public function beforeAction(){
		$action				= Yii::app()->controller->action->id;
		$redirect_actions	= array('index', 'fullpdf', 'fullteacherpdf');
		if(in_array(strtolower($action), $redirect_actions)){
			if(Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL)==1){ // timetable format is not selected as course level					
				$params	= array('/timetable/teachersTimetable/'.$action);
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
		if(isset($_REQUEST['department_id']) and isset($_REQUEST['employee_id']) and isset($_REQUEST['day_id']))
		
			$this->render('index',array('department_id'=>$_REQUEST['department_id'],'employee_id'=>$_REQUEST['employee_id'],'day_id'=>$_REQUEST['day_id']));
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
		if($_REQUEST['department_id']!=NULL and $_REQUEST['department_id'] != '0'){			
			$data=Employees::model()->findAll('employee_department_id=:id AND is_deleted=:y',array(':id'=>(int) $_POST['department_id'],':y'=>0));
			if($data!=NULL){
				echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Teacher')), true);
				echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','All Teacher')), true);
				$data=CHtml::listData($data,'id','fullname');
			}
			else{
				echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Teacher')), true);
			}
		}
		else
		{
			$data='';
			echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Teacher')), true);
			echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','All Teacher')), true);
			$data=CHtml::listData($data,'id','fullname');
		}
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
		}
	 }
	 
    public function actionFullPdf()
    {
        //$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
        $batch_name = ' Teacher Timetable.pdf';
        /*
        # HTML2PDF has very similar syntax		
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->setDefaultFont('freesans');
        $html2pdf->WriteHTML($this->renderPartial('exportpdf', array(), true));
        $html2pdf->Output($batch_name);
        */
        Yii::app()->osPdf->generate("application.modules.timetable.views.teachersTimetable.exportpdf", $batch_name, array(),1);
        ////////////////////////////////////////////////////////////////////////////////////
    }
	
    public function actionFullTeacherPdf()
    {
        //$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
        $batch_name = ' Teacher Timetable.pdf';
		
		if(isset($_REQUEST['format']) and $_REQUEST['format']=="cal"){
       		Yii::app()->osPdf->generate("application.modules.timetable.views.flexibleTeachersTimetable.exportfullpdf", $batch_name, array(), 0, "", "A4", 5, 5, 5, 5);
		}
		else{
        	Yii::app()->osPdf->generate("application.modules.timetable.views.flexibleTeachersTimetable.exportfulltblpdf", $batch_name, array());
		}
       
        
        ////////////////////////////////////////////////////////////////////////////////////
    }
	
}