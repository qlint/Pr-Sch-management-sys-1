<style>

.formCon input[type="text"], input[type="password"], textArea, select {

	padding: 6px 3px 6px 3px;

	width: 140px;

}



.ui-menu .ui-menu-item a {

	color: #000 !important;

}

.ui-menu .ui-menu-item a:hover {

	color: #fff !important;

}

.ui-autocomplete {

	box-shadow: 0 0 6px #d6d6d6;

}

</style>

<?php

 $this->breadcrumbs=array(

	Yii::t('app','Examination')=>array('/examination'),

	Yii::t('app','CBSE Grade Book'),

);



  $student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');

  ?>

<?php $form=$this->beginWidget('CActiveForm', array(

  'id'=>'student-form',

  'enableAjaxValidation'=>false,

  )); ?>



<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>

    <td valign="top"><div class="cont_right">

        <h1><?php echo Yii::t('app','CBSE Grade Book');?></h1>

        <div class="button-bg">

            <div class="top-hed-btn-left"> </div>

            <div class="top-hed-btn-right">

                <ul>                                                    

                    <li><?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>', array('/examination/exam/cbsc','cid'=>$_REQUEST['cid'],'bid'=>$_REQUEST['bid']),array('class'=>'a_tag-btn')); ?></li>                               

                </ul>

            </div> 

        </div>

        <?php

  //if(isset($_REQUEST['flag']) and $_REQUEST['flag']==1)

  if($flag==1)

  {

  echo '<div class="listhdg" align="center">'.Yii::t('app','Invalid search! Please enter a student name.').'</div>';	

  }

  else

  {

  }

  if(isset($list))

  { 

  $details=Students::model()->findByAttributes(array('id'=>$student,'is_deleted'=>0,'is_active'=>1));

  $batch_model  =   Students::getStudentBatch($student);
  $batch=Batches::model()->findByAttributes(array('id'=>$batch_model->id,'is_deleted'=>0,'is_active'=>1));
  
  $batch_id=    $_REQUEST['bid'];
  
  $batch=Batches::model()->findByAttributes(array('id'=>$batch_id,'is_deleted'=>0,'is_active'=>1));

  $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));

  $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);

  $semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id));

  ?>

        <h3><?php echo Yii::t('app','Student Information');?></h3>

        <div class="tablebx">

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr class="tablebx_topbg">

              <td><?php echo Yii::t('app','Admission No');?></td>

              <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>

              <td><?php echo Yii::t('app','Student Name');?></td>

              <?php } ?>

              <td><?php echo Yii::t('app','Course');?></td>

              <?php if(in_array('batch_id', $student_visible_fields)){ ?>

              <td><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>

              <?php } ?>

			  <?php if($sem_enabled==1 and $batch->semester_id!=NULL){ ?>

              <td><?php echo Yii::t('app','Semester');?></td>

              <?php } ?>

            </tr>

            <tr>

              <td><?php echo $details->admission_no; ?></td>

              <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>

              <td style="padding:10px;"><?php echo CHtml::link($details->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$details->id)); ?></td>

              <?php }?>

              <td><?php 

  if($course->course_name!=NULL)

  echo $course->course_name;

  else

  echo '-';

  ?></td>

              <?php if(in_array('batch_id', $student_visible_fields)){ ?>

              <td><?php 

  if($batch->name!=NULL)

  echo $batch->name;

  else

  echo '-';

  ?></td>

              <?php } ?>

			  <?php if($sem_enabled==1 and $batch->semester_id!=NULL){ ?>

              <td style="padding:10px;"><?php echo ucfirst($semester->name);  ?></td>

              <?php }?>

            </tr>

          </table>

        </div>

        <!-- END div class="tablebx" Student Information --> 

        <br />

        <br />

        <h3><?php echo Yii::t('app','Assessment Report');?></h3>

        <?php

  $examgroups = CbscExamGroup17::model()->findAll('batch_id=:x',array(':x'=>$batch->id)); // Selecting exam groups in the batch of the student

  if($examgroups!=NULL) // If exam groups present

  { 

  $i = 1;

  foreach($examgroups as $examgroup) 

  {

  $flag1=0;

  ?>

        <?php

  $exams = CbscExams17::model()->findAll('exam_group_id=:x',array(':x'=>$examgroup->id)); // Selecting exams(subjects) in an exam group

  if($exams!=NULL)

  { 

    
  ?>

        <br />

        <span style="float:left;">

        <h4><?php echo $i.'. '.ucfirst($examgroup->name); $i++;?></h4>

        </span>

        <?php

  

  foreach($exams as $exam)

  {

    $subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));

    if($subject!=NULL) // Checking if exam for atleast subject is created.

    { 

        $score = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student));

        if($score!=NULL)

        {

            $flag1=1;

        }

    }

  }

  ?>

<?php 

  if($flag1==1)

  { ?>

        <span style="float:right"><?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/report/default/studentcbscpdf','exam_group_id'=>$examgroup->id,'id'=>$student, 'bid'=>$_REQUEST['bid']),array('target'=>"_blank",'class'=>'pdf_but')); ?></span>

        <?php

  }

  ?>

        <!-- Single Exam Table -->

        <div class="tablebx result-table" style="clear:both">

          <div class="os-table">

            <div class="tbl-grd"></div>

            <table class="table table-bordered2" border="0" cellpadding="0" cellspacing="0" width="100%">

              <thead>

                <tr>

                  <th rowspan="3" colspan="2" class="header-td1" width="400"><?php echo Yii::t('app','Subject');?></th>

                  <th  width="100"class="header-td1"><?php echo Yii::t('app','Score');?></th>  
				  
				  <th  width="100"class="header-td1"><?php echo Yii::t('app','Grade');?></th>                

                  <th width="100"><?php echo Yii::t('app','Remarks');?></th>

                </tr>

              </thead>

              <tbody>

                <?php

                    $status	=0;

                    if($exams!=NULL)
                    { 
                      $score_flag=0;  
                      foreach($exams as $exam)
                      {                          
                        $min 	 = $exam->minimum_marks;
                        $subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
                        if($subject!=NULL) // Checking if exam for atleast subject is created.
                        {

                            $score = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student,));

                            if($score!=NULL)                            
                            { 
                                $score_flag=1;
                                ?>
                                <tr>
                                    <td colspan="2" style="text-align:left;padding-left:50px"><?php 
                                    if($subject->name!=NULL){

                                          if($subject->elective_group_id==0){

                                                  echo ($subject->name!=NULL)? ucfirst($subject->name):'-';

                                          }else{	 
                                                  $electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));
                                                  $elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
                                                  echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
                                                  }
                                    }
                                    else
                                    continue;
                                    ?>
                                    </td>
                                    <td><?php

                                        if($score->total!=NULL)

                                        {

                                        echo $score->total;

                                        }

                                        else

                                        {

                                        echo '-';

                                        }

                                        ?>

                                    </td>

                                    <td>
                                        <?php if($examgroup->class == 1){
                                                                  echo CbscExamScores17::model()->getClass1Grade($score->total);

                                                         }
                                                         else{
                                                                  echo CbscExamScores17::model()->getClass2Grade($score->total);

                                                         }
                                        ?>

                                    </td>                                     
                                    <td><?php

                                        if($score->remarks!=NULL)

                                        {

                                        echo $score->remarks;

                                        }

                                        else

                                        {

                                        echo '-';

                                        }

                                        ?>

                                    </td>

                                </tr>

                            <?php                           
                            }else{
                                ?>
                                <tr>
                                    <td colspan="2" style="text-align:left;padding-left:50px">
                                        <?php 
                                        if($subject->name!=NULL){
                                            if($subject->elective_group_id==0){
                                                    echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
                                            }else{	 
                                                $electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));
                                                $elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
                                                echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
                                            }
                                        }
                                        else
                                        continue;
                                        ?>
                                    </td>
                                    <td> <?php echo '-'; ?> </td> 
                                    <td> <?php echo '-'; ?> </td>                                   
                                    <td> <?php echo '-'; ?> </td>                                  
                                </tr> 
                                <?php
                            }

                           

                            

                        }

                      }
                      
                      if($score_flag==0){
                          ?>
                                <tr><td colspan="4"><?php echo Yii::t('app','No Scores Found'); ?></td></tr>  
                            <?php
                      }
                    }

                     

                    ?>

              </tbody>

            </table>

          </div>

          <?php

  }

 

  /*else //If no exam created

  {

  echo '<tr><td colspan="4" style="padding-top:10px; padding-bottom:10px; text-align:center;"><strong>'.Yii::t('app','No exam created for any subject in this batch!').'</strong></td></tr>';

  }*/

  /*if($exam_score==0)

  {

  echo '<tr><td colspan="4" style="padding-top:10px; padding-bottom:10px; text-align:center;"><strong>'.Yii::t('app','No mark is entered for the Exam').'</strong></td></tr>';

  }*/

  ?>

          

          <!-- END Single Exam Table -->

          <?php

  

  } // END foreach($examgroups as $examgroup)

  }

  else // If no exam groups present in the batch of the student

  {

  echo '<div class="listhdg" align="center">'.Yii::t('app','No exam details available!').'</div>';	

  }

  

  } //END isset($list)

  ?>

          <div class="clear"></div>

        </div>

        <!-- End div class="cont_right" --> 

      </div></td>

  </tr>

</table>

</div>

</div>

<?php $this->endWidget(); ?>

