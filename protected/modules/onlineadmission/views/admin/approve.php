<style type="text/css">
.newstatus{ 
	background-color: #93c0d6;
    color: #fff;
    float: left;
    font-size: 12px;
    font-weight: bold;
    margin-right: 5px;
    padding: 5px 10px;
}					
.loading_app{ 
	background-image:url(images/loading_app.gif);
	height:30px;
	float:left;
	width:30px;
	margin-left:10px;
	display:none
}	
.ui-widget-content a {
    color: #fff !important;
}	
.formbut-n{
	margin-top:10px;
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'jobDialog'.$model->id,
	'options'=>array(
		'title'=>Yii::t('app','Student Approval'),
		'autoOpen'=>true,
		'modal'=>'true',
		'width'=>'500',
		'height'=>'auto',
	),
));
?>

	<div style="padding:10px 20px 10px 20px; overflow:hidden">
    	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'student-approval-form',			
			));
				
				if($error_flag == 1){
					if($model != NULL and $form->errorSummary($model)){
						echo '<div class="notify-header">'.Yii::t('app','Student Related Errors').'</div>';
						echo $form->errorSummary($model);
					}			
					if($model_1 != NULL and $form->errorSummary($model_1)){
						echo '<div class="notify-header">'.Yii::t('app','Guardian Related Errors').'</div>';
						echo $form->errorSummary($model_1);
					}	
					echo CHtml::link(Yii::t('app','Edit Profile'), array('/onlineadmission/admin/profileedit', 'id'=>$model->id),array('class'=>'formbut-n'));			
				}
				else{ 
		?>
        
                    <div class="formCon">	
                        <div class="formConInner" style="width:100%; height:auto;">
                            <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forOnlineRegistration")){ ?>
                                    <tr>                    
                                        <td><strong><?php echo Yii::t('app','Full Name'); ?></strong></td>
                                        <td style="padding:8px;">
                                            <?php 
                                                $name = '-';
                                                if(FormFields::model()->isVisible("fullname", "Students", "forOnlineRegistration")){                                               
                                                       $name =  $model->studentFullName('forOnlineRegistration');
                                                }
                                                echo $name;
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo Yii::t('app','Registration ID'); ?></strong></td>
                                    <td style="padding:8px;"><?php echo $model->registration_id; ?></td>
                                </tr>
                                <?php if(FormFields::model()->isVisible('date_of_birth','Students','forOnlineRegistration')){ ?>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr> 
                    
                                    <tr>
                                        <td><strong><?php echo Yii::t('app','Date of Birth'); ?></strong></td>
                                        <td style="padding:8px;">
                                        <?php
                                        $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                        if($settings!=NULL)
                                        {	
                                            $dob = date($settings->displaydate,strtotime($model->date_of_birth));
                                        }
                                        else
                                        {
                                            $dob = $model->date_of_birth;
                                        }
                                        echo $dob;
                                        ?>
                                        </td>
                                    </tr>
                                <?php } ?> 
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr> 
                                <?php if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){ ?>
                                    <tr>
                                        <td><strong><?php echo Yii::app()->getModule("students")->labelCourseBatch(); ?></strong></td>
                                        <td style="padding:8px;">
        <?php                                					                                	 
                                            $batches 				= Batches::model()->findAll("is_deleted=:x AND academic_yr_id=:y AND is_active=:z", array(':x'=>'0',':y'=>Yii::app()->user->year,':z'=>1));
                                            $data 					= array();
                                            foreach($batches as $batch){                            
                                                $data[$batch->id] = $batch->course123->course_name.' / '.$batch->name;
                                            }
                                        
                                            if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
                                                echo CHtml::dropdownlist('batch', '', $data, array('id'=>'batch-'.microtime(),'empty'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'encode'=>false,'options' => array($_REQUEST['bid']=>array('selected'=>true)),));
                                            }
                                            else{
                                                echo CHtml::dropdownlist('batch', '', $data, array('id'=>'batch-'.microtime(),'empty'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'encode'=>false,));
                                            }                                            
        ?>                                
                                        </td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>    
            
<?php
					$registered_guardian 	= Guardians::model()->findByAttributes(array('id'=>$model->parent_id));
					$criteria				= new CDbCriteria;
					$criteria->join 		= 'JOIN guardian_list t2 ON t.id = t2.guardian_id JOIN students t1 ON t1.id = t2.student_id'; 	
					$criteria->condition 	= 't1.type=:type and t.email=:email';
					$criteria->params 		= array(':type'=>0,':email'=>$registered_guardian->email);
					$existing_guardian 		= Guardians::model()->find($criteria);	
					if($existing_guardian){
						$siblings = Students::model()->findAllByAttributes(array('parent_id'=>$existing_guardian->id,'type'=>0));
						echo Yii::t('app','Guardian already exists in the database').'.'.Yii::t('app','Guardian can login using their existing account').'.';
		?>
						<div class="formCon">	
							<div class="formConInner">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<?php if(FormFields::model()->isVisible("fullname", "Guardians", "forOnlineRegistration")){ ?>
										<tr>
											<td><strong><?php echo Yii::t('app','Guardian Name'); ?></strong></td>
											<td style="padding:8px;">
		<?php                                     										
												$gname	= $existing_guardian->parentFullName('forOnlineRegistration');                    
												echo CHtml::link($gname, array('/students/guardians/view', 'id'=>$existing_guardian->id),array('target'=>'_blank','style'=>'color:#f60 !important'));                                            
		?>                                    
											</td>
										</tr>
									<?php } ?>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr> 
									<tr>
										<td><strong><?php echo Yii::t('app','Guardian Email'); ?></strong></td>
										<td style="padding-left:8px;"><?php echo $existing_guardian->email; ?></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
		<?php
									if($siblings){
		?>
										<tr>
											<td><strong><?php echo Yii::t('app','Siblings'); ?></strong></td>
											<td style="padding:8px;">
		<?php                                        
												foreach($siblings as $sibling){
													if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){												
														$sname=  $sibling->studentFullName('forStudentProfile');
							echo CHtml::link($sname, array('/students/students/view', 'id'=>$sibling->id),array('target'=>'_blank','style'=>'color:#f60 !important')).'<br/>';
													}
												}
		?>                                        
											</td>
										</tr>
		<?php								
									}
		?>                            
								</table>
							</div>
						</div>        
		<?php				
					}
					elseif($registered_guardian){
		?>
						<div class="formCon">	
							<div class="formConInner">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td><strong><?php echo Yii::t('app','Guardian Name'); ?></strong></td>
										<td style="padding:8px;">
											<?php 
												echo CHtml::link(ucfirst($registered_guardian->first_name).' '.ucfirst($registered_guardian->last_name), array('/onlineadmission/admin/view', 'id'=>$model->id),array('target'=>'_blank','style'=>'color:#f60 !important'));
											?>
										</td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr> 
									<tr>
										<td><strong><?php echo Yii::t('app','Guardian Email'); ?></strong></td>
										<td style="padding:8px;"><?php echo $registered_guardian->email; ?></td>
									</tr>                            
								</table>                
							</div>
						</div>
		<?php				
					}
					echo $form->hiddenField($model,'id');
		?>            
					<div class="row buttons">
						<div style="float:left">
							 <?php echo CHtml::ajaxSubmitButton(Yii::t('app','Approve'),CHtml::normalizeUrl(array('approve')),array('dataType'=>'json','timeout'=>'90000' ,'beforeSend' => 'function(){
									$(".loading_app").show();
								}','success'=>'js: function(data) { 
										if (data.status == "success")
										{
											$("#jobDialog'.$model->id.'").dialog("close");
											window.location.reload();
										}
									}'),array('id'=>'closeJobDialog'.$model->id,'name'=>'approve','class'=>'gifloadimage')); ?>
                        </div>
                        <div class="loading_app"></div>
                    </div>        	
        <?php 
				}
			$this->endWidget(); 
		?>
    </div>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
















