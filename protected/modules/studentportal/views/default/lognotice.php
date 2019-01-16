<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,400italic' rel='stylesheet' type='text/css'>
<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
?>
<?php $this->renderPartial('leftside');?>

<div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-sign-in" aria-hidden="true"></i><?php echo Yii::t('app','Student Log');?><span><?php echo Yii::t('app','View your Student Log here'); ?> </span></h2>
  </div>
 
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
    <ol class="breadcrumb">
     <li class="active"><?php echo Yii::t('app','Student Log'); ?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>


<div class="contentpanel">
	
    <div id="col-sm-9 col-lg-12">
       <!-- <div class="parentright_innercon" style="background:#f2f2f2;">-->
       
       <div class="people-item">
      <div class="media"> <a href="#" class="pull-left">
        <?php
		 			$student = Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id,'is_active'=>'1','is_deleted'=>'0'));
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
                     if($student->photo_file_name!=NULL)
                     { 
					 	$path = Students::model()->getProfileImagePath($student->id);
                        echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" class="thumbnail media-object" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />';
                    }
                    ?>
        </a>
        <div class="media-body">
          <?php
          if(FormFields::model()->isVisible("fullname", "Students", "forStudentPortal")){
          ?>
          <h4 class="person-name"><?php echo $student->studentFullName("forStudentPortal");?></h4>
          <?php
          }
          ?>
          <?php 
		  $semester_enabled	= Configurations::model()->isSemesterEnabled();    
		  $batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'status'=>1, 'result_status'=>0));
		  if(count($batchstudents)>1){ 
			echo CHtml::link('View Course Details', array('/studentportal/default/course'));
			}
			else{?>	
				  <?php if(in_array('batch_id', $student_visible_fields)){ ?>      
				  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
					<?php 
					  $batch = Batches::model()->findByPk($batchstudents[0]['batch_id']);
					  $course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
					   $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
					  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
					  echo $batch->course123->course_name;
					  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
					?>
				  </div>          
				  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
				  <?php } ?>
				  
				   <?php  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
							<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo $semester->name;?></div>
					<?php } ?>
					
					
		<?php } ?> 
          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
          
        </div>
      </div>
    </div>
     <div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Student Log'); ?></h3>
    </div>
      
        <div class="people-item">
        
               
                	<?php
					Yii::app()->clientScript->registerScript(
					   'myHideEffect',
					   '$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
					   CClientScript::POS_READY
					);
					
					if(Yii::app()->user->hasFlash('successMessage')): 
					?>
					<div class="flashMessage" style="color:#C00; padding-left:300px;">
						<?php echo Yii::app()->user->getFlash('successMessage'); ?>
					</div>
					<?php
					endif;
					
					if(Yii::app()->user->hasFlash('errorMessage')): 
					?>
					<div class="flashMessage" style="color:#C00; padding-left:300px;">
						<?php echo Yii::app()->user->getFlash('errorMessage'); ?>
					</div>
					<?php
					endif;
					?>
                    <?php $comments=LogComment::model()->findAllByAttributes(array('user_id'=>$student->id, 'user_type'=>1),array('order' => 'date desc'));

					
					 ?>
                    
                    <?php
					if($comments){
					  foreach($comments as $comment){
						if($comment->visible_s){
							$user_com=Profile::model()->findByAttributes(array('user_id'=>$comment->created_by));
						 ?>
                          
                    <div class="log_comment_box" id="delete_div_<?php echo $comment->id; ?>">
                     
                     			<h3><?php echo $user_com->fullname; ?><span>
                     
                                <?php   $roles=Yii::app()->authManager->getRoles($comment->created_by);
										foreach ($roles as $role)
										{
											echo '( '.ucfirst($role->name).' )';
										}
										?></span></h3>
                                	
                                   <h4 class="label label-success"><?php echo $comment->category->name;?></h4>
                                    
                                    <p><?php echo $comment->comment;?></p>
                                    
<smal class="text-muted"><?php echo date($settings->displaydate,strtotime($comment->date)).' '.date($settings->timeformat,strtotime($comment->date));?></small>
                                    <span class="log_cmnt_date"></span>
                     </div>
                     <?php }}
					 
					}else{
						echo Yii::t('app','No logs added');
					}
					  ?>
                     
               <!-- END div class="profile_details"-->
                
           
         
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
	<div class="clear"></div>
</div> <!-- div id="parent_Sect" -->