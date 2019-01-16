<style type="text/css">
.max_mark_err{ color:#F00;}
.min_mark_err{ color:#F00;}
.time_table_dash table tr td, th{ word-break:normal;}
.grid-view table.items th a{ font-size:11px;}
.ui-datepicker-trigger{
	margin-top:5px;	
}
</style>

<?php
$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
if(Yii::app()->user->year){
	$year = Yii::app()->user->year;
}
else{
	$year = $current_academic_yr->config_value;
}
$is_insert 	= PreviousYearSettings::model()->findByAttributes(array('id'=>2));
$is_edit 	= PreviousYearSettings::model()->findByAttributes(array('id'=>3));
$is_delete 	= PreviousYearSettings::model()->findByAttributes(array('id'=>4));

$template = '';
if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0)){
	$template = $template.'{update}';
}

if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0)){
	$template .= (($template!="")?' ':'').'{delete}';
}

if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0)){
?>
    <div class="formCon">
        <div class="formConInner">
        <?php
            $exam = CommonExams::model()->findByPk($_REQUEST['id']);
            if($exam!=NULL){
                $exam_groups	= ExamGroups::model()->findAllByAttributes(array('common_exam_id'=>$exam->id));
                $exam->batches	= (count($exam_groups)>0)?CHtml::listData($exam_groups, 'batch_id', 'batch_id'):array();
                $criteria				= new CDbCriteria;
                $criteria->condition	= '`admin_id`<>:zero AND `is_edit`=:zero';
                $criteria->params		= array(':zero'=>0);
                $criteria->addInCondition('`batch_id`', $exam->batches);
                $criteria->group		= '`admin_id`';
                $criteria->distinct		= true;
                $common_pool_subjects	= Subjects::model()->findAll($criteria);
				
				$criteria				= new CDbCriteria;
				$criteria->condition	= '`admin_id`=:zero';
                $criteria->params		= array(':zero'=>0);
                $criteria->addInCondition('`batch_id`', $exam->batches);
                $batch_subjects			= Subjects::model()->findAll($criteria);
				
                if($common_pool_subjects!=NULL or $batch_subjects!=NULL){
					$all_exams_created	= true;
					?>
                    <h3><?php echo Yii::t('app','Enter exam related details here');?></h3>
                    <?php
                    $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'exams-form',
                        'enableAjaxValidation'=>false,
                    ));
                    ?>        
                        <div class="exam-table exam-table-line">
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <?php
                                    echo $form->labelEx($model,'max_mark', array('class'=>'aaaa'));		
                                    echo $form->textField($model,'max_mark',array('id'=>'max_mark', 'class'=>'text-btm','maxlength'=>3));
                                ?>
                                <div class="max_mark_err"></div>
                                <?php
                                    echo $form->labelEx($model,'min_mark');
                                    echo $form->textField($model,'min_mark',array('id'=>'min_mark', 'class'=>'text-btm','maxlength'=>3));
                                ?>
                                <div class="min_mark_err"></div>
                                <br /><br />                            
                                <tr>
                                    <th width="22%"><?php echo Yii::t('app','Subject name');?></th>
                                    <th width="13%"><?php echo Yii::t('app','Max Marks');?></th>
                                    <th width="13%"><?php echo Yii::t('app','Min Marks');?></th>
                                    <th width="20%"><?php echo Yii::t('app','Start Time');?></th>
                                    <th width="20%"><?php echo Yii::t('app','End Time');?></th>
                                </tr>
                            
                                <?php
                                    $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                    if($settings!=NULL){
                                        $date=$settings->dateformat;
                                    }
                                    else{
                                        $date = 'dd-mm-yy';	
                                    }
                                    
									$index	= 1;									
									?>
                                    <tr>
                                        <td colspan="5" align="center"><b><?php echo Yii::t('app', 'Common Pool Subjects');?> (<span id="common-pool-sub-count"></span>)</b></td>
                                    </tr>
                                    <?php
                                    $count=0;
                                    foreach($common_pool_subjects as $subject){
                                        $criteria				= new CDbCriteria;
                                        $criteria->condition	= '`batch_id`=:batch_id AND `common_exam_id`=:common_exam_id';
                                        $criteria->params		= array(':batch_id'=>$subject->batch_id, ':common_exam_id'=>$exam->id);
                                        $exam_group				= ExamGroups::model()->find($criteria);
                                        $exam_exists			= Exams::model()->findByAttributes(array('exam_group_id'=>$exam_group->id,'subject_id'=>$subject->id));
                                        if($exam_exists==NULL){
											$all_exams_created	= false;
                                            echo '<tr>';
                                            
                                            echo '<td>';
                                            echo $subject->name;
                                            echo $form->hiddenField($model,'exam_group_id['.$index.']',array('value'=>$exam_group->id));
                                            echo $form->hiddenField($model,'subject_id['.$index.']',array('value'=>$subject->id));
                                            echo '</td>';
                                            
                                            echo '<td>'.$form->textField($model,'maximum_marks['.$index.']',array('class'=>'max_mark','size'=>3,'maxlength'=>3)).'</td>';
                                            echo '<td>'.$form->textField($model,'minimum_marks['.$index.']',array('class'=>'min_mark','size'=>3,'maxlength'=>3)).'</td>';
                                            echo '<td>';
                                            
                                            $this->widget('application.extensions.timepicker.timepicker', array(
                                                'model' => $model,
                                                'options'=>array(
                                                    'dateFormat'=>$date,																															
                                                ),
                                                'name'=>'start_time',
                                                'tabularLevel' => "[".$index."]",
                                                'id'=>'Exams_start_time_'.$index
                                            ));
                                            
                                            echo '</td>';
                                            
                                            echo '<td>';
                                            $this->widget('application.extensions.timepicker.timepicker', array(
                                                'model' => $model,
                                                'options'=>array(
                                                    'dateFormat'=>$date,																															
                                                ),
                                                'name'=>'end_time',
                                                'tabularLevel' => "[".$index."]",
                                                'id'=>'Exams_end_time_'.$index
                                            ));
                                            
                                            echo '</td>';
                                            
                                            echo '</tr>';
                                            
                                            $index++;
                                            $count++;
                                        }
                                    }
                                    ?>
                                    <script type="application/javascript">
                                        $(document).ready(function(e) {
                                            $('#common-pool-sub-count').html(<?php echo $count;?>);
                                        });
                                    </script>
                                    <?php
									
									// batches - subjects
									foreach($exam->batches as $batch_id){
										$batch					= Batches::model()->findByPk($batch_id);
										if($batch!=NULL){
											$criteria				= new CDbCriteria;
											$criteria->condition	= '`admin_id`=:zero AND `batch_id`=:batch_id';
											$criteria->params		= array(':zero'=>0, ':batch_id'=>$batch_id);
											$batch_subjects	= Subjects::model()->findAll($criteria);
											?>
                                                <tr>
                                                    <td colspan="5" align="center">
                                                    	<b>
															<?php
                                                            	echo CommonExams::model()->getBatchName($batch->id).' - '.Yii::t('app', 'Subjects').' (<span id="batch-sub-count-'.$batch_id.'"></span>)';
															?>
                                                     	</b>
                                                   	</td>
                                                </tr>
											<?php
											$count	= 0;
											foreach($batch_subjects as $subject){												
												$criteria				= new CDbCriteria;
												$criteria->condition	= '`batch_id`=:batch_id AND `common_exam_id`=:common_exam_id';
												$criteria->params		= array(':batch_id'=>$subject->batch_id, ':common_exam_id'=>$exam->id);
												$exam_group				= ExamGroups::model()->find($criteria);
												$exam_exists			= Exams::model()->findByAttributes(array('exam_group_id'=>$exam_group->id,'subject_id'=>$subject->id));
												if($exam_exists==NULL){	
													$all_exams_created	= false;										
													echo '<tr>';
													
													echo '<td>';
													echo $subject->name;
													echo $form->hiddenField($model,'exam_group_id['.$index.']',array('value'=>$exam_group->id));
													echo $form->hiddenField($model,'subject_id['.$index.']',array('value'=>$subject->id));
													echo '</td>';
													
													echo '<td>'.$form->textField($model,'maximum_marks['.$index.']',array('class'=>'max_mark','size'=>3,'maxlength'=>3)).'</td>';
													echo '<td>'.$form->textField($model,'minimum_marks['.$index.']',array('class'=>'min_mark','size'=>3,'maxlength'=>3)).'</td>';
													echo '<td>';
													
													$this->widget('application.extensions.timepicker.timepicker', array(
														'model' => $model,
														'options'=>array(
															'dateFormat'=>$date,																															
														),
														'name'=>'start_time',
														'tabularLevel' => "[".$index."]",
														'id'=>'Exams_start_time_'.$index
													));
													
													echo '</td>';
													
													echo '<td>';
													$this->widget('application.extensions.timepicker.timepicker', array(
														'model' => $model,
														'options'=>array(
															'dateFormat'=>$date,																															
														),
														'name'=>'end_time',
														'tabularLevel' => "[".$index."]",
														'id'=>'Exams_end_time_'.$index
													));
													
													echo '</td>';
													
													echo '</tr>';
													
													$index++;
													$count++;
												}
											}
											?>
                                            <script type="application/javascript">
												$(document).ready(function(e) {
													$('#batch-sub-count-<?php echo $batch_id?>').html(<?php echo $count;?>);
												});
											</script>
                                            <?php
										}
									}
                                ?>
                            </table>
                            <br />
                            
                            <!--for eelctive subjects-->
                                                    
                            <?php
                                if($i==1){                        
                                    echo '<div class="notifications nt_green"><i>'.Yii::t('app','Exams Created For All Subjects').'</i></div>';                         
                                }
                            ?>
                        </div>
                        <br />
                        
                        <div align="left">
                            <?php if($i!=1)echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
                        </div>        
                    <?php $this->endWidget(); ?>
                    
                    <div class="exam-table exam-table-line" id="all_created_msg" style="display:none;">
                        <div class="notifications nt_green">
                            <i><?php echo Yii::t('app','Exams Created For All Subjects');?></i>
                        </div>
                    </div>
                    
					<script>
						<?php if($all_exams_created){?>
							$('form#exams-form').remove();
							$('#all_created_msg').show();
                        <?php }else{ ?>
							$('#all_created_msg').remove();
						<?php }?>
                    </script>
                    
					<?php
                } else{
                    echo '<i>'.Yii::t('app','No Subjects').'</i>';
                }
            } else{
                echo '<i>'.Yii::t('app','No Such Exam Scheduled').'</i>';
            }
        ?>
        </div>
    </div>
<?php
}
?>

<?php
Yii::app()->clientScript->registerScript(
	'myHideEffect',
	'$(".success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
	CClientScript::POS_READY
);
?>
<?php
/* Success Message */
if(Yii::app()->user->hasFlash('success')): 
?>
	<div class="success" style="background:#FFF; color:#C00; padding-left:200px; font-size:16px">
	<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
<?php endif;
 /* End Success Message */
?>

<?php
$exam_groups	= ExamGroups::model()->findAllByAttributes(array('common_exam_id'=>$_REQUEST['id']));
$exam_group_ids	= (count($exam_groups)>0)?CHtml::listData($exam_groups, 'id', 'id'):array();
$criteria		= new CDbCriteria;
$criteria->addInCondition('exam_group_id', $exam_group_ids);
$exams 			= Exams::model()->findAll($criteria);
if($exams!=NULL){
	?>
    <div >
        <div >
            <?php
                $model	= new Exams('search');
                $model->unsetAttributes();
                $model->exam_group_id	= $exam_group_ids;
            ?>
          	<h3><?php echo Yii::t('app','Scheduled Subjects');?></h3>
            
			<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'exams-grid',
					'dataProvider'=>$model->search(),
					'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
					'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',            
					'columns'=>array(            
						array(
							'name'=>'subject_id',
							'value'=>array($model,'subjectname')            
						),
						array(	
							'name'=>'start_time',
							'value'=>array($model,'starttime'),
							'filter'=>false
						),
						array(	
							'name'=>'end_time',
							'value'=>array($model,'endtime'),
							'filter'=>false,            
						),
						'maximum_marks',
						'minimum_marks',
						array(
							'header'=>Yii::t('app','Action'),
							'class'=>'CButtonColumn',
							'htmlOptions'=>array('style'=>''),
							'deleteConfirmation'=>Yii::t('app','Are you sure you want to delete this exam?'),
							'deleteButtonUrl'=>'Yii::app()->controller->createUrl("/examination/commonExams/deleteExam", array("id"=>$data->id))',
							'afterDelete'=>'function(link, success, data){window.location.reload();}',
							'buttons' => array(            
								'update' => array(
									'label' => Yii::t('app','Update'), // text label of the button            
									'url'=>'Yii::app()->createUrl("/examination/exams/update", array("sid"=>$data->id,"exam_group_id"=>$data->exam_group_id,"id"=>$data->batchId))', // a PHP expression for generating the URL of the button            
								),
							),
							'template'=>$template
						),
						array(
							'class' => 'CButtonColumn',
							'buttons' => array(            
							'add' => array(
								'label' => Yii::t('app','Exam Score'), // text label of the button            
								'url'=>'Yii::app()->createUrl("examination/examScores/create", array("examid"=>$data->id,"id"=>$data->batchId))', // a PHP expression for generating the URL of the button
							)
						),
						'template' => '{add}',
						'header'=>Yii::t('app','Manage'),
						'htmlOptions'=>array('style'=>'width:10%'),
						'headerHtmlOptions'=>array('style'=>'color:#FF6600')
					),
				),
            ));?>
    	</div>
    </div>
<?php
}
else{
	echo '<div class="notifications nt_red"><i>'.Yii::t('app','Nothing Scheduled').'</i></div>'; 
}
?>

<br />
<script type="application/javascript">
$(document).ready(function(e) {
		$('input[type="text"][name="Exams[maximum_marks][]"],input[type="text"][name="Exams[minimum_marks][]"],input[type="text"][name="ElectiveExams[maximum_marks][]"],input[type="text"][name="ElectiveExams[maximum_marks][]"]').blur(function(e) {
		if(isNaN($(this).val())){
			$(this).val('');
		}
	});
	
    $('#max_mark').blur(function(e) {
	   var val = $(this).val();
		if(!isNaN(val)){
			$(".max_mark_err").html("");
			$(".max_mark").val(val);
		}
		else{
			$(".max_mark_err").html("<?php echo Yii::t('app','Max Mark must be an integer'); ?>");
			return false;
		} 
	});
	
	$('#min_mark').blur(function(e) {
		var val = $(this).val();
		if(!isNaN(val)){
			$(".min_mark_err").html("");
			$(".min_mark").val(val);
		}
		else{
			$(".min_mark_err").html("<?php echo Yii::t('app','Min Mark must be an integer'); ?>");
			return false;
		}
	});
	
	$("form#exams-form").submit(function(e) {
		var textBox = "";
		$("form#exams-form").find('input[type=text]').each(function(){
			textBox += $(this).val();
		});
		
		if (textBox == "") {
			$(".errorMessage").remove();
			alert("<?php echo Yii::t("app", "Fill the details related to exam");?>");
		}
		else{	
			var that	= this;
			var data	= $(that).serialize();
			$(that).find("input[type='submit']").attr("disabled", true);
			$.ajax({
				url:'<?php echo Yii::app()->createUrl("/examination/commonExams/manage", array("id"=>$_REQUEST['id']));?>',
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
});
</script>