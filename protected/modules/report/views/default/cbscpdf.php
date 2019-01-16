<style>
.report-stu-dtls-table table{
	border-collapse:collapse;

	   	
}
.os-table table{
	border-collapse:collapse;
	width:100%;
	border:1px solid #000;	
}
.os-table table td{
	border-collapse:collapse;
	border:1px solid #000;
	 font-size:10px;
	 padding:5px 5px;	
}
.os-table table td.header-td1{
	 text-align:center;
	  font-weight:bold;	
	 
}
.report-hed td{ text-align:center; font-size:10px;}
.listbxtop_hdng-f1{
	 font-size:12px;	
}
.listbxtop_hdng-f2{
	 font-size:12px;	
}
.report-stu-dtls-table .inner-table{
	 border:1px solid #000;	
}
.report-stu-dtls-table .inner-table td{

	 padding:10px;
	 font-size:11px;	
}
.report-stu-dtls-table .br-td{
	 border-right:1px solid #000;

}
.report-table-cmn table{ border-collapse:collapse; border:1px solid #000;}
.report-table-cmn table td{ border-bottom:1px solid #000; padding:6px; font-size:10px; border-right:1px solid #000;}
.report-table-cmn table th{ border-bottom:1px solid #000; padding:6px; font-size:10px; border-right:1px solid #000;}
.report-table-cmn .tbl-td-left{text-align:left; text-transform:uppercase;}
.report-table-cmn .tbl-td-center{text-align:center;}
.bold-cnt{ font-weight:bold; text-transform:uppercase;}
.report-hed td{
	  text-transform:uppercase;	
}
.report-table-cmn-box table{
	border-collapse:collapse; border:1px solid #000;
}
.report-table-cmn-box table td{ padding:6px; font-size:10px; border-right:1px solid #000;}
.report-table-cmn-ftr table td{
	padding:10px;
	font-size:12px;	
}
</style>
<?php
$student_id= $_REQUEST['id'];
$model= Students::model()->findByPk($student_id);
$batch_id= $model->batch_id;
$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student_id, 'batch_id'=>$batch_id));
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
$employee=Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
$sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
$semester		=	Semester::model()->findByAttributes(array('id'=>$batch->semester_id));

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
<!-- Header -->

	

        <table width="100%" cellspacing="0" cellpadding="0">

            <tr>

                <td class="first " width="100">

                           <?php $logo=Logo::model()->findAll();?>

                            <?php

                            if($logo!=NULL)

                            {

                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);

                                echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';

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

                                <?php echo Yii::t('app','Phone:')." ".$college[2]->config_value; ?>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

    <hr />

    <br />

    <!-- End Header -->

<table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>

          <table class="report-hed" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
              <tr>
                <td><h2><?php echo Yii::t('app','REPORT CARD - 2017-2018'); ?></h2></td>
                </tr>
                
              </tbody>
          </table>
   
<table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>
<div class="report-stu-dtls-table">

              <table cellspacing="0" cellpadding="0" border="0" width="100%" class="inner-table">
            <tbody>
                <tr>
                <td width="100"><?php echo Yii::t('app','Student Name:');?></td>
                <td width="2">:</td>
                <td class="br-td"><?php 
								   if(FormFields::model()->isVisible("fullname", "Students", 'forStudentProfile')){
										echo $model->studentFullName('forStudentProfile');
									} 
               						?>
               </td>
                <td width="100"><?php echo Yii::t('app',"Roll No"); ?></td>
                <td width="2">:</td>
                <td><?php echo $batch_student->roll_no;?></td>

              <tr>
                <td width="100"><?php echo Yii::t('app',"Admission No"); ?></td>
                <td width="2">:</td>
                <td class="br-td">
                 <?php if(FormFields::model()->isVisible('admission_no','Students','forStudentProfile'))
				   {
					  echo $model->admission_no;         
				   }
				   ?></td>
                <td width="100"><?php echo Yii::t('app','Course'); ?></td>
                <td width="2">:</td>
                <td><?php echo $course->course_name;?></td>
              </tr>
              <tr>
                <td width="100"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></td>
                <td width="2">:</td>
                <td class="br-td"><?php echo $batch->name;?></td>
                <?php ?>
                <td width="100"><?php
				if($sem_enabled==1 and $batch->semester_id!=NULL){ 
				 echo Yii::t('app',"Semester"); 
				}
				 ?></td>
                <td width="2">:</td>
                <td> <?php echo ucfirst($semester->name);?></td>
                </tr>
              </tbody>
              </table>

        </div>      
       
<table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>

<div class="os-table tablebx">
          <table class="table" cellspacing="0" cellpadding="0" width="100%">
            <tbody>
              <tr>
                <td rowspan="3" class="header-td1"><?php echo Yii::t('app','SUBJECT'); ?></td>
                <td colspan="2" class="header-td1"><?php echo Yii::t('app','TERM I'); ?></td>
                <td colspan="2" class="header-td1"><?php echo Yii::t('app','TERM II'); ?></td>
                <td colspan="2" class="header-td1"><?php echo Yii::t('app','TOTAL TERM I+II'); ?></td>
              </tr>
               <?php
              $fa1_exam_group = CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'FA1','result_published'=>1));
			  $sa1_exam_group = CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'SA1','result_published'=>1));
			  $fa2_exam_group = CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'FA2','result_published'=>1));
			  $sa2_exam_group = CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'SA2','result_published'=>1));
			  ?>
              <tr>
                <td class="header-td1"  rowspan="2">
					<?php if($fa1_exam_group!=NULL){
							echo ucfirst($fa1_exam_group->name);
					}?>
                </td>
                <td class="header-td1"  rowspan="2">
					<?php if($sa1_exam_group!=NULL){
							echo ucfirst($sa1_exam_group->name);
					}?>
                </td>
                <td class="header-td1"  rowspan="2">
					<?php if($fa2_exam_group!=NULL){
							echo ucfirst($fa2_exam_group->name);
					}?>
                </td>
                <td class="header-td1"  rowspan="2">
					<?php if($sa2_exam_group!=NULL){
							echo ucfirst($sa2_exam_group->name);
					}?>
                </td>
                <td class="header-td1" colspan="2"><?php echo Yii::t("app","OVERALL"); ?></td>
              </tr>
              <tr>
                <td class="header-td1" colspan="2"><?php echo Yii::t('app',"Mark"); ?></td>
              </tr>
               <?php  $subjects= Subjects::model()->findAllByAttributes(array('batch_id'=>$batch_id,'elective_group_id'=>0, 'cbsc_common'=>0));
		if($subjects!=NULL)
        {
            $i=1;
            foreach ($subjects as $subject)
            {  
				$mark	= 0;              
        ?> 
            <tr>
                <td class="header-td1"><?php echo ucfirst($subject->name); ?></td>
                <td align="center">  
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
                <td align="center"> 
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
                <td align="center"> 
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
                <td align="center">
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
                <td colspan="2" align="center">
					<?php 					  	
						echo $mark;
						$grand_total+=$mark;
                    ?>
                </td>
                
                </tr>

          
              
               <?php
        $i++;
            }
			?>
             <tr>
            <td class="header-td1" colspan="5"><?php echo Yii::t("app","AVERAGE"); ?></td>
            <td class="header-td1" colspan="2"><?php echo round($grand_total/count($subjects), 1);?></td>
            <!--<td> - </td>--> 
          </tr>

            <?php
        }
        else
        {
             echo "<tr><td colspan='7'>".Yii::t("app","Subjects not found")."</td></tr>";
           
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
        
        <table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>        
 <div class="report-table-cmn">
              <table cellspacing="0" cellpadding="0" border="0" width="100%" class="inner-table">
            <tbody>
                <tr>
                <th class="tbl-td-left" ><?php echo Yii::t('app','SUBJECT'); ?></th>
                <th width="180"><?php echo Yii::t('app','TERM I'); ?></th>
                <th width="180"><?php echo Yii::t('app','TERM II'); ?></th>
				</tr>
                <?php 
					 if($cbsc_exam_scores_sa1_gk!=NULL or $cbsc_exam_scores_sa2_gk!=NULL){?>	 
                
                <tr>
                <td class="tbl-td-left"><?php echo Yii::t('app','GK'); ?></td>
                <td class="tbl-td-center"><?php  if($sa1_score_gk == 1){
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
                <td class="tbl-td-center"><?php  if($sa2_score_gk == 1){
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
                <td class="tbl-td-left"><?php echo Yii::t('app','Drawing'); ?></td>
                <td class="tbl-td-center"><?php  if($sa1_score_draw == 1){
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
                <td class="tbl-td-center"><?php  if($sa2_score_draw == 1){
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
										}?>
                    </td>
                    </tr>
                 <?php } ?>     
                 </tbody>
                </table>
            </div> 
      <?php } ?>      
        
        <table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>        
      
 <div class="report-table-cmn">
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
                        echo ($tot_working-$tot_abs); ?>   </td>
                <td class="tbl-td-center"><?php 
                        $tot_working= Attendances::model()->getterm2Attendance($student_id,$batch_id);
                        $tot_abs= Attendances::model()->getLeaves($student_id);                       
                        echo ($tot_working-$tot_abs); ?> </td>
				</tr>  
              </tbody>
              </table>
              </div>
        <table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>     
      
 <div class="report-table-cmn-box">
              <table cellspacing="0" cellpadding="0" border="0" width="100%" class="inner-table">
            <tbody>
                <tr>
                <td>
                	<h3><?php echo Yii::t('app','Result'); ?></h3>
                    
                </td>
				</tr>                
                <tr>
                <td>
                	<p></p>
                    
                </td>
				</tr>                 
              </tbody>
              </table>              
              

        </div> 
        <table cellspacing="0" cellpadding="0" border="0" width="100%"><tbody><tr><td height="10"></td></tr></tbody></table> 
 <div class="report-table-cmn-ftr">
              <table cellspacing="0" cellpadding="0" border="0" width="100%" class="inner-table">
            <tbody>
                <tr>
                <td class="tbl-td-left" colspan="2"> </td>
                <td width="225" rowspan="2" valign="bottom"><?php echo Employees::model()->getTeachername($employee->id); ?></td>
                <td width="225" rowspan="3" valign="bottom"><?php echo Yii::t('app','Principal(with School Seal)'); ?></td>
				</tr>
                
                <tr>
                <td class="tbl-td-left" width="50"><?php echo Yii::t('app','Place'); ?></td>
                <td class="tbl-td-center">-- <?php  echo Yii::t('app','New Delhi'); ?></td>
				</tr>
                
                <tr>
                <td class="tbl-td-left" width="50"><?php echo Yii::t('app','Date'); ?></td>
                <td class="tbl-td-center">-- 
                					<?php   
									 if($settings!=NULL)
									{	
											$date1 = date($settings->displaydate,strtotime(date('Y-m-d')));
											echo $date1;										
									}else{
											date('Y-m-d');
									}
				 				 ?></td>
                                 <td><?php echo Yii::t('app','Class Teacher'); ?></td>
             
              
				</tr>
                                                          
                                 
              </tbody>
              </table>              
              

        </div>         
                