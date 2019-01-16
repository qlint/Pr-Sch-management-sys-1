<style>
.infored_bx{
	padding:5px 20px 7px 20px;
	background:#e44545;
	color:#fff;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border-radius:4px;
	font-size:15px;
	font-style:italic;
	text-shadow: 1px -1px 2px #862626;
	text-align:left;
}


input.disabled_field
{
	background-color:#EFEFEF !important;
}
.exam-table-line input[type="text"], input[type="password"], textArea {
    border-radius: 0px !important;
    border: 1px #c2cfd8 solid;
    padding: 7px 3px;
    background: #fff;
    margin: 0 2px;
    box-shadow: none !important;
    box-sizing: border-box;
    width: 81px !important;
}
</style>

    
<?php 
if(isset($_REQUEST['id']))
{
	
	$criteria = new CDbCriteria;
	$criteria->condition = 'is_deleted=:is_deleted AND is_active=:is_active';
	$criteria->params[':is_deleted'] = 0;
	$criteria->params[':is_active'] = 1;
	
	
	$batch_students = BatchStudents::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'result_status'=>0,'status'=>1));
	if($batch_students)
	{
		$count = count($batch_students);
		$criteria->condition = $criteria->condition.' AND (';
		$i = 1;
		foreach($batch_students as $batch_student)
		{
			
			$criteria->condition = $criteria->condition.' id=:student'.$i;
			$criteria->params[':student'.$i] = $batch_student->student_id;
			if($i != $count)
			{
				$criteria->condition = $criteria->condition.' OR ';
			}
			$i++;
			
		}
		$criteria->condition = $criteria->condition.')';
	}
	else
	{
		$criteria->condition = $criteria->condition.' AND batch_id=:batch_id';
		$criteria->params[':batch_id'] = $_REQUEST['id'];
	}
	$criteria->order ='first_name ASC';
	$posts=Students::model()->findAll($criteria);
	
	
	
	//$posts=Students::model()->findAll("batch_id=:x and is_active=:y and is_deleted=:z", array(':x'=>$_REQUEST['id'],':y'=>1,':z'=>0));
?>
	<?php
	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
	if(Yii::app()->user->year)
	{
		$year = Yii::app()->user->year;
	}
	else
	{
		$year = $current_academic_yr->config_value;
	}
	$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
	$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
	$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
	
	
	$template = '';
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
	{
		$template = $template.'{update}';
	}
	
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
	{
		$template = $template.'{delete}';
	}
	
	
	$insert_score = 0;
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
	{
		$insert_score = 1;
	}
	
	?>

	<?php 	
	if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
	{
	?>
		<div>
			<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
				<div class="y_bx_head" style="width:650px;">
				<?php 
					echo Yii::t('app','You are not viewing the current active year. ');
					if($is_insert->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
					{ 
						echo Yii::t('app','To enter the scores, enable Insert option in Previous Academic Year Settings.');
					}
					elseif($is_insert->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
					{
						echo Yii::t('app','To edit the scores, enable Edit option in Previous Academic Year Settings.');
					}
					elseif($is_insert->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
					{
						echo Yii::t('app','To delete the scores, enable Delete option in Previous Academic Year Settings.');
					}
					else
					{
						echo Yii::t('app','To manage the scores, enable the required options in Previous Academic Year Settings.');	
					}
				?>
				</div>
				<div class="y_bx_list" style="width:650px;">
					<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
				</div>
			</div>
		</div><br/>
	<?php
	}
	?>


    <div class="formCon">
        <div class="attnd-tab-inner-blk">
        <?php 
            if($posts!=NULL)
            {
            ?>
                
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'exam-scores-form',
                    'enableAjaxValidation'=>false,
                )); ?>
                <?php
                if(Yii::app()->user->hasFlash('success'))
                {
                ?>
                    <div class="infogreen_bx" style="margin:10px 0 10px 10px; width:575px;"><?php echo Yii::app()->user->getFlash('success');?></div>
                <?php
                }
                else if(Yii::app()->user->hasFlash('error'))
                {
                ?>
                    <div class="infored_bx" style="margin:10px 0 10px 10px; width:575px;"><?php echo Yii::app()->user->getFlash('error');?></div>
                <?php
                }
                ?>
                
                <?php  echo $form->hiddenField($model,'exam_id',array('value'=>$_REQUEST['examid'])); ?>
                <h3><?php 
				$exm = Exams::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
				if($exm!=NULL)
				{
					$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
					$egup = ElectiveGroups::model()->findByAttributes(array('id'=>$sub->elective_group_id));
					if($egup!=NULL)
						echo "Elective Group : ".$sub->name;
				}
				?>
</h3>

                <p><?php echo Yii::t('app','Enter Exam Scores here:');?></p>
                <div class="exam-table exam-table-line">
                    <table border="0" cellpadding="0" cellspacing="0p" width="100%" class="">
                        <?php 
                        $i=1;
                        $j=0;
						$k=0;
						
                        foreach($posts as $posts_1)
                        { 
							$sub=NULL;
							$student_elective=NULL;
                            $checksub = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid'],'student_id'=>$posts_1->id));
                            $exm = Exams::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
							if($exm!=NULL)
							{
                            	$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
							}
							if($sub!=NULL)
							{
								
								$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$posts_1->id, 'elective_group_id'=>$sub->elective_group_id));
							}
							
                            if($checksub==NULL and (($sub->elective_group_id==0 and count($sub)!=0) or ($sub->elective_group_id!=0 and count($student_elective)!=0)))
                            {
                                if($j==0)
                                {
                                ?>
                                
                                    <tr>
                                     <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                    	<th width="350"><?php echo Yii::t('app','Roll No');?></th>
                                    <?php } ?>
                                        <th width="350"><?php echo Yii::t('app','Student Name');?></th>
                                        <th width="150"><?php echo Yii::t('app','Subject');?></th>
                                         <?php   
											$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id));
											
											
											$r=1;
											if($subject_cps !=NULL){
												foreach($subject_cps as $subject_cp){ 
												?>
												<th width="300"><?php echo ucfirst($subject_cp->split_name);?></th> 
												<?php
												}
												?>
                                                <th width="50"><?php echo Yii::t('app','Total Marks');?></th>
                                                <?php
											}else{?> 
                                             <th width="50"><?php echo Yii::t('app','Marks');?></th>
                                             <?php
											
                                             }
                                                 ?>
                                        <th width="50"><?php echo Yii::t('app','Remarks');?></th>
                                    </tr>
                                    <?php 
                                    $j++;
                                }
								
								if($student_elective==NULL and $sub->elective_group_id==0){
									$flag=0;
                                ?>
                                
                                    <tr>
                                     <?php if(Configurations::model()->rollnoSettingsMode() != 2){ 
										 $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$posts_1->id, 'batch_id'=>$posts_1->batch_id, 'status'=>1));?>
                                    	 <td align="center"><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
                                                             echo $batch_student->roll_no;
                                                        }
                                                        else{
                                                            echo '-';
                                                        }
                                                        ?><br />
                                    	</td>
                                    <?php } ?>
                                    <td>
                                        <?php echo $posts_1->studentFullName("forStudentProfile"); ?><br />
                                    </td>
                                    <td>
                                    
                                        <?php 
										echo ucfirst($sub->name);
                                        if($sub->elective_group_id!=0)
                                        {
                                            /*$studentelctive = StudentElectives::model()->findByAttributes(array('student_id'=>$posts_1->id));
                                            if($studentelctive==NULL) 
                                            {*/
                                            ?>
                                                <?php /*?><?php echo '<i><span style="color:#E26214;">'.Yii::t('app','Elective not assigned').'</span></i>  '.CHtml::link(Yii::t('app','Add now'),array('/courses/batches/elective','id'=>$_REQUEST['id'])); ?><?php */?>
                                            <?php
                                           // }
										   $flag=1;
                                        }?>
                                        <?php echo $form->hiddenField($model,'student_id['.$k.']',array('value'=>$posts_1->id,'id'=>$posts_1->id)); ?>
                                    </td>
                                    <?php   
									if($subject_cps !=NULL){?>
                                        <td>
                                            <?php 
                                            if($insert_score == 1 and $flag==0)
                                            {
                                                echo $form->textField($model,'sub_category1['.$k.']',array('maxlength'=>3,'class'=>'mark1'));
                                            }
                                            else
                                            {
                                                echo $form->textField($model,'sub_category1['.$k.']',array('maxlength'=>3,'class'=>'disabled_field','disabled'=>'disabled','class'=>'mark1'));
                                            }
                                            ?>
                                        </td>  
                                        <td>
                                            <?php 
                                            if($insert_score == 1 and $flag==0)
                                            {
                                                echo $form->textField($model,'sub_category2['.$k.']',array('maxlength'=>3,'class'=>'mark2'));
                                            }
                                            else
                                            {
                                                echo $form->textField($model,'sub_category2['.$k.']',array('maxlength'=>3,'class'=>'disabled_field','disabled'=>'disabled','class'=>'mark2'));
                                            }
                                            ?>
                                       </td>
                                        <?php
											}else{
												?><td style="display:none"><?php 
													echo $form->textField($model,'sub_category1['.$k.']',array('maxlength'=>3,'class'=>'mark1','style'=>'display:none')); 
													?></td><td style="display:none"><?php
													echo $form->textField($model,'sub_category2['.$k.']',array('maxlength'=>3,'class'=>'mark2','style'=>'display:none')); ?></td><?php
										    	}?> 
                                         
                                    <td>
                                        <?php 
									if($subject_cps !=NULL){
										if($insert_score == 1 and $flag==0)
										{
											echo $form->textField($model,'marks['.$k.']',array('maxlength'=>3,'class'=>'total','readonly'=>true));
										}
										else
										{
											echo $form->textField($model,'marks['.$k.']',array('maxlength'=>3,'class'=>'disabled_field','disabled'=>'disabled','class'=>'total','readonly'=>true));
										}
									}else{
										if($insert_score == 1 and $flag==0)
										{
											echo $form->textField($model,'marks['.$k.']',array('maxlength'=>3,'class'=>'to_total'));
										}
										else
										{
											echo $form->textField($model,'marks['.$k.']',array('maxlength'=>3,'class'=>'disabled_field','disabled'=>'disabled','class'=>'to_total'));
										}
									}
										?>
                                    </td>                        
                                    <td>
										<?php 
										if($insert_score == 1 and $flag==0)
										{
											echo $form->textField($model,'remarks['.$k.']',array('maxlength'=>255));
										}
										else
										{
											echo $form->textField($model,'remarks['.$k.']',array('maxlength'=>255,'class'=>'disabled_field','disabled'=>'disabled'));
										}
										?>
									</td>
                                </tr>	
                                
                                <?php echo $form->hiddenField($model,'grading_level_id'); ?>
                                <?php //echo $form->hiddenField($model,'is_failed'); ?>
                                
                                
                                <?php 
                                echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
                                echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d')));
								} // subject mark form ends here
								else{ 
									$flag=0;
									//if($student_elective->elective_group_id==$sub->elective_group_id){
                                ?>
                                
                                        <?php 
                                        if($sub->elective_group_id!=0)
                                        {
                                            $studentelctive = StudentElectives::model()->findByAttributes(array('elective_group_id'=>$sub->elective_group_id,'student_id'=>$posts_1->id,'elective_group_id'=>$sub->elective_group_id));
											$electiveid = Electives::model()->findByAttributes(array('id'=>$studentelctive->elective_id));
										?>
										<?php
                                            if($studentelctive!=NULL) 
                                            {
											
                                        ?>
                                        <tr>
										<?php if(Configurations::model()->rollnoSettingsMode() != 2){ 
										 $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$posts_1->id, 'batch_id'=>$posts_1->batch_id, 'status'=>1));?>
                                    	 <td align="center"><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
                                                             echo $batch_student->roll_no;
                                                        }
                                                        else{
                                                            echo '-';
                                                        }
                                                        ?><br />
                                    	</td>
                                    <?php } ?>
                                            <td>                       
                                                <?php echo $posts_1->first_name.' '.$posts_1->middle_name.' '.$posts_1->last_name;?>
                                            </td>
                                            <td>
                                        	<?php
                                                if($electiveid!=NULL)
												{
													echo ucfirst($electiveid->name);
													
												}
											?>
                                      <?php echo $form->hiddenField($model,'student_id['.$k.']',array('value'=>$posts_1->id,'id'=>$posts_1->id)); ?>
                                    </td>
                                    <?php   
									if($subject_cps !=NULL and $sub->split_subject){?>
                                        <td>
                                            <?php 
                                            if($insert_score == 1 and $flag==0)
                                            {
                                                echo $form->textField($model,'sub_category1['.$k.']',array('maxlength'=>3,'class'=>'mark1'));
                                            }
                                            else
                                            {
                                                echo $form->textField($model,'sub_category1['.$k.']',array('maxlength'=>3,'class'=>'disabled_field','disabled'=>'disabled','class'=>'mark1'));
                                            }
                                            ?>
                                        </td>  
                                        <td>
                                            <?php 
                                            if($insert_score == 1 and $flag==0)
                                            {
                                                echo $form->textField($model,'sub_category2['.$k.']',array('maxlength'=>3,'class'=>'mark2'));
                                            }
                                            else
                                            {
                                                echo $form->textField($model,'sub_category2['.$k.']',array('maxlength'=>3,'class'=>'disabled_field','disabled'=>'disabled','class'=>'mark2'));
                                            }
                                            ?>
                                       </td>
                                        <?php
											}else{
												?><td style="display:none"><?php 
													echo $form->textField($model,'sub_category1['.$k.']',array('maxlength'=>3,'class'=>'mark1','style'=>'display:none')); 
													?></td><td style="display:none"><?php
													echo $form->textField($model,'sub_category2['.$k.']',array('maxlength'=>3,'class'=>'mark2','style'=>'display:none')); ?></td><?php
										    	}?> 
                                         
                                    <td>
                                        <?php 
									if($subject_cps !=NULL){
										if($insert_score == 1 and $flag==0)
										{
											echo $form->textField($model,'marks['.$k.']',array('maxlength'=>3,'class'=>'total','readonly'=>true));
										}
										else
										{
											echo $form->textField($model,'marks['.$k.']',array('maxlength'=>3,'class'=>'disabled_field','disabled'=>'disabled','class'=>'total','readonly'=>true));
										}
									}else{
										if($insert_score == 1 and $flag==0)
										{
											echo $form->textField($model,'marks['.$k.']',array('maxlength'=>3,'class'=>'to_total'));
										}
										else
										{
											echo $form->textField($model,'marks['.$k.']',array('maxlength'=>3,'class'=>'disabled_field','disabled'=>'disabled','class'=>'to_total'));
										}
									}
										?>
                                    </td>                 
                                    <td>
										<?php 
										if($insert_score == 1 and $flag==0)
										{
											echo $form->textField($model,'remarks['.$k.']',array('maxlength'=>255));
										}
										else
										{
											echo $form->textField($model,'remarks['.$k.']',array('maxlength'=>255,'class'=>'disabled_field','disabled'=>'disabled'));
										}
										?>
									</td>
                                </tr>
                                	
                                
                                <?php echo $form->hiddenField($model,'grading_level_id'); ?>
                                <?php //echo $form->hiddenField($model,'is_failed'); ?>
                                
                                
                                <?php 
                                echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
                                echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d')));
									
										}
									}
								}
							
								
                               
									
							$i++;
							$k++;} 
                        }// END foreach($posts as $posts_1)
                        ?>
                    </table>
                    
                    <br />
                    <?php 
					
                    if($i==1)
                    {
						
                    
                        echo '<div class="notifications nt_green">'.'<i>'.Yii::t('app','Exam Score Entered For All Students').'</i></div>'; 
                        $allscores = ExamScores::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['examid']));
                        $sum=0;
                        foreach($allscores as $allscores1)
                        {
                            $sum=$sum+$allscores1->marks;
                        }
                        $avg=$sum/count($allscores);
						 $avg=substr($avg,0,5);
                        echo '<div class="notifications nt_green">'.Yii::t('app','Class Average').' = '.$avg.'</div>';
                        echo '<div style="padding-left:10px;">';
                        echo CHtml::link(Yii::t('app', 'Generate PDF'), array('examScores/pdf','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']),array('target'=>"_blank",'class'=>'pdf_but'));
                        
                        echo '</div>';
                    }
                    ?>
                </div> <!-- END div class="tableinnerlist" -->
            
                <div>
                    <?php 
					if($insert_score == 1)
					{
						if($i!=1)
						{ 
							echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('class'=>'formbut')); 
						}
					}?>
                </div>
            
            <?php $this->endWidget(); ?>
            <?php 
            }// END if($posts!=NULL)
            else
            {
                echo '<i>'.Yii::t('app','No Students In This').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>';
            }
            ?>
         </div> <!-- END div class="formConInner" -->
    </div> <!-- END div class="formCon" -->
    
    
    <?php
	
	$checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid']));
	if($checkscores!=NULL)
	{
	?>
        
        
        <?php 
		$model1=new ExamScores('search');
        $model1->unsetAttributes();  // clear any default values
        if(isset($_GET['examid']))
        	$model1->exam_id=$_GET['examid'];
        ?>
        <h3> <?php echo Yii::t('app','Scores');?></h3>
       
        <?php
        if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
		{
		?>
        <div style="position:relative">    
            <div class="edit_bttns" style="width:250px; top:-10px; right:-101px;">
                <ul>
                    <li>
                    <?php echo CHtml::link('<span>'.Yii::t('app','Clear All Scores').'</span>', "#", array('submit'=>array('examScores/deleteall','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']), 'confirm'=>Yii::t('app','Are you sure you want to delete all scores ?.'), 'csrf'=>true,'class'=>'addbttn last'));?>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <br /><br />
        <?php
		}
		?>
        
        
        <?php
		
		
	   $exm = Exams::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
	   $examgroups = ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id));  
		if($exm!=NULL)
		{
			$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
		}
		$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id)); 
			
        if($examgroups->exam_type =='Marks') // Marks Only
        {
           $checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid']));
            if($checkscores!=NULL)
            {
				$new_array=array();
				
				if(Configurations::model()->rollnoSettingsMode() != 2){
					$new_array[]	= array(
                        'header'=>Yii::t('app','Roll No'),
                        'value'=>array($model,'studentRollno'),
                        'name'=> 'roll_no',
                        'sortable'=>true,
                    );
				}
				
				if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
					$new_array[]	= array(
                        'header'=>Yii::t('app','Student Name'),
                        'value'=>array($model,'studentFullName'),
                        'name'=> 'firstname',
                        'sortable'=>true,
                    );
				}
				if($subject_cps !=NULL){
					$t=1;
					foreach($subject_cps as $subject_cp){
						$new_array[]	= array(
							'header'=>ucfirst($subject_cp->split_name),
							'value'=>array($model,'category'.$t), 
						);
						$t++;
					}
				}
        		$new_array[]	= 'marks';
				$new_array[]	= array(
						'header'=>Yii::t('app','Remarks'),
						'value'=>array($model,'getRemarks'), 
					); 
				$new_array[]	= array(
						'header'=>Yii::t('app','Action'),
                        'class'=>'CButtonColumn',
						'deleteConfirmation'=>Yii::t('app','Are you sure you want to delete this score ?'),
                        'buttons' => array(
                                                                 
									'update' => array(
									'label' => Yii::t('app','Update'), // text label of the button
									
									'url'=>'Yii::app()->createUrl("/examination/examScores/update", array("sid"=>$data->id,"examid"=>$data->exam_id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
								  
									),
									
								),
								'template'=>$template,
								'afterDelete'=>'function(){window.location.reload();}',
								'visible'=>($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)),
                                                                
                    );
				
				
                $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'exam-scores-grid',
                'dataProvider'=>$model1->search(),
                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                'columns'=>$new_array,
            )); 
            }
        }
        else if($examgroups->exam_type =='Grades') // Grades Only
        {
            $checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid']));
            if($checkscores!=NULL)
            {
				$new_array=array();
				if(Configurations::model()->rollnoSettingsMode() != 2){
					$new_array[]	= array(
                        'header'=>Yii::t('app','Roll No'),
                        'value'=>array($model,'studentRollno'),
                        'name'=> 'roll_no',
                        'sortable'=>true,
                    );
				}
				
				if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
					$new_array[]	= array(
                        'header'=>Yii::t('app','Roll No'),
                        'value'=>array($model,'studentFullName'),
                        'name'=> 'firstname',
                        'sortable'=>true,
                    );
				}
				
				if($subject_cps !=NULL){
					$t=1;
					foreach($subject_cps as $subject_cp){
						$new_array[]	= array(
							'header'=>ucfirst($subject_cp->split_name),
							'value'=>array($model,'category'.$t), 
						);
						$t++;
					}
				}
				$new_array[]	= 'marks';
				$new_array[]	= array(
                        'header'=>Yii::t('app','Grades'),
                        'value'=>array($model,'getgradinglevel'),
                        'name'=> 'grading_level_id',
                    );
				
				$new_array[]	= array(
						'header'=>Yii::t('app','Remarks'),
						'value'=>array($model,'getRemarks'), 
					);
				$new_array[]	= array(
						'header'=>Yii::t('app','Action'),
                        'class'=>'CButtonColumn',
						'deleteConfirmation'=>Yii::t('app','Are you sure you want to delete this scores ?'),
                        'buttons' => array(
                                                                 
										'update' => array(
										'label' => Yii::t('app','Update'), // text label of the button
										
										'url'=>'Yii::app()->createUrl("/examination/examScores/update", array("sid"=>$data->id,"examid"=>$data->exam_id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
									  
										),
										
									),
						'template'=>$template,
						'afterDelete'=>'function(){window.location.reload();}',
						'visible'=>($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)),
													
                    );
				
                $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'exam-scores-grid',
                'dataProvider'=>$model1->search(),
                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                'columns'=>$new_array,
            )); 
            }
        
        }
        else  // Marks and Grades
        { 
            $checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid']));
            if($checkscores!=NULL)
            {
        		$new_array=array();
				
				if(Configurations::model()->rollnoSettingsMode() != 2){
					$new_array[]	= array(
                        'header'=>Yii::t('app','Roll No'),
                        'value'=>array($model,'studentRollno'),
                        'name'=> 'roll_no',
                        'sortable'=>true,
                    );
				}
				if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
					$new_array[]	= array(
                        'header'=>Yii::t('app','Student Name'),
                        'value'=>array($model,'studentFullName'),
                        'name'=> 'firstname',
                        'sortable'=>true,
                    );
				} 
				
				
				
				if($subject_cps !=NULL){
					$t=1;
					foreach($subject_cps as $subject_cp){
						$new_array[]	= array(
							'header'=>ucfirst($subject_cp->split_name),
							'value'=>array($model,'category'.$t), 
						);
						$t++;
					}
				}
				$new_array[]	= 'marks';
				$new_array[]	= array(
                        'header'=>Yii::t('app','Grades'),
                        'value'=>array($model,'getgradinglevel'),
                        'name'=> 'grading_level_id',
                    );
				
				$new_array[]	= array(
						'header'=>Yii::t('app','Remarks'),
						'value'=>array($model,'getRemarks'), 
					);
				$new_array[]	= array(
						'header'=>Yii::t('app','Action'),
                        'class'=>'CButtonColumn',
						'deleteConfirmation'=>Yii::t('app','Are you sure you want to delete this scores ?'),
                        'buttons' => array(
                                                                 
											'update' => array(
											'label' => Yii::t('app','Update'), // text label of the button
											
											'url'=>'Yii::app()->createUrl("/examination/examScores/update", array("sid"=>$data->id,"examid"=>$data->exam_id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
										  
											),
											
										),
						'template'=>$template,
						'afterDelete'=>'function(){window.location.reload();}',
						'visible'=>($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)),
                                                                
                    );
                $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'exam-scores-grid',
                'dataProvider'=>$model1->search(),
                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                'columns'=>$new_array,
            )); 
            }
        }
        
        echo '</div></div>';
        
        
	}
	else
	{
		echo '<div class="notifications nt_red">'.'<i>'.Yii::t('app','No Scores Updated').'</i></div>'; 
	}
	?>
       
<?php

} // END if REQUEST['id'] 
else
{
	echo '<div class="notifications nt_red">'.'<i>'.Yii::t('app','Nothing Found').'</i></div>'; 
}
?>
<script>
$('input[type="text"][name="ExamScores[marks][]"]').blur(function(e) {
    if(isNaN($(this).val())){
  $(this).val('');
 }
});

$(document).ready(function(){
 $('.mark1').change(function(e) {
		var mark_val	= $(this).closest('tr').find('input[class=mark2]').val();
		var total		= parseInt($(this).val())+parseInt(mark_val);
		if(!isNaN(total)){
			$(this).closest('tr').find('input[class=total]').val(total);
		}
	});
    
    $('.mark2').change(function(e) {
      var mark_val 		= $(this).closest('tr').find('input[class=mark1]').val();
		var total		= parseInt($(this).val())+parseInt(mark_val);
		if(!isNaN(total)){
			$(this).closest('tr').find('input[class=total]').val(total);
		}
    });
});
$('.to_total').change(function(e) {
	var mark_val 		= $(this).closest('tr').find('input[class=mark1]').val(0);
	var mark_val 		= $(this).closest('tr').find('input[class=mark2]').val(0);
    });


$("form#exam-scores-form").submit(function(e) {
	var textBox = "";
	$("form#exam-scores-form").find('input[type=text]').each(function(){
		textBox += $(this).val();
	});
	
	if (textBox == "") {
		$(".errorMessage").remove();
		alert("<?php echo Yii::t("app", "Fill the Exam Scores ");?>");
	}
	else
	{
		var that	= this;
		var data	= $(that).serialize();
		$(that).find("input[type='submit']").attr("disabled", true);
		$.ajax({
			url:'<?php echo Yii::app()->createUrl("/examination/examScores/create", array("id"=>$_REQUEST['id'], "examid"=>$_REQUEST['examid']));?>',
			type:'POST',
			data:data,
			dataType:"json",
			success: function(response){
				$(that).find("input[type='submit']").attr("disabled", false);
				$(".errorMessage").remove();
				if(response.status=="success"){
                                    
					window.location.reload();
				}
				else if(response.hasOwnProperty("errors")){
					var errors	= response.errors;
					$.each(errors, function(attribute, earray){
						$.each(earray, function(index, error){
							var error_div	= $("<div class='errorMessage' style='font-weight:100;' />");
							error_div.text(error);
							$('#' + attribute).closest("td").append(error_div);
						});										
					});				
				}
				else if(response.hasOwnProperty("message")){
					alert(response.message);
				}
				else{
					alert("<?php echo Yii::t("app", "Some problem found while saving datass !!");?>");
				}
			},
                            error:function(){
				$(that).find("input[type='submit']").attr("disabled", false);
				alert("<?php echo Yii::t("app", "Some problem found while saving data !!");?>");
			}
			
		});
	}
	return false;
});
</script>
	
	
	