<?php

class PayslipController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex()
	{
		$model	=	new Staff; 
		$criteria	=	new CDbCriteria;
		$criteria->condition 	=	'is_deleted=0';
		
		if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL)
		{
			if((substr_count( $_REQUEST['name'],' '))==0)
			{ 	
				 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
				 $criteria->params[':name'] = $_REQUEST['name'].'%';
			}
			else if((substr_count( $_REQUEST['name'],' '))>=1)
			{
				 $name=explode(" ",$_REQUEST['name']);
				 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
				 $criteria->params[':name'] = $name[0].'%';
				 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name1 or last_name LIKE :name1 or middle_name LIKE :name1)';
				 $criteria->params[':name1'] = $name[1].'%';
			}
		}	
		
		if(isset($_REQUEST['employee_number']) and $_REQUEST['employee_number']!=NULL)
		{
			$criteria->condition=$criteria->condition.' and '.'employee_number LIKE :employee_number';
			$criteria->params[':employee_number'] = $_REQUEST['employee_number'].'%';
		}
		
		if(isset($_REQUEST['Staff']['gender']) and $_REQUEST['Staff']['gender']!=NULL)
		{
			$model->gender = $_REQUEST['Staff']['gender'];
			$criteria->condition=$criteria->condition.' and '.' `t`.gender LIKE :gender';
		    $criteria->params[':gender'] = $_REQUEST['Staff']['gender']."%";
		}
		
		$total = Staff::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(25);
		$pages->applyLimit($criteria);  // the trick is here
		$list		=	Staff::model()->findAll($criteria);
		$this->render('index',array(
		'list'=>$list,
		'model'=>$model,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>25)) ;
	}
	
	public function actionGenerate($id){
		$employee	=	Staff::model()->findByPk($id);
		$model		=	new SalaryDetails;
		if(isset($_POST['SalaryDetails'])){
			$model->attributes 	=	$_POST['SalaryDetails'];
			$model->created_at 	=	date('Y-m-d H:i:s');
			
			if($model->validate()){
				$model->salary_date =	date('Y-m-d',strtotime($model->salary_date));
				if($model->save()){
					$student 	= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
					if(!is_dir('payslips/')) 
					{
						mkdir('payslips/');
					}
					$pathname 	= 	Yii::getPathOfAlias('webroot')."/payslips/".$model->id.'_'.$model->salary_date.'.pdf';
					$filename	=	$model->id.'_'.$model->salary_date.'.pdf';	
					$salary		=	SalaryDetails::model()->findByPk($model->id);	
					Yii::app()->osPdf->generate("application.modules.hr.views.payslip.pdf", $pathname, array('model'=>$salary), "", "F");
					$model->saveAttributes(array("filename"=>$filename));
					$subject	=	'Pay Slip for the month '.date('M.Y', strtotime($model->salary_date));
					$message	=	'Please Find the Attachment';
					$attachement=	"payslips/".$model->id.'_'.$model->salary_date.'.pdf';
					UserModule::sendMailA($employee->email,$subject,$message,"text",$attachement);
					if($model->hike>0){
						$basic_pay	=	$employee->basic_pay + $model->hike;
						$employee->saveAttributes(array("basic_pay"=>$basic_pay));
					}
					Yii::app()->user->setFlash('successMessage',Yii::t('app',"Payslip generated successfully!!!"));
					$this->redirect(array("index"));
				}
			}
		}
		$this->render('generate',array('employee'=>$employee,"model"=>$model));
	}
	
	public function actionPayslips($id){
		$criteria	=	new CDbCriteria;
		$criteria->condition 	=	'employee_id=:eid';
		$criteria->params 		=	array(":eid"=>$id);
		
		$total = SalaryDetails::model()->count($criteria);
		
		$pages = new CPagination($total);
		$pages->setPageSize(25);
		$pages->applyLimit($criteria);  // the trick is here
		$list		=	SalaryDetails::model()->findAll($criteria);
		
		$this->render('payslips',array(
		'list'=>$list,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>25,)) ;
	}
	
	public function actionDownload($id){
		$payslip	=	SalaryDetails::model()->findByPk($id);
		$pathname 	= 	Yii::getPathOfAlias('webroot')."/payslips/".$payslip->filename;	
		Yii::app()->getRequest()->sendFile($payslip->filename, file_get_contents($pathname));
	}
	
	public function actionReport(){
		$model		=	new SalaryDetails;
		$criteria	=	new CDbCriteria;
		$criteria->condition 	=	'`t`.employee_id <> 0';	
		$criteria->join 		=	' LEFT JOIN `employees` `e` ON `e`.id=`t`.employee_id';
		$criteria->order 		=	'salary_date DESC';
		if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL)
		{
			if((substr_count( $_REQUEST['name'],' '))==0)
			{ 	
				 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
				 $criteria->params[':name'] = $_REQUEST['name'].'%';
			}
			else if((substr_count( $_REQUEST['name'],' '))>=1)
			{
				 $name=explode(" ",$_REQUEST['name']);
				 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
				 $criteria->params[':name'] = $name[0].'%';
				 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name1 or last_name LIKE :name1 or middle_name LIKE :name1)';
				 $criteria->params[':name1'] = $name[1].'%';
			}
		}	
		
		if(isset($_REQUEST['employee_number']) and $_REQUEST['employee_number']!=NULL)
		{
			$criteria->condition=$criteria->condition.' and '.'employee_number LIKE :employee_number';
			$criteria->params[':employee_number'] = $_REQUEST['employee_number'].'%';
		}
		
		if(isset($_REQUEST['SalaryDetails']['salary_date']) and $_REQUEST['SalaryDetails']['salary_date']!=NULL)
		{
			$model->salary_date = $_REQUEST['SalaryDetails']['salary_date'];
			$criteria->condition=$criteria->condition.' and '.' `t`.salary_date LIKE :salary_date';
		    $criteria->params[':salary_date'] = "%-".$_REQUEST['SalaryDetails']['salary_date']."-%";
		}
		
		$total = SalaryDetails::model()->count($criteria);
		
		$pages = new CPagination($total);
		$pages->setPageSize(25);
		$pages->applyLimit($criteria);  // the trick is here
		$list		=	SalaryDetails::model()->findAll($criteria);
		
		if(isset($_GET['print'])){
			
			Yii::app()->osPdf->generate("application.modules.hr.views.payslip.reportpdf", 'report.pdf', array('list'=>$list), 1, "I");
		}
		
		$this->render('report',array(
		'list'=>$list,
		'model'=>$model,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>25,)) ;
	}
}