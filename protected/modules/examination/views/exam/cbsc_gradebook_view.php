<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/examination'),
	Yii::t('app','CBSE Grade Book'),
);
$student_id= $_REQUEST['id'];
$model= Students::model()->findByPk($student_id);
$batch_id= $model->batch_id;
$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student_id, 'batch_id'=>$batch_id));
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));

$year_name="";
    if($batch->academic_yr_id!=NULL)
    {
        $academic_model= AcademicYears::model()->findByPk($batch->academic_yr_id);
        if($academic_model!=NULL)
        {
          $year_name=  $academic_model->name; 
        }        

    }

 $fa1_weightage = $fa2_weightage = $sa1_weightage =  $sa2_weightage = "";
    $weightage_settings= CbscExamSettings::model()->findByAttributes(array('academic_yr_id'=>Yii::app()->user->year));
    if($weightage_settings!=NULL)
    {    
        
        $fa1_weightage= $weightage_settings->fa1_weightage;
        $fa2_weightage= $weightage_settings->fa2_weightage;
        $sa1_weightage= $weightage_settings->sa1_weightage;
        $sa2_weightage= $weightage_settings->sa2_weightage;
    }
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  
  <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
  <td valign="top"><!-- div class="cont_right" -->
    
    <div class="cont_right">
      <h1><?php echo Yii::t('app','Report Card')." - ".$year_name; ?></h1>
      <div class="report-hed">
        <div class="report-stu-dtls-table">
          <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
              <tr>
                <td width="95"><span><?php echo Yii::t('app','Student Name'); ?></span></td>
                <td width="160"><?php 
								   if(FormFields::model()->isVisible("fullname", "Students", 'forStudentProfile')){
										echo $model->studentFullName('forStudentProfile');
									} 
               ?>
               </td>
                <td width="95"><span><?php echo Yii::t('app','Admission No'); ?></span></td>
                <td width="150"><p> <?php if(FormFields::model()->isVisible('admission_no','Students','forStudentProfile'))
										   {
											  echo $model->admission_no;         
										   }
										   ?></p>
                </td>
                <td width="80"><span><?php echo Yii::t('app','Date Of Birth'); ?></span></td>
                <td><p><?php if(FormFields::model()->isVisible('date_of_birth','Students','forStudentProfile'))
								{
									if($settings!=NULL and $model->date_of_birth!=NULL)
									{	
											$date1 = date($settings->displaydate,strtotime($model->date_of_birth));
											echo $date1;										
									}else{
											echo '-';
									}
								}
								?></p>
               </td>
              </tr>
              <tr>
                <td><span><?php echo Yii::t('app',"Mother's Name"); ?></span></td>
                <td><?php echo Students::model()->getMotherName($model->id); ?></td>
                <td><span><?php echo Yii::t('app',"Father's Name"); ?></span></td>
                <td colspan="2"><?php echo Students::model()->getFatherName($model->id); ?></td>
              </tr>
              <tr>
                <td><span><?php echo Yii::t('app',"Course"); ?></span></td>
                <td><p><?php echo $course->course_name;?></p></td>
                <td><span><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></span></td>
                <td><p><?php echo $batch->name;?></p></td>
                <td><span><?php echo Yii::t('app',"Roll No"); ?></span></td>
                <td><p><?php echo $batch_student->roll_no;?></p></td>
              </tr>
              </tbody>
          </table>
        </div>
        <div class="os-table tablebx">
          <div class="tbl-grd"></div>
          <table class="table" width="100%" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td rowspan="3" class="header-td1"><?php echo Yii::t('app','SUBJECT'); ?></td>
                <td colspan="2" class="header-td1"><?php echo Yii::t('app','TERM I'); ?></td>
                <td colspan="2" class="header-td1"><?php echo Yii::t('app','TERM II'); ?></td>
                <td colspan="3" class="header-td1"><?php echo Yii::t('app','TOTAL TERM I+II'); ?></td>
              </tr>
              <?php
              $fa1_exam_group = CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'FA1','result_published'=>1));
			  $sa1_exam_group = CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'SA1','result_published'=>1));
			  $fa2_exam_group = CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'FA2','result_published'=>1));
			  $sa2_exam_group = CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'SA2','result_published'=>1));
			  ?>
              <tr>
                <td class="header-td"  rowspan="2">
					<?php if($fa1_exam_group!=NULL){
							echo $fa1_exam_group->name;
					}?>
                </td>
                <td class="header-td"  rowspan="2">
					<?php if($sa1_exam_group!=NULL){
							echo $sa1_exam_group->name;
					}?>
                </td>
                <td class="header-td"  rowspan="2">
					<?php if($fa2_exam_group!=NULL){
							echo $fa2_exam_group->name;
					}?>
                </td>
                <td class="header-td"  rowspan="2">
					<?php if($sa2_exam_group!=NULL){
							echo $sa2_exam_group->name;
					}?>
                </td>
                <td class="header-td"  colspan="2"><?php echo Yii::t("app","OVERALL"); ?></td>
              </tr>
              <tr>
                <td class="header-td1" colspan="2"><?php echo Yii::t('app',"Mark"); ?></td>
          
              </tr>
 <?php $subjects= Subjects::model()->findAllByAttributes(array('batch_id'=>$batch_id,'elective_group_id'=>0, 'cbsc_common'=>0));
		if($subjects!=NULL)
        {
            $i=1;
            foreach ($subjects as $subject)
            {  
				$mark	= 0;              
        ?> 
              <tr>
                <td class="header-td1"><?php echo ucfirst($subject->name); ?></td>
                <td>  
						<?php
                        $term1_fa1_score= "";
                        if($fa1_weightage!="")
                        {
                            $cbsc_exam_group_model= CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'FA1','result_published'=>1));
                            if($cbsc_exam_group_model!=NULL)
                            { 
                                $cbsc_exam_model= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_model->id,'subject_id'=>$subject->id));
                                if($cbsc_exam_model!=NULL)
                                {
                                    $cbsc_exam_score= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_model->id));
                                    if($cbsc_exam_score!=NULL)
                                    { 
										$term1_fa1_score = $cbsc_exam_score->marks;
										$fa1_score = ($term1_fa1_score/$cbsc_exam_model->maximum_marks)*$fa1_weightage;
										echo $fa1_score;
										$mark	= $mark + $fa1_score;
                                    }
									else{
										echo '-';
									}
                                }
                            }
                        }
                        else
                        {
                            echo "-";
                        }
                    ?>
                 </td>
                <td> 
					<?php
                    $term1_sa1_score="";
                    if($sa1_weightage!="")
                    { 
                        $cbsc_exam_group_model= CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'SA1','result_published'=>1));
                        if($cbsc_exam_group_model!=NULL)
                        {
                            $cbsc_exam_model= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_model->id,'subject_id'=>$subject->id));
                            if($cbsc_exam_model!=NULL)
                            { 
                                $cbsc_exam_score= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_model->id));
                                if($cbsc_exam_score!=NULL)
                                { 
                                    $term1_sa1_score = $cbsc_exam_score->marks;;
									$sa1_score = ($term1_sa1_score/$cbsc_exam_model->maximum_marks)*$sa1_weightage;
									echo $sa1_score;
									$mark	= $mark + $sa1_score;
                                }
								else{
										echo '-';
								}
                            }
                        }
                    }
                    else
                    {
                        echo "-";
                    }
                    ?>
                </td>
                <td> 
						<?php
                        $term2_fa2_score= "";
                        if($fa2_weightage!="")
                        {
                        $cbsc_exam_group_model= CbscExamGroups::model()->findByAttributes(array('term_id'=>2,'batch_id'=>$batch_id,'exam_type'=>'FA2','result_published'=>1));
                        if($cbsc_exam_group_model!=NULL)
                        {
                            $cbsc_exam_model= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_model->id,'subject_id'=>$subject->id));
                            if($cbsc_exam_model!=NULL)
                            {
                                $cbsc_exam_score= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_model->id));
                                if($cbsc_exam_score!=NULL)
                                {
                                    $term2_fa2_score = $cbsc_exam_score->marks;;
									$fa2_score = ($term2_fa2_score/$cbsc_exam_model->maximum_marks)*$fa2_weightage;
									echo $fa2_score;
									$mark	= $mark + $fa2_score;
                                }
								else{
										echo '-';
								}								
                            }
                        }
                        }
                        else
                        {
                            echo "-";
                        }
                    ?>
               </td>
                <td>
					<?php
                    $term2_sa2_score="";
                    if($sa2_weightage!="")
                    { 
                        $cbsc_exam_group_model= CbscExamGroups::model()->findByAttributes(array('term_id'=>2,'batch_id'=>$batch_id,'exam_type'=>'SA2','result_published'=>1));
                        if($cbsc_exam_group_model!=NULL)
                        {
                            $cbsc_exam_model= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_model->id,'subject_id'=>$subject->id));
                            if($cbsc_exam_model!=NULL)
                            {
                                $cbsc_exam_score= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_model->id));
                                if($cbsc_exam_score!=NULL)
                                {
									$term2_sa2_score = $cbsc_exam_score->marks;;
									$sa2_score = ($term2_sa2_score/$cbsc_exam_model->maximum_marks)*$sa2_weightage;
									echo $sa2_score;
									$mark	= $mark + $sa2_score;
                                }
								else{
										echo '-';
								}
                            }
                        }
                    }
                    else
                    {
                        echo "-";
                    }
                    ?>
               </td>
                <td>
					<?php 					  	
						echo $mark;
						$grand_total+=$mark;
						
                    ?>
                </td>
               <!-- <td> - </td>-->
              </tr>
               <?php
        $i++;
            }
			
						 
			
			?>
            <tr>
            	<td colspan="5" class="header-td"><?php echo Yii::t("app","AVERAGE"); ?></td>
               <td class="header-td"><?php
			    echo round($grand_total/count($subjects), 1);
			    ?></td>
            </tr>
            <?php
        }
        else
        {
             echo "<tr><td colspan='13'>".Yii::t("app","Subjects not found")."</td></tr>";
           
        }
        ?>
            </tbody>
          </table>
        </div>
        
         			<?php
					$gk_subject = Subjects::model()->findByAttributes(array('name'=>'GK', 'batch_id'=>$batch_id, 'cbsc_common'=>1));
					$drawing_subject = Subjects::model()->findByAttributes(array('name'=>'Drawing', 'batch_id'=>$batch_id, 'cbsc_common'=>1)); 
					$cbsc_exam_group_sa1= CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'SA1','result_published'=>1));
					$cbsc_exam_group_sa2= CbscExamGroups::model()->findByAttributes(array('term_id'=>2,'batch_id'=>$batch_id,'exam_type'=>'SA2','result_published'=>1));
					
					 //Term 1 GK SA1 score
						 if($cbsc_exam_group_sa1!=NULL)
						 { 
								$cbsc_exam_sa1_gk= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_sa1->id, 'subject_id'=>$gk_subject->id)); 
							if($cbsc_exam_sa1_gk!=NULL)
                            {
								$cbsc_exam_scores_sa1_gk= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_sa1_gk->id));
                                if($cbsc_exam_scores_sa1_gk!=NULL)
                                { 
									$sa1_score_gk = $cbsc_exam_scores_sa1_gk->marks;
									
                                }
								else{
									$sa1_score_gk = 0;
								}
							}
						}
					
					
					 //Term 2 GK SA2 score
						 if($cbsc_exam_group_sa2!=NULL)
						 {
								$cbsc_exam_sa2_gk= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_sa2->id, 'subject_id'=>$gk_subject->id)); 
							if($cbsc_exam_sa2_gk!=NULL)
                            {
								$cbsc_exam_scores_sa2_gk= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_sa2_gk->id));
                                if($cbsc_exam_scores_sa2_gk!=NULL)
                                {
									$sa2_score_gk = $cbsc_exam_scores_sa2_gk->marks;
									
                                }
								else{
									$sa2_score_gk = 0;
								}
							}
						}
						
						 //Term 1 Drawing SA1 score
						 if($cbsc_exam_group_sa1!=NULL)
						 { 
								$cbsc_exam_sa1_draw= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_sa1->id, 'subject_id'=>$drawing_subject->id)); 
							if($cbsc_exam_sa1_draw!=NULL)
                            {
								$cbsc_exam_scores_sa1_draw = CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_sa1_draw->id));
                                if($cbsc_exam_scores_sa1_draw!=NULL)
                                { 
									$sa1_score_draw = $cbsc_exam_scores_sa1_draw->marks;
									
                                }
								else{
									$sa1_score_draw = 0;
								}
							}
						}
					?> 
                   
                    <?php
					 //Term 2 Drawing SA2 score
						 if($cbsc_exam_group_sa2!=NULL)
						 {
								$cbsc_exam_sa2_draw= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_sa2->id, 'subject_id'=>$drawing_subject->id)); 
							if($cbsc_exam_sa2_draw!=NULL)
                            {
								$cbsc_exam_scores_sa2_draw= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_sa2_draw->id));
                                if($cbsc_exam_scores_sa2_draw!=NULL)
                                {
									$sa2_score_draw = $cbsc_exam_scores_sa2_draw->marks;
									
                                }
								else{
									$sa2_score_draw = 0;
								}
							}
						}
					
					
 if($cbsc_exam_scores_sa1_gk!=NULL or $cbsc_exam_scores_sa2_gk!=NULL or $cbsc_exam_scores_sa1_draw!=NULL or $cbsc_exam_scores_sa2_draw!=NULL){
	 ?>
        <div class="os-table tablebx">
            <div class="tbl-grd"></div>
                <table class="table" width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="header-td1"><?php echo Yii::t('app','SUBJECT'); ?></td>
                            <td class="header-td1"><?php echo Yii::t('app','TERM I'); ?></td>
                            <td  class="header-td1"><?php echo Yii::t('app','TERM II'); ?></td>
                        </tr>
                          <?php 
					 if($cbsc_exam_scores_sa1_gk!=NULL or $cbsc_exam_scores_sa2_gk!=NULL){
						?>	 
                        <tr>
                            <td><?php echo Yii::t('app','GK'); ?></td>
                            <td><?php  if($sa1_score_gk == 1){
											echo "A";
										}
										if($sa1_score_gk == 2){
											echo "B";
										}
										if($sa1_score_gk == 3){
											echo "C";
										}
										if($sa1_score_gk == 4){
											echo "D";
										}
										if($sa1_score_gk == 5){
											echo "E";
										}?>
                            </td>
                            <td><?php  if($sa2_score_gk == 1){
											echo "A";
										}
										if($sa2_score_gk == 2){
											echo "B";
										}
										if($sa2_score_gk == 3){
											echo "C";
										}
										if($sa2_score_gk == 4){
											echo "D";
										}
										if($sa2_score_gk == 5){
											echo "E";
										}?>
                            </td>
                        </tr> 
              <?php } 
              if($cbsc_exam_scores_sa1_draw!=NULL or $cbsc_exam_scores_sa2_draw!=NULL){ 
			  ?>      
                         <tr>
                            <td><?php echo Yii::t('app','Drawing'); ?></td>
                           <td><?php  if($sa1_score_draw == 1){
											echo "A";
										}
										if($sa1_score_draw == 2){
											echo "B";
										}
										if($sa1_score_draw == 3){
											echo "C";
										}
										if($sa1_score_draw == 4){
											echo "D";
										}
										if($sa1_score_draw == 5){
											echo "E";
										}?>
                            </td>
                            <td><?php  if($sa2_score_draw == 1){
											echo "A";
										}
										if($sa2_score_draw == 2){
											echo "B";
										}
										if($sa2_score_draw == 3){
											echo "C";
										}
										if($sa2_score_draw == 4){
											echo "D";
										}
										if($sa2_score_draw == 5){
											echo "E";
										}?></td>
                        </tr>
                          <?php } ?>                          
                    </tbody>
                </table>
            </div>
        </div>
 <?php } ?>       
        
 <div class="os-table tablebx">
 <div class="tbl-grd"></div>
              <table cellspacing="0" cellpadding="0" border="0" width="100%" class="inner-table">
            <tbody>
                <tr>
                <th class="tbl-td-left"> <?php echo Yii::t('app','Attendance'); ?></th>
                <th width="180"><?php echo Yii::t('app','TERM I'); ?></th>
                <th width="180"><?php echo Yii::t('app','TERM II'); ?></th>
				</tr>
                
                <tr>
                <td class="tbl-td-left"><?php echo Yii::t('app','Total Working Session'); ?></td>
                <td class="tbl-td-center"> <?php echo Attendances::model()->getterm1Attendance($student_id,$batch_id); ?></td>
                <td class="tbl-td-center"><?php echo Attendances::model()->getterm2Attendance($student_id,$batch_id); ?></td>
				</tr>
                <tr>
                <td class="tbl-td-left"><?php echo Yii::t('app','Total Attandance of the Student'); ?></td>
                <td class="tbl-td-center"><?php 
                        $tot_working= Attendances::model()->getterm1Attendance($student_id,$batch_id);
                        $tot_abs= Attendances::model()->getLeaves($student_id);                       
                        echo ($tot_working-$tot_abs); ?>        </td>
                <td class="tbl-td-center"><?php 
                        $tot_working= Attendances::model()->getterm2Attendance($student_id,$batch_id);
                        $tot_abs= Attendances::model()->getLeaves($student_id);                       
                        echo ($tot_working-$tot_abs); ?>   </td>
				</tr>  
              </tbody>
              </table>
              </div>
         
      </div>
        </div>
      </td>
       </tr>
</table>


  
   

              

