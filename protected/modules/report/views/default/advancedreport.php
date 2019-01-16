<style>
.formCon select{
	padding:7px 3px !important;
}


</style>
<script language="javascript">



function tab()
{
	$("#filter").slideToggle("slow");
	$("#Students_dobrange").val('');
	$("#Students_admissionrange").val('');
	$("#Students_date_of_birth").val('');
	$("#Students_admission_date").val('');	
}
</script>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Report')=>array('/report'),
	Yii::t('app','Advanced Report'),
);
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
$semester_enabled		  = Configurations::model()->isSemesterEnabled(); 
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-form',
	'enableAjaxValidation'=>false,
	'method' => 'GET',
	'action'=>CController::createUrl('/report/default/advancedreport')
)); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
        <td width="247" valign="top">
       		<?php $this->renderPartial('left_side');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Student Information');?></h1>
                <div class="formCon">
                    <div class="formConInner">
                    <div class="text-fild-bg-block">
                        <div class="text-fild-block inputstyle">
                        	<label class="opnsl_label"><?php echo Yii::t('app','Name');?></label>
                            <input type="text" name="studentname" value="<?php echo (isset($_REQUEST['studentname']) and $_REQUEST['studentname'] != NULL)?$_REQUEST['studentname']:''; ?>" />
                        </div>
						<div class="text-fild-block inputstyle">
                        	<label class="opnsl_label"><?php echo Yii::t('app','Admission Number');?></label>
                            <input type="text" name="admissionnumber" value="<?php echo (isset($_REQUEST['admissionnumber']) and $_REQUEST['admissionnumber'] != NULL)?$_REQUEST['admissionnumber']:''; ?>" />
                        </div>
                        <div class="text-fild-block inputstyle">
                        	<label class="opnsl_label"><?php echo Yii::t('app','Email');?></label>
                            <input type="text" name="email" value="<?php echo (isset($_REQUEST['email']) and $_REQUEST['email'] != NULL)?$_REQUEST['email']:''; ?>" />
                        </div>
                    </div>
                    
                    <div class="text-fild-bg-block">
                        <div class="text-fild-block inputstyle">
                       		<label class="opnsl_label"> <?php echo Yii::t('app','Gender');?></label>
                            <?php echo CHtml::activeDropDownList($model,'gender',array('M' => Yii::t('app','Male'), 'F' => Yii::t('app','Female')),array('prompt'=>Yii::t('app','All'), 'options' => array($_REQUEST['Students']['gender']=>array('selected'=>true)))); ?>
                        </div>
						<div class="text-fild-block inputstyle">
                        	<label class="opnsl_label"><?php echo Yii::t('app','Blood Group');?></label>
                            <?php echo CHtml::activeDropDownList($model,'blood_group',
                                        array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-'),
                                        array('prompt' => Yii::t('app','Select'), 'options' => array($_REQUEST['Students']['blood_group']=>array('selected'=>true)))); ?>
                        </div>
                        <div class="text-fild-block inputstyle">
                        	
                        </div>
                    </div>
                    <div class="text-fild-bg-block">
                        <div class="checkbox_custom">
                       		<input type="checkbox" id="checkbox-1-1" class="regular-checkbox"  name="guard" <?php if(isset($_REQUEST['guard']) and $_REQUEST['guard'] == 'on'){ ?> checked="checked" <?php } ?> /><label for="checkbox-1-1"><?php echo Yii::t('app','Include guardian details');?></label>
                        </div>
                        

                    </div>
                    
                                        
                        <?php /*?><table width="100%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Name');?></strong></td>
                                <td>&nbsp;</td>
                                <td><input type="text" name="studentname" value="<?php echo (isset($_REQUEST['studentname']) and $_REQUEST['studentname'] != NULL)?$_REQUEST['studentname']:''; ?>" /></td>
                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Admission Number');?></strong></td>
                                <td>&nbsp;</td>
                                <td> <input type="text" name="admissionnumber" value="<?php echo (isset($_REQUEST['admissionnumber']) and $_REQUEST['admissionnumber'] != NULL)?$_REQUEST['admissionnumber']:''; ?>" /></td>
                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Email');?></strong></td>
                                <td>&nbsp;</td>
                                <td><input type="text" name="email" value="<?php echo (isset($_REQUEST['email']) and $_REQUEST['email'] != NULL)?$_REQUEST['email']:''; ?>" /></td>
                            </tr>
                            
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td valign="top"><strong><?php echo Yii::t('app','Gender');?></strong></td>
                                <td>&nbsp;</td>
                                <td><?php echo CHtml::activeDropDownList($model,'gender',array('M' => Yii::t('app','Male'), 'F' => Yii::t('app','Female')),array('prompt'=>Yii::t('app','All'), 'options' => array($_REQUEST['Students']['gender']=>array('selected'=>true)))); ?></td>
                            </tr>
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Blood Group');?></strong></td>
                                <td>&nbsp;</td>
                                <td><?php echo CHtml::activeDropDownList($model,'blood_group',
                                        array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-'),
                                        array('prompt' => Yii::t('app','Select'), 'options' => array($_REQUEST['Students']['blood_group']=>array('selected'=>true)))); ?></td>
                            </tr>
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                               
                                <td>
                               
                                <input type="checkbox" id="checkbox-1-1" class="regular-checkbox"  name="guard" <?php if(isset($_REQUEST['guard']) and $_REQUEST['guard'] == 'on'){ ?> checked="checked" <?php } ?> /><strong><?php echo Yii::t('app','Include guardian details');?></strong></td>
                                <td>&nbsp;</td>
                                <td></td>
                            </tr>
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                        </table><?php */?> <!-- END class="s_search" -->
                       
                        <?php
                        $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                        if($settings!=NULL)
                        {
                        	$date=$settings->dateformat;                        
                        }
                        else
                       		$date = 'dd-mm-yy';	
                        ?>
                        
                        
                        <div onclick="tab();" style="cursor:pointer; color:#069; padding-left:0px; padding-top:4px;">
                        	<span style="font-weight:bold;">
								<?php echo Yii::t('app','Advanced Search');?>
							</span>
						</div>
                        <div class="white_bx" style="display:none" id="filter">
                        <!-- Advanced Search Table -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="opnsl_inputField_table " >
                            <tr>
                                <td>
                               		 <label class="opnsl_label"><?php echo Yii::t('app','Date Of Birth');?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                        <tr>
                                            <td>
												<?php
                                                echo CHtml::activeDropDownList($model,'dobrange',array('1' => 'less than', '2' => 'equal to', '3' => 'greater than'),array('prompt' =>'Option')); 
                                                ?>
                                            </td>
                                            <td>
												<?php 
                                                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                                'name'=>'Students[date_of_birth]',
                                                'model'=>$model,
                                                'value'=>$model->date_of_birth,
                                                // additional javascript options for the date picker plugin
                                                'options'=>array(
                                                'showAnim'=>'fold',
                                                'dateFormat'=>$date,
                                                'changeMonth'=> true,
                                                'changeYear'=>true,
                                                'yearRange'=>'1950:2050'
                                                ),
                                                'htmlOptions'=>array(
                                                'readonly' => 'readonly',
                                                ),
                                                ));?>
                                            </td>
                                        </tr>
                                    </table> 
                                </td>
                            </tr>
                            <tr> 
                                <td>
                                  <label class="opnsl_label"><?php echo Yii::t('app','Admission Date');?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                            	<?php echo CHtml::activeDropDownList($model,'admissionrange',array('1' => 'less than', '2' => 'equal to', '3' => 'greater than'),array('prompt'=>'Option')); ?>
                                            </td>
                                            <td>
												<?php 
                                                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                                'name'=>'Students[admission_date]',
                                                //'attribute'=>'admission_date',
                                                'model'=>$model,
                                                'value'=>$model->admission_date,
                                                'options'=>array(
                                                'showAnim'=>'fold',
                                                'dateFormat'=>$date,
                                                'changeMonth'=> true,
                                                'changeYear'=>true,
                                                ),
                                                'htmlOptions'=>array(
                                                'readonly'=>"readonly",
                                                ),
                                                ));
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr> 
                            </table>
						</div>
                         <!-- END Advanced Search Table -->
                        <?php //echo CHtml::checkBox('guard'); 
						?>
                        <div style="margin-top:10px;">
                        <?php echo CHtml::hiddenField('search', 'Search');?>
						<?php echo CHtml::submitButton(Yii::t('app','Search'),array('name'=>'','class'=>'formbut')); ?></div>  
                       
                    </div> <!-- END div class="formConInner" -->
                </div> <!--END div class="formCon" -->
                
                <div class="pdf-box">
                    <div class="box-one"></div>
                    <div class="box-two">
                         <div class="pdf-div">
							 <?php
							 	if(isset($list) and $list != NULL){
							?>
                                    <button  type="submit" class="pdf_but-input" name="pdf-btn" formtarget="_blank" style="outline:none;">
                                    	<?php echo Yii::t('app','Generate PDF')?>
                                    </button>  								 	
							<?php
                            	}
                             ?>
                         </div>
                    </div>
                </div>
                <!-- Search Results -->  
                <?php if(isset($list))
                {					
               ?>
                            
					<div class="tablebx">  
                      <!--  <div class="pagecon">
							 <?php 
                                  $this->widget('CLinkPager', array(
                                  'currentPage'=>$pages->getCurrentPage(),
                                  'itemCount'=>$item_count,
                                  'pageSize'=>$page_size,
                                  'maxButtonCount'=>5,
                                  //'nextPageLabel'=>'My text >',
                                  'header'=>'',
                              'htmlOptions'=>array('class'=>'pages'),
                              ));?>
                        </div> <!-- End div class="pagecon" -->  
                        <!-- Result Table -->                                  
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="tablebx_topbg">
                                <td><?php echo Yii::t('app','Sl. No.');?></td>	
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                <td><?php echo Yii::t('app','Student Name');?></td>
                                <?php } ?>
                                <td><?php echo Yii::t('app','Admission No');?></td>
                                <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                                <td><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></td>
                                <?php } ?>
								
								<?php if($semester_enabled == 1){ ?>
                                <td><?php echo Yii::t('app','Semester');?></td>
                                <?php } ?>
								
                                <?php if(in_array('gender', $student_visible_fields)){ ?>
                                <td><?php echo Yii::t('app','Gender');?></td>
                                <?php } ?>
								
                                <?php
								if( ($flag=='1'))
								{
								?>
								<td><?php echo Yii::t('app','Guardian');?></td>
								<?php
								}
								
								?>
							</tr>
							<?php 
							if($list!=NULL)
							{
								if(isset($_REQUEST['page']))
								{
								$i=($pages->pageSize*$_REQUEST['page'])-9;
								}
								else
								{
								$i=1;
								}
								
								$cls="even";
								
								foreach($list as $list_1)
								{ 
								?>
									<tr class=<?php echo $cls;?>>
									
										<td><?php echo $i; ?></td>
										<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
										<td>
											<?php echo CHtml::link($list_1->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$list_1->id)) ?>
										</td>
                                        <?php }?>
										
										<td>
											<?php echo $list_1->admission_no ?>
										</td>
										<?php if(in_array('batch_id', $student_visible_fields)){ ?>
										<td>
											<?php 
											$batc = Batches::model()->findByAttributes(array('id'=>$list_1->batch_id)); 
											if($batc!=NULL)
											{
												$cours = Courses::model()->findByAttributes(array('id'=>$batc->course_id)); 
												echo $cours->course_name.' / '.$batc->name; 
											}
											else
											{
												echo '-'; 
											}
											?>
										</td>
										<?php }?>
										<?php $sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($cours->id);?>
										<td>
											<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batc->semester_id != NULL){ 
													$semester	= Semester::model()->findByAttributes(array('id'=>$batc->semester_id));
													echo ucfirst($semester->name);
											 } 
											 else{
												 echo '-';
											 }?>
										</td>
										
										
										
                                        <?php if(in_array('gender', $student_visible_fields)){ ?>
										<td>
											<?php if($list_1->gender=='M')
											{
												echo 'Male';
											}
											elseif($list_1->gender=='F')
											{
												echo 'Female';
											}
											else
											{
												echo '-';
											}?>
										</td>
										<?php } ?>
										
										<?php
										if(isset($flag) && ($flag!='0'))
										{
										$guard=Guardians::model()->findByAttributes(array('id'=>$list_1->parent_id));
										?>
										<td>
											<?php 
											if($guard!=NULL)
												echo CHtml::link(ucfirst($guard->first_name).' '.ucfirst($guard->last_name),array('/students/guardians/view','id'=>$guard->id));
											else
												echo '-';	
											?> 
										</td>
										<?php
										}
										
										?>
										<!--<td style="border-right:none;">Task</td>-->
									</tr>
									
									<?php
									if($cls=="even")
									{
										$cls="odd" ;
									}
									else
									{
										$cls="even"; 
									}
									$i++;
								}
							} // End If $list!=NULL
							else //$list == NULL
							{
								echo '<tr><td align="center" colspan="5"><strong>'.Yii::t('app','No Results Found!').'</strong></td></tr>';		
							}
							?>
							</table>
							<!-- End Result Table -->
							<div class="pagecon">
							<?php 
							                                         
							  $this->widget('CLinkPager', array(
							  'currentPage'=>$pages->getCurrentPage(),
							  'itemCount'=>$item_count,
							  'pageSize'=>$page_size,
							  'maxButtonCount'=>5,
							  //'nextPageLabel'=>'My text >',
							  'header'=>'',
							  'htmlOptions'=>array('class'=>'pages'),
							  ));?>
							 </div> <!-- End bottom div class="pagecon" -->
							<div class="clear"></div>
						</div> <!-- End div class="tablebx" -->
						<?php 
						
                }
				
                ?>
                <!-- End Search Results --> 
            </div>
        </td>
    </tr>
</table>
 <?php $this->endWidget(); ?>