<style type="text/css">
.nothing-found{
	text-align:center;
	font-style:italic;
}
</style>
<?php 
    $this->renderPartial('leftside');
	
	$guardian	= Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->Id));
	
	$criteria				= new CDbCriteria();
	$criteria->join			= 'JOIN `guardian_list` `t1` ON `t1`.`student_id` = `t`.`id`';
	$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t`.`is_completed`=:is_completed AND `t`.`is_online`=:is_online AND `t1`.`guardian_id`=:guardian_id';
	$criteria->params		= array(':is_deleted'=>0, ':is_completed'=>3, ':is_online'=>1, ':guardian_id'=>$guardian->id);
	$students				= Students::model()->findAll($criteria);	
?>
<div class="pageheader">
    <h2><i class="fa fa-desktop"></i> <?php echo Yii::t('app','Online Admission'); ?> <span><?php echo Yii::t('app','View online admission details here'); ?></span></h2>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">        
        	<li class="active"><?php echo Yii::t('app','Online Admission'); ?></li>
        </ol>
    </div>
</div>	
<div class="contentpanel">
	<div class="panel-heading">
   		<h3 class="panel-title"><?php echo Yii::t('app','Online Registration Status');?></h3>       
    </div>
    <div class="people-item">
        <div class="opnsl_headerBox">
            <div class="opnsl_actn_box"></div>
            <div class="opnsl_actn_box">
                <div class="opnsl_actn_box1">
					<?php 						
						if(Configurations::model()->checkAdmissionEnabled()){
							echo CHtml::link(Yii::t('app','Add Siblings'),array('/onlineadmission/registration/step1'),array('class'=>'btn btn-primary',"target"=>"_blank")); 
						}
					?>
                </div>               
            </div>            
        </div>
    
    	<div class="table-responsive">       
        	<table class="table table-bordered mb30">
                <thead>
                    <tr class="pdtab-h">
                        <th width="15"><?php echo '#';?></th>                        
                        <th width="30"><?php echo Yii::t('app','Student Name');?></th>                           
                        <th width="20"><?php echo Yii::t('app','Status');?></th>                           
                        <th width="35"><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></th>
                    </tr>
<?php
					if($students){						
						$i	= 1;
						foreach($students as $student){
							if($student->status == 0){ // Pending						
								$status = Yii::t('app','Under review');	
								$color	= '#33b5e5';						
							}
							elseif($student->status == 1){ // Approve						
								$status = Yii::t('app','Approved');		
								$color	= '#00C851';						
							}
							elseif($student->status == -1){ // Disapprove						
								$status = Yii::t('app','Disapproved');	
								$color	= '#ff4444';							
							}					
							elseif($student->status == -3){ // Disapprove						
								$status = Yii::t('app','Waiting list');	
								$color	= '#FF8800';							
							}	
							else{
								$status	= '-';
								$color	= '';
							}
?>	
							<tr>                            
								<td><?php echo $i; ?></td>
                                <td><?php echo CHtml::link($student->studentFullName('forParentPortal'), array('/onlineadmission/registration/status','id'=>$student->id,'from'=>'parent'),array("target"=>"_blank")); ?></td>
                                <td>
                                	<span style="color:<?php echo $color; ?>">
										<?php echo $status; ?>
                                    </span>
                                    <?php
										if($student->status == -3){
											$waitinglist	= WaitinglistStudents::model()->findByAttributes(array('student_id'=>$student->id,'status'=>0));
											echo "<br><span style='font-style:italic;'>".Yii::t('app','Priority :').$waitinglist->priority.'</span>';								
										}
									?>
                                </td>
                                <td>
									<?php
										$batch	= array(); 
										if($student->status == 0 or $student->status == 1){ //In case pending or approved status
											$batch	= Batches::model()->findByPk($student->batch_id);											
										}
										else if($student->status == -3){												
											if($waitinglist){
												$batch	= Batches::model()->findByAttributes(array('id'=>$waitinglist->batch_id));
											}
										}
										if($batch != NULL){
											echo html_entity_decode(ucfirst($batch->course123->course_name))." / ".html_entity_decode(ucfirst($batch->name));	
										}
										else{
											echo '-';
										}
									?>
                                </td>
                            </tr>    
<?php								
							$i++;
						}
					}
					else{
?>
						<tr>
                        	<td colspan="4" class="nothing-found"><?php echo Yii::t('app', 'No Students Found'); ?></td>
                        </tr>
<?php						
					}
?>                    
                </thead>
        	</table>        
        </div>
    </div>
</div>