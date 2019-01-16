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
<?php $this->renderPartial('leftside');?> 
    <?php
    $user=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
    $guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$students = Students::model()->findAllByAttributes(array('parent_id'=>$guardian->id));
	
	if(count($students)==1) // Single Student 
	{
		$student = Students::model()->findByAttributes(array('id'=>$students[0]->id));
	}
	elseif(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL) // If Student ID is set
	{
		$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		
	}
	elseif(count($students)>1) // Multiple Student
	{
		$student = Students::model()->findByAttributes(array('id'=>$students[0]->id));
	}
    $exam = ExamScores::model()->findAll("student_id=:x", array(':x'=>$student->id));
	//var_dump($exam);exit;
	$electives = ElectiveScores::model()->findAll("student_id=:x", array(':x'=>$student->id));
	//var_dump($electives);exit;
    ?>
<div class="pageheader">
    <div class="col-lg-8">
      <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Exams'); ?><span><?php echo Yii::t('app','View your exams here'); ?></span></h2>
    </div>
    <div class="col-lg-2"> 
       
                <?php
                if(count($students)>1) // Show drop down only if more than 1 student present
				{
					$student_list = CHtml::listData($students,'id','studentnameforparentportal');
				?>
                    <div class="student_dropdown" style="top:15px;">
                        <?php
                        echo CHtml::dropDownList('sid','',$student_list,array('prompt'=>Yii::t('app','Select'),'id'=>'studentid','class'=>'form-control input-sm mb14','style'=>'width:auto;display: inline; margin-left: 7px;','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getstudent();'));
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
    	<!--<div class="col-sm-9 col-lg-12">-->
        <div>
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
                  <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){?>
                      <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong> <?php 
                            $batch = Batches::model()->findByPk($student->batch_id);
                            echo $batch->course123->course_name;
                            ?></div>
                      <div class="text-muted"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
                  <?php } ?>    
                  <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
                  
                </div>
              </div>
            </div>
            <div class="panel-heading">
              <!-- panel-btns -->
              <h3 class="panel-title"><?php echo Yii::t('app','Assessment Batch List');?></h3>
            </div>
            <div class="people-item">
                <div class="table-responsive">      
                       
                	<table class="table table-bordered mb30">
                    <tr>
                        <th><?php echo Yii::t('app','Batch Name');?></th>
                        <th><?php echo Yii::t('app','Course');?></th>
                        
                        <th><?php echo Yii::t('app','Academic Year');?></th>
                        
                        <th><?php echo Yii::t('app','Status');?></th>
                        <th><?php echo Yii::t('app','Manage');?></th>
                    </tr>
                    <?php
                    if($exam==NULL)
                    {
                    	echo '<tr><td align="center" colspan="6"><i>'.Yii::t('app','No Assessments').'</i></td></tr>';	
                    } // END if($exam==NULL)
                    else
                    {
						$displayed_flag = '';
						foreach($exam as $exams)
						{
						
							$exm=Exams::model()->findByAttributes(array('id'=>$exams->exam_id));
							
							$group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id,'result_published'=>1));
							
							$criteria = new CDbCriteria;
						    $criteria->condition = 'batch_id=:x';
						    $criteria->params = array(':x'=>$group->batch_id);	
						    $criteria->order = 'min_score DESC';
						    $grades = GradingLevels::model()->findAll($criteria);
									 
			                  $t = count($grades); 
							  $k = count($grades);
							if($group!=NULL and count($group) > 0)
							{
							
								echo '<tr>';
								if($exm!=NULL)
								{
									$displayed_flag = 1;
									//$group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id));
									echo '<td>'.$group->name.'</td>';
									$sub=Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
									
								
									if($sub->elective_group_id!=0 and $sub->elective_group_id!=NULL)
									{
										
										
									$student_elective = StudentElectives::model()->findByAttributes(array
									('student_id'=>$student->id,'elective_group_id'=>$sub->elective_group_id));
										
									
										if($student_elective!=NULL) // Elective assigned
										{
											$electname = Electives::model()->findByAttributes(array('id'=>$student_elective-> 
											elective_id,'elective_group_id'=>$sub->elective_group_id));
											$elective_group = ElectiveGroups::model()->findByPk($sub->elective_group_id);
											
											if($electname!=NULL)
											{
												echo '<td>'.ucfirst($elective_group->name).'-'.$electname->name.' '.Yii::t('app','( Elective )').'</td>';
											}
										}
									
										
									}
									else
									{
										echo '<td>'.$sub->name.'</td>';
									}
										if($group->exam_type == 'Marks') {  
				  echo "<td>".$exams->marks."</td>"; } 
				  else if($group->exam_type == 'Grades') {
				   echo "<td>";
				 
					   foreach($grades as $grade)
							{
								
							 if($grade->min_score <= $exams->marks)
								{	
									$grade_value =  $grade->name;
								}
								else
								{
									$t--;
									
									continue;
									
								}
							echo $grade_value ;
							break;
							
							}
							if($t<=0) 
								{
									echo $glevel = Yii::t('app',"No Grades");
								}
					
						echo "</td>"; 
						} 
				   else if($group->exam_type == 'Marks And Grades'){
					   
					echo "<td>";foreach($grades as $grade)
						{
						 if($grade->min_score <= $exams->marks)
							{	
								$grade_value =  $grade->name;
							}
							else
							{
								$t--;
								
								continue;
								
							}
						echo $exams->marks . " & ".$grade_value;
						break;
						
							
						} 
						if($t<=0) 
							{
								echo $exams->marks.' '.Yii::t('app',"& No Grades");
							}
						echo "</td>"; } 
									echo '<td>';
									if($exams->remarks!=NULL)
									{
										echo $exams->remarks;
									}
									else
									{
										echo '-';
									}
									echo '</td>';
									if($exams->marks >= $exm->minimum_marks)
										echo '<td>'.Yii::t('app','Passed').'</td>';
									else
										echo '<td>'.Yii::t('app','Failed').'</td>';
								}
								
								
								echo '<td>';
									if($exams->marks!=NULL)
									{
										$total_mark=0;
										$exam_all = ExamScores::model()->findAll("exam_id=:x", array(':x'=>$exams->exam_id));
										foreach($exam_all as $exam_one)
											$total_mark += $exam_one->marks;
										$final_mark=round(($total_mark/count($exam_all)),2);						
										
															
										 if($group->exam_type == 'Marks') 
										 {  
											echo $final_mark;
										 } 
										 else if($group->exam_type == 'Grades') 
										 {
										   
										   foreach($grades as $grade)
										   {
												if($grade->min_score <= $final_mark)
												{	
													$grade_value =  $grade->name;
												}
												else
												{
													$k--;
													continue;
												}
												echo $grade_value ;
												break;
											}
											if($k<=0) 
											{
												echo $glevel = Yii::t('app',"No Grades") ;
											} 
												
										} 
										else if($group->exam_type == 'Marks And Grades')
										{
											
											foreach($grades as $grade)
											{
												if($grade->min_score <= $final_mark)
												{	
													$grade_value =  $grade->name;
												}
												else
												{
													$k--;
													continue;
												}
												echo $final_mark . " & ".$grade_value;
												break;
											} 
											if($k<=0) 
											{
												echo $final_mark.' '.Yii::t('app',"& No Grades");
											}
											
										} 
										
									}
									else
									{
										echo '-';
									}
									echo '</td>';
								
								
								
								
								
								echo '</tr>';
								
							}
							/*else{
							continue;
							}*/	
						} // END foreach($exam as $exams)
						if($displayed_flag==NULL)
						{	
							echo '<tr><td align="center" colspan="6"><i>'.Yii::t('app','No Result Published').'</i></td></tr>';
						}
                    }
                    ?>    
                </table>
            </div> 
            <?php /*?><div class="profile_details"> //  Only list the Elective group Assessment report
                <h3><?php echo Yii::t('app','Electives');?></h3>
                <br />  
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?php echo Yii::t('app','Exam Group Name');?></th>
                        <th><?php echo Yii::t('app','Subject');?></th>
                        <th><?php echo Yii::t('app','Marks');?></th>
                        <th><?php echo Yii::t('app','Remarks');?></th>
                        <th><?php echo Yii::t('app','Result');?></th>
                    </tr>
                    <?php
                    if($electives==NULL)
                    {
                    	echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Assessments').'</i></td></tr>';	
                    }
                    else
                    {
						
						$displayed_flag = '';
						foreach($electives as $elective)
						{
							//$sub=Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
							$exm=ElectiveExams::model()->findByAttributes(array('id'=>$elective->exam_id));
							
							$group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id,'result_published'=>1));
							if($group!=NULL and count($group) > 0)
							{
								echo '<tr>';
								if($exm!=NULL)
								{
									$displayed_flag = 1;
									//$group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id));
									echo '<td>'.$group->name.'</td>';
									$sub=ElectiveGroups::model()->findByAttributes(array('id'=>$exm->elective_id));
									echo '<td>'.$sub->name.'</td>';
									echo '<td>'.$elective->marks.'</td>';
									echo '<td>';
									if($elective->remarks!=NULL)
									{
										echo $elective->remarks;
									}
									else
									{
										echo '-';
									}
									echo '</td>';
									if($elective->is_failed==NULL)
										echo '<td>'.Yii::t('app','Passed').'</td>';
									else
										echo '<td>'.Yii::t('app','Failed').'</td>';
								}
								echo '</tr>';
							}
							/*else{
							continue;
							}*/	
						 /*?>}
						if($displayed_flag==NULL)
						{	
							echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Result Published').'</i></td></tr>';
						}
                    }
                    ?>    
                </table>
            </div>
<?php */?>            
            <!-- END div class="profile_details" -->
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
