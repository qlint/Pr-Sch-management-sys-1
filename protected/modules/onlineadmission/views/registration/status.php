<?php
$this->breadcrumbs=array(
	Yii::t('app','Registration'),
);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/online_register.css" />
    <link rel="icon" type="image/ico" href="<?php echo Yii::app()->request->baseUrl; ?>/uploadedfiles/school_logo/favicon.ico"/>
    <title><?php $college=Configurations::model()->findByPk(1); ?><?php echo $college->config_value ; ?></title>
</head>
<?php $logo=Logo::model()->findAll();?>
        	
<div class="loginboxWrapper">
<div class="logo">            
			<?php 
			if($logo!=NULL)
			{
				echo '<img src="'.Yii::app()->request->baseUrl.'/uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" border="0" height="55" />';
			}
			?>
            </div>
	<?php
	if(Yii::app()->user->id!=NULL and $_REQUEST['from']=='parent')
	{		
		
		$profile = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));		
	}
	else
	{
		$profile = Students::model()->findByAttributes(array('id'=>Yii::app()->session['profile']));
	}
	$parent = Guardians::model()->findByAttributes(array('id'=>$profile->parent_id));	
	
	$waitinglist_details = WaitinglistStudents::model()->findByAttributes(array('student_id'=>$profile->id,'status'=>0));
	?>
    <div class="hed"><h1><?php echo Yii::t('app','Application Status'); ?></h1></div>
    <div class="cont_right formWrapper">
	<?php
    
    $admin = User::model()->findByPk(1);
    $school = Configurations::model()->findByPk(1);
    //if($id)
    //{
    ?>
    <?php
	//$profile->status = 0;
	if($profile->status == 0) // Pending
	{
		$status = Yii::t('app','Your application is under review');
		$bg = 'confirm_clock';
		$icon = 'status_clock';
	}
	elseif($profile->status == 1) // Approve
	{
		$status = Yii::t('app','Your application is approved');
		$bg = 'status_top';
		$icon = 'status_tick';
	}
	elseif($profile->status == -1) // Disapprove
	{
		$status = Yii::t('app','Your application is disapproved');
		$bg = 'confirm_cross';
		$icon = 'status_cross';
	}
	elseif($profile->status == -3) // Waiting List
	{
		$status = Yii::t('app','You have been placed on the waiting list').'<br>'.Yii::t('app','Your priority number is').' '.$waitinglist_details->priority;
		$bg = 'confirm_cross';
		$icon = 'status_cross';
	}
	?>
   
    <div class="confirm_bx">
        <div class="<?php echo $bg; ?>"><?php echo Yii::t('app','Application Status'); ?></div> 
		<div class="status_botom">
		<?php
		if(Yii::app()->session['profile'] or Yii::app()->user->id!=NULL)
		{
		?>
	
            <div class="<?php echo $icon; ?>"></div>
            
            <h2>
            <?php echo $status; ?>
            </h2>
            
 <div class="button_box_top">           
<?php if(Yii::app()->user->id==NULL){?>  
	<div class="add_student_bt" style="float:right;margin: 0 8px 15px 0;"> <?php echo CHtml::link(Yii::t('app','Add Another Student'), array('/onlineadmission/registration/step1'),array('target'=>'_blank')); ?></div>                      
	<div class="add_student_bt" style="float:right;margin: 0 8px 15px 0;"> <?php echo CHtml::link(Yii::t('app','Add Sibling'), array('/onlineadmission/registration/step1','from'=>'online'),array('target'=>'_blank')); ?></div>
	<?php 
		if($profile->status != 1){ 
	?>	
    		<div class="add_student_bt" style="float:right;margin: 0 8px 15px 0;"> <?php echo CHtml::link(Yii::t('app','Edit'), array('/onlineadmission/registration/edit')); ?></div>    
                    
<?php
		}
	} ?>
</div>    
<!-- Image -->

	<div class="img_box_pr">
    	<?php
	$settings = UserSettings::model()->findByAttributes(array('user_id'=>1));
	$student=Students::model()->findByAttributes(array('id'=>$profile->id));
	 
	 if($student->photo_file_name){
		$path = Students::model()->getProfileImagePath($student->id); 
    	echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="100"  />';
	 }
	 elseif($student->gender == 'M')
	 {
		echo '<img  src="images/s_prof_m_image.png" alt='.$student->first_name.' />'; 
	 }
	 elseif($student->gender == 'F')
	 {
		echo '<img  src="images/s_prof_fe_image.png" alt='.$student->first_name.' />';  
	 }
	 ?>
    </div>
    <div class="logout_box">
    	<?php						
			if(Yii::app()->user->id==NULL)
			{
				if(Yii::app()->session['profile'])
				{
					echo CHtml::link(Yii::t('app','Logout'), array('logout'),array('class'=>'goback_but')); 
				}
				else
				{
					echo CHtml::link(Yii::t('app','Go Home'), array('index'),array('class'=>'goback_but')); 
				}			
			}
			?>
    </div>
	
            
<!-- Image End --> 
  
	        
            <table width="95%" border="0" cellspacing="0" cellpadding="0">
            	<tr>
                	<td width="50%" colspan="2" align="center" style="padding-left:20px; border:0px; font-size:22px;"><?php echo Yii::t('app','STUDENT PROFILE '); ?></td>
                </tr>
                <tr>
                	<th width="50%" colspan="2" align="left" style="padding-left:20px;"><?php echo Yii::t('app','Personal Details'); ?></th>
                </tr>
                <?php if(FormFields::model()->isVisible('registration_id','Students','forOnlineRegistration')){?>
                    <tr>
                        <td width="50%"><?php echo $profile->getAttributeLabel('registration_id');?></td>
                        <td width="50%"><?php echo $profile->registration_id; ?></td>
                    </tr>
                <?php } ?>  
                <?php if(FormFields::model()->isVisible('registration_date','Students','forOnlineRegistration')){?>  
                <tr>
                    <td><?php echo $profile->getAttributeLabel('registration_date');?></td>
                    <td><?php if($profile->registration_date != NULL)
								{
									
									if($settings!=NULL)
									{	
										$profile->registration_date = date($settings->displaydate,strtotime($profile->registration_date));
									}
									echo $profile->registration_date;
								}
								else
								{
									echo '-';
								}
								?>
                    </td>
                </tr>
                 <?php } ?>  
                 <?php if(FormFields::model()->isVisible('first_name','Students','forOnlineRegistration')){?>
                    <tr>
                        <td width="50%"><?php echo $profile->getAttributeLabel('first_name');?></td>
                        <td width="50%"><?php echo $profile->first_name; ?></td>
                    </tr>
                <?php } ?> 
                 <?php if(FormFields::model()->isVisible('middle_name','Students','forOnlineRegistration')){?>
                    <tr>
                        <td width="50%"><?php echo $profile->getAttributeLabel('middle_name');?></td>
                        <td width="50%"><?php if($profile->middle_name!=NULL)
												{
													echo $profile->middle_name;
												}
												else
												{
													echo '-';
												}
											?></td>
                    </tr>
                <?php } ?> 
                 <?php if(FormFields::model()->isVisible('last_name','Students','forOnlineRegistration')){?>
                    <tr>
                        <td width="50%"><?php echo $profile->getAttributeLabel('last_name');?></td>
                        <td width="50%"><?php echo $profile->last_name; ?></td>
                    </tr>
                <?php } ?>  
                <?php if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){?>
                    <tr>
                        <td width="50%"><?php echo Yii::t('app','Course');?></td>
                        <td width="50%"><?php $batch_name = Batches::model()->findByAttributes(array('id'=>$profile->batch_id,'is_active'=>1));
												if($batch_name!=NULL)
												{
													echo $batch_name->course123->course_name;
												}
												else
												{
													echo '-';
												}
												?></td>
										
                    </tr>
                <?php } ?> 
                <?php if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){?>
                    <tr>
                        <td width="50%"><?php echo $profile->getAttributeLabel('batch_id');?></td>
                        <td width="50%"><?php if($batch_name!=NULL)
												{
													echo $batch_name->name;
												}
												else
												{
													echo '-';
												}
											?></td>
                        
                    </tr>
                <?php } ?>  
                
          <?php if(FormFields::model()->isVisible('date_of_birth','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('date_of_birth');?></td>
                <td width="50%"> <?php if($profile->date_of_birth!=NULL)
										{
											if($settings!=NULL)
											{	
												$date1=date($settings->displaydate,strtotime($profile->date_of_birth));
												echo $date1;
							
											}
											else
											echo $profile->date_of_birth;  
										}
										else
										{
											echo '-';	
										}
										?></td>
                   
            </tr>
        <?php } ?>
        
        <?php if(FormFields::model()->isVisible('national_student_id','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('national_student_id');?></td>
                <td width="50%"><?php if($profile->national_student_id!=NULL)
										{
											echo $profile->national_student_id;
										}
										else
										{
											echo '-';
										}
									?></td>
            </tr>
        <?php } ?> 
        
       <?php if(FormFields::model()->isVisible('gender','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('gender');?></td>
                <td width="50%"> <?php if($profile->gender=='M')
                            echo Yii::t('app','Male');
                        else if($profile->gender=='F')
                            echo Yii::t('app','Female');
                        else
                            echo '-'; ?></td>
            </tr>
        <?php } ?>
        <?php if(FormFields::model()->isVisible('blood_group','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('blood_group');?></td>
                <td width="50%"><?php if($profile->blood_group!=NULL)
										{
											echo $profile->blood_group;
										}
										else
										{
											echo '-';
										}
									?></td>
            </tr>
        <?php } ?>      
        <?php if(FormFields::model()->isVisible('birth_place','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('birth_place');?></td>
                <td width="50%"><?php if($profile->birth_place!=NULL)
										{
											echo $profile->birth_place;
										}
										else
										{
											echo '-';
										}
									?></td>
            </tr>
        <?php } ?>       
         <?php if(FormFields::model()->isVisible('nationality_id','Students','forOnlineRegistration')){?>
            <tr>
                <td class=""><?php echo $profile->getAttributeLabel('nationality_id');?></td>
                <td class=""><?php 
								if($profile->nationality_id!=NULL)
								{
									$natio_id=Nationality::model()->findByAttributes(array('id'=>$profile->nationality_id));
									echo $natio_id->name; 
								}
								else{
									echo '-';
								}?>
							</td>
                    
            </tr>
        <?php } ?>
         <?php if(FormFields::model()->isVisible('language','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('language');?></td>
                <td width="50%"><?php if($profile->language!=NULL)
										{
											echo $profile->language;
										}
										else
										{
											echo '-';
										}
									?></td>
            </tr>
        <?php } ?> 
         <?php if(FormFields::model()->isVisible('religion','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('religion');?></td>
                <td width="50%"><?php if($profile->religion!=NULL)
										{
											echo $profile->religion;
										}
										else
										{
											echo '-';
										}
									?></td>
            </tr>
        <?php } ?> 
        <?php if(FormFields::model()->isVisible('student_category_id','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('student_category_id');?></td>
                <td width="50%">
                    <?php 
                    if($profile->student_category_id!=NULL)
                    {
                        $cat =StudentCategories::model()->findByAttributes(array('id'=>$profile->student_category_id));
                        if($cat!=null)
                        { 
                            echo $cat->name;  
                        }
                    }
                    else{
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
         <?php } ?> 
         <!-- DYNAMIC FIELDS START -->
                                    <?php 
                                    $fields= FormFields::model()->getDynamicFields(1, 1, "forOnlineRegistration");
                                    if($fields)
                                    {
                                        foreach ($fields as $key => $field) 
                                        {							
                                            if($field->form_field_type!=NULL)
                                            {
                                                if(FormFields::model()->isVisible($field->varname,'Students','forOnlineRegistration'))
                                                {
													
                                                    ?>  
                                                    <tr>  
                                                        <td class=""><?php echo $profile->getAttributeLabel($field->varname);?></td>                                       
                                                        <td class=""><?php $field_name= $field->varname;
																if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
																	echo FormFields::model()->getFieldValue($profile->$field_name);
															  	}
															  	else if($field->form_field_type==6){  // date value
																	if($settings!=NULL and $profile->$field_name!=NULL and $profile->$field_name!="0000-00-00"){
															    		$date1  = date($settings->displaydate,strtotime($profile->$field_name));
															    		echo $date1;
																	}
																	else{
																		if($profile->$field_name!=NULL and $profile->$field_name!="0000-00-00"){
																  			echo $profile->$field_name;
																		}
																		else{
																			echo '-';
																		}
																	}
															  	}
															  	else{
																	echo (isset($profile->$field_name) and $profile->$field_name!="")?$profile->$field_name:"-";
															  	}
																?></td>
                                                    </tr>                        
                                                    <?php
                                                } 
                                            } 				                                            
                                        }
                                    }
                                    ?>
                                    <!-- DYNAMIC FIELDS END -->
            </table>
            
            <table width="95%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                	<th width="50%" colspan="2" align="left" style="padding-left:20px;"><?php echo Yii::t('app','Contact Details'); ?></th>
                </tr>
                <?php if(FormFields::model()->isVisible('address_line1','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('address_line1');?></td>
                <td width="50%"><?php if($profile->address_line1!=NULL)
										{
											echo $profile->address_line1;
										}
										else
										{
											echo '-';
										}
									?></td>
            </tr>
        <?php } ?>
        <?php if(FormFields::model()->isVisible('address_line2','Students','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $profile->getAttributeLabel('address_line2');?></td>
                    <td width="50%"><?php if($profile->address_line2!=NULL)
                                            {
                                                echo $profile->address_line2;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            </tr>
         <?php } ?>    
         <?php if(FormFields::model()->isVisible('city','Students','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $profile->getAttributeLabel('city');?></td>
                    <td width="50%"><?php if($profile->city!=NULL)
                                            {
                                                echo $profile->city;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            </tr>
         <?php } ?>    
         <?php if(FormFields::model()->isVisible('state','Students','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $profile->getAttributeLabel('state');?></td>
                    <td width="50%"><?php if($profile->state!=NULL)
                                            {
                                                echo $profile->state;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            </tr>
         <?php } ?>    
         <?php if(FormFields::model()->isVisible('country_id','Students','forOnlineRegistration')){?>
            <tr>
                <td width="50%"><?php echo $profile->getAttributeLabel('country_id');?></td>
                <td width="50%">
                    <?php
                    if($profile->country_id!=NULL)
                    {
                        $posts=Countries::model()->findByAttributes(array('id'=>$profile->country_id));
                        echo $posts->name; 
                    }
                    else
                    {
                        echo '-';
                    }?>
                 </td>
            </tr>
        <?php } ?>
        <?php if(FormFields::model()->isVisible('pin_code','Students','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $profile->getAttributeLabel('pin_code');?></td>
                    <td width="50%"><?php if($profile->pin_code!=NULL)
                                            {
                                                echo $profile->pin_code;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            </tr>
         <?php } ?>         
         <?php if(FormFields::model()->isVisible('phone1','Students','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $profile->getAttributeLabel('phone1');?></td>
                    <td width="50%"><?php if($profile->phone1!=NULL)
                                            {
                                                echo $profile->phone1;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            </tr>
         <?php } ?>  
         <?php if(FormFields::model()->isVisible('phone2','Students','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $profile->getAttributeLabel('phone2');?></td>
                    <td width="50%"><?php if($profile->phone2!=NULL)
                                            {
                                                echo $profile->phone2;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            </tr>
         <?php } ?>  
         <?php if(FormFields::model()->isVisible('email','Students','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $profile->getAttributeLabel('email');?></td>
                    <td width="50%"><?php if($profile->email!=NULL)
                                            {
                                                echo $profile->email;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            </tr>
         <?php } ?>
         
         <!-- DYNAMIC FIELDS START -->
                                    <?php 
                                    $fields= FormFields::model()->getDynamicFields(1, 2, "forOnlineRegistration");
                                    if($fields)
                                    {
                                        foreach ($fields as $key => $field) 
                                        {							
                                            if($field->form_field_type!=NULL)
                                            {
                                                if(FormFields::model()->isVisible($field->varname,'Students','forOnlineRegistration'))
                                                {
                                                    ?>  
                                                    <tr>  
                                                        <td class="last"><?php echo $profile->getAttributeLabel($field->varname);?></td>
                                                        <td class="last"><?php $field_name= $field->varname;
																if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
																	echo FormFields::model()->getFieldValue($profile->$field_name);
															  	}
															  	else if($field->form_field_type==6){  // date value
																	if($settings!=NULL and $profile->$field_name!=NULL and $profile->$field_name!="0000-00-00"){
															    		$date1  = date($settings->displaydate,strtotime($profile->$field_name));
															    		echo $date1;
																	}
																	else{
																		if($profile->$field_name!=NULL and $profile->$field_name!="0000-00-00"){
																  			echo $profile->$field_name;
																		}
																		else{
																			echo '-';
																		}
																	}
															  	}
															  	else{
																	echo (isset($profile->$field_name) and $profile->$field_name!="")?$profile->$field_name:"-";
															  	}
														?></td>
                                                    </tr>                        
                                                    <?php
                                                } 
                                            } 				                                            
                                        }
                                    }
                                    ?>
                                    <!-- DYNAMIC FIELDS END -->
         
			</table>

<!-- Parent 1 details Start -->           
            <table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="50%" colspan="2" align="center" style="padding-left:20px; border:0px; font-size:22px;"><?php echo Yii::t('app','GUARDIAN DETAILS'); ?></td>
                </tr>
                <tr>
                	<th width="50%" colspan="2" align="left" style="padding-left:20px;"><?php echo Yii::t('app','Personal Details'); ?></th>
                </tr>
          <?php if(FormFields::model()->isVisible('first_name','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('first_name');?></td>
                    <td width="50%"><?php if($parent->first_name!=NULL)
                                            {
                                                echo $parent->first_name;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>
         <?php if(FormFields::model()->isVisible('last_name','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('last_name');?></td>
                    <td width="50%"><?php if($parent->last_name!=NULL)
                                            {
                                                echo $parent->last_name;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>
         <?php if(FormFields::model()->isVisible('relation','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('relation');?></td>
                    <td width="50%"><?php if($parent->relation!=NULL)
                                            {
                                                echo $parent->relation;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>
         <?php if(FormFields::model()->isVisible('dob','Guardians','forOnlineRegistration')){?>   
              <tr>    
                <td width="50%"><?php echo $parent->getAttributeLabel('dob');?></td>
                <td width="50%"><?php if($settings!=NULL and $parent->dob!=NULL and $parent->dob!='0000-00-00'){	
												$date1=date($settings->displaydate,strtotime($parent->dob));
												echo $date1;										
												}else{
													echo '-';
												}?></td>
										  
             </tr>
         <?php } ?>                          
         <?php if(FormFields::model()->isVisible('education','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('education');?></td>
                    <td width="50%"><?php if($parent->education!=NULL)
                                            {
                                                echo $parent->education;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?> 
         <?php if(FormFields::model()->isVisible('occupation','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('occupation');?></td>
                    <td width="50%"><?php if($parent->occupation!=NULL)
                                            {
                                                echo $parent->occupation;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?> 
         <?php if(FormFields::model()->isVisible('income','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('income');?></td>
                    <td width="50%"><?php if($parent->income!=NULL)
                                            {
                                                echo $parent->income;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?> 
         <!-- DYNAMIC FIELDS START -->
                                    <?php 
                                    $fields= FormFields::model()->getDynamicFields(2, 1, "forOnlineRegistration");
                                    if($fields)
                                    {
                                        foreach ($fields as $key => $field) 
                                        {							
                                            if($field->form_field_type!=NULL)
                                            {
                                                if(FormFields::model()->isVisible($field->varname,'Guardians','forOnlineRegistration'))
                                                {
                                                    ?>  
                                                    <tr>  
                                                        <td class="last"><?php echo $parent->getAttributeLabel($field->varname);?></td>
                                                        <td class="last"><?php $field_name= $field->varname;
															if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
																echo FormFields::model()->getFieldValue($parent->$field_name);
															}
															else if($field->form_field_type==6){  // date value
																if($settings!=NULL and $parent->$field_name!=NULL and $parent->$field_name!="0000-00-00"){
																	$date1  = date($settings->displaydate,strtotime($parent->$field_name));
																	echo $date1;
																}
																else{
																	if($parent->$field_name!=NULL and $parent->$field_name!="0000-00-00"){
																		echo $parent->$field_name;
																	}
																	else{
																		echo '-';
																	}
																}
															}
															else{
																echo (isset($parent->$field_name) and $parent->$field_name!="")?$parent->$field_name:"-";
															}
															?></td>
                                                    </tr>                        
                                                    <?php
                                                } 
                                            } 				                                            
                                        }
                                    }
                                    ?>
                                    <!-- DYNAMIC FIELDS END -->              
                
               
			</table>  
            
            <table width="95%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                	<th width="50%" colspan="2" align="left" style="padding-left:20px;"><?php echo Yii::t('app','Contact Details'); ?></th>
                </tr>
         <?php if(FormFields::model()->isVisible('office_address_line1','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('office_address_line1');?></td>
                    <td width="50%"><?php if($parent->office_address_line1!=NULL)
                                            {
                                                echo $parent->office_address_line1;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>  
         <?php if(FormFields::model()->isVisible('office_address_line2','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('office_address_line2');?></td>
                    <td width="50%"><?php if($parent->office_address_line2!=NULL)
                                            {
                                                echo $parent->office_address_line2;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>    
         <?php if(FormFields::model()->isVisible('city','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('city');?></td>
                    <td width="50%"><?php if($parent->city!=NULL)
                                            {
                                                echo $parent->city;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>  
         <?php if(FormFields::model()->isVisible('state','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('state');?></td>
                    <td width="50%"><?php if($parent->state!=NULL)
                                            {
                                                echo $parent->state;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>       
          <?php if(FormFields::model()->isVisible('country_id','Guardians','forOnlineRegistration')){?>   
              <tr>    
                <td width="50%"><?php echo $parent->getAttributeLabel('country_id');?></td>
                <td width="50%"><?php if($parent->country_id){
				$count = Countries::model()->findByAttributes(array('id'=>$parent->country_id));
																if(count($count)!=0)
																echo $count->name;
				}
				else
				{
					echo '-';
				}?></td>
             </tr>
           <?php } ?>
           <?php if(FormFields::model()->isVisible('office_phone1','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('office_phone1');?></td>
                    <td width="50%"><?php if($parent->office_phone1!=NULL)
                                            {
                                                echo $parent->office_phone1;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>
         <?php if(FormFields::model()->isVisible('office_phone2','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('office_phone2');?></td>
                    <td width="50%"><?php if($parent->office_phone2!=NULL)
                                            {
                                                echo $parent->office_phone2;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?>  
         <?php if(FormFields::model()->isVisible('mobile_phone','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('mobile_phone');?></td>
                    <td width="50%"><?php if($parent->mobile_phone!=NULL)
                                            {
                                                echo $parent->mobile_phone;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?> 
         <?php if(FormFields::model()->isVisible('email','Guardians','forOnlineRegistration')){?> 
                <tr>
                    <td width="50%"><?php echo $parent->getAttributeLabel('email');?></td>
                    <td width="50%"><?php if($parent->email!=NULL)
                                            {
                                                echo $parent->email;
                                            }
                                            else
                                            {
                                                echo '-';
                                            }
                                        ?></td>
            	</tr>
         <?php } ?> 
         
         <!-- DYNAMIC FIELDS START -->
                                    <?php 
                                    $fields= FormFields::model()->getDynamicFields(2, 2, "forOnlineRegistration");
                                    if($fields)
                                    {
                                        foreach ($fields as $key => $field) 
                                        {							
                                            if($field->form_field_type!=NULL)
                                            {
                                                if(FormFields::model()->isVisible($field->varname,'Guardians','forOnlineRegistration'))
                                                {
                                                    ?>  
                                                    <tr>  
                                                        <td class=""><?php echo $parent->getAttributeLabel($field->varname);?></td>
                                                        <td class=""><?php $field_name= $field->varname;
															if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
																echo FormFields::model()->getFieldValue($parent->$field_name);
															}
															else if($field->form_field_type==6){  // date value
																if($settings!=NULL and $parent->$field_name!=NULL and $parent->$field_name!="0000-00-00"){
																	$date1  = date($settings->displaydate,strtotime($parent->$field_name));
																	echo $date1;
																}
																else{
																	if($parent->$field_name!=NULL and $parent->$field_name!="0000-00-00"){
																		echo $parent->$field_name;
																	}
																	else{
																		echo '-';
																	}
																}
															}
															else{
																echo (isset($parent->$field_name) and $parent->$field_name!="")?$parent->$field_name:"-";
															}
															?></td>
                                                    </tr>                        
                                                    <?php
                                                } 
                                            } 				                                            
                                        }
                                    }
                                    ?>
                                    <!-- DYNAMIC FIELDS END --> 
                
			</table>                                              
<!-- Parent 1 details Ends -->

      
           	<?php }?>
            <br />
            
            
            <?php if(FormFields::model()->isVisible('file','StudentDocument','forOnlineRegistration'))
        				 { ?>
					<h3><?php echo Yii::t('app','Student Document List'); ?></h3>
                   <?php 
                    $documents = StudentDocument::model()->findAllByAttributes(array('student_id'=>$profile->id)); // Retrieving documents of student with id $_REQUEST['id'];
                    if($documents) // If documents present
                            {
                    ?>
                    <table width="95%" cellspacing="0" cellpadding="0" style="margin:0 20px">
                    <tbody>
                        <tr>
                           <th width="40%"><?php echo Yii::t('app','Document Name'); ?></th>
                           <th width="40%"><?php echo Yii::t('app','Document Type'); ?></th>
                           <?php if($profile->status != 1){ ?>
                           	<th width="15%"><?php echo Yii::t('app','Download'); ?></th>
                           <?php } ?>
                        </tr>
                    </tbody>
                    </table>
                        <table width="95%" border="0" cellspacing="0" cellpadding="0" style="margin:0 20px; border-top:none;">
                            <?php
                            
                                foreach($documents as $document) // Iterating the documents
                                {
                                    $studentDocumentList = StudentDocumentList::model()->findByAttributes(array('id'=>$document->title));
                            ?>
                                    <tr>
                                        <td width="40%"><?php echo ucfirst($document->doc_type);?></td>
                                        <td width="40%"><?php echo ucfirst($document->file_type);?></td>
                                       <?php if($profile->status != 1){ ?> 
                                        <td width="15%">                                            
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('registration/download','id'=>$document->id,'student_id'=>$document->student_id),array('class'=>'tt-download')); ?>
                                            <?php /*?><?php echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', array('/onlineadmission/registration/stdDocDelete','document_id'=>$document->id,'token'=>$this->encryptToken($document->student_id),),array('class'=>'tt-download','confirm'=>'Are you sure?')); ?> <?php */?>                                               
                                        	<?php echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('registration/stdDocEdit','document_id'=>$document->id,'token'=>$this->encryptToken($document->student_id),),array('class'=>'tt-download')); ?>
                                        </td>
                                       <?php } ?> 
                                    </tr>
                            <?php	
                                } // End foreach($documents as $document)
                            ?>
                            </table>
                            <?php	
                            }										
                            else // If no documents present
                            {
                            ?>   
                                <br />                                        
                                <div align="center"><?php echo Yii::t('app','No document(s) uploaded'); ?></div>
                            <?php
                            }
						 }
                            ?>                        
             <br />           
             
             	
                    <?php
					$criteria = new CDbCriteria;
					$criteria->join = 'LEFT JOIN student_document osd ON osd.title = t.id and osd.student_id = '.$profile->id.'';
		 			$criteria->addCondition('osd.title IS NULL');
					
                    $documents = StudentDocumentList::model()->findAll( $criteria);
                   
                    if($documents) // If documents present
                    {
                    ?>
                    	<h3><?php echo Yii::t('app','Missing Document List'); ?></h3>
                    <table width="95%" cellspacing="0" cellpadding="0" style="margin:0 20px">
                    <tbody>
                        <tr>
                           <th width="40%"><?php echo Yii::t('app','Document Name'); ?></th>
                           <?php if($profile->status != 1){ ?>                          
                           		<th width="15%"><?php echo Yii::t('app','Upload'); ?></th>
                           <?php } ?>     
                          
                        </tr>
                    </tbody>
                    </table>
                        <table width="95%" border="0" cellspacing="0" cellpadding="0" style="margin:0 20px; border-top:none;">
                            <?php
                            
                                foreach($documents as $document) // Iterating the documents
                                {
									
                                    $studentdoc = StudentDocument::model()->findByAttributes(array('title'=>$document->id,'student_id'=>$profile->id));
                           
													?><tr>
                                                    <td width="40%"><?php echo ucfirst($document->name);?></td>
                                                    <?php if($profile->status != 1){ ?> 
                                                    	<td width="15%">                                            
                                            
                                                  			<?php echo CHtml::link('<span>'.Yii::t('app','Upload').'</span>', array('registration/missingDocEdit','document_id'=>$document->id,'token'=>$this->encryptToken($profile->id),),array('class'=>'tt-download')); ?>
                                        				</td>
                                                    <?php } ?>    
                                                  
												</tr>
                                                    <?php
												
											}
											?></table>
                                            <?php
										}
												
								?>       
             <br />           
            <h4><?php echo Yii::t('app','If you have any questions about the application review process, we encourage you to contact us at').'<br/> '.$admin->email; ?></h4>
            
			
           
      </div> <!-- END div class="status_botom" -->
    </div> <!-- END div class="confirm_bx" -->
    
    <?php
    //}
    ?>
    
    
    	
        </div> <!-- END div class="cont_right formWrapper" -->
    <div class="clear"></div>
</div> <!-- END div class="loginboxWrapper" -->
