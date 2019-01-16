<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,400italic' rel='stylesheet' type='text/css'>

<script>
	function getstudent() // Function to see student profile
	{
		var studentid = document.getElementById('studentid').value;
		if(studentid!='')
		{
			window.location= 'index.php?r=parentportal/default/lognotice&id='+studentid;	
		}
		else
		{
			window.location= 'index.php?r=parentportal/default/lognotice';
		}
	}
</script>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-sign-in" aria-hidden="true"></i><?php echo Yii::t('app','Log'); ?><span><?php echo Yii::t('app','View Log details here'); ?></span> </h2>
        </div>
        <div class="col-lg-2">
        
        <?php
		
		$guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$criteria = new CDbCriteria;		
		$criteria->join = 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
		$criteria->condition = 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
		$criteria->params = array(':guardian_id'=>$guardian->id,':is_active'=>1,'is_deleted'=>0);
		$students = Students::model()->findAll($criteria); 
		$student_list = CHtml::listData($students,'id','studentnameforparentportal');
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		
		
		 if(count($students)==1 or (isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)) // Show drop down only if more than 1 student present
        {
        echo CHtml::dropDownList('sid','',$student_list,array('prompt'=>Yii::t('app','Select Student'),'id'=>'studentid','class'=>'form-control input-sm mb14','style'=>'width:auto;','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getstudent();'));}
        ?>
        </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Log'); ?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    <?php $this->renderPartial('leftside');?>
<div class="contentpanel">
<!--<div class="col-sm-9 col-lg-12">-->
<div>
<div id="parent_Sect">
	
    <div id="parent_rightSect">
       <!-- <div class="parentright_innercon" style="background:#f2f2f2;">-->
        <div class="parentright_innercon">
        	
			<?php
            if(count($students)==1 or (isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)) // Single Student 
			{
				if(count($students)>1) // Show drop down only if more than 1 student present
				{
			?>
                    <?php /*?><div class="student_dropdown">
                        <?php
                        echo CHtml::dropDownList('sid','',$student_list,array('prompt'=>Yii::t('app','Select Student'),'id'=>'studentid','style'=>'width:200px;','class'=>'form-control','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getstudent();'));
                        ?>
                        <br />
                    </div><?php */?> <!-- END div class="student_dropdown" -->
            <?php
				//$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id'],'parent_id'=>$guardian->id,'is_active'=>'1','is_deleted'=>'0'));
					$criteria->condition = $criteria->condition." and (t.id LIKE :student_id)";
					$criteria->params[':student_id']  = $_REQUEST['id'];
					/*var_dump($criteria->condition);
					var_dump($criteria->params);
					exit;*/
					$student = Students::model()->find($criteria);
				} // END Show drop down only if more than 1 student present
				else
				{
					//$student = Students::model()->findByAttributes(array('parent_id'=>$guardian->id,'is_active'=>'1','is_deleted'=>'0'));	
					$student = Students::model()->find($criteria);	
				}
				 
			?>
            	<div class="people-item">
                          <div class="media">
                            <a href="#" class="pull-left">
                                <?php
                                 if($student->photo_file_name!=NULL)
                                 { 
								 	$path = Students::model()->getProfileImagePath($student->id);
                                    echo '<img  src="'.$path.'" width="100" height="103" class="thumbnail media-object" />';
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
									<h4 class="person-name"><?php $name = $student->studentFullName('forParentPortal');
										echo CHtml::link($name,array('/parentportal/default/studentprofile', 'id'=>$student->id));
									?></h4>
									<?php
								}
								?>
								<?php 
								$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'result_status'=>0));
								if(count($batchstudents)>1){ 
								echo CHtml::link('View Course Details', array('/parentportal/default/course', 'id'=>$student->id));
								}
								else{?>	
									  <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){?>
									  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
										<?php 
										  $batch = Batches::model()->findByPk($batchstudents[0]['batch_id']);
										  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
										  echo ($batch->course123->course_name)?$batch->course123->course_name:"-";
										  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
										?>
									  </div>          
									  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' : ';?></strong> <?php echo ($batch->name)?$batch->name:"-";?></div>
									  <?php } ?>
									  <?php
									  $semester_enabled		= Configurations::model()->isSemesterEnabled();   
									  $sem_enabled			= Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
									  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
												<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' : ';?></strong> <?php echo ($semester->name)?$semester->name:"-";?></div>
										<?php } ?>
							<?php } ?>	  
                              <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' : ';?></strong> <?php echo $student->admission_no; ?></div>
                              
                            </div>
                          </div>
                        </div> <!-- END div class="profile_top" -->
                        
                <div class="profile_details">
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
                    <?php $comments=LogComment::model()->findAllByAttributes(array('user_id'=>$student->id, 'user_type'=>1),array('order' => 'date desc')); ?>
                    
                    <?php  if($comments!=NULL)
						   {
							   foreach($comments as $comment){
						if($comment->visible_p){
							$user_com=Profile::model()->findByAttributes(array('user_id'=>$comment->created_by));
						 ?>
                    <div class="people-item" id="delete_div_<?php echo $comment->id; ?>">
                                	<h3><?php echo $user_com->fullname; ?>
                                    	<span style="color:#4A90CC;">                     
											<?php   
												$roles=Yii::app()->authManager->getRoles($comment->created_by);
												foreach ($roles as $role){
													echo '( '.ucfirst($role->name).' )';
												}
                                            ?>
                                    	</span>
                                    </h3>
                                    <h4 class="  label label-success"><?php echo $comment->category->name;?></h4>
                                     <div class="clear"></div>
                                    <p><?php echo $comment->comment;?></p>
                                    <smal class="text-muted"><?php 
									$date_time		=	Configurations::model()->convertDateTime($comment->date);
									echo $date_time;
									
									?></smal>
                     </div>
                     <?php }}
						   }
						   else
						   {
							?>
                            <div class="people-item" id="delete_div_empty">
                            	<h5><?php echo Yii::t('app',"No Log added");?></h5>
                            </div>
                            <?php  
						   }?>
                     
                </div> <!-- END div class="profile_details"-->
                
           
                
                
                
                
                
            <?php
			
			} // END Single Student
			elseif(count($students)>1 and !isset($_REQUEST['id'])) // More than one Student. Display List
			{
			?>
				<div id="profile_summary" style="position:relative; top:0px;">
				<?php
                foreach($students as $student)
                {
                ?>
                <div class="people-item">
                    <div class="media">
                        <div class="s_pimgbx">
                        <a href="#" class="pull-left">
							<?php
                            if($student->photo_file_name!=NULL)
                            { 
								$path = Students::model()->getProfileImagePath($student->id);
                            	echo '<img class="thumbnail media-object"  src="'.$path.'" alt="'.$student->photo_file_name.'" />';
                            }
                            elseif($student->gender=='M')
                            {
                                echo '<img class="thumbnail media-object"  src="images/portal/s_profile_m_icon.png" alt='.$student->first_name.' />'; 
                            }
                            elseif($student->gender=='F')
                            {
                                echo '<img class="thumbnail media-object"  src="images/portal/s_profile_fmicon.png" alt='.$student->first_name.' />';
                            }
                            ?>
                            </a>
                        </div> <!-- END div class="s_pimgbx" -->
                    <!-- END div class="s_pimg" -->
                    <div class="media-body">
                    <h4 class="person-name">
                        <?php echo CHtml::link($student->studentFullName('forParentPortal'), array('/parentportal/default/lognotice', 'id'=>$student->id));?>
                        </h4>
                        
                            	<div class="text-muted"><?php
								
								if($student->batch_id!=0 and $student->batch_id!=NULL)
								{
									$batch = Batches::model()->findByPk($student->batch_id);
								}
								else if ($student->batch_id==0)
								{
									
									$criteria = new CDbCriteria();
									$criteria->condition  = "student_id =:sid";
									$criteria->params = array(':sid'=>$student->id);
									$criteria->order = "id DESC";
									$criteria->limit = 1;
									
									$last = BatchStudents::model()->findAll($criteria);
									$batch = Batches::model()->findByPk($last[0]->batch_id);
								?>
                              
                               
                                	Alumni
                                </div>
								<?php	
								}

								?>
                                <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){?>
                                    <div class="text-muted">
                                    <?php echo Yii::t('app','Course :');?>
                                        
                                            <?php 
                                            echo $batch->course123->course_name;
                                            ?>
                                        
                                    </div>
                                    
                                    <div class="text-muted">
                                    <?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' : ';?><?php echo $batch->name;?></div>
                               <?php } ?>
                               <div class="text-muted"><?php echo Yii::t('app','Admission No').' : ';?><?php echo $student->admission_no; ?></div>
                           
                        </div> <!-- END div class="s_profile_listinner" -->
                    </div>
                    
                      <!-- END div class="s_profile_list_lft" -->
                    
                    	
                     
                    </div><!-- END div class="s_profile_list_rht" -->
                    <div class="clear"></div>
                </div> <!-- END div class="s_profile_listbx" -->
                <?php
                } // END foreach($students as $student)
                ?>
                </div> <!-- END div id="profile_summary" -->
			<?php                
			} // END More than one student. End Display List
			elseif(count($students)<=0) // No Student
			{
			?>
            	<div class="yellow_bx" style="background-image:none;width:750px;padding-bottom:45px;">
                    <div class="y_bx_head">
                        <?php echo Yii::t('app','No student details are available now!'); ?>
                    </div>      
                </div>
            <?php
			} // END No Student
			?>
         
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
	<div class="clear"></div>
</div>
</div>
</div> <!-- div id="parent_Sect" -->