<?php

$this->breadcrumbs=array(

	Yii::t('app','Report')=>array('/report'),

	Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Assessment Report'),

);

$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');

?>





<?php $form=$this->beginWidget('CActiveForm', array(

	'id'=>'student-form',

	'enableAjaxValidation'=>false,

)); ?>





<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td width="247" valign="top">

    <?php $this->renderPartial('left_side');?>

 </td>

    <td valign="top"> 

    <div class="cont_right">

    <h1><?php echo Yii::t('app','Assessment Report');?></h1>

	<div class="formCon">

     <div class="formConInner"> 

        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="s_search">

        	<tr>
                <td><strong><?php echo Yii::t('app','Academic Year');?></strong><span class="required"> *</span>
				<?php

                $academic_yrs = AcademicYears::model()->findAll("is_deleted =:x", array(':x'=>0));

                $academic_yr_options = CHtml::listData($academic_yrs,'id','name');

                ?>

                <?php

                echo CHtml::dropDownList('yid','',$academic_yr_options,array('prompt'=>Yii::t('app','Select Year'),

                'ajax' => array(

                'type'=>'POST',

                'url'=>CController::createUrl('/report/default/batches'),

				'success' => 'function(data){										

					var json = $.parseJSON(data);

					$("#batch").html(json.batch_list);

					$("#exam_id").html(json.examination);
					$("#semester_id").html(json.semester);

				}',                

         		'data'=>'js:{yid:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',

				),'options'=>array($_REQUEST['yid']=>array('selected'=>true))));

                ?>

                <div class="required" id="academic_yr_error"></div>

                </td>

			</tr>

            <tr>
            	<td colspan="4">&nbsp;</td>
            </tr>
            <?php 
			$disp_status='none';
			if(Configurations::model()->isSemesterEnabled()){
				$disp_status='block';
			} 
			?>
            <tr>
            	<td></td>
                <td style="width:100px;"><strong> 
				 <div class="" style="display:<?php echo $disp_status; ?>; padding-right: 10px" id="sem_div">
				<?php echo Yii::t('app','Select Semester'); ?></strong>
                </div>
                </td>
                <td></td>
                <td>
				
                <?php 
				if(isset($_REQUEST['semester_id']) and $_REQUEST['semester_id']!=NULL){
					$sid	=	$_REQUEST['semester_id'];
					$criteria=new CDbCriteria;
					$criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id'; 
					$sem_datas	= Semester::model()->findAll($criteria);
					$sem_datas		= CHtml::listData($sem_datas,'id','name');
					echo CHtml::dropDownList('semester_id',(isset($_REQUEST['semester_id']))?$_REQUEST['semester_id']:'',$sem_datas,array('prompt'=>Yii::t('app','Select'),
						'style'=>'width:200px;',
						'ajax' => array(
						'type'=>'POST',
						'url'=>CController::createUrl('/report/default/sembatches'),
						'update'=>'#batch',
						'beforeSend'=>'js:function(){   
																													
									$("#batch").find("option").not(":first").remove();
									
						}', 
						'data'=>'js:{status:1,semester_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
						), 
						'id'=>'semester_id',
						'options' => array($sid=>array('selected'=>true))));	
				}else{
						echo CHtml::dropDownList('semester_id',(isset($_REQUEST['semester_id']))?$_REQUEST['semester_id']:'','',array('prompt'=>Yii::t('app','Select'),
						'ajax' => array(
						'type'=>'POST',
						'url'=>CController::createUrl('/report/default/sembatches'),
						'update'=>'#batch',
						'beforeSend'=>'js:function(){   
																													
									$("#batch").find("option").not(":first").remove();
									
						}', 
						'data'=>'js:{status:1,semester_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
						), 
						'id'=>'semester_id',
						'options' => array()));
				}
					?>
                </td>
            </tr>

            <tr>

            	<td colspan="4">&nbsp;</td>

            </tr>

        

            <tr>

                <td>&nbsp;</td>

                <td><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></strong><span class="required"> *</span></td>

                <td>&nbsp;</td>

                 <?php 

				 

					if($year_id==NULL)

					{

						$current_academic_yr = Configurations::model()->findByPk(35);

						$year_id  = $current_academic_yr->config_value;					

					}

                  $criteria  = new CDbCriteria;

                  $criteria ->condition = 'academic_yr_id =:academic_yr and is_active=:x and is_deleted=:y';

                  $criteria->params = array(':academic_yr'=>$year_id,':x'=>1,':y'=>0);

                  ?>

                <td> 

                <?php 

				

                if($batch_id!=NULL)

                {

                    echo CHtml::dropDownList('batch','',CHtml::listData(Batches::model()->findAll($criteria),'id','coursename'),array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),

                    'ajax' => array(

                    'type'=>'POST',

                    'url'=>CController::createUrl('/report/default/batch'),

                    'update'=>'#exam_id',

                    'data'=>'js:{batch:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',),

					'options' => array($batch_id=>array('selected'=>true))));

                }

                else

                {

                    echo CHtml::dropDownList('batch','','',array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),

                    'ajax' => array(

                    'type'=>'POST',

                    'url'=>CController::createUrl('/report/default/batch'),

                    'update'=>'#exam_id',

                    'data'=>'js:{batch:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',)));

                }

                ?>

                <div class="required" id="batch_error"></div>

                </td>

            </tr>

            <tr>

                <td colspan="4">&nbsp;</td>

                </tr>

                <tr>

                <td>&nbsp;</td>

                <td valign="top"><strong><?php echo Yii::t('app','Examination');?></strong></td>

                <td>&nbsp;</td>

                

                <td><?php 

                if($batch_id)

                {

                    $data=ExamGroups::model()->findAll('batch_id=:x',array(':x'=>$batch_id));

                    $data=CHtml::listData($data,'id','name');

                    echo CHtml::activeDropDownList($model_1,'id',$data,array('prompt'=>Yii::t('app','Select Examination'),'id'=>'exam_id','options' => array($group_id=>array('selected'=>true))));

                    

                }

                else

                {

                    echo CHtml::activeDropDownList($model_1,'id',array(),array('prompt'=>Yii::t('app','Select Examination'),'id'=>'exam_id'));

                }?></td>

            </tr>

            <tr>

            	<td colspan="4">&nbsp;</td>

            </tr>

        </table>

       <div style="margin-top:10px;"><?php echo CHtml::submitButton(Yii::t('app','Search'),array('name'=>'search','class'=>'formbut', 'id'=>'search_btn')); ?></div>                       

       </div>

       </div>

     <br />

  <?php

  if(isset($list) and $list!=NULL)

  {

	  

	$flag='';

	$cls="even";

	 

	?>

     <div class="" style="top:220px;">

	 	<?php

			if($group_id != NULL and $batch_id != NULL){ 

				echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/report/default/assessmentpdf','examid'=>$group_id,'id'=>$batch_id),array('target'=>"_blank",'class'=>'pdf_but')); 

			}

		?>

     </div>

     <br />

     

    <!-- Batch Assessment Report -->

    <div class="tablebx" style="overflow-x:auto;">

    	<!-- Assessment Table -->

    	<table width="100%" border="0" cellspacing="0" cellpadding="0">

        	<!-- Table Headers -->

        	<tr class="tablebx_topbg">

                 <td style="width:90px;"><?php echo Yii::t('app','SL No.');?></td>

                <?php if(Configurations::model()->rollnoSettingsMode() != 1){?>

                <td style="width:90px;"><?php echo Yii::t('app','Adm No');?></td>

                <?php }?>

                 <?php if(Configurations::model()->rollnoSettingsMode() != 2){ ?>

                      <td style="width:90px;"><?php echo Yii::t('app','Roll No');?></td>

				<?php }?>

                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>

                <td style="width:auto;min-width:100px;"><?php echo Yii::t('app','Name');?></td>

                <?php }?>

                 

                <?php

				foreach($list as $exam) // Creating subject column(s)

				{

                	$subject=Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));

				?>

                	<td style="width:auto; min-width:80px; text-align:center;">

						<?php

							if($subject->elective_group_id==0){

								echo ($subject->name!=NULL)? ucfirst($subject->name):'-';

							}

							else{	 

								$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$subject->elective_group_id));

								$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));

								echo ($subject->name!=NULL)? ucfirst($subject->name):'-';

							}

						?>

                   	</td>

                <?php

				}

				?>

            </tr>

            <!-- End Table Headers -->

            <?php

			$batch_sl = 1;

            $students	= 	BatchStudents::model()->BatchStudent($batch_id);        

		

			if(isset($students) and $students!=NULL) // Checking if students are present

			{

				foreach($students as $student) // Creating row corresponding to each student.

				{

				?>

					<tr class=<?php echo $cls;?>>

                  <td style="padding-top:10px; padding-bottom:10px;"><?php echo $batch_sl; $batch_sl++;?></td>

                     <?php if(Configurations::model()->rollnoSettingsMode() != 1){?>

						<td>

							<?php echo $student->admission_no; ?>

						</td>

                    <?php }?>

                    <?php if(Configurations::model()->rollnoSettingsMode() != 2){ 

					$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));?>

                    	<td>

							<?php if($batch_student!=NULL and $batch_student->roll_no!=0){

								  				echo $batch_student->roll_no;

								  			}

											else{

												echo '-';

											}?>

						</td>

                     <?php }?>

                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>

						<td>

							<?php echo CHtml::link($student->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$student->id)); ?>

						</td>

                        <?php }?>

						<?php

						foreach($list as $exam) // Creating subject column(s)

						{

						$score = ExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));

						$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));

						?>

						

						<td>

						<?php

						if($score->marks!=NULL or $score->remarks!=NULL)

						{

						?>

							<!-- Mark and Remarks Column -->

							<table align="center" width="100%" style="border:none;width:auto; min-width:80px;">

								<tr>

									<td style="border:none;<?php if($score->is_failed == 1){?>color:#F00;<?php }?>">

										<?php 

										 $grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$examgroup->batch_id));

			                                             $t = count($grades);

														 if($examgroup->exam_type == 'Marks') {  

														 echo $score->marks; } 

														  else if($examgroup->exam_type == 'Grades') {

														  	

														   foreach($grades as $grade)

																{

																	

																 if($grade->min_score <= $score->marks)

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

																		$glevel = " No Grades" ;

																	} 

																

																} 

														   else if($examgroup->exam_type == 'Marks And Grades'){

															 foreach($grades as $grade)

																{

																	

																 if($grade->min_score <= $score->marks)

																	{	

																		$grade_value =  $grade->name;

																	}

																	else

																	{

																		$t--;

																		

																		continue;

																		

																	}

																echo $score->marks . " & ".$grade_value ;

																break;

																

																	

																} 

																if($t<=0) 

																	{

																		echo $score->marks." & ".Yii::t('app',"No Grades") ;

																	}

																 } 

										?>

									</td>

								</tr>

								<tr>

									<td style="border:none;<?php if($score->is_failed == 1){?>color:#F00;<?php }?>">

										<?php 

										if($score->remarks!=NULL)

											echo $score->remarks;

										else

											echo '-';

										?>

									</td>

								</tr>

							</table>

							<!-- End Mark and Remarks Column -->

						<?php

						}

						else

						{

							echo '-';

						}

						?>

						</td>

						<?php

						}

						?>

					</tr>

				<?php 

				} // END Creating row corresponding to each student.

  			} // End Checking if students are present

			else{

?>

				<tr>

                	<td colspan="<?php echo count($list)+3; ?>"><?php echo Yii::t('app','No Students Found'); ?></td>

                </tr>

<?php				

			}

			?>

        	

        </table>

        <!-- End Assessment Table -->

    </div>

    <!-- End Batch Assessment Report -->

    <br />

  

     <?php

	

  }

  else if(isset($_POST['ExamGroups'])){

	?>

    <div class="status_box" style="width:622px; margin:0px 0 5px;">

        <div class="sb_icon"></div>

        <span style="color:#FF0D50"><?php echo Yii::t("app", "No data found");?></span>

    </div>

    <?php

  }

  ?>   

<div class="clear"></div>

    </div>

</td>

</tr>

</table>

<?php $this->endWidget(); ?>

<script type="text/javascript">

$('#search_btn').click(function(ev){

	$('#academic_yr_error').html('');

	$('#batch_error').html('');

	var academic_yr = $('#yid').val();

	var batch_id	= $('#batch').val();

	var flag		= 0;

	if(academic_yr == ''){

		flag = 1;

		$('#academic_yr_error').html('<?php echo Yii::t('app','Academic Year cannot be blank'); ?>');

	}

	if(batch_id == ''){

		flag = 1;

		$('#batch_error').html('<?php echo Students::model()->getAttributeLabel('batch_id').' '.Yii::t('app','cannot be blank'); ?>');

	}

	if(flag == 1){

		return false;

	}

});

</script>