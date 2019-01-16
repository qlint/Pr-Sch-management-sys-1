 <?php
	  $model=new TimetableEntries('new');
	  $this->performAjaxValidation($model);
	
	  if(isset($_POST['TimetableEntries'])){ 
			   $model->attributes=$_POST['TimetableEntries'];
               $errors= array();
                           
			   if($model->validate()) 
               { 	  
				   if(isset($_POST['elective_id']) && $_POST['elective_id']!= NULL)
                       { 
						   $elective_array	=	$_POST['employee_id'];
						   
					   foreach($_POST['elective_id'] as $key => $val)
                       { 
						   $elective_model = new TimetableEntries('new');
						   $elective_model->batch_id = $_POST['TimetableEntries']['batch_id'];
						   $elective_model->weekday_id = $_POST['TimetableEntries']['weekday_id'];
						   $elective_model->class_timing_id = $_POST['TimetableEntries']['class_timing_id'];
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
									 $elective_array	=	$_POST['employee_id'];
                                    $count= count($_POST['elective_id']);                                  
                                    $flag=0;
                                    foreach($_POST['elective_id'] as $key => $val)
                                    {                                                                                                                                                                                            
                                         $elective_model = new TimetableEntries('new');;
                                         $elective_model->batch_id = $_POST['TimetableEntries']['batch_id'];
                                         $elective_model->weekday_id = $_POST['TimetableEntries']['weekday_id'];
                                         $elective_model->class_timing_id = $_POST['TimetableEntries']['class_timing_id'];
                                        
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
										//}
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
                                          $keys= "TimetableEntries_".$attribute;
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
                                    echo CJSON::encode(array('status'=>'error','errors'=>$errors));                                               
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
	   Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;
	   
	   $this->renderPartial('create',array('model'=>$model,'batch_id'=>$_GET['batch_id'],'weekday_id'=>$_GET['weekday_id'],'class_timing_id'=>	$_GET['class_timing_id']),false,true);
	  
	   
	 