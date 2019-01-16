<div id="parent_Sect">
	<?php 
    $this->renderPartial('leftside');
	$guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->Id));
	$parents = Guardians::model()->findAllByAttributes(array('email'=>$guardian->email));
	
	?>
    <div class="pageheader">
      <h2><i class="fa fa-desktop"></i> <?php echo Yii::t('app','Online Admission'); ?> <span><?php echo Yii::t('app','View online admission details here'); ?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','Online Admission'); ?></li>
        </ol>
      </div>
    </div>
    <div class="contentpanel">
<!--<div class="col-sm-9 col-lg-12">-->
<div>
    <div id="parent_rightSect">
    <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Students Status');?></h3>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>

                        <li><?php 
								$check_enable = OnlineRegisterSettings::model()->findByAttributes(array('config_key'=>'EnableAdmission'));
								if($check_enable->config_value==1){
									echo CHtml::link(Yii::t('app','Add Siblings'),array('/onlineadmission/registration/step1'),array('class'=>'btn btn-primary',"target"=>"_blank")); 
								}
							?>
                        </li>
                      </ul>
                    </div>
                  </div>
                        </div>
        <div class="people-item">
             <!-- END div class="profile_top" -->
<?php if($parents!=NULL)
	  {
?>		     
            <div class="table-responsive">
            
                <table class="table table-bordered mb30" >
                	<thead>
                        <tr class="pdtab-h">
                            <th width="25%"><?php echo Yii::t('app','Sl. No.');?></th>
                        <?php
                            if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){						
                        ?> 	
                            <th width="25%"><?php echo Yii::t('app','Student Name');?></th>
                        <?php } ?>    
                            <th width="25%"><?php echo Yii::t('app','Status');?></th>
                        <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){ ?>    
                            <th width="25%"><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></th>
                        <?php } ?>    
                        </tr>
                    </thead>
                <?php
				$i=1;
				foreach($parents as $parent)
				{
					
					$student = Students::model()->findAllByAttributes(array('parent_id'=>$parent->id,'is_deleted'=>0,'is_completed'=>3));
					foreach($student as $students)
					{
					
						$waitinglist_details = WaitinglistStudents::model()->findByAttributes(array('student_id'=>$students->id,'status'=>0));					 
						$batch = Batches::model()->findByAttributes(array('id'=>$waitinglist_details->batch_id));
						
						$approvedStudent = Students::model()->findByAttributes(array('email'=>$students->email));
						$approveStudentBatch = Batches::model()->findByAttributes(array('id'=>$approvedStudent->batch_id));
						
						$pending_student_batch = Batches::model()->findByAttributes(array('id'=>$students->batch_id));
						
						
						
						if($students->status == 0) // Pending
						{
							$status = Yii::t('app','Under review');
							
						}
						elseif($students->status == 1) // Approve
						{
							$status = Yii::t('app','Approved');
							
						}
						elseif($students->status == -1) // Disapprove
						{
							$status = Yii::t('app','Disapproved');
							
						}					
						elseif($students->status == -3) // Disapprove
						{
							$status = Yii::t('app','Waiting list');
							
						}					
						
						
					?>
						<tr>
							<td><?php echo $i; ?></td>
                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ ?>
							<td><strong><?php echo CHtml::link($students->studentFullName('forParentPortal'), array('/onlineadmission/registration/status','id'=>$students->id,'from'=>'parent'),array("target"=>"_blank")); ?></strong></td>
                        <?php } ?>
										                      
							<td>
								<?php 
									echo $status;
									if($students->status == -3)
									{
										echo "<br>".Yii::t('app','Priority :').$waitinglist_details->priority;								
									}
								?>
							</td>
                            <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){ ?> 
                                <td>
                                    <?php 	
                                        if($students->status == -3)
                                        { 														
                                            echo $batch->course123->course_name." - ".$batch->name;								
                                        }
                                        elseif($students->status == 1)
                                        {
                                            echo $approveStudentBatch->course123->course_name." - ".$approveStudentBatch->name;								
                                        }									
                                        elseif($students->status == 0)
                                        {
                                            echo $pending_student_batch->course123->course_name." - ".$pending_student_batch->name;	
                                        }
                                        else									
                                        {
                                            echo "-";
                                        }
                                    ?>
                                </td>
                            <?php } ?>    
						</tr>  
					<?php
					$i++;
					}
				}
				?>              
                </table>
            </div> <!-- END div class="profile_details" -->
<?php }else { echo '<div class="listhdg" align="center">'.Yii::t('app','Nothing Found!!').'</div>'; } ?>            
        </div> <!-- END div class="parentright_innercon" -->
    </div>
    </div>
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->

