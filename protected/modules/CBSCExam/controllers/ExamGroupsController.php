<?php

class ExamGroupsController extends RController
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
			'accessControl', // perform access control for CRUD operations
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
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'returnForm'),
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
		$model=new CbscExamGroups;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CbscExamGroups']))
		{
			$model->attributes=$_POST['CbscExamGroups'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['CbscExamGroups']))
		{
			$model->attributes=$_POST['CbscExamGroups'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	private function registerAssets(){

            Yii::app()->clientScript->registerCoreScript('jquery');

         //IMPORTANT about Fancybox.You can use the newest 2.0 version or the old one
        //If you use the new one,as below,you can use it for free only for your personal non-commercial site.For more info see
		//If you decide to switch back to fancybox 1 you must do a search and replace in index view file for "beforeClose" and replace with 
		//"onClosed"
        // http://fancyapps.com/fancybox/#license
          // FancyBox2
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.css', 'screen');
         // FancyBox
         //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.js', CClientScript::POS_HEAD);
         // Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.css','screen');
        //JQueryUI (for delete confirmation  dialog)
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/js/jquery-ui-1.8.12.custom.min.js', CClientScript::POS_HEAD);
         Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/css/dark-hive/jquery-ui-1.8.12.custom.css','screen');
          ///JSON2JS
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/json2/json2.js');
       

           //jqueryform js
               Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/jquery.form.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/form_ajax_binding.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/client_val_form.css','screen');

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
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('CbscExamGroups');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CbscExamGroups('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CbscExamGroups']))
			$model->attributes=$_GET['CbscExamGroups'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CbscExamGroups::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionAjax_Create()
	{
		if(isset($_POST['CbscExamGroups'])){
			$model = new CbscExamGroups;			
			$model->attributes = $_POST['CbscExamGroups'];
			$model->date = date('Y-m-d',strtotime($model->date));
						                       
			if($model->save(false)){															
				ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'11',$model->id,ucfirst($model->name),NULL,NULL,NULL); 
							
				// Send SMS if saved
				$notification = NotificationSettings::model()->findByAttributes(array('id'=>6));
				$college=Configurations::model()->findByPk(1);
				$to = '';
				// Send SMS,mail,message only if, SMS or mail or message is enabled and schedule is published
				if($model->date_published=='1'){ 
					$students=Students::model()->findAll("batch_id=:x and is_deleted=:y and is_active=:z", array(':x'=>$model->batch_id,':y'=>0,':z'=>1)); 
					
					$reg_arr = array();//device ids of all students in batch
					$argument_arr_1 = array();
					
					foreach($students as $student){ 
						if($student->phone1){ // Checking if phone number is provided
							$to = $student->phone1;	
						}
						elseif($student->phone2){
							$to = $student->phone2;
						}
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						if($settings!=NULL){	
							$model->date=date($settings->displaydate,strtotime($model->date));				
						}
						
						//SMS	
						if($to!='' and $notification->sms_enabled=='1' and $notification->student == '1'){ // Sending SMS to each student											
							$from		= $college->config_value;
							$template	= SystemTemplates::model()->findByPk(8);
							$message 	= $template->template;
							$message 	= str_replace("<Exam Name>",$model->name,$message);
							
							SmsSettings::model()->sendSms($to,$from,$message);
						}
						
						//create message to app device...
						if(Configurations::model()->isAndroidEnabled()){											
							$reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$student->uid));
							foreach($reg_ids as $reg_id){
								$reg_arr[] = $reg_id->device_id; 
							}
							$template	= SystemTemplates::model()->findByPk(8);
							$message 	= $template->template;
							$message 	= str_replace("<Exam Name>",$model->name,$message);
							$argument_arr_1 = array('message'=>$message,'device_id'=>$reg_arr);		
						}
						
						//Mail
						if($notification->mail_enabled == '1' and $notification->student == '1'){
							$template	= EmailTemplates::model()->findByPk(6);											
							$subject 	= $template->subject;
							$message 	= $template->template;
							$subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
							$subject 	= str_replace("{{EXAM NAME}}",$model->name,$subject);											
							$message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
							$message 	= str_replace("{{EXAM NAME}}",$model->name,$message);
							$message 	= str_replace("{{EXAM DATE}}",$model->exam_date,$message);
							
							if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){
								UserModule::sendMail($student->email,$subject,$message);
							}						
						}	
						
						//message
						if($schedule_notification->msg_enabled == '1' and $schedule_notification->student == '1'){											
							$subject = $model->name.Yii::t('app',' is scheduled');
							$message = Yii::t('app','Hi, ').$model->name.Yii::t('app',' exam is scheduled on ').$model->exam_date;
							
							NotificationSettings::model()->sendMessage($student->uid,$subject,$message);						
						}	
					}
					if(Configurations::model()->isAndroidEnabled()){	
						Configurations::model()->devicenotice($argument_arr_1,"Exams","exams");
					}
				}	
				echo json_encode(array('success'=>true,'id'=>$model->primaryKey) );
				exit;
			} 
			else{	
				echo json_encode(array('success'=>false));
				exit;
			}
		}
	}
	
	 public function actionReturnView(){

               //don't reload these scripts or they will mess up the page
                //yiiactiveform.js still needs to be loaded that's why we don't use
                // Yii::app()->clientScript->scriptMap['*.js'] = false;
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                                                 'jquery.min.js'=>false,
                                                 'jquery.js'=>false,
                                                 'jquery.fancybox-1.3.4.js'=>false,
                                                 'jquery.fancybox.js'=>false,
                                                 'jquery-ui-1.8.12.custom.min.js'=>false,
                                                 'json2.js'=>false,
                                                 'jquery.form.js'=>false,
                                                'form_ajax_binding.js'=>false
        );

        $model=$this->loadModel($_POST['id']);
        $this->renderPartial('view',array('model'=>$model),false, true);
      }
	
	 public function actionReturnForm(){

              //Figure out if we are updating a Model or creating a new one.
             if(isset($_POST['update_id'])){
				$model= $this->loadModel($_POST['update_id']);
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL){	
					$model->exam_date=date($settings->displaydate,strtotime($model->exam_date));
				}
			 }
			 else $model=new CbscExamGroups;
           
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                                                 'jquery.min.js'=>false,
                                                 'jquery.js'=>false,
                                                 'jquery.fancybox-1.3.4.js'=>false,
                                                 'jquery.fancybox.js'=>false,
                                                 'jquery-ui-1.8.12.custom.min.js'=>false,
                                                 'json2.js'=>false,
                                                 'jquery.form.js'=>false,
                                                 'form_ajax_binding.js'=>false
        );


        $this->renderPartial('_ajax_form', array('model'=>$model), false, true);
      }

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cbsc-exam-groups-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
