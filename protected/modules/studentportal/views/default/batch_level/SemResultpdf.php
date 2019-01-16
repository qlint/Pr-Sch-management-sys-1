<style>
	.table-responsive table td{
		bo	
	}
.table{
	margin:30px 0px;
	font-size:15px;
	border-collapse:collapse
}

.table td,th{
	border:1px  solid #C5CED9;
	padding:5px 7px;
	text-align:left;
	
}
.panel-title{
	text-align:center;
	color:#333;
	font-weight:600;
	 font-family:Arial, Helvetica, sans-serif;	
}
</style>



    <?php
    
    $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $exam = ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
    
    $exam_group_id="";
    $exam_arr=array();
	 if(isset($_REQUEST['sem']) && $_REQUEST['sem']!=NULL)
    {
		$sem=Semester::model()->findByPk($_REQUEST['sem']);
	}
    if(isset($_REQUEST['id']) && $_REQUEST['id']!=NULL)
    {
        $exam_group_id= $_REQUEST['id'];
        $exams= Exams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group_id));    
        foreach ($exams as $exam)
        {
            $exam_arr[]=$exam->id;
        }
    }
    
    
    $criteria= new CDbCriteria;
    $criteria->condition= "student_id=:student_id";
    $criteria->params= array(':student_id'=>$student->id);
    $criteria->addInCondition('exam_id', $exam_arr);
    $exam= ExamScores::model()->findAll($criteria);
	$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
    ?>
     <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php $filename=  Logo::model()->getLogo();
                            if($filename!=NULL)
                            { 
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
     <h3 class="panel-title"><?php echo Yii::t('app','Semester Assessment Report');?></h3> 
    <div class="people-item">
                          
                   <table style="font-size:14px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
            <?php 
				//$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
            ?>
            <tr>
                <?php
                if(FormFields::model()->isVisible("fullname", "Students", "forStudentPortal")){
                ?>
                <td style="width:150px;"><?php echo Yii::t('app','Name'); ?></td>
                <td style="width:10px;">:</td>
                <td style="width:350px;"><?php echo $student->studentFullName("forStudentPortal"); ?></td>
                <td width="150"><?php echo Yii::t('app','Admission Number'); ?></td>
                <td style="width:10px;">:</td>
                <td width="350"><?php echo $student->admission_no; ?></td>                
                <?php
                }
                else{
                ?>
                <td width="150"><?php echo Yii::t('app','Admission Number'); ?></td>
                <td style="width:10px;">:</td>
                <td width="350"><?php echo $student->admission_no; ?></td>                
                <td style="width:150px;">&nbsp;</td>
                <td style="width:10px;">&nbsp;</td>
                <td style="width:350px;">&nbsp;</td>
                <?php
                }
                ?>
            </tr>
            
            <tr>
            	<?php 
                if(FormFields::model()->isVisible('batch_id', 'Students', "forStudentPortal")){
				?>

                <td><?php echo Yii::t('app','Course'); ?></td>
                <td>:</td>
                <td>
					<?php 
					$batch = Batches::model()->findByPk($_REQUEST['bid']);
					echo $batch->course123->course_name;
					?>
				</td>
                <td><?php echo Yii::t('app','Batch'); ?></td>
                <td>:</td>
                <td>
					<?php 
					 echo $batch->name;
					?>
				</td>
              
                <?php 
                }   
                ?>
            
            </tr>
            <tr>
             <td><?php echo Yii::t('app','Semester'); ?></td>
                <td>:</td>
                <td>
					<?php 
					echo $sem->name;
					?>
				</td>
            </tr>
           
           
        </table>       
                        </div>
        <div class="panel-heading">
       	 
        </div>                    
        <div class="people-item">                   
          <div class="table-responsive"> 
				  <?php
                        
                        $grouptype=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'result_published'=>1));
                    ?> 
                       
                 <table class="table table-hover mb30" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <th><?php echo Yii::t('app','Exam Group Name');?></th>
                        <th><?php echo Yii::t('app','Subject');?></th>
                        <th><?php echo $grouptype->exam_type;?></th>
                        <th><?php echo Yii::t('app','Remarks');?></th>
						<th><?php echo Yii::t('app','Result');?></th>
                    </tr>
                    <?php
                    if($exam==NULL)
                    {
                    	echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Assessments').'</i></td></tr>';	
                    }
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
										$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$batch->id));
										
										if($student_elective!=NULL)
										{
											$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id));
											
											if($electname!=NULL)
											{
												echo '<td>'.$sub->name."-".$electname->name.'</td>';
											}
										}
									
										
									}
									else
									{
										echo '<td>'.$sub->name.'</td>';
									}
                                                                       
					if($group->exam_type == 'Marks') 
                                        {  
                                            echo "<td>".$exams->marks."</td>"; 

                                        } 
                                        else if($group->exam_type == 'Grades') 
                                        {
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
								$glevel = Yii::t('app'," No Grades");;
							} 
						echo "</td>"; 
						} 
				   else if($group->exam_type == 'Marks And Grades'){
					echo "<td>"; foreach($grades as $grade)
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
						echo $exams->marks." & ".$grade_value ;
						break;
						
							
						} 
						if($t<=0) 
							{
								echo $exams->marks." & ".Yii::t('app','No Grades');
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
								}
								if($exams->marks >= $exm->minimum_marks)
										echo '<td>'.Yii::t('app','Passed').'</td>';
									else
										echo '<td>'.Yii::t('app','Failed').'</td>';
								echo '</tr>';
							}
							/*else{
							continue;
							}*/	
						}
						if($displayed_flag==NULL)
						{	
							echo '<tr><td align="center" colspan="5"><i>'.Yii::t('app','No Result Published').'</i></td></tr>';
						}
                    }
                    ?>    
                </table>
            </div> 
            
            
            <!-- END div class="profile_details" -->
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
