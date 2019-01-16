<?php
/**
 * ImportCSV Module
 *
 * @author Artem Demchenkov <lunoxot@mail.ru>
 * @version 0.0.3
 *
 * ImportCSV is used for load positions from CSV file to database.
 * Import occurs in three steps:
 *
 * Upload file;
 * Select delimiter and table;
 * Select mode and columns in table.
 * Module has 3 modes:
 * 
 * Insert all - Add all rows;
 * Insert new - Add new rows. Old rows remain unchanged;
 * Insert new and replace old - Add new rows. Old rows replace.
 * All parameters from the previous imports will be saved in a special .php file in upload folder.
 * 
 * Requirements 
 * 
 * Yii 1.1
 * 
 * Usage 
 * 
 * 1) Copy all the 'importcsv' folder under /protected/modules;
 * 
 * 2) Register this module in /protected/config/main.php
 * 
 * 'modules'=>array(
 *         .........
 *         'importcsv'=>array(
 *             'path'=>'upload/importCsv/', // path to folder for saving csv file and file with import params
 *         ),
 *         ......
 *     ),
 * 3) Create a directory which you use in 'path'. Do not forget to set access permissions for directory 'path';
 * 
 * 4) The module is available here:
 * 
 * http://yourproject/importcsv.
 * 
 * Or here:
 * 
 * http://yourproject/index.php?r=importcsv.
 * 
 * Or somewhere else:-) It depends from path settings in your project;
 * 
 * 5) ATTENTION! The first row of your csv-file must will be a row with column names. 
 *
*/

class ImportCsv extends CFormModel
{
    /*
     *
     *  Insert new rows to database
     *
     *  $table - db table
     *  $linesArray - lines with values from csv
     *  $columns - list of csv columns
     *  $tableColumns - list of table columns
     *
     */

    public function InsertAll($table, $linesArray, $columns, $tableColumns, $selectedmodel, $allowedColumns, $dataTypes, $uniqueColumns, $externals=NULL, $extDataTypes=NULL, $extCompares=NULL, $compares=NULL, $csvFields=NULL)
    {
            // $columnsLength - size of columns array
            // $tableString - rows in table
            // $tableString - items in csv
            // $linesLength - size of lines for insert array
			
            $columnsLength   = sizeof($columns);
            $tableString = '';
            $csvString   = '';
            $n = 0;
			$original_loop_count	=	0;
            $linesLength = sizeof($linesArray);			
			$newentries		=	array();
			
			$importcompleted	= false;
			$total_rows_inserted = 0;
			$exception_array	= array();
			$warning_array		= array();
			$csv_missing_rows	= array();			
			//clearing sessions
			if(Yii::app()->user->hasState("csv_missing_rows")){
				unset(Yii::app()->session['csv_missing_rows']);
			}
			
			//action
			$action 			= Yii::app()->controller->module->action;
			
			//required columns from config
			$requiredColumns	= Yii::app()->controller->module->models[$selectedmodel]['requiredColumns'];
			
			//dynamic columns from config
			$dynamicColumns		= Yii::app()->controller->module->models[$selectedmodel]['dynamicColumns'];
			
			//tab selctions
			$tab_selections	= array(1=>"Students", 2=>"Guardians");
			
			//external attribute details from config
			$ext_config	= Yii::app()->controller->module->models[$selectedmodel]['external'];
			
			//compare attribute details from config
			$cmp_config	= Yii::app()->controller->module->models[$selectedmodel]['compare'];
			
			//compare attribute on update
			$updateCompareAttr	= Yii::app()->controller->module->models[$selectedmodel]['updateCompareAttr'];
			
			//table primaty key
			$primary_key_column	= $selectedmodel::model()->tableSchema->primaryKey;
			
            // watching all strings in array
            for($k=0; $k<$linesLength; $k++) {
                // watching all columns in POST
                $n_in 		= 0;
				
				//checking for unique fields
				$val_col_cout	= 0;
                $is_valid		= true;
				$hasError		= false;
				$hasWarning		= false;
				$errMsg			= "";
				$warnMsg		= "";
				//if all fileds are empty
				if(implode('', $linesArray[$k])==''){
					$is_valid	=	false;
				}

				//validations
				//validating main model				
				
				//check if edit/add
				if($action=='insert'){
					$mainmodel	= new $selectedmodel;
				}
				else if($action=='update'){
					$remainCols		= array_values(array_diff($allowedColumns, array_keys($compares)));
					$mainmodel		= NULL;
					foreach($updateCompareAttr as $attr){
						$attrKey	= array_search($attr, $remainCols);
						$compVal	= stripslashes($linesArray[$k][$columns[$attrKey]-1]);
						
						// already exists ?
						$mainmodel	= $selectedmodel::model()->findByAttributes(array($attr=>$compVal));						
					}
					
					if($mainmodel==NULL){	// skip the following
						$is_valid	=	false;
						$hasError	= true;
						$errMsg		.= Yii::t('app', 'Can\'t edit. Row not found')."<br />";
					}
				}
				
				if($is_valid){
					for($i=0; $i<count($tableColumns); $i++){					
						if(in_array($tableColumns[$i], $dynamicColumns)){		//check for dynamic feilds					
							$data		=	stripslashes($linesArray[$k][$columns[$val_col_cout]-1]);
							
							$tab_selection	= array_search($selectedmodel, $tab_selections);
							$dynamicField	= FormFields::model()->findByAttributes(array("tab_selection"=>$tab_selection, "varname"=>$tableColumns[$i]));						
							if($dynamicField!=NULL){
								//check if it is a dropdown or radio
								if(in_array($dynamicField->form_field_type, array(3, 4, 5))){
									$dynamicFieldData	= FormFieldData::model()->findByAttributes(array("field_id"=>$dynamicField->id, "option_name"=>$data));
									$data	= ($dynamicFieldData!=NULL)?$dynamicFieldData->id:0;
								}
								else if($dynamicField->form_field_type==6){		//date field, format data
									$data	= $this->formatValue($data, array('type'=>'date'));
								}
							}						
							$mainmodel->$tableColumns[$i]	= $data;						
							$val_col_cout++;
						}					
						else if(in_array($tableColumns[$i], $allowedColumns) and !array_key_exists($tableColumns[$i], $compares)){
							$data		=	stripslashes($linesArray[$k][$columns[$val_col_cout]-1]);
							if(isset($dataTypes[$tableColumns[$i]])){
								$data	=	$this->formatValue($data, $dataTypes[$tableColumns[$i]]);
							}
							$mainmodel->$tableColumns[$i]	= $data;						
							$val_col_cout++;			
						}
					}
					
					if(!$mainmodel->validate()){
						$is_valid		=	false;
						$hasError		=	true;				
						foreach($mainmodel->getErrors() as $attribute=>$errors){
							$errMsg	.= $errors[0]."<br />";
						}
					}
					
					//validating external models
					if($externals!=NULL){
						foreach($externals as $attribute=>$external_attrs){
							if(isset($ext_config[$attribute])){
								$ext_model	= isset($ext_config[$attribute]['model'])?$ext_config[$attribute]['model']:NULL;
								$extDynamicColumns	= isset($ext_config[$attribute]['model'])?$ext_config[$attribute]['dynamicColumns']:array();
								if($ext_model){
									$externalmodel	= new $ext_model;
									foreach($external_attrs as $external_attr=>$csv_column){
										if($csv_column!='' and $csv_column!=0){
											$data	= trim(preg_replace( '/\s+/', ' ',stripslashes($linesArray[$k][$csv_column-1])));										
											if(in_array($external_attr, $extDynamicColumns)){		//check for dynamic feilds											
												$tab_selection	= array_search($ext_model, $tab_selections);
												$dynamicField	= FormFields::model()->findByAttributes(array("tab_selection"=>$tab_selection, "varname"=>$external_attr));						
												if($dynamicField!=NULL){
													//check if it is a dropdown or radio
													if(in_array($dynamicField->form_field_type, array(3, 4, 5))){
														$dynamicFieldData	= FormFieldData::model()->findByAttributes(array("field_id"=>$dynamicField->id, "option_name"=>$data));
														$data	= ($dynamicFieldData!=NULL)?$dynamicFieldData->id:0;
													}
													else if($dynamicField->form_field_type==6){		//date field, format data
														$data	= $this->formatValue($data, array('type'=>'date'));
													}
												}
											}
											else{									
												if(isset($extDataTypes[$external_attr])){
													$data	=	$this->formatValue($data, $extDataTypes[$external_attr]);
												}
											}
											$externalmodel->$external_attr	= $data;
										}
									}
									
									if(!$externalmodel->validate()){
										$is_valid		=	false;
										$hasError		=	true;				
										foreach($externalmodel->getErrors() as $attribute=>$errors){
											$errMsg	.= $errors[0]."<br />";
										}
									}
								}
							}
						}
					}
				}
				//validations end
							
				if($is_valid and $mainmodel->save()){					
					//last insert id
					$primary_key	= $mainmodel->primaryKey;
					
					//external table entries
					if($externals!=NULL){
						$ext_attributes = array();
						foreach($externals as $attribute=>$external_attrs){
											//-----^----------^-----
							   //column of primary table=>external attributes
							   
							$ext_primary_key	=	NULL;
							
							if(isset($ext_config[$attribute])){
								$ext_model		= isset($ext_config[$attribute]['model'])?$ext_config[$attribute]['model']:NULL;
								$extDynamicColumns	= isset($ext_config[$attribute]['model'])?$ext_config[$attribute]['dynamicColumns']:array();
								if($ext_model){
									$ext_table		= $ext_model::model()->tableSchema->name;
									$extColString	= "";
									$extValString	= "";
									$extcolcount	= 0;
									foreach($external_attrs as $external_attr=>$csv_column){											
										//check already exists
										if(is_array($ext_config[$attribute]['uniqueColumns']) and in_array($external_attr, $ext_config[$attribute]['uniqueColumns'])){
											if($csv_column!='' and $csv_column!=0){
												
												$data			= trim(preg_replace( '/\s+/', ' ',stripslashes($linesArray[$k][$csv_column-1])));
												if($data!=NULL and trim($data)!=""){
													$already_there	= $ext_model::model()->findByAttributes(array($external_attr=>$data));
													
													if($already_there!=NULL){
														$ext_primary_key_column	= $ext_model::model()->tableSchema->primaryKey;
														$ext_primary_key		= $already_there->$ext_primary_key_column;
														break;
													}
												}
											}
										}
										
										$field_value	= CHtml::encode(stripslashes($linesArray[$k][$csv_column - 1]));
										
										if(in_array($external_attr, $extDynamicColumns)){		//check for dynamic feilds											
											$tab_selection	= array_search($ext_model, $tab_selections);
											$dynamicField	= FormFields::model()->findByAttributes(array("tab_selection"=>$tab_selection, "varname"=>$external_attr));						
											if($dynamicField!=NULL){
												//check if it is a dropdown or radio
												if(in_array($dynamicField->form_field_type, array(3, 4, 5))){
													$dynamicFieldData	= FormFieldData::model()->findByAttributes(array("field_id"=>$dynamicField->id, "option_name"=>$field_value));
													$field_value	= ($dynamicFieldData!=NULL)?$dynamicFieldData->id:0;
												}
												else if($dynamicField->form_field_type==6){		//date field, format data
													$field_value	= $this->formatValue($field_value, array('type'=>'date'));
												}
											}
										}
										else{
											if(isset($extDataTypes[$external_attr])){
												$field_value	=	$this->formatValue($field_value, $extDataTypes[$external_attr]);
											}
										}
										
										$field_value	=	addslashes($field_value);
										
										
										if($extcolcount!=0 and $extColString!=""){
											$extColString	.= ", ";
											$extValString	.= ", ";
										}
										//column string
										$extColString	.= $external_attr;
										//value string
										$extValString	.= "'".$field_value."'";
										$extcolcount++;
									}
									
									$new_row	= false;
									if($ext_primary_key==NULL){		//change to ==
										$new_row		= true;
										//execute query here
										$sql			= "INSERT INTO ".$ext_table."(".$extColString.") VALUES (".$extValString.")";
										$command 		= Yii::app()->db->createCommand($sql);
										$extquerylength = $command->execute();
										if($extquerylength){
											//last insert id of external table
											$ext_primary_key	= Yii::app()->db->getLastInsertID();
										}
									}
									//if parent generated
									if($ext_primary_key){
										//update external table if wanted
										if($action=='update' and $new_row==false){
											$externalmodel	= $ext_model::model()->findByPk($ext_primary_key);
											foreach($external_attrs as $external_attr=>$csv_column){
												if($csv_column!='' and $csv_column!=0){
													$data	= trim(preg_replace( '/\s+/', ' ',stripslashes($linesArray[$k][$csv_column-1])));										
													if(in_array($external_attr, $extDynamicColumns)){		//check for dynamic feilds											
														$tab_selection	= array_search($ext_model, $tab_selections);
														$dynamicField	= FormFields::model()->findByAttributes(array("tab_selection"=>$tab_selection, "varname"=>$external_attr));						
														if($dynamicField!=NULL){
															//check if it is a dropdown or radio
															if(in_array($dynamicField->form_field_type, array(3, 4, 5))){
																$dynamicFieldData	= FormFieldData::model()->findByAttributes(array("field_id"=>$dynamicField->id, "option_name"=>$data));
																$data	= ($dynamicFieldData!=NULL)?$dynamicFieldData->id:0;
															}
															else if($dynamicField->form_field_type==6){		//date field, format data
																$data	= $this->formatValue($data, array('type'=>'date'));
															}
														}
													}
													else{									
														if(isset($extDataTypes[$external_attr])){
															$data	=	$this->formatValue($data, $extDataTypes[$external_attr]);
														}
													}
													$externalmodel->$external_attr	= $data;
												}
											}
											
											$externalmodel->save();
										}
										
										//update query for primary table
										$sql				= "UPDATE ".$table." SET ".$attribute."=".$ext_primary_key." WHERE ".$primary_key_column."=".$primary_key;
										$command 			= Yii::app()->db->createCommand($sql);
										$updatequerylength 	= $command->execute();
										if(!$updatequerylength){
											//error message
										}
									}																			
								}
							}							
							if($extCompares!=NULL){
								$ext_attributes[$attribute] = $ext_primary_key;
							}
						}
					}
					//external table entries end
					
					// external compare table entries					
					if($extCompares!=NULL){
						foreach($ext_attributes as $ext_attribute=>$id)
						{
							$ext_cmp_config = Yii::app()->controller->module->models[$selectedmodel]['external'][$ext_attribute]['compare'];
							$compare_key	= key($ext_cmp_config);									
							$cmp_data		= array();
							$compare_attributes	= array();
							$count = count($extCompares);									
							while($count!=0){
								if(isset($ext_cmp_config[$compare_key])){
									$cmp_model	= $ext_cmp_config[$compare_key]['model'];
									foreach($extCompares[$ext_attribute][$compare_key] as $attribute=>$csv_column){	
										$compare_attributes[$attribute]	= trim(preg_replace( '/\s+/', ' ',stripslashes($linesArray[$k][$csv_column-1])));
									}
									$already_in_db	= $cmp_model::model()->findByAttributes($compare_attributes);
									if($already_in_db!=NULL){
										//clear compare attributes
										$compare_attributes	= array();										
										$ext_table = $ext_model::model()->tableSchema->name;
										$cmp_primary_key_column				= $cmp_model::model()->tableSchema->primaryKey;
										$cmp_primary_key					= $already_in_db->$cmp_primary_key_column;
										$cmp_data[$compare_key]				= $cmp_primary_key;
										
										
										if(isset($cmp_config[$compare_key]['compareWith'])){
											$compare_attributes[$compare_key]	= $cmp_primary_key;
											$compare_key	= $cmp_config[$compare_key]['compareWith'];											
										}
										else{
											//update query for primary table											
											$sql				= "UPDATE ".$ext_table." SET ".$compare_key."=".$cmp_primary_key." WHERE ".$primary_key_column."=".$id;											
											$command 			= Yii::app()->db->createCommand($sql);
											$updatequerylength 	= $command->execute();
											if(!$updatequerylength){
												//error message
											}
											
											//insert to another table if
											if(isset($cmp_config[$compare_key]['insertInto'])){
												$insert_into	=	$cmp_config[$compare_key]['insertInto'];
												
												$insert_datas	=	array();
												
												if($insert_into['base_attribute']){
													$cmp_data[$insert_into['base_attribute']]	= $primary_key;
													$insert_datas[$insert_into['base_attribute']]	= $primary_key;
												}
												$insert_into_model	= (isset($insert_into['model']))?$insert_into['model']:NULL;
												
												//selected attributes
												if(isset($insert_into['attributes'])){
													foreach($insert_into['attributes'] as $attribute){
														$insert_datas[$attribute]	= $cmp_data[$attribute];
													}
												}
												
												//preset values
												if(isset($insert_into['preset'])){
													$presets	=	$insert_into['preset'];
													foreach($presets as $attribute=>$settings){
														if(!is_array($settings)){
															$insert_datas[$attribute]	= $settings;
														}
														else{
															$preset_model	= $settings['model'];
															$criteria		=	new CDbCriteria;
															if(isset($settings['criteria'])){
																$criteria->condition	=	$settings['criteria']['condition'];	
																
																$params		=	array();
																foreach($settings['criteria']['params'] as $param=>$value){
																	if(isset($cmp_data[$value]))
																		$params[$param]	=	$cmp_data[$value];
																	else
																		$params[$param]	=	$value;
																}
																
																$criteria->params	=	$params;
															}															
															//if found / not
															if($preset_model::model()->find($criteria))																
																$insert_datas[$attribute]	= $settings['values'][1];																
															else															
																$insert_datas[$attribute]	= $settings['values'][0];
														}														
													}
												}
												if(class_exists($insert_into_model)){
													$insert_into_table	= $insert_into_model::model()->tableSchema->name;													

													$sql			= "INSERT INTO ".$insert_into_table."(".implode(",", array_keys($insert_datas)).") VALUES (".implode(",", $insert_datas).")";
													$command 		= Yii::app()->db->createCommand($sql);
													$extquerylength = $command->execute();
													if($extquerylength){
														//any operations
													}
												}
											}
											//stop looping
											$compare_key = next($extCompares);
											$compare_key = key($extCompares);
										}
									}
									else{
										//stop looping
										$compare_key = next($extCompares);
										$compare_key = key($extCompares);
									}									
								}
							 	$count--;
							}
							$ext_attribute_count--;
						}
					}					
					// external compare table entries end
					
					//compare table entries
					if($compares!=NULL){
						foreach($cmp_config as $compare_key=>$conf_settings){
							//$compare_key	= key($compares);
							$cmp_data		= array();
							$compare_attributes	= array();
							//$count = count($compares);
							
							if(!isset($cmp_config[$compare_key]['skip_counting']) or $cmp_config[$compare_key]['skip_counting']==false){
								while($compare_key!=NULL){									
									if(isset($cmp_config[$compare_key])){
										//default compare columns
										if(isset($cmp_config[$compare_key]['defaultColumns'])){
											foreach($cmp_config[$compare_key]['defaultColumns'] as $attribute=>$value){
												if(is_callable($value)){	// is function
													$value	= $value();
												}
												
												$compare_attributes[$attribute]	= trim(preg_replace( '/\s+/', ' ', $value));
											}
										}
										
										$cmp_model			= $cmp_config[$compare_key]['model'];
										$comp_attr_string	= "";
										$i=0;
										foreach($compares[$compare_key] as $attribute=>$csv_column){
											$i++;											
											$comp_attr_string	.= $cmp_model::model()->getAttributeLabel($attribute);
											if($i<count($compares[$compare_key])){
												if($i==count($compares[$compare_key])-1){
													$comp_attr_string	.= " and ";
												}
												else if(count($compares[$compare_key])>2){
													$comp_attr_string	.= ", ";
												}
											}
											$compare_attributes[$attribute]	= trim(preg_replace( '/\s+/', ' ',stripslashes($linesArray[$k][$csv_column-1])));											
										}
										
										$already_in_db	= $cmp_model::model()->findByAttributes($compare_attributes);
										if($already_in_db!=NULL){
											//clear compare attributes
											$compare_attributes			= array();											
											$cmp_primary_key_column		= $cmp_model::model()->tableSchema->primaryKey;
											$cmp_primary_key			= $already_in_db->$cmp_primary_key_column;
											$cmp_data[$compare_key]		= $cmp_primary_key;
											if(isset($cmp_config[$compare_key]['compareWith'])){
												$compare_attributes[$compare_key]	= $cmp_primary_key;
												$compare_key	= $cmp_config[$compare_key]['compareWith'];											
											}
											else{
												if($compare_key == 'batch_id' and $selectedmodel=="Students") // Insert Batch details into batch_students table.
												{
													$batch_exist	= BatchStudents::model()->findByAttributes(array('student_id'=>$primary_key, 'batch_id'=>$already_in_db->id));
													if(!$batch_exist){	// check whether batch exists
														//set all other bacthes to previous
														$batch_students			= BatchStudents::model()->findAllByAttributes(array('student_id'=>$primary_key, 'result_status'=>0));
														foreach($batch_students as $batch_student){
															$batch_student->status			= 0;
															$batch_student->result_status	= 3;
															$batch_student->save();
														}
														
														$current_batch_columns 	= array('student_id','batch_id','academic_yr_id','status','result_status');
														$current_batch_datas 	= array($primary_key,$already_in_db->id,$already_in_db->academic_yr_id,1,0);
														$sql_batch_students 	= "INSERT INTO batch_students (".implode(",", $current_batch_columns).") VALUES (".implode(",", $current_batch_datas).")";
														$command 				= Yii::app()->db->createCommand($sql_batch_students);
														$updatequerylength 		= $command->execute();
													}
												}
												
												//update query for primary table
												$sql	= "UPDATE ".$table." SET ".$compare_key."=".$cmp_primary_key." WHERE ".$primary_key_column."=".$primary_key;
												$command 			= Yii::app()->db->createCommand($sql);
												$updatequerylength 	= $command->execute();
												if(!$updatequerylength){
													//error message
												}
												
												//insert to another table if
												if(isset($cmp_config[$compare_key]['insertInto'])){
													$insert_into	=	$cmp_config[$compare_key]['insertInto'];
													
													$insert_datas	=	array();
													
													if($insert_into['base_attribute']){
														$cmp_data[$insert_into['base_attribute']]	= $primary_key;
														$insert_datas[$insert_into['base_attribute']]	= $primary_key;
													}
													$insert_into_model	= (isset($insert_into['model']))?$insert_into['model']:NULL;
													
													//selected attributes
													if(isset($insert_into['attributes'])){
														foreach($insert_into['attributes'] as $attribute){
															$insert_datas[$attribute]	= $cmp_data[$attribute];
														}
													}
													
													//preset values
													if(isset($insert_into['preset'])){
														$presets	=	$insert_into['preset'];
														foreach($presets as $attribute=>$settings){
															if(!is_array($settings)){
																$insert_datas[$attribute]	= $settings;
															}
															else{
																$preset_model	= $settings['model'];
																$criteria		=	new CDbCriteria;
																if(isset($settings['criteria'])){
																	$criteria->condition	=	$settings['criteria']['condition'];	
																	
																	$params		=	array();
																	foreach($settings['criteria']['params'] as $param=>$value){
																		if(isset($cmp_data[$value]))
																			$params[$param]	=	$cmp_data[$value];
																		else
																			$params[$param]	=	$value;
																	}
																	
																	$criteria->params	=	$params;
																}
																
																//if found / not
																if($preset_model::model()->find($criteria))
																	
																	$insert_datas[$attribute]	= $settings['values'][1];																
																else
																
																	$insert_datas[$attribute]	= $settings['values'][0];
															}														
														}
													}
													
													if(class_exists($insert_into_model)){
														$insert_into_table	= $insert_into_model::model()->tableSchema->name;													
														$sql			= "INSERT INTO ".$insert_into_table."(".implode(",", array_keys($insert_datas)).") VALUES (".implode(",", $insert_datas).")";
														$command 		= Yii::app()->db->createCommand($sql);
														$extquerylength = $command->execute();
														if($extquerylength){
															//any operations
														}
													}
												}													
												//stop while loop
												$compare_key	= NULL;
											}
										}
										else{
											//stop while loop
											$hasWarning		= true;
											$warnMsg		.= preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $cmp_model).' - '.$comp_attr_string." not found<br />";	
											$compare_key	= NULL;
										}									
									}
									else{
										//stop while loop
										$compare_key	= NULL;
									}
								}
							}
						}
					}
					//compare table entries end					
					$importcompleted	= true;	
					
					if($selectedmodel=="Students"){
						$student_details 	= Students::model()->findByPk($mainmodel->id);
						if($student_details){
							$guardian_details 	= Guardians::model()->findByPk($student_details->parent_id);
						}
						if($student_details!=NULL and $guardian_details!=NULL){
							$already_added		= GuardianList::model()->findByAttributes(array('student_id'=>$mainmodel->id, 'guardian_id'=>$guardian_details->id));
							if(!$already_added){
								$guardian_list 		= new GuardianList;
								$guardian_list->student_id  = $mainmodel->id;
								$guardian_list->guardian_id = $guardian_details->id;
								$guardian_list->relation 	= $guardian_details->relation;
								$guardian_list->save();
							}
						}
					}
									
					$total_rows_inserted++;
				}
				else{
					array_push($csv_missing_rows, $linesArray[$k]);
				}
				
				if($hasError){
					array_push($exception_array, "<b style='color:#FFF; display:block;'>Row ".($k+1)." - Errors</b>".$errMsg);
				}
				if($hasWarning){
					array_push($warning_array, "<b style='color:#FFF; display:block;'>Row ".($k+1)." - Warnings</b>".$warnMsg);
				}
           	}
			
			if(count($csv_missing_rows)>0){
				array_unshift($csv_missing_rows, $csvFields[0]);				
				//set session variable
				Yii::app()->user->setState("csv_missing_rows", $csv_missing_rows);
			}
			
			$response	= array();
			
            if($importcompleted)
				$response['status']	= 1;
            else 
				$response['status']	= 0;
			
			$response['csv_missing_rows']		= $csv_missing_rows;
			$response['exceptions']				= $exception_array;
			$response['warnings']				= $warning_array;
			$response['total_rows']				= $linesLength;
			$response['total_rows_inserted']	= $total_rows_inserted;

			return $response;
    }

    /*
     * 
     *  Update old rows
     *  $table - db table
     *  $csvLine - one line from csv
     *  $columns - list of csv columns
     *  $tableColumns - list of table columns
     *  $needle - value for compare from csv
     *  $tableKey - key for compare from table
     * 
     */

    public function updateOld($table, $csvLine, $columns, $tableColumns, $needle, $tableKey)
    {
        // $columnsLength - size of columns array
        // $tableString - rows in table
        // $csvLine - items from csv
        
        $columnsLength = sizeof($columns);
        $tableString = '';
        $n           = 0;
        
        for($i=0; $i<$columnsLength; $i++) {
            if($columns[$i]!='') {
                $tableString = ($n!=0) ? $tableString.", ".$tableColumns[$i]."='".CHtml::encode(stripslashes($csvLine[$columns[$i]-1]))."'" : $tableColumns[$i]."='".CHtml::encode(stripslashes($csvLine[$columns[$i]-1]))."'";

                $n++;
            }
        }

        // update row in database

        $sql="UPDATE ".$table." SET ".$tableString." WHERE ".$tableKey."='".$needle."'";
        $command=Yii::app()->db->createCommand($sql);

        if($command->execute())
             return (1);
        else
             return (0);
    }

    /*
     * get columns from selected table
     * $table - db table
     * @return array list of db columns
     *
     */

    public function tableColumns($table)
    {
        return Yii::app()->getDb()->getSchema()->getTable($table)->getColumnNames();
    }

    /*
     * get attribute from all rows from selected table
     *
     * $table - db table
     * $attribute - columns in db table
     * @return - rows array
     *
     */

    public function selectRows($table, $attribute)
    {
        $sql = "SELECT ".$attribute." FROM ".$table;
        $command=Yii::app()->db->createCommand($sql);
        return ($command->queryAll());
    }
	
	protected function formatValue($value, $params){
		if(isset($params['type'])){
			switch($params['type']){
				case "date":	
					$dateformats	=	array('Y-m-d', 'Y/m/d', 'Y.m.d', 'd-m-Y', 'd/m/Y', 'd.m.Y', 'd/m/y', 'd-m-y', 'd.m.y');		//add more date formats if needed
					foreach($dateformats as $format){
						if($this->validateDate($value, $format)){
							$d 		=	DateTime::createFromFormat($format, $value);
							$value	=	$d->format(isset($params['format'])?$params['format']:'Y-m-d');
							break;
						}
					}
				break;
				
				case "gender":
					$genderformats	=	array('M'=>array('male', 'm', '1', 'M'), 'F'=>array('female', 'f', '2', 'F'));		//add more gender formats if needed
					foreach($genderformats as $dbval=>$formats){
						if(in_array(str_replace(array(' '),'',strtolower($value)), $formats)){
							$value	=	$dbval;
							break;
						}
					}
				break;
					
				case "boolean":
					if(strtoupper(trim($value))=="Y")
						$value	=	1;
					else
						$value	=	0;
				break;
				
			}
		}
		return $value;
	}
	
	protected function perfectValue($value){
		$value	=	mysql_real_escape_string($value);
		return $value;
	}
	
	protected function validateDate($date, $format = 'Y-m-d H:i:s'){
		$d	=	DateTime::createFromFormat($format, $date);
		return $d;// && $d->format($format) == $date;
	}
}

?>
