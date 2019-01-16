<style type="text/css">
.sp_col{
	border-bottom:1px #eee solid;
	padding-bottom:8px;
}
</style>
<?php 
	$this->renderPartial('leftside');
	
	$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
	$guardian 	= Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	      
	$criteria 				= new CDbCriteria;		
	$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
	$criteria->condition 	= 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
	$criteria->params 		= array(':guardian_id'=>$guardian->id,':is_active'=>1,'is_deleted'=>0);
	$students 				= Students::model()->findAll($criteria);  	  	
?>
<div class="pageheader">
	<h2><i class="fa fa-user"></i> <?php echo Yii::t('app','Profile'); ?> <span><?php echo Yii::t('app','View your profile here'); ?></span></h2>
	<div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
		<ol class="breadcrumb">			
			<li class="active"><?php echo Yii::t('app','Profile'); ?></li>
		</ol>
	</div>
</div>
<div class="contentpanel"> 
	<div class="people-item">
    	<div class="profile_block">
        	<div class="media-body media-body_m">
                    <h4 class="person-name"><?php echo $guardian->parentFullName('forParentPortal');?></h4>
                    <div class="text-muted text-muted-bdr">
                    <i class="fa fa-user"></i>
                        <?php
							foreach($students as $student){
								$guardian_relation = GuardianList::model()->findByAttributes(array('guardian_id'=>$guardian->id,'student_id'=>$student->id));								
								if($guardian_relation and $student->studentFullName('forParentPortal')!=''){							
									echo Yii::t('app',ucfirst($guardian_relation->relation)).' '.Yii::t('app','of').' : '; 
									echo CHtml::link($student->studentFullName('forParentPortal'), array('/parentportal/default/studentprofile','id'=>$student->id)).'&nbsp;&nbsp;&nbsp;';
								}
							}
                		?>
                    </div>
                </div>
                <?php echo CHtml::link('<span>'.Yii::t('app','Edit Profile').'</span>',array('default/edit','id'=>$guardian->id),array('class'=>'btn btn-xs prtl_btn btn_edit_prfl'));?>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel_block">
                        <div class="panel-heading">
                        	<h5 class="panel-title"><?php echo Yii::t('app', 'Profile Details'); ?></h5> 
                        </div>
                        <div class="people-item">
                            <div class="table-responsive">
                            	<h5 class="subtitle"><?php echo Yii::t('app', 'General Details'); ?></h5>
                                <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                    <thead>
                                    	<?php if(FormFields::model()->isVisible('dob','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('dob'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->dob != NULL and $guardian->dob != '0000-00-00'){
                                                             echo date($settings->displaydate,strtotime($guardian->dob));
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                    	<?php } ?>
										<?php if(FormFields::model()->isVisible('education','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('education'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->education != NULL){
                                                             echo html_entity_decode(ucfirst($guardian->education));
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('occupation','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('occupation'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->occupation != NULL){
                                                             echo html_entity_decode(ucfirst($guardian->occupation));
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('income','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('income'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->income != NULL){
                                                             echo html_entity_decode(ucfirst($guardian->income));
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php 
											$fields	= FormFields::model()->getDynamicFields(2, 1, "forParentPortal");
											if($fields){
												foreach ($fields as $key => $field){							
													if($field->form_field_type!=NULL){
														if(FormFields::model()->isVisible($field->varname,'Guardians','forParentPortal')){
															?>    
																<tr> 
																	<th width="200"><?php echo $guardian->getAttributeLabel($field->varname);?></th> 																				       
																<td>
																<?php
																 $field_name = $field->varname;
																  if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
																	echo FormFields::model()->getFieldValue($guardian->$field_name);
																  }
																  else if($field->form_field_type==6){  // date value
																		if($settings!=NULL and $guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
																			echo date($settings->displaydate,strtotime($guardian->$field_name));
																		
																		}
																		else{
																			if($guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
																				echo $guardian->$field_name;
																			}
																			else{
																				echo '-';
																			}
																		}
																  }
																  else{
																	echo (isset($guardian->$field_name) and $guardian->$field_name!="")?html_entity_decode(ucfirst($guardian->$field_name)):"-";
																  }
																?>
																</td>
															</tr><?php
														} 
													} 				                                            
												}
											}
										?>
                                        <?php if(FormFields::model()->isVisible('email','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('email'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->email != NULL){
                                                             echo $guardian->email;
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('mobile_phone','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('mobile_phone'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->mobile_phone != NULL){
                                                             echo $guardian->mobile_phone;
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('office_phone1','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('office_phone1'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->office_phone1 != NULL){
                                                             echo $guardian->office_phone1;
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('office_phone2','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('office_phone2'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->office_phone2 != NULL){
                                                             echo $guardian->office_phone2;
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('office_address_line1','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('office_address_line1'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->office_address_line1 != NULL){
                                                             echo html_entity_decode(ucfirst($guardian->office_address_line1));
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('office_address_line2','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('office_address_line2'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->office_address_line2 != NULL){
                                                             echo html_entity_decode(ucfirst($guardian->office_address_line2));
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('city','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('city'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->city != NULL){
                                                             echo html_entity_decode(ucfirst($guardian->city));
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('state','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('state'); ?></th>
                                                <td>
                                                    <?php 
                                                         if($guardian->state != NULL){
                                                             echo html_entity_decode(ucfirst($guardian->state));
                                                         }
                                                         else{
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('country_id','Guardians','forParentPortal')){ ?>
                                            <tr>
                                                <th width="200"><?php echo $guardian->getAttributeLabel('country_id'); ?></th>
                                                <td>
                                                    <?php 
                                                         $country	= Countries::model()->findByPk($guardian->country_id);
														 if($country != NULL){
															 echo html_entity_decode(ucfirst($country->name));
														 }
														 else{
															echo '-';
														 }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php 
											$fields	= FormFields::model()->getDynamicFields(2, 2, "forParentPortal");
											if($fields){
												foreach($fields as $key => $field){							
													if($field->form_field_type!=NULL){
														if(FormFields::model()->isVisible($field->varname,'Guardians','forParentPortal')){
															?>    
																<tr> 
																<th width="200"><?php echo $guardian->getAttributeLabel($field->varname);?></th>                                      																			
																<td>
																<?php
																  $field_name = $field->varname;
																  if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
																	echo FormFields::model()->getFieldValue($guardian->$field_name);
																  }
																  else if($field->form_field_type==6){  // date value
																		if($settings!=NULL and $guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
																			echo date($settings->displaydate,strtotime($guardian->$field_name));
																		}
																		else{
																			if($guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
																				echo $guardian->$field_name;
																			}
																			else{
																				echo '-';
																			}
																		}																
																  }
																  else{
																	echo (isset($guardian->$field_name) and $guardian->$field_name!="")?html_entity_decode(ucfirst($guardian->$field_name)):"-";
																  }
																?>
																</td>
															</tr><?php
														} 
													} 				                                            
												}
											}
										?>
                                    </thead>
                                </table>
                            </div>
                    	</div>                
                    </div>
                </div>
           	</div>             
		</div>
	</div>                                                        
</div>