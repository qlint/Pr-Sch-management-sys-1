<?php
class TimetableEntriesController extends RController
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
				'actions'=>array('index','view','settime','Dynamiccities'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','remove'),
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
		$model=new TimetableEntries('new');;

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['TimetableEntries']))
		{
			$model->attributes=$_POST['TimetableEntries'];
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

		if(isset($_POST['TimetableEntries']))
		{
			$model->attributes=$_POST['TimetableEntries'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/*public function actionDynamiccities()
	{
		$model=new TimetableEntries;	
		$this->performAjaxValidation($model);
		$flag=true;
		if(isset($_POST['TimetableEntries']))
		{ 
			$flag=false;
			$model->attributes=$_POST['TimetableEntries'];
			if($model->save()) {
				// $this->renderPartial('teaching',array('vsearch' =>'111'), false, true);
				//	$this->RenderPartial ('new', array ('vsearch' =>'122'), false, true);
				//	Yii :: app()->end();
				echo CJSON::encode(array(
				'status'=>'success',
				));
				exit;    
			}
			else
			{
				echo CJSON::encode(array(
				'status'=>'error',
				));
				exit;    
			}
		}
		if($flag){
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$this->renderPartial('create',array('model'=>$model,'batch_id'=>$_GET['batch_id'],'weekday_id'=>$_GET['weekday_id'],'class_timing_id'=>$_GET['class_timing_id']),false,true);
		}	
	}*/
	
	public function actionSettime()
	{ 
	  $model=new TimetableEntries('new');;
	  $this->performAjaxValidation($model);
	  if(isset($_POST['TimetableEntries'])){ //var_dump($_POST['TimetableEntries']);exit;
			   $model->attributes=$_POST['TimetableEntries'];
                           $errors= array();
			   if($model->validate()) 
                          {
							  				  
				   if(isset($_POST['elective_id']) && $_POST['elective_id']!= NULL)
                                   {
					   foreach($_POST['elective_id'] as $key => $val)
                                           {                                                                                                                                                                                            
						   $elective_model = new TimetableEntries('new');;
						   $elective_model->batch_id = $_POST['TimetableEntries']['batch_id'];
						   $elective_model->weekday_id = $_POST['TimetableEntries']['weekday_id'];
						   $elective_model->class_timing_id = $_POST['TimetableEntries']['class_timing_id'];
						   /*$subject = Electives::model()->findByAttributes(array('id'=>$val));
						   echo "batch".$_POST['batch_id'];exit;*/
						   $elective_model->subject_id = $val;
						   $elective_model->employee_id = $_POST['employee_id'][$key];
						   $elective_model->is_elective = 2;
						   if($elective_model->save())
                                                   {
							   
						   }
						   else
                                                   {                                                                                                          
                                                       foreach($elective_model->getErrors() as $attribute=>$error){
                                                           if($attribute=="subject_id")
                                                           {
                                                                $keys		= "TimetableEntries_".$attribute;
                                                           }
                                                           else
                                                           {
                                                               $keys= $attribute.$key;
                                                           }
                                                                $errors[$keys][]	= $error[0];
                                                        }
							echo CJSON::encode(array(
									'status'=>'error',
									'errors'=>$errors                                                                        
									));
                                                        exit;
						   }
						   //var_dump($elective_model->getErrors());exit;					   
					   }
				   }
				   else
				   {
					   if(isset($_POST['split_subject']) and $_POST['split_subject']!=0){
							$model->split_subject	=	$_POST['split_subject'];
						}else{
							$model->split_subject	 =0;
						}
			       		$model->save();   
                        echo CJSON::encode(array('status'=>'success',));
						exit;  
				   }				  
					  
				}
			  else
			  {  
                              if(isset($_POST['elective_id']) && $_POST['elective_id']!= NULL)
                              {
								 
                                  $count= count($_POST['elective_id']);
                                  $flag=0;
                                            foreach($_POST['elective_id'] as $key => $val)
                                            {                                                                                                                                                                                            
                                                     $elective_model = new TimetableEntries('new');;
                                                     $elective_model->batch_id = $_POST['TimetableEntries']['batch_id'];
                                                     $elective_model->weekday_id = $_POST['TimetableEntries']['weekday_id'];
                                                     $elective_model->class_timing_id = $_POST['TimetableEntries']['class_timing_id'];
                                                     /*$subject = Electives::model()->findByAttributes(array('id'=>$val));
                                                     echo "batch".$_POST['batch_id'];exit;*/
                                                     $elective_model->subject_id = $val;
                                                     $elective_model->employee_id = $_POST['employee_id'][$key];
                                                     $elective_model->is_elective = 2;
                                                     if($elective_model->validate())
                                                     {
                                                         $flag=$flag+1;                                                        
                                                     }
                                                     else
                                                    {
                                                        foreach($elective_model->getErrors() as $attribute=>$error)
                                                               {
                                                                   if($attribute=="subject_id")
                                                                   {
                                                                        $keys= "TimetableEntries_".$attribute;
                                                                   }
                                                                   else
                                                                   {
                                                                       $keys= $attribute.$key;
                                                                   }
                                                                   $errors[$keys][]	= $error[0];
                                                                }
                                                    }
                                            }
                                            if($flag==$count)
                                            {
                                                foreach($_POST['elective_id'] as $key => $val)
                                                {
                                                    $elective_model = new TimetableEntries('new');;
                                                    $elective_model->batch_id = $_POST['TimetableEntries']['batch_id'];
                                                    $elective_model->weekday_id = $_POST['TimetableEntries']['weekday_id'];
                                                    $elective_model->class_timing_id = $_POST['TimetableEntries']['class_timing_id'];                                                 
                                                    $elective_model->subject_id = $val;
                                                    $elective_model->employee_id = $_POST['employee_id'][$key];
                                                    $elective_model->is_elective = 2;
                                                    $elective_model->save();
                                                }
                                            }                                                        				                                                                                    
                              }
                              else
                              {
								  
                                  foreach($model->getErrors() as $attribute=>$error)
                                  {
									  
                                       if($attribute=="subject_id")
                                       {
										   
                                            $keys		= "TimetableEntries_".$attribute;
                                       }
                                       else
                                       {
                                           $keys= $attribute."0";
                                       }
                                       $errors[$keys][]	= $error[0];
                                  }
                              }
                                           if($errors!=NULL)
                                           {
											  ;
                                                        echo CJSON::encode(array(
														 
									'status'=>'error',
									'errors'=>$errors                                                                      
									));
                                                        exit;
                                           }
                                           else
                                           {
                                               echo CJSON::encode(array('status'=>'success',));
                                                exit;  
                                           }
			   }
	  }
	 
	   Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	   Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
	   //Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;
	   $this->renderPartial('create',array('model'=>$model,'batch_id'=>$_GET['batch_id'],'weekday_id'=>$_GET['weekday_id'],'class_timing_id'=>	$_GET['class_timing_id']),false, true);
	 }
	 
	 
	public function actionUpdatetime(){
            $model = TimetableEntries::model()->findByPk($_GET['id']);
            $errors= array();
            $success_flag=1;
            if(isset($_POST['TimetableEntries'])){
                $model                  = TimetableEntries::model()->findByPk($_POST['TimetableEntries']['id']);
                $model->scenario	= 'new';
                $model->attributes      = $_POST['TimetableEntries'];
                $model->subject_id      = $_POST['TimetableEntries']['subject_id'];
                $model->is_elective     = $_POST['TimetableEntries']['is_elective'];
                $old_data           = TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$model->batch_id,'weekday_id'=>$model->weekday_id,'class_timing_id'=>$model->class_timing_id));
                $old_row_ids    = CHtml::listData($old_data, 'id', 'id');
                
                if(isset($_POST['TimetableEntries']['is_elective']) && $_POST['TimetableEntries']['is_elective']==2){    
                    if(isset($_POST['elective_id']) && $_POST['elective_id']!= NULL){   //for electives               
                        
                        $new_data_count = count($_POST['elective_id']);
                        $old_data_count = count($old_row_ids);
                        $old_entries    = array_values($old_row_ids);  
                        
                        foreach($_POST['elective_id'] as $key => $val){                             
                            if(!empty($old_entries)){
                                $elective_model = TimetableEntries::model()->findByPk($old_entries[$key]);
                                $elective_model->scenario	= 'new';
                                $elective_model->batch_id           = $_POST['TimetableEntries']['batch_id'];
                                $elective_model->weekday_id         = $_POST['TimetableEntries']['weekday_id'];
                                $elective_model->class_timing_id    = $_POST['TimetableEntries']['class_timing_id'];
                                $elective_model->subject_id         = $val;
                                $elective_model->employee_id        = (isset($_POST['TimetableEntries']['employee_id'][$key]))?$_POST['TimetableEntries']['employee_id'][$key]:$_POST['employee_id'][$key];						  
                                $elective_model->is_elective        = 2; 
                                unset($old_entries[$key]);
                            }else{                                                        
                                $elective_model = new TimetableEntries('new');
                                $elective_model->batch_id = $_POST['TimetableEntries']['batch_id'];
                                $elective_model->weekday_id = $_POST['TimetableEntries']['weekday_id'];
                                $elective_model->class_timing_id = $_POST['TimetableEntries']['class_timing_id'];
                                $elective_model->subject_id = $val;
                                $elective_model->employee_id = (isset($_POST['TimetableEntries']['employee_id'][$key]))?$_POST['TimetableEntries']['employee_id'][$key]:$_POST['employee_id'][$key];						  
                                $elective_model->is_elective = 2; 
                            }
                           
                            
                            if(!$elective_model->validate()){
                                
                                $success_flag=0;
                                foreach($elective_model->getErrors() as $attribute=>$error){
                                    $keys		= ($attribute=="subject_id")?"TimetableEntries_".$attribute:$attribute.$key;       
                                    $errors[$keys][]	= $error[0];
                                }																	
                            }                            
                        }
                        
                        
                        if($success_flag==1){
                            $old_row_ids = array_values($old_row_ids); 
                            foreach($_POST['elective_id'] as $key => $val){                                                                 
                                if(!empty($old_row_ids)){
                                    $elective_model = TimetableEntries::model()->findByPk($old_row_ids[$key]);
                                    $elective_model->scenario	= 'new';
                                    $elective_model->batch_id           = $_POST['TimetableEntries']['batch_id'];
                                    $elective_model->weekday_id         = $_POST['TimetableEntries']['weekday_id'];
                                    $elective_model->class_timing_id    = $_POST['TimetableEntries']['class_timing_id'];
                                    $elective_model->subject_id         = $val;
                                    $elective_model->employee_id        = (isset($_POST['TimetableEntries']['employee_id'][$key]))?$_POST['TimetableEntries']['employee_id'][$key]:$_POST['employee_id'][$key];						  
                                    $elective_model->is_elective        = 2; 
                                    unset($old_row_ids[$key]);
                                }else{                                                        
                                    $elective_model = new TimetableEntries('new');
                                    $elective_model->batch_id = $_POST['TimetableEntries']['batch_id'];
                                    $elective_model->weekday_id = $_POST['TimetableEntries']['weekday_id'];
                                    $elective_model->class_timing_id = $_POST['TimetableEntries']['class_timing_id'];
                                    $elective_model->subject_id = $val;
                                    $elective_model->employee_id = (isset($_POST['TimetableEntries']['employee_id'][$key]))?$_POST['TimetableEntries']['employee_id'][$key]:$_POST['employee_id'][$key];						  
                                    $elective_model->is_elective = 2; 
                                }
                                $elective_model->save();
                            }
                                                        
                        }else{                            
                            echo CJSON::encode(array('status'=>'error','errors'=>$errors ));   exit; 												                                                                 										
                        }                                                                        											                                                
                    }
                }else{
                    unset($old_row_ids[$_POST['TimetableEntries']['id']]); //remove the post id from old entries ids array                                   
                    if(isset($_POST['split_subject']) and $_POST['split_subject']!=0){ 
                        $model->split_subject	=	$_POST['split_subject'];
                    }else{
                        $model->split_subject	=0;
                    }
                    if($model->validate()){
                        if($model->save()){ 
                            $success_flag=1;
                        }                                                                
                    }else{ 
                        $success_flag=0;
                        foreach($model->getErrors() as $attribute=>$error){                        
                            $keys		= ($attribute=="subject_id")?"TimetableEntries_".$attribute:$attribute."0";                        
                            $errors[$keys][]= $error[0];
                        }
                        if($errors!=NULL){
                            echo CJSON::encode(array('status'=>'error','errors'=>$errors ));   exit; 												                                                                 										
                        }                   
                    }      
                }
                                
                if($success_flag==1){                        
                    //delete old entries
                    $criteria= new CDbCriteria();
                    $criteria->addInCondition('id', $old_row_ids);                        
                    $data   = TimetableEntries::model()->deleteAll($criteria);                        
                    echo CJSON::encode(array('status'=>'success'));   exit; 
                }
                    
                           
            }
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            $this->renderPartial('update',array('model'=>$model,'batch_id'=>$_GET['batch_id'],'weekday_id'=>$_GET['weekday_id'],'class_timing_id'=>$_GET['class_timing_id']),false, true);
        }
	 
	 
	public function actionDynamicsubjects()
	{	
		if(isset($_REQUEST['sub_id'])){
			$subject = Subjects::model()->findByPk($_REQUEST['sub_id']);
		}
		else if(isset($_REQUEST['TimetableEntries']['subject_id'])){
			$subject = Subjects::model()->findByPk($_REQUEST['TimetableEntries']['subject_id']);
		}
		if($subject->elective_group_id != 0){
			
			$group = ElectiveGroups::model()->findByPk(($subject)?$subject->elective_group_id:0);
			if($group){
				$electives =  Electives::model()->findAllByAttributes(array('elective_group_id'=>$group->id,'is_deleted'=>0));
				echo json_encode(array('status'=>'elective', 'data'=>$this->renderPartial('_ajax_form',array('electives'=>$electives,'subject'=>$subject),true,false)));
				Yii::app()->end();
			}
			else
				throw new CHttpException(404,'The specified post cannot be found.');
		}else{
			echo json_encode(array('status'=>'Nonelective', 'data'=>$this->renderPartial('_ajax_dropdown', array('subject_id'=>$subject->id),true,false)));
			Yii::app()->end();			
		}
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
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	
	public function actionRemove($id)
	{
		if(Yii::app()->request->isPostRequest){	
			$entry = TimetableEntries::model()->findByPk($id);
			if($entry!=NULL){
				TimetableEntries::model()->deleteAllByAttributes(array('batch_id'=>$entry->batch_id,'weekday_id'=>$entry->weekday_id,'class_timing_id'=>$entry->class_timing_id));
			}
			
			$this->redirect(array('weekdays/timetable','id'=>$_REQUEST['batch_id']));
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
		$dataProvider=new CActiveDataProvider('TimetableEntries');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TimetableEntries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TimetableEntries']))
			$model->attributes=$_GET['TimetableEntries'];

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
		$model=TimetableEntries::model()->findByPk($id);
		$model->scenario	= 'new';
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='timetable-entries-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}
