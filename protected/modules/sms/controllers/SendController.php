<?php

class SendController extends RController
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('send','update'),
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
	/*public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}*/

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionSendtogroup()
	{
		$model=new Sms;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Sms']))
		{
			$model->attributes=$_POST['Sms'];
			if($model->validate())
			{
				
				
				if($model->uid == 'parent')
				{
					$userlist = Guardians::model()->findAll() ;
				}
				
				if($model->uid == 'teacher')
				{
					$userlist = Employees::model()->findAll() ;
				}
				
					if($userlist!=NULL)
					{
						 
					  foreach($userlist as $user)
					  {
						  if($user->mobile_phone)
						  {
							$status = SmsSettings::model()->sendSms($user->mobile_phone,0,$model->message) ;
							$mobile_phone = $user->mobile_phone ;
						  }
						  else
						  {
							  $status = 0 ;
							  $mobile_phone = 0 ;
						  }
						  
						  $Sms=new Sms;
						  $Sms->uid = $user->uid ;
						  $Sms->phone_number = $mobile_phone ; 
						  $Sms->message = $model->message ;
						  $Sms->status = $status ;
						  $Sms->save();
						  
					  }
						 
						  
					}
				
				
				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	
	
	public function actionIndex()
	{
		if(Yii::app()->request->isAjaxRequest){
			$response	= array('status'=>'failed');
			if(isset($_POST['sms'])){
				if(isset($_POST['message']) and $_POST['message']!=""){
					$message	= $_POST['message'];				
					if(isset($_POST['recipients']) and $_POST['recipients']!=""){
						$recipients	= $_POST['recipients'];						
						$recipients_data	= explode(',', $recipients);
						$check_num = array();
						foreach($recipients_data as $recipient_data){
							$parts	= explode(':', $recipient_data);
							if(count($parts)>0){
								$name				= (count($parts)>1)?$parts[0]:NULL;
								$phone_number		= end($parts);
								//$hello			= '';
								//$hello			.=	($name==NULL)?"Hi,":"Hi ".$name.",";
								if(!in_array($check_num))
								$status 			= SmsSettings::model()->sendSms($phone_number, 0, $message);
								//$status 			= 1;
								$Sms				= new Sms;
								$Sms->phone_number 	= $phone_number; 
								$Sms->message 		= $message;
								$Sms->status 		= $status;
								$Sms->save();
								$check_num[] 		= $phone_number;
							}
						}
						$response['status']		= 'success';
						$response['message']	= 'Message sent';
					}
					else{
						$response['message']	= 'Enter numbers to send SMS';
					}
				}
				else{
					$response['message']	= 'Enter message to send SMS';
				}				
			}
			else{
				$response['message']	= 'Invalid request';
			}
			echo json_encode($response);
			Yii::app()->end();
		}
		else{
			//registering js files
			Yii::app()->clientScript->registerScriptFile(
				Yii::app()->assetManager->publish(
					Yii::getPathOfAlias('application.modules.sms.assets') . '/js/ajaxupload/ajaxupload.js'
				)
			);
		
			Yii::app()->clientScript->registerScript('browseActionPath', 'var browseActionPath="' . $this->createUrl('/sms/send/browse') . '"', CClientScript::POS_BEGIN);
		
			Yii::app()->clientScript->registerScriptFile(
				Yii::app()->assetManager->publish(
					Yii::getPathOfAlias('application.modules.sms.assets') . '/js/ajaxupload/download.js'
				)
			);
			
			$this->render('send');
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	 
	/*public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Sms']))
		{
			$model->attributes=$_POST['Sms'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}*/

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
			throw new CHttpException(400, Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}


	/**
	 * Manages all models.
	 */
	/*public function actionAdmin()
	{
		$model=new Sms('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Sms']))
			$model->attributes=$_GET['Sms'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}*/
	
	public function actionBrowse(){
		$response	= array("status"=>"failed");
		$filename	= explode(".", $_FILES['myfile']['name']);
		$fname		= current( $filename );
		$extension	= end( $filename );
		$phonenumbers	= array();
		
		if($extension == "xls"){	// or $extension == "xlsx"
			Yii::import('application.extensions.ExcelReader.*');
			require_once('excel_reader.php');     // include the class
			$path	= $_FILES['myfile']['tmp_name'];

			// creates an object instance of the class, and read the excel file data
			$excel = new PhpExcelReader;
			$excel->read($path);
			
			$nr_sheets 	= count($excel->sheets);       // gets the number of sheets
			if($nr_sheets>0){				
				// traverses the number of sheets and sets html table with each sheet data in $excel_data
				$sheet	= $excel->sheets[0];				
				$rows	= $sheet['numRows'];
				$cols	= $sheet['numCols'];
				if($rows>1){
					$fields	= array();
					$x 		= 1;
					$y		= 1;
					while( $y<=$cols ){
						$fields[$y - 1]	= isset($sheet['cells'][$x][$y]) ? str_replace("\s","",$sheet['cells'][$x][$y]) : '';
						$y++;
					}
					$nameindex		= $this->array_search2d("name", $fields);
					$nameindex		= ( $nameindex === false )?false:( $nameindex + 1 );
					$numberindex	= $this->array_search2d("number", $fields);
					$numberindex	= ( $numberindex === false )?false:( $numberindex + 1 );
					
					if( $numberindex === false ){
						$response["message"]	= Yii::t('app', "Excel file must have a field")." `number`";
					}
					else{
						$x++;						
						while($x <= $rows) {					
							if( $nameindex !== false )
								$phonenumbers[$x - 1]["name"]	= isset($sheet['cells'][$x][$nameindex]) ? $sheet['cells'][$x][$nameindex] : '';
							$phonenumbers[$x - 1]["number"]	= isset($sheet['cells'][$x][$numberindex]) ? $sheet['cells'][$x][$numberindex] : '';
							$x++;
						}			
						$response["status"]		= "success";
						$response["numbers"]	= $phonenumbers;
					}
				}
				else{
					$response["message"]	= Yii::t('app', "No data found");
				}
			}
			else{
				$response["message"]	= Yii::t('app', "No data found");
			}
		}	
		else if ($extension == "csv") {
			$contents	= file_get_contents( $_FILES['myfile']['tmp_name'] );			
			$datas 		= array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $contents));
			
			$start		= 0;
			if(isset($datas[$start])){
				$nameindex		= $this->array_search2d("name", $datas[$start]); //array_search('name', str_replace("\s","",$datas[$start]));
				$numberindex	= $this->array_search2d("number", $datas[$start]); //array_search('number', $datas[$start]);
				
				//echo $numberindex;exit;
				
				if( $numberindex=== false ){
					$response["message"]	= Yii::t('app', "CSV file must have a field")." `number`";					
				}
				else{
					$start++;
					while( $start < count( $datas ) ){
						if( $nameindex )
							$phonenumbers[$start - 1]["name"]	= $datas[$start][$nameindex];
						$phonenumbers[$start - 1]["number"]	= $datas[$start][$numberindex];
						$start++;
					}
					$response["status"]		= "success";
					$response["numbers"]	= $phonenumbers;
				}
			}
			else{
				$response["message"]	= Yii::t('app', "No datas found");
			}
		}
		else {
			$response["message"]	= Yii::t('app', "Please upload a"). " csv / .xls ".Yii::t('app', "file");
		}
		
		echo json_encode($response);
		Yii::app()->end();
	}
	
	public function actionMobile(){
		$this->render('mobile');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Sms::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sms-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function array_search2d($needle, $haystack) {
		for ($i = 0, $l = count($haystack); $i < $l; ++$i) {
			if ($needle==$haystack[$i]) return $i;
		}
		return false;
	}
}
