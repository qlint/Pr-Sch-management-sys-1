<?php

class ContactsController extends RController
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
	public function actionCreate()
	{		
		$model=new Contacts;
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));			
		$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
		date_default_timezone_set($timezone->timezone);	
		$model->created_by	= Yii::app()->user->id;
		$model->created_at	= date('Y-m-d H:i:s');
		$model->status		= 1;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Contacts']))
		{
			$model->attributes=$_POST['Contacts'];
			if($model->save()){
				if(isset($model->group) and count($model->group)>0){
					foreach($model->group as $group){
						$list	= new ContactsList;
						$list->contact_id	= $model->id;
						$list->group_id		= $group;
						$list->save();
					}
				}
				$this->redirect(array('index'));
			}
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
		
		$criteria				= new CDbCriteria;
		$criteria->condition	= '`contact_id`=:contact_id';
		$criteria->params		= array(':contact_id'=>$id);
		$criteria->select		= '`group_id`';
		$lists					= ContactsList::model()->findAll($criteria);
		
		$currentgroups	= array();
		foreach($lists as $list){
			$currentgroups[]	= $list->group_id;
		}
		
		$model->group		= $currentgroups;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Contacts']))
		{
			$model->attributes=$_POST['Contacts'];
			if($model->save()){
				$newgroups	= array();
				if(isset($_POST['groups']) and count($_POST['groups'])>0){
					$newgroups	= $model->group;
					foreach($newgroups as $group){
						if(!in_array($group, $currentgroups)){
							$list	= new ContactsList;
							$list->contact_id	= $model->id;
							$list->group_id		= $group;
							$list->save();
						}
					}
				}
				
				$removegroups	= array_diff($currentgroups, $newgroups);
								
				$criteria		= new CDbCriteria;
				$criteria->condition	= '`contact_id`=:contact_id';
				$criteria->params		= array(':contact_id'=>$model->id);
				$criteria->addInCondition('`group_id`', $removegroups);
				ContactsList::model()->deleteAll($criteria);
				
				$this->redirect(array('index'));
			}
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
				ContactsList::model()->deleteAllByAttributes(array('contact_id'=>$id));
		}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			$this->redirect(array('index'));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	
	public function actionDeletecontacts()
	{
		if(Yii::app()->request->isPostRequest){
			if(isset($_POST['contacts']) and count($_POST['contacts'])>0){
				$contacts	= $_POST['contacts'];
				foreach($contacts as $contact){
					if($this->loadModel($contact)->delete()){
						ContactsList::model()->deleteAllByAttributes(array('contact_id'=>$contact));
					}
				}
			}
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	
	public function actionAddtogroups()
	{
		if(Yii::app()->request->isPostRequest){
		if(isset($_POST['contacts']) and count($_POST['contacts'])>0 and isset($_POST['groups']) and count($_POST['groups'])>0){
			$contacts	= $_POST['contacts'];
			$groups		= $_POST['groups'];
			foreach($contacts as $contact){
				foreach($groups as $group){
					$alreadyinlist	= ContactsList::model()->findAllByAttributes(array('contact_id'=>$contact, 'group_id'=>$group));
					if(!$alreadyinlist){
						$list	= new ContactsList;
						$list->contact_id	= $contact;
						$list->group_id		= $group;
						$list->save();
					}
				}
			}
		}
		else{
			$this->renderPartial('_addtogroup');
		}
	}
	else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
}
	
	public function actionRemovefromgroups(){
		if(isset($_POST['contacts']) and count($_POST['contacts'])>0 and isset($_POST['groups']) and count($_POST['groups'])>0){
			$contacts	= $_POST['contacts'];
			$groups		= $_POST['groups'];			
			foreach($groups as $group){
				$criteria	= new CDbCriteria;
				$criteria->condition	= '`group_id`=:group_id';
				$criteria->params	= array(':group_id'=>$group);
				$criteria->addInCondition('`contact_id`', $contacts);
				ContactsList::model()->deleteAll($criteria);
			}
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		if(isset($_GET['group'])){
			$criteria->condition= '`l`.`group_id`=:group_id';
			$criteria->join		= 'LEFT JOIN `contacts_list` `l` ON `l`.`contact_id` = `t`.`id`';
			$criteria->params   = array(':group_id'=>$_GET['group']);
			$criteria->distinct = true;
		}
		
		$criteria->order = '`id` DESC';
		
		$total		= Contacts::model()->count($criteria);
		$pages		= new CPagination($total);
        $pages->setPageSize(9);
        $pages->applyLimit($criteria);  // the trick is here!
		$contacts 	= Contacts::model()->findAll($criteria);
		
		 
		$this->render('index',array(
			'contacts'	=> $contacts,
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
		$model=new Contacts('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Contacts']))
			$model->attributes=$_GET['Contacts'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}*/
	
	public function actionSearch(){
		$this->renderPartial('_searchcontact');
	}
	
	public function actionGroups(){
		$this->renderPartial('_searchgroup');
	}
	
	public function actionImport(){
		//registering js files
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->assetManager->publish(
				Yii::getPathOfAlias('application.modules.sms.assets') . '/js/ajaxupload/ajaxupload.js'
			)
		);
	
		Yii::app()->clientScript->registerScript('browseActionPath', 'var browseActionPath="' . $this->createUrl('/sms/contacts/browse') . '";', CClientScript::POS_BEGIN);
	
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->assetManager->publish(
				Yii::getPathOfAlias('application.modules.sms.assets') . '/js/ajaxupload/importcontacts.js'
			)
		);
		$this->render('import');
	}
	
	public function actionBrowse(){
		if($_FILES['myfile']['name']!=""){
			$filename	= explode(".", $_FILES['myfile']['name']);
			$fname		= current( $filename );
			$extension	= end( $filename );
			$phonenumbers	= array();
			
			$allowedfileformats	= array();
			if(Contacts::model()->import_contacts_config()){
				$import_config		= Contacts::model()->import_contacts_config();
				if($import_config['allowed_file_formats']){
					$allowedfileformats	= $import_config['allowed_file_formats'];
				}
			}
			
			if(!in_array($extension, $allowedfileformats)){
				$this->renderPartial('import/_error', array('error'=>1));
			}
			else{
				$datas	= array();
				if($extension == "xls"){			//excel file				
					require_once(__DIR__.'/../../../extensions/ExcelReader/excel_reader.php');     // include the class		
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
						$x = 1;
						while($x <= $rows) {
							$y = 1;
							while($y <= $cols) {
								$cell = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
								$datas[$x - 1][$y - 1]	= $cell;
								$y++;
							}
							$x++;
						}
					}					
				}
				else if($extension == "csv"){		//csv file
					$contents	= file_get_contents( $_FILES['myfile']['tmp_name'] );			
					$datas 		= array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $contents));
				}
				$this->renderPartial('import/_step1', array('datas'=>$datas));
			}			
		}
		else{
			$this->renderPartial('import/_error', array('error'=>0));
		}		
	}
	
	public function actionSave(){
		$response	= array('status'=>'failed');
		if(Yii::app()->request->isAjaxRequest){
			if(isset($_POST['datas']) and $_POST['datas']!="" and isset($_POST['import_fields']) and count($_POST['import_fields'])>0){
				$datas	= json_decode($_POST['datas']);		
				$fields	= $_POST['import_fields'];		
				$start			= 1;
				$singlequery	= false;
				$inserted_rows	= 0;
				
				$requiredattributes	= array();
				if(Contacts::model()->import_contacts_config()){
					$import_config		= Contacts::model()->import_contacts_config();
					if($import_config['required_attributes']){
						$requiredattributes	= $import_config['required_attributes'];
					}
				}
				
				$valid	= true;
				foreach($fields as $field=>$value){
					if(in_array($field, $requiredattributes) and $value==NULL){
						$valid	= false;
					}
				}
				
				if(!$valid){
					$response['data']	= $this->renderPartial('import/_error', array('error'=>2), true);
				}
				else{				
					while($start < count($datas)){
						if(!$singlequery){
							$contact	= new Contacts;
							$contact->created_by	= Yii::app()->user->id;
							$contact->created_at	= date('Y-m-d H:i:s');
							$contact->status		= 1;
							
							foreach($fields as $field=>$value){
								if($value!=NULL){
									$contact->$field	= $datas[$start][$value];
								}
							}						
							if($contact->save()){
								$inserted_rows++;
								
								if(isset($_POST['groups']) and count($_POST['groups'])>0){
									foreach($_POST['groups'] as $group){
										$list	= new ContactsList;
										$list->contact_id	= $contact->id;
										$list->group_id		= $group;
										$list->save();
									}
								}
							}
						}
						
						$start++;
					}
					
					$response['status']	= "success";
					$response['data']	= $this->renderPartial('import/_step3', array('inserted_rows'=>$inserted_rows, 'total_rows'=>count($datas) - 1), true);
				}
			}
		}
		
		echo json_encode($response);
		Yii::app()->end();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Contacts::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='contacts-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	//Downloading a demo file of csv file 
	public function actionDownload()
	{				
		$file = "sms-contacts.csv";
		$file_name = "sms-contacts.csv";		
		$file_path = 'uploadedfiles/csv_file_document/'.$file;
		$file_content = file_get_contents($file_path);		
		header("Content-Type: text/csv");
		header("Content-disposition: attachment; filename=".$file_name);
		header("Pragma: no-cache");
		echo $file_content;
		exit;
	}
}
