<?php $this->renderPartial('leftside');?> 

    <?php
    $batch_id="";
    if(isset($_REQUEST['bid']) && $_REQUEST['bid']!=NULL)
    {
        $batch_id= $_REQUEST['bid'];
        $ex_group= ExamGroups::model()->findAllByAttributes(array('batch_id'=>$batch_id,'result_published'=>1));                             
    }
    $student_id="";
    if(isset($_REQUEST['sid']) && $_REQUEST['sid']!=NULL)
    {
        $student_id= $_REQUEST['sid'];
    }
    
    
    $guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $student=Students::model()->findByAttributes(array('id'=>$student_id));
    $exam = ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
	//$electives = ElectiveScores::model()->findAll("student_id=:x", array(':x'=>$student->id));

	$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
    ?>
    
    
   <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Exams'); ?><span><?php echo Yii::t('app','View your Exam Group here'); ?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Exams'); ?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    
     <div class="contentpanel">
     	<!--<div class="col-sm-9 col-lg-12">-->
        <div>
                <?php 
  
                if(isset($student_id)  && GuardianList::model()->checkRelation($student_id,$guardian->id))
                { 
                ?> 
            
                        <div class="people-item">
                          <div class="media">
                            <a href="#" class="pull-left">
                                <?php
                     if($student->photo_file_name!=NULL)
                     { 
					 	$path = Students::model()->getProfileImagePath($student->id);
                        echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" />';
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
									  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo ($batch->name)?$batch->name:"-";?></div>
									  <?php } ?>
									  <?php 
											  $semester_enabled		= Configurations::model()->isSemesterEnabled();   
											  $sem_enabled			= Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
											  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
												<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo ($semester->name)?$semester->name:"-";?></div>
										<?php } ?>
							<?php } ?>	
					          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
					          
					        </div>
                          </div>
                        </div>
                         <!-- END div class="profile_top" -->
                         
                         <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Assessment'); ?></h3>  
                        </div>
                        
                        
                        <div class="people-item">
                             <?php $semester_enabled	= Configurations::model()->isSemesterEnabled();?>
                              <table class="table table-hover mb30">
                                <tr>
                                    <th><?php echo Yii::t('app','Course');?></th><th>:</th>
                                    <th>
                                     <?php 
                                      $batch = Batches::model()->findByPk($_GET['bid']);
                                      $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
                                      echo ($batch->course123->course_name)?$batch->course123->course_name:"-"; 
                                    ?>
                                    </th>                        
                                    <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></th><th>:</th>
                                    <th><?php echo ($batch->name)?$batch->name:"-";?></th>
                                    <?php
                                    if(isset($semester_enabled) and $semester_enabled==1){
                                        if($batch->semester_id!=NULL){
                                        ?>
                                        <th><?php echo Yii::t('app','Semester');?></th><th>:</th>  
                                        <th><?php echo ($semester->name)?$semester->name:"-";?></th>
                                        <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                </table>
                            
                            <div class="btn-demo" style="position:relative; top:-8px; right:3px; float:right;">                    
                                <div class="edit_bttns" >
                                    <ul>
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/parentportal/default/exams'),array('class'=>'addbttn last'));?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                           
                            
                            
                         <div class="table-responsive">
                        
                        <table class="table table-hover mb30">
                    <tr>
                        <th><?php echo Yii::t('app','Exam Group Name');?></th>                        
                        <th><?php echo Yii::t('app','Action');?></th>
                    </tr>
                    <?php
                    if($ex_group==NULL)
                    {
                    	echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Exam Goups').'</i></td></tr>';	
                    }
                    else
                    {
                        foreach ($ex_group as $exam_group)
                        {
                            echo "<tr>";
                            echo "<td>".ucfirst($exam_group->name)."</td>";
                            echo "<td>".CHtml::link(Yii::t('app','View Result'),array('default/examList', 'id'=>$exam_group->id,'bid'=>$batch_id,'sid'=>$student_id))."</td>";
                            echo "</tr>";
                        }
                    }
                    ?>    
                </table>
            </div> 
            
            
            <!-- END div class="profile_details" -->
        </div> <!-- END div class="parentright_innercon" -->
        
                <?php }
                else
                            {
                                ?>
                                <div class="people-item">
                                        <div class="formCon">
                                            <div class="formConInner">
                                                <center><?php echo Yii::t("app", "No Result Found") ?></center>
                                            </div>
                                        </div>
                                </div>
                                    <?php
                            } ?>
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
