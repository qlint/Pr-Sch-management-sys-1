<style>
table.attendance_table{ border-collapse:collapse}

.attendance_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	width:auto;
	/*max-width:600px;*/
	border-top:1px solid #CCC ;
	border-right:1px solid #CCC;
}
.attendance_table td{
	border:1px solid #CCC;
	padding-top:10px; 
	padding-bottom:10px;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:14px;
	padding:10px;
	border-left:1px solid #CCC;
	border-bottom:1px solid #CCC;
}

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}

</style>
<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
if(isset($_REQUEST['id']))
{ 
?>

	<!-- Header -->
    
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
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; padding-left:10px;">
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
    <br />
	<!-- End Header -->

	<?php
    if(isset($_REQUEST['id']))
    {  
   ?>
   
    <div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','SEMESTER WISE ASSESSMENT REPORT '); ?></div><br />
    <?php $student=Students::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_deleted'=>0,'is_active'=>1));
		if($_REQUEST['sem_id']!=0 or $_REQUEST['sem_id']!=NULL){
			  $semester=Semester::model()->findByAttributes(array('id'=>$_REQUEST['sem_id']));
		}
	 ?>
    <!-- Batch details -->
    <table style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
            	<?php if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ ?>
            	<td style="width:150px;"><?php echo Yii::t('app','Student Name');?></td>
                <td style="width:10px;">:</td>
                <td style="width:200px;"><?php echo $student->studentFullName("forParentPortal");?></td>
				<?php } ?>
                
                <td style="width:150px;"><?php echo Yii::t('app','Admission Number ');?></td>
                <td style="width:10px;">:</td>
                 <td style="width:200px;"><?php echo $student->admission_no;?></td>
				
				<td><?php echo Yii::t('app','Semester');?></td>
                <td style="width:10px;">:</td>
                 <td style="width:200px;"><?php
				if($_REQUEST['sem_id']!=0 or $_REQUEST['sem_id']!=NULL){ 
					echo ucfirst($semester->name);
				}
				if($_REQUEST['sem_id']==0){ 
					echo Yii::t('app','All');;
				}
				?></td>
            </tr>
        </table>
  
    <!-- END Batch details -->
    <?php 
                    if(isset($_REQUEST['sem_id']) && $_REQUEST['sem_id']!=NULL)
                    {                
                        $criteria= new CDbCriteria;
                        $criteria->join= 'JOIN batch_students `bs` ON t.id=`bs`.batch_id';
                        $criteria->condition= '`bs`.student_id=:student_id AND t.academic_yr_id=:academic_yr_id AND t.is_active=:is_active AND t.is_deleted=:is_deleted';
                        $criteria->params= array(':student_id'=>$student->id, ':academic_yr_id'=>Yii::app()->user->year, ':is_active'=>1, ':is_deleted'=>0);
                        if(isset($_REQUEST['sem_id']) && $_REQUEST['sem_id']!=0)
                        {
                            $criteria->condition .= ' AND t.semester_id=:semester_id';
                            $criteria->params[':semester_id']=$_REQUEST['sem_id'];
                        }
                        $batches= Batches::model()->findAll($criteria);
                        //$batches= Batches::model()->findAllByAttributes(array('course_id'=>$course_array,'semester_id'=>$_REQUEST['sem_id'],'is_deleted'=>0,'is_active'=>1));
                        if($batches==NULL)
                        {
                            echo Yii::t('app','No Result Found');	
                        }
                        else
                        {
                                               
			foreach ($batches as $batch)
                        {
							$cbsc_format    = ExamFormat::getCbscformat($batch_id);
                            ?>                           			
                            <h4><?php echo ucfirst($batch->name); ?></h4>                                                             
                            <?php
							$batch_id = $batch->id;
							if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ //cbsc
                            	
                    			if($cbsc_format){
									$ex_group= CbscExamGroup17::model()->findAllByAttributes(array('batch_id'=>$batch->id,'result_published'=>1));
								}else{
                            		$ex_group= CbscExamGroups::model()->findAllByAttributes(array('batch_id'=>$batch->id,'result_published'=>1),array('order'=>'date DESC'));
								}
							}
							else{
								$ex_group= ExamGroups::model()->findAllByAttributes(array('batch_id'=>$batch->id,'result_published'=>1),array('order'=>'exam_date DESC'));
							}
                            if($ex_group!=NULL)
                            {
                                foreach ($ex_group as $exam_group)
                                {
                                    ?><h6><?php echo Yii::t('app','Exam Group').'  :-  '.ucfirst($exam_group->name); ?></h6><?php
                                    $exam_arr= array();                                   
                                    $exam_group_id= $exam_group->id;
									if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ //cbsc
                                    	if($cbsc_format){
											$exams= CbscExams17::model()->findAllByAttributes(array('exam_group_id'=>$exam_group_id));
										}else{
											$exams= CbscExams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group_id));
										}
									}
									else{
										$exams= Exams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group_id));
									}
                                    if($exams!=NULL)
                                    {
                                        foreach ($exams as $exam)
                                        {
                                            $exam_arr[]=$exam->id;
                                        }

                                        $criteria= new CDbCriteria;
                                        $criteria->condition= "student_id=:student_id";
                                        $criteria->params= array(':student_id'=>$student->id);
                                        $criteria->addInCondition('exam_id', $exam_arr);
										if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ //cbsc 
											if($cbsc_format){
												$exams= CbscExamScores17::model()->findAll($criteria);
											}else{
                                        		$exam= CbscExamScores::model()->findAll($criteria);
											}
										}
										else{
											$exam= ExamScores::model()->findAll($criteria);
										}
										if($cbsc_format){ 
											$this->renderPartial('batch_level/semester_pdf17',array('exams'=>$exam,'student'=>$student,'batch'=>$batch,'ex_group_id'=>$exam_group_id));
										}
                                        else 
                                        if(isset($exam))
                                        {                                      
                                                ?>
                                                <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
                                                    <tr class="tablebx_topbg" style="background-color:#DCE6F1;">      
                                                        <td style="width:150px;"><?php echo Yii::t('app','Subject');?></td>
                                                        <td style="width:130px;"><?php echo Yii::t('app','Score');?></td>
                                                        <td style="width:130px;"><?php echo Yii::t('app','Remarks');?></td>
                                                        <td style="width:150px;"><?php echo Yii::t('app','Result');?></td>
                                                    </tr>
                                                        <?php
                                                           if($exam==NULL)
                                                           {
                                                               echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Results Found').'</td></tr>';	
                                                           }
                                                           else
                                                           {
                                                               $displayed_flag = '';
                                                               foreach($exam as $exams)
                                                               {
																   if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ //cbsc
																   
																	   $exm=CbscExams::model()->findByAttributes(array('id'=>$exams->exam_id));
																	   $group=CbscExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id,'result_published'=>1));
																   }
																   else{
																	   $exm=Exams::model()->findByAttributes(array('id'=>$exams->exam_id));
																	   $group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id,'result_published'=>1));
																   }
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
																					
																			if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ //cbsc
																				if($exams->marks!=NULL){ 
																							echo "<td>".$exams->marks."</td>"; 
																						}
																						else{
																							echo '-';
																						}
																			}
																			else{
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
                                                                                        if($t<=0)                                                                                                     {
                                                                                        $glevel = Yii::t('app'," No Grades");;
                                                                                        } 
                                                                                        echo "</td>"; 
                                                                                    } 
                                                                                    else if($group->exam_type == 'Marks And Grades')
                                                                                    {
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
                                                                                                if($t<=0){                                                                                                         
                                                                                                echo $exams->marks." & ".Yii::t('app','No Grades');
                                                                                                }
                                                                                            echo "</td>"; 
                                                                                            
                                                                                    } 
																			}
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
                                                                               echo '</tr>';
                                                                   }                                                                       	
                                                               }
                                                               if($displayed_flag==NULL)
                                                               {	
                                                                       echo '<tr><td align="center" colspan="5"><i>'.Yii::t('app','No Result Published').'</i></td></tr>';
                                                               }
                                                           }
                                                       ?>                                                        
                                                </table>    
                                                <?php
                                        }
                                    }
                                    else
                                        echo Yii::t('app','No Result Found');
                                    
                                }
                            }
                            else
                            {
                                echo Yii::t('app','No Exam Groups Found');	
                            }
                            ?><?php                                                       
                        }
                    }
                    }
                ?>          
    
   
   
   <?php
    }
    ?>
    
    
    
<?php
}
else
{
	
	echo '<td align="center" colspan="5"><strong>'.Yii::t('app','No Data Available!').'</strong></td></tr>';
}
?>