<?php 
/*$data=ExamScores::model()->findAll('YEAR(updated_at) = YEAR(CURDATE())');
$total = 0;
foreach ($data as $data1)
{ 
	$mark[]=$data1->marks;
	$mark_obtain=array_sum($mark);
	$exam=Exams::model()->findAll('YEAR(updated_at) = YEAR(CURDATE()) AND id=:x',array(':x'=>$data1->exam_id));
    foreach ($exam as $exam1)
	{
		$max[]=$exam1->maximum_marks;
		$max_mark=array_sum($max);
	}
}

$student_total=count(ExamScores::model()->findAll());
$student_fail=count(ExamScores::model()->findAll('YEAR(updated_at) = YEAR(CURDATE()) AND is_failed=:y',array(':y'=>1)));
$student_pass=$student_total - $student_fail;
$average=round(($student_pass/$student_total)*100);
$mark_avg=round(($mark_obtain/$max_mark)*100); 
$lastId = Yii::app()->db->createCommand('SELECT id FROM exam_scores  ORDER BY id DESC LIMIT 1')->queryScalar();
$last=ExamScores::model()->findByAttributes(array('id'=>$lastId));
$pass=ExamScores::model()->findAll('exam_id=:x',array(':x'=>$last->exam_id));
foreach ($pass as $pass1)
{ 
	$mark1[]=$pass1->marks;
	$mark_obtain1=array_sum($mark1);
} 
$last_exam=Exams::model()->findByAttributes(array('id'=>$last->exam_id));
$last_grp=$last_exam->exam_group_id;
$last_max=$last_exam->maximum_marks;
$last_total=count(ExamScores::model()->findAll('exam_id=:x',array(':x'=>$last->exam_id)));
$last_fail=count(ExamScores::model()->findAll('exam_id=:x AND is_failed=:y',array(':x'=>$last->exam_id,':y'=>1)));
$last_pass=$last_total-$last_fail;
$avg=round(($last_pass/$last_total)*100);
$last_maxtotal=$last_max * $last_total;
$last_avg=round(($mark_obtain1/$last_maxtotal)*100);


//annual exam average
$anual_avg	=	0;
$criteria	=	new CDbCriteria;
$criteria->select	=	"AVG(marks) AS marks";
$data		=	ExamScores::model()->find($criteria);
if($data){
	$anual_avg	=	round($data->marks);
}*/

$average	= 0;
$anual_avg	= 0;
$avg		= 0;
$last_avg	= 0;
//exam_group_ids
$exam_group_ids	= array();
//exam_ids
$exam_ids	= array();
$exam_criteria	= array();

$all_students_marks	= array();
$passed_students	= array();
$max_total_marks	= 0;
$students_total_marks	= 0;

//exam groups
$criteria		= new CDbCriteria;
/*$criteria->condition	= '`exam_date` < :today AND YEAR(`exam_date`) = :this_year';
$criteria->params		= array(
							':today' 	=> date('Y-m-d'), 
							':this_year'=> date('Y'),
						  );*/
						  
$criteria->condition	= 'YEAR(`exam_date`) = :this_year AND `result_published`=:result_published';
$criteria->params		= array( 
							':this_year' => date('Y'),
							':result_published' => 1
						  );
						  						  
$exam_groups	= ExamGroups::model()->findAll($criteria);

if(count($exam_groups)>0){
	foreach($exam_groups as $exam_group){
		array_push($exam_group_ids, $exam_group->id);
	}
	
	//exams
	$criteria		= new CDbCriteria;
	$criteria->addInCondition('`exam_group_id`', $exam_group_ids);
	$exams	= Exams::model()->findAll($criteria);
	if(count($exams)>0){
		foreach($exams as $exam){	
			array_push($exam_ids, $exam->id);	
			$exam_criteria[$exam->id]	= array(
											'min'	=> $exam->minimum_marks,
											'max'	=> $exam->maximum_marks,
										  );
		}
		
		//exam scores
		$criteria		= new CDbCriteria;
		$criteria->addInCondition('`exam_id`', $exam_ids);
		$exam_scores	= ExamScores::model()->findAll($criteria);
		if(count($exam_scores)>0){
			foreach($exam_scores as $exam_score){
				$all_students_marks[$exam_score->student_id][$exam_score->exam_id]	= $exam_score->marks;
			}
			
			//fetching all student ids for checkig if student exists
			$allstudents	= Students::model()->findAll();
			$student_ids	= array();
			foreach($allstudents as $student){
				array_push($student_ids, $student->id);
			}
			
			foreach($all_students_marks as $student_id=>$student_marks){				
				if(in_array($student_id, $student_ids)){
					$student_passed_the_exam	= true;
					foreach($student_marks as $exam_id=>$mark){
						if($mark < $exam_criteria[$exam_id]['min'])		//checking mark with $exam_criteria min mark
							$student_passed_the_exam	= false;
							
						$max_total_marks		+= $exam_criteria[$exam_id]['max'];
						$students_total_marks	+= ($mark > $exam_criteria[$exam_id]['max'])?$exam_criteria[$exam_id]['max']:$mark;
					}
					
					//check if student passed
					if($student_passed_the_exam)
						array_push($passed_students, $student_id);	
				}
				else{
					unset($all_students_marks[$student_id]);
				}
			}
		}
	}
}
//annual exam pass
$average	=	floor(( count($passed_students) / count($all_students_marks) ) * 100);

//annual exam average marks
$anual_avg	=	floor(( $students_total_marks / $max_total_marks ) * 100);

?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/justgage.1.0.1.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/raphael.2.1.0.min.js"></script>
<script>
	var g1, g2, g3, g4;
	window.onload = function(){
	
	var g1 = new JustGage({
	id: "g1", 
	value: <?php echo $average;?>, 
	levelColors : Array("#fd2600","#fc4f00","#b0e108"),
	min: 0,
	max: 100,
	title: "<?php echo Yii::t('app','Annual Exam Pass'); ?>",
	titleFontColor :"#464646",
	label: "<?php echo Yii::t('app','percentage'); ?>"
	});
	
	var g2 = new JustGage({
	id: "g2", 
	value:<?php echo $anual_avg;?>, 
	min: 0,
	max: 100,
	titleFontColor :"#464646",
	title: "<?php echo Yii::t('app','Annual Exam Average Marks'); ?>",
	label: "<?php echo Yii::t('app','marks'); ?>"
	});
	
	/*var g3 = new JustGage({
	id: "g3", 
	value:<?php //echo $avg;?>, 
	min: 0,
	max: 100,
	titleFontColor :"#464646",
	title: "Last Assessment Pass",
	label: "percentage"
	});
	
	var g4 = new JustGage({
	id: "g4", 
	value:<?php //echo $last_avg;?>, 
	min: 0,
	max: 100,
	titleFontColor :"#464646",
	title: "Last Assessment Average",
	label: "percentage"
	});*/
	
	/*setInterval(function() {
	g1.refresh(getRandomInt(50, 100));
	g2.refresh(getRandomInt(50, 100));          
	g3.refresh(getRandomInt(0, 50));
	g4.refresh(getRandomInt(0, 50));
	}, 2500);*/
	};
</script>
<?php
	$this->breadcrumbs=array(
	Yii::t('app','Examination'),
	);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('left_side');?>
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="75%">
                    <div class="cont_right">
                    <h1><?php echo Yii::t('app','Assessments Dashboard'); ?></h1>
                    <div class="clear"></div>
                    <div id="g1"></div>
                    <div id="g2"></div>
                    <!--<div class="clear"></div>
                    <div id="g3"></div>
                    <div id="g4"></div>-->
                    <div class="clear"></div>
                    <div class="pdtab_Con" style="width:97%">
                    <div style="font-size:13px; padding:5px 0px"><strong><?php echo Yii::t('app','Recent Exams'); ?></strong></div>
                    <div class="pdtab_Con">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="pdtab-h">
                                <td align="center">
                                    <?php echo Yii::t('app','Date');?>
                                </td>
                                <td align="center">
                                    <?php echo Yii::t('app','Assessment');?>
                                </td>
                                <td align="center">
                                	<?php echo Yii::t('app','Class Teacher');?>
                                </td>
                                <td align="center">
                                	<?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?>
                                </td>
                                <td align="center">
                                	<?php echo Yii::t('app','Average');?>
                                </td>
                            </tr>
                           
                           
								<?php
								
								$recent_exam_groups = ExamGroups::model()->findAllByAttributes(array(),array('order'=>'id DESC','limit'=>5));
								if($recent_exam_groups!=NULL) //If exam groups are present
								{
									foreach($recent_exam_groups as $recent_exam_group)
									{
										
									?>
                                    	<tr>
                                    		<td align="center">
												<?php
													if($recent_exam_group->exam_date!=NULL)
													{
														$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
														if($settings!=NULL)
														{	
															$recent_exam_group->exam_date = date($settings->displaydate,strtotime($recent_exam_group->exam_date));
														}
														echo $recent_exam_group->exam_date;
													}
													else
													{
														echo '-';
													}
													
												?>
											</td>
                                            <td align="center">
                                            	<?php
													if($recent_exam_group->name!=NULL)
													{
														echo ucfirst($recent_exam_group->name);
													}
													else
													{
														echo '-';
													}
												?>
                                            </td>
                                            <td align="center">
                                            	<?php
													if($recent_exam_group->batch_id!=NULL)
													{
														$batch = Batches::model()->findByAttributes(array('id'=>$recent_exam_group->batch_id));
														$students = Students::model()->countByAttributes(array('batch_id'=>$recent_exam_group->batch_id));
														if($batch->employee_id!=NULL)
														{
															$class_teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
															if($class_teacher->first_name!=NULL)
															{
																echo Employees::model()->getTeachername($class_teacher->id);
															}
															else
															{
																echo '-';
															}
														}
														else
														{
															echo '-';
														}
													}
													else
													{
														echo '-';
													}
												?>
                                            </td>
                                            <td align="center">
                                            	<?php
													if($batch->name!=NULL)
													{
														$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
														echo ucfirst($batch->name).' / '.ucfirst($course->course_name);
													}
													else
													{
														echo '-';
													}
												?>
                                            </td>
                                            <td align="center">
                                            	<?php
												$recent_exams = Exams::model()->findAllByAttributes(array('exam_group_id'=>$recent_exam_group->id));
												if($recent_exams!=NULL)
												{
													if($students>0)
													{
														$total_maximum_marks = 0;
														$total_exam_average = 0;
														$i=0;
														foreach($recent_exams as $recent_exam)
														{
															$i=1;
															$student_count=0;
															$class_average=0;
															$exam_student_total =0;
															$total_maximum_marks = $total_maximum_marks + $recent_exam->maximum_marks;
															$scores = ExamScores::model()->findAllByAttributes(array('exam_id'=>$recent_exam->id));
															if($scores!=NULL)
															{
																foreach($scores as $score)
																{
																	$student_count++;
																	$exam_student_total = $exam_student_total + $score->marks;
																}
															}
															$class_average = $exam_student_total/$student_count;
															$total_exam_average = $total_exam_average+$class_average;
															$i++;
														}
														$average = round($total_exam_average/$i,2);
														if($average != 0)
														{
															echo $average;
														}
														else
														{
															
															echo Yii::t('app','No scores entered');
															
														}
													}
													else
													{
														echo Yii::t('app','No students');
														//echo 'No students';
													}
												}
												else
												{
													echo Yii::t('app','No exams created');
													//echo 'No exams created';
												}
												?>
                                            </td>
										</tr>	
                                    <?php 
									}
								}
								else
								{
									//echo '<td align="center" colspan="5"><strong>'.'No Recent Exams!'.'</td>';
									echo '<td align="center" colspan="5"><strong>'.Yii::t('app','No Recent Exams!').'</td>';
								}
								
								
								
								 
                                //$Recent = Yii::app()->db->createCommand('SELECT  id,student_id,exam_id,marks  FROM exam_scores  ORDER BY id DESC LIMIT 5')->query(); 
//                                if(count($Recent) >0 )
//                                {
//									foreach ($Recent as $Recent1)
//									{	
//										$recent_exam=Exams::model()->findAll('id=:x',array(':x'=>$Recent1['exam_id']));
//										$max1=array();
//										$student=Students::model()->findAll('id=:x',array(':x'=>$Recent1['student_id']));
//										foreach ($student as $student1)
//										{  
//											$batch=Batches::model()->findAll('id=:x',array(':x'=>$student1['batch_id']));
//											foreach ($batch as $batch1)
//											{
//												$course1=Courses::model()->findByAttributes(array('id'=>$batch1->course_id));
//											}			
//										}
//										foreach ($recent_exam as $recent_exam1)
//										{
//											$max1[]=$recent_exam1->maximum_marks.'<br/>';
//											$max_mark1=array_sum($max1);
//											$recent_obt=ExamScores::model()->findAll('id=:x',array(':x'=>$Recent1['id'])); 
//											$mark1=array();
//											foreach ($recent_obt as $recent_obt1)
//											{
//												$mark1[]=$recent_obt1->marks.'<br/>';
//												$recent_obtain=array_sum($mark1);
//											}		  
//											
//											$recent_avg=round(($recent_obtain/$max_mark1)*100);
//											$recent1=ExamGroups::model()->findAllByAttributes(array('batch_id'=>$batch1->id,'id'=>$recent_exam1->exam_group_id));
//											foreach($recent1 as $recent_1)
//											{
//												$recent_1->name;
//												$recent_1->exam_date;
//												//echo $recent_1->name;	
//											}
//											$batch=Batches::model()->findByAttributes(array('id'=>$Recent1['batch_id']));
//											$emp1=Employees::model()->findByAttributes(array('id'=>$batch1->employee_id));
//											$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
//											if($settings!=NULL)
//											{	
//												$recent1->exam_date=date($settings->displaydate,strtotime($recent1->exam_date));
//											}
//											?>
											<!--<td align="center"><?php echo $recent_1->exam_date;?> </td>
											<td align="center"><?php echo $recent_1->name;?> </td>
											<td align="center"><?php echo Employees::model()->getTeachername($emp1->id);?> </td>
											<td align="center"><?php echo $course1->course_name;;?>  </td>
											<td align="center"><?php echo $recent_avg.' %';?> </td>
										</tr>-->
										            
										
									<?php
									
									//}
//								}
//								
//                            }	
//                            else
//                            {
//                            	echo ' <tr><td align="center" colspan="5"><strong>'.'No Recent Exams!'.'</td> <tr>';
//                            }
                            ?>
                            
                        </table>
                        </div>
                        </div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


