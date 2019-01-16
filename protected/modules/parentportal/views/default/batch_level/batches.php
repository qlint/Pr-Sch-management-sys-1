 <script>
	function getstudent() // Function to see student profile
	{
		var studentid = document.getElementById('studentid').value;
		if(studentid!='')
		{
			window.location= 'index.php?r=parentportal/default/exams&id='+studentid;	
		}
		else
		{
			window.location= 'index.php?r=parentportal/default/exams';
		}
	}
</script>
<?php $semester_enabled	= Configurations::model()->isSemesterEnabled(); ?>
<?php $this->renderPartial('leftside');?> 
    <?php
    
    
    
    $student_id= "";
    $user=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
    $guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$students = Students::model()->findAllByAttributes(array('parent_id'=>$guardian->id));
	$criteria = new CDbCriteria;		
			$criteria->join = 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
			$criteria->condition = 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
			$criteria->params = array(':guardian_id'=>$guardian->id,':is_active'=>1,'is_deleted'=>0);
			$wards = Students::model()->findAll($criteria);
                        $list= array();                       
                        if($wards!=NULL)
                        {
                            foreach($wards as $ward)
                            {
                                if($ward->studentFullName('forParentPortal')!=''){
                                    $list[$ward->id]= $ward->studentFullName('forParentPortal');
                                }
                            }
                        }
                   
        if(count($wards)>0)
        {
         $sid=  key($list);               
	if(count($wards)==1) // Single Student 
	{
		//$student = Students::model()->findByAttributes(array('id'=>$students[0]->id));
                $student = Students::model()->findByAttributes(array('id'=>$sid));
	}
	elseif(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL) // If Student ID is set
	{
		$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		
	}
	elseif(count($wards)>1) // Multiple Student
	{
		$student = Students::model()->findByAttributes(array('id'=>$sid));
	}
        
        $student=Students::model()->findByAttributes(array('id'=>$student->id));
        $batches= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id));
        $student_id= $student->id;
        $exam = ExamScores::model()->findAll("student_id=:x", array(':x'=>$student->id));
       
        $student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forParentPortal');
        }
                        
                       
                        
    ?>
<div class="pageheader">
    <div class="col-lg-8">
      <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Exams'); ?><span><?php echo Yii::t('app','View your exams here'); ?></span></h2>
    </div>
    <div class="col-lg-2"> 
       
                <?php
                if(count($wards)>1) // Show drop down only if more than 1 student present
				{
					$student_list = CHtml::listData($students,'id','studentnameforparentportal');
				?>
                    <div class="student_dropdown" style="top:15px;">
                        <?php
                        echo CHtml::dropDownList('sid','',$list,array('prompt'=>Yii::t('app','Select'),'id'=>'studentid','class'=>'form-control input-sm mb14','style'=>'width:auto;display: inline; margin-left: 7px;','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getstudent();'));
                        ?>

						                        
                    </div> <!-- END div class="student_dropdown" -->
            	<?php
				}
				?>
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
          <div>
        <?php
        $flag=0;
        if(count($wards)>0)
        {
        ?>
    	<!--<div class="col-sm-9 col-lg-12">-->
       
            <?php 
  
                if(isset($student_id) && GuardianList::model()->checkRelation($student_id,$guardian->id))
                { 
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
                  <h4 class="person-name"><?php echo $student->studentFullName('forParentPortal');?></h4>
                  <?php 
				  $student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
				  	$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id, 'result_status'=>0));
				  if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){ ?>
                  <?php if(count($batchstudents)>1){ 
							echo CHtml::link('View Course Details', array('/parentportal/default/course', 'id'=>$student->id));
						}
						else{?>	
							  <?php if(in_array('batch_id', $student_visible_fields)){ ?>      
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
				  
				<?php  } ?>    
                  <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
                  
                </div>
              </div>
            </div>
            <div class="panel-heading">
              <!-- panel-btns -->
              <h3 class="panel-title"><?php echo Yii::t('app','Assessment Batch List');?></h3>
            </div>
            <div class="people-item">
                
                
                
<div class="button-bg button-bg-none">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
                            <li>
                                <?php echo CHtml::link('<span>'.Yii::t('app','View Timetable').'</span>',array('/parentportal/default/examTimetable','sid'=>$student->id),array('class'=>'btn btn-primary'));?>
                            </li>
                            <?php
                            if(isset($semester_enabled) and $semester_enabled==1){?> 
                            <li>
                                <?php echo CHtml::link('<span>'.Yii::t('app','Semester Results').'</span>',array('/parentportal/default/semesters','id'=>$student->id),array('class'=>'btn btn-primary'));?>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                
                <div class="table-responsive">
                
                   <table class="table table-bordered mb30">
                    <tr>
                        <th><?php echo Yii::t('app','Batch Name');?></th>
                        <th><?php echo Yii::t('app','Course');?></th>
                        <?php
                            if(isset($semester_enabled) and $semester_enabled==1){?> 
							<th><?php echo Yii::t('app','Semester');?></th> 
                        <?php
						}?>
                        <th><?php echo Yii::t('app','Academic Year');?></th>
                        <th><?php echo Yii::t('app','Status');?></th>
                        <th><?php echo Yii::t('app','Manage');?></th>
                        <th><?php echo Yii::t('app','Online Exams');?></th>
                    </tr>
                       <?php
                    if($batches==NULL)
                    {
                    	echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Assessments').'</i></td></tr>';	
                    }
                    else
                    {
			foreach ($batches as $batch)
                        {
                            echo "<tr>";
                            $batch_id= $batch->batch_id;
                            $batch_model= Batches::model()->findByPk($batch_id);
                            if($batch_model!=NULL)
                            {
                                if(in_array('batch_id', $student_visible_fields))
                                { 
                                    echo "<td>".$batch_model->name."</td>";
                                    $batchs = Batches::model()->findByPk($batch_model->id);
                                    echo "<td>".$batchs->course123->course_name."</td>";
                                    if(isset($semester_enabled) and $semester_enabled==1){?>
                                    <td>
                                        <?php if($batch_model->semester_id!=NULL){ 
                                                $semester = Semester::model()->findByAttributes(array('id'=>$batch_model->semester_id));
                                                echo ucfirst($semester->name);
                                              } 
                                              else{
                                                  echo '-';
                                              }?>
                                        </td>  
                                        <?php 
									}
                                    $year_name="-";
                                    $academic_model= AcademicYears::model()->findByPk($batch_model->academic_yr_id);
                                    if($academic_model!=NULL)
                                    {
                                      $year_name=  $academic_model->name; 
                                    }
                                    echo "<td>".$year_name."</td>";
                                    if($batch->status==1)
                                    {
                                        echo "<td>".Yii::t('app','Current Batch')."</td>";
                                    }
                                    else
                                    {
                                        echo "<td>".Yii::t('app','Previous Batch')."</td>";
                                    }
                                    $level = Configurations::model()->findByPk(41);
                                    if($batch->status==1 && $level->config_value !=1 && ExamFormat::model()->getExamformat($batch_id)== 2)
                                    {
										$cbsc_format    = ExamFormat::getCbscformat($batch_id);
                    					if($cbsc_format){
											echo "<td>".CHtml::link(Yii::t('app','View Result'),array('default/cbsc17','sid'=>$student->id,'bid'=>$batch_id), array('class'=>'view_Exmintn_atg Exm_aTgColor_y'))."</td>";
										}else{
                                         echo "<td>".CHtml::link(Yii::t('app','View Result'),array('default/cbsc','sid'=>$student->id), array('class'=>'view_Exmintn_atg Exm_aTgColor_y'))."</td>";
										}
                                    }
                                    else
                                    {
                                        echo "<td>".CHtml::link(Yii::t('app','View Exam Groups'),array('default/exam', 'bid'=>$batch_id,'sid'=>$student->id), array('class'=>'view_Exmintn_atg Exm_aTgColor_r'))."</td>";
                                    }
									
									echo "<td>".CHtml::link(Yii::t('app','View Online Exams'),array('/onlineexam/default/exams', 'id'=>$student->id,'bid'=>$batch_id), array('class'=>'view_Exmintn_atg Exm_aTgColor_g'))."</td>";
                                    
                                }
                                
                            }
                            echo "</tr>";
                        }
                    }
                    ?>  
                </table>
            </div> 
                
            
            <!-- END div class="profile_details" -->
            </div> 
            
            <?php }
                else
                    {
                       $flag=1; 
                    }
        }
        else
        {
            $flag=1;
        }                                                
        
        if($flag==1)
        {
            ?>
            <div class="people-item">

                            <center><?php echo Yii::t("app", "No Result Found") ?></center>

            </div>
                <?php
        }
        ?>
        
        
    </div>
       
        
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
