<?php
class CreateController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionIndex()
	{
		//get academic year
		if(Yii::app()->user->year){
			$year 					= Yii::app()->user->year;
		}
		else{
			$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
			$year 					= $current_academic_yr->config_value;
		}
		
		if($year){		
			$category	= new FeeCategories;
			$particular	= new FeeParticulars;				
			
			//save fee categories
			if(isset($_POST['FeeCategories'])){
				$errors		= array();
				$has_error	= false;				
				$category->attributes		= $_POST['FeeCategories'];				
				$category->academic_year_id	= $year;
				$category->created_by		= Yii::app()->user->id;
				$category->created_at		= date("Y-m-d h:i:s");		
				
				if(!$category->validate()){
					$has_error	= true;
					//get error from category
					foreach($category->getErrors() as $attribute=>$error){
						$key		= "FeeCategories_".$attribute;							
						$errors[$key][]	= $error[0];
					}
				}
				
				//save fee particulars
				if(isset($_POST['FeeParticulars']['name'])){
					foreach($_POST['FeeParticulars']['name'] as $i=>$name){
						$particular	= new FeeParticulars;
						$particular->academic_year_id	= $year;
						$particular->name				= $name;
						$particular->description		= $_POST['FeeParticulars']['description'][$i];
						$particular->tax				= $_POST['FeeParticulars']['tax'][$i];
						$particular->discount_value		= $_POST['FeeParticulars']['discount_value'][$i];
						$particular->discount_type		= $_POST['FeeParticulars']['discount_type'][$i];						
						$particular->created_by			= Yii::app()->user->id;
						$particular->created_at			= date("Y-m-d h:i:s");
											
						if(!$particular->validate()){
							$has_error	= true;
							//get error from particular
							foreach($particular->getErrors() as $attribute=>$error){
								$key		= "FeeParticulars_".$attribute."_".$i;							
								$errors[$key][$i]	= $error[0];
							}			
						}
						
						//validate access
						if(isset($_POST['FeeParticularAccess'][$i])){
							$no_of_accesses	= count();
							foreach($_POST['FeeParticularAccess'][$i]['access_type'] as $j=>$access_type){
								$access						= new FeeParticularAccess;
								$access->access_type		= $access_type;
								$access->academic_year_id	= $year;
								if($access->access_type==1){		//school level
									$access->course					= $_POST['FeeParticularAccess'][$i]['course'][$j];
									$access->batch					= $_POST['FeeParticularAccess'][$i]['batch'][$j];
									$access->student_category_id	= $_POST['FeeParticularAccess'][$i]['student_category_id'][$j];
								}
								else{		//admission number
									$access->admission_no			= $_POST['FeeParticularAccess'][$i]['admission_no'][$j];
									$access->setScenario('admission_no');
								}
																
								$access->amount				= $_POST['FeeParticularAccess'][$i]['amount'][$j];
								$access->created_by			= Yii::app()->user->id;
								$access->created_at			= date("Y-m-d h:i:s");
								
								if(!$access->validate()){
									$has_error	= true;
									//get error from particular
									foreach($access->getErrors() as $attribute=>$error){
										$key		= "FeeParticularAccess_".$i."_".$attribute."_".$j;
										$errors[$key][$i]	= $error[0];
									}			
								}
							}
						}
					}
				}
				else{
					$has_error	= true;
				}
				
				
				//FeeParticularAccess
				
				
				if($has_error==true){
					echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
					exit;
				}
				else{
					//save fee category
					if($category->save()){
						//save fee particulars
						foreach($_POST['FeeParticulars']['name'] as $i=>$name){
							$particular	= new FeeParticulars;
							$particular->fee_id				= $category->id;
							$particular->academic_year_id	= $year;
							$particular->name				= $name;
							$particular->description		= $_POST['FeeParticulars']['description'][$i];
							$particular->tax				= ($_POST['FeeParticulars']['tax'][$i]!="")?$_POST['FeeParticulars']['tax'][$i]:0;
							
							if($_POST['FeeParticulars']['discount_value'][$i]!=""){
								$particular->discount_value		= $_POST['FeeParticulars']['discount_value'][$i];
								$particular->discount_type		= $_POST['FeeParticulars']['discount_type'][$i];	
							}
							
							$particular->created_by			= Yii::app()->user->id;
							$particular->created_at			= date("Y-m-d h:i:s");
							if($particular->save()){
								//save particular accesses
								foreach($_POST['FeeParticularAccess'][$i]['access_type'] as $j=>$access_type){									
									if($access_type==1){
										$already_found				= FeeParticularAccess::model()->findByAttributes(array('access_type'=>$access_type, 'particular_id'=>$particular->id, 'course'=>$_POST['FeeParticularAccess'][$i]['course'][$j], 'batch'=>$_POST['FeeParticularAccess'][$i]['batch'][$j], 'student_category_id'=>$_POST['FeeParticularAccess'][$i]['student_category_id'][$j]));
										if($already_found==NULL){
											$access						= new FeeParticularAccess;
											$access->access_type		= $access_type;
											$access->particular_id		= $particular->id;
											$access->academic_year_id	= $year;
											$access->course				= $_POST['FeeParticularAccess'][$i]['course'][$j];
											$access->batch				= $_POST['FeeParticularAccess'][$i]['batch'][$j];
											$access->student_category_id= $_POST['FeeParticularAccess'][$i]['student_category_id'][$j];
											$access->amount				= $_POST['FeeParticularAccess'][$i]['amount'][$j];
											$access->created_by			= Yii::app()->user->id;
											$access->created_at			= date("Y-m-d h:i:s");
											$access->save();
										}
									}
									else{
										//split admission numbers
										$admission_nos	= explode(",", str_replace(" ", "", $_POST['FeeParticularAccess'][$i]['admission_no'][$j]));
										foreach($admission_nos as $admission_no){
											$already_found				= FeeParticularAccess::model()->findByAttributes(array('access_type'=>$access_type, 'particular_id'=>$particular->id, 'admission_no'=>$admission_no));
											if($already_found==NULL){
												$access						= new FeeParticularAccess;
												$access->access_type		= $access_type;
												$access->particular_id		= $particular->id;
												$access->academic_year_id	= $year;
												$access->admission_no		= $admission_no;
												$access->amount				= $_POST['FeeParticularAccess'][$i]['amount'][$j];
												$access->created_by			= Yii::app()->user->id;
												$access->created_at			= date("Y-m-d h:i:s");
												$access->save();
											}
										}
									}
								}
							}
						}
						
						//send success message
						echo CJSON::encode(array('status'=>'success', 'redirect'=>Yii::app()->createUrl('/fees/subscriptions', array('id'=>$category->id))));
						exit;
					}
					else{
						echo CJSON::encode(array('status'=>'error', 'message'=>Yii::t("app", "Some problem found while saving data !!")));			
						exit;
					}
				}
			}
			
			$this->render('index',array('category'=>$category, 'particular'=>$particular, 'access'=>$access));
		}
		else{
			if(Yii::app()->request->isAjaxRequest){
				echo CJSON::encode(array('status'=>'error', 'message'=>Yii::t("app", "You are not created an academic year !!")));			
				exit;
			}
			else{
				$this->render('/default/no_academic_year');
			}
		}
	}
		
	public function actionAddParticular($ptrow=""){
		$particular	= new FeeParticulars;
		$data		= $this->renderPartial('_particular',array('particular'=>$particular, 'ptrow'=>$ptrow), true);
		echo CJSON::encode(array('status'=>'success', 'data'=>$data));
	}
	
	public function actionAddParticularAccess($ptrow="", $acrow=""){
		$data		= $this->renderPartial('_access',array('ptrow'=>$ptrow, 'acrow'=>$acrow), true);
		echo CJSON::encode(array('status'=>'success', 'data'=>$data));
	}
	
	public function actionAddParticularAccessType($type, $ptrow="", $acrow=""){
		$access		= new FeeParticularAccess;
		$data		= $this->renderPartial('_access_'.$type,array('access'=>$access, 'ptrow'=>$ptrow, 'acrow'=>$acrow), true);
		echo CJSON::encode(array('status'=>'success', 'data'=>$data));
	}
	
	public function actionGetBatches($course){
		$criteria	= new CDbCriteria;
		$criteria->compare("course_id", $course);
		$criteria->compare("is_active", 1);
		$data		= Batches::model()->findAll($criteria);		
		echo CHtml::tag('option', array('value' => ""), Yii::t('app', "All")." ".Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), true);		
		$data		= CHtml::listData($data, 'id', 'name');
		foreach($data as $value=>$name){
			echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
		}
	}
}