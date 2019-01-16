<?php

class ContactgroupsController extends RController
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
	/*public function actionCreate()
	{
		$model=new ContactGroups;
		$model->created_by	= Yii::app()->user->id;
		$model->created_at	= date('Y-m-d H:i:s');
		$model->status		= 1;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ContactGroups']))
		{
			$model->attributes=$_POST['ContactGroups'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}*/

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

		if(isset($_POST['ContactGroups']))
		{
			$model->attributes=$_POST['ContactGroups'];
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
		 if(Yii::app()->request->isPostRequest){
		// we only allow deletion via POST request
		if($this->loadModel($id)->delete()){
			ContactsList::model()->deleteAllByAttributes(array('group_id'=>$id));
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		$this->redirect(array('index'));
		 }
	 else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{		
		$this->registerAssets();
		$criteria = new CDbCriteria;
		$criteria->order = '`id` DESC';
		
		$total		= ContactGroups::model()->count($criteria);
		$pages		= new CPagination($total);
        $pages->setPageSize(9);
        $pages->applyLimit($criteria);  // the trick is here!
		$contactgroups 	= ContactGroups::model()->findAll($criteria);
		
		 
		$this->render('index',array(
			'contactgroups'	=> $contactgroups,
			'pages' 	=> $pages,
			'item_count'=> $total,
			'page_size'	=> 9
		)) ;
	}

	/**
	 * Manages all models.
	 */
	/*public function actionAdmin()
	{
		$model=new ContactGroups('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ContactGroups']))
			$model->attributes=$_GET['ContactGroups'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}*/
	
	public function actionContacts(){
		$response	= array('status'=>'failed');
		if(Yii::app()->request->isAjaxRequest and isset($_POST['groups']) and count($_POST['groups'])>0){			
			$groups		= $_POST['groups'];
			$criteria	= new CDbCriteria;
			$criteria->join	= 'JOIN `contacts_list` `l` ON `l`.`contact_id`=`t`.`id`';
			$criteria->addInCondition('`l`.`group_id`',$groups);
			$criteria->select	= '`first_name`, `last_name`, `mobile`';
			$criteria->distinct	= true;
			$contacts	= Contacts::model()->findAll($criteria);
			$phonenumbers	= array();
			foreach($contacts as $key=>$contact){
				$phonenumbers[$key]['name']		= $contact->fullname;
				$phonenumbers[$key]['number']	= $contact->mobile;
			}
			$response['status']	 	= 'success';
			$response['numbers']	= $phonenumbers;
		}
		echo json_encode($response);
		Yii::app()->end();
	}
	
	public function actionEmailcontacts(){
		$response	= array('status'=>'failed');
		if(Yii::app()->request->isAjaxRequest and isset($_POST['groups']) and count($_POST['groups'])>0){			
			$groups		= $_POST['groups'];
			$criteria	= new CDbCriteria;
			$criteria->join	= 'JOIN `contacts_list` `l` ON `l`.`contact_id`=`t`.`id`';
			$criteria->addInCondition('`l`.`group_id`',$groups);
			$criteria->select	= '`first_name`, `last_name`, `email`';
			$criteria->distinct	= true;
			$contacts	= Contacts::model()->findAll($criteria);
			$emails	= array();
			foreach($contacts as $key=>$contact){
				$emails[$key]['name']		= $contact->fullname;
				$emails[$key]['email']	= $contact->email;
			}
			$response['status']	 	= 'success';
			$response['emails']		= $emails;
		}
		echo json_encode($response);
		Yii::app()->end();
	}
	
	public function actionDeletegroups(){
		if(isset($_POST['groups']) and count($_POST['groups'])>0){
			$groups	= $_POST['groups'];
			foreach($groups as $group){
				if($this->loadModel($group)->delete()){
					ContactsList::model()->deleteAllByAttributes(array('group_id'=>$group));
				}
			}
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ContactGroups::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='contact-groups-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionReturnForm(){
		//Figure out if we are updating a Model or creating a new one.
		if(isset($_POST['update_id']))
			$model	= $this->loadModel($_POST['update_id']);
		else {			
			$model	= new ContactGroups;
			$model->created_by	= Yii::app()->user->id;
			$model->created_at	= date('Y-m-d H:i:s');
			$model->status		= 1;
		}
		//  Comment out the following line if you want to perform ajax validation instead of client validation.
		//  You should also set  'enableAjaxValidation'=>true and
		//  comment  'enableClientValidation'=>true  in CActiveForm instantiation ( _ajax_form  file).
			
		//$this->performAjaxValidation($model);
		
		//don't reload these scripts or they will mess up the page
		//yiiactiveform.js still needs to be loaded that's why we don't use
		// Yii::app()->clientScript->scriptMap['*.js'] = false;
		$cs	= Yii::app()->clientScript;
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
	
	  
	public function actionAjax_Create(){	
		if(isset($_POST['ContactGroups']))
		{
			$model	= new ContactGroups;
			$model->created_by	= Yii::app()->user->id;
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));			
			$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
       		date_default_timezone_set($timezone->timezone);
			$model->created_at	= date('Y-m-d H:i:s');
			$model->status		= 1;
			//set the submitted values
			$model->attributes	= $_POST['ContactGroups'];
			//return the JSON result to provide feedback.
			if($model->save(false)){
				echo json_encode(array('success'=>true,'id'=>$model->primaryKey) );
				exit;
			}
			else{
				echo json_encode(array('success'=>false));
				exit;
			}
		}
	}
	
	public function actionAjax_Update(){
		if(isset($_POST['ContactGroups']))
		{
			$model	= $this->loadModel($_POST['update_id']);
			$model->attributes	= $_POST['ContactGroups'];
			if( $model->save(false)){
				echo json_encode(array('success'=>true));
				exit;
			}
			else{
				echo json_encode(array('success'=>false));
				exit;
			}
		}	
	}
	
	private function registerAssets(){
        //Yii::app()->clientScript->registerCoreScript('jquery');
		
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
}
