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
    width:42px !important;
}
.exam-table table th{
	padding:10px 6px;
}
</style>

    
<?php
if(isset($_REQUEST['id']))
{
	
	$criteria = new CDbCriteria;
	$criteria->condition = 'is_deleted=:is_deleted AND is_active=:is_active';
	$criteria->params[':is_deleted'] = 0;
	$criteria->params[':is_active'] = 1;
	
	
	$batch_students = BatchStudents::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'result_status'=>0));
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
	$criteria->order = "first_name ASC";
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
				$exm = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
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
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="">
                        <?php 
                        $i=1;
                        $j=0;
						$k=0;
						
                        foreach($posts as $posts_1)
                        { 
							$sub=NULL;
							$student_elective=NULL;
                            $checksub = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid'],'student_id'=>$posts_1->id));
                            $exm = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
							if($exm!=NULL)
							{
                            	$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
							}
							if($sub!=NULL)
							{
								
								$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$posts_1->id, 'elective_group_id'=>$sub->elective_group_id));
							}
							
                            if($checksub==NULL )
                            {
                                if($j==0)
                                {
                                ?>
                                
                                    <tr>
                                   
                                        <th  width="12%"><?php echo Yii::t('app','Student Name');?></th>
                                        <th width="9%"><?php echo Yii::t('app','Subject');?></th> 
                                        <th width="6%"><?php echo Yii::t('app','Written Exam');?></th> 
                                        <th width="8%"><?php echo Yii::t('app','Periodic Test');?></th>
                                        <th width="10%"><?php echo Yii::t('app','Note Book');?></th>
                                        <th width="11%"><?php echo Yii::t('app','Subject Enrichment');?></th> 
                                        <th width="3%"><?php echo Yii::t('app','Total');?></th>  
                                        <th  width="6%"><?php echo Yii::t('app','Remarks');?></th>
                                    </tr>
                                    <?php 
                                    $j++;
                                }  $flag=0; ?>									
									<tr>
									
                                        <td>
                                       		<?php echo $posts_1->studentFullName("forStudentProfile"); ?><br />
                                        </td>
                                        <td>                                        
											<?php  
                                            echo ucfirst($sub->name);
                                            if($sub->elective_group_id!=0)
                                            {
                                            	$flag=1;
                                            }?>
                                            <?php echo $form->hiddenField($model,'student_id['.$k.']',array('value'=>$posts_1->id,'id'=>$posts_1->id)); ?>
                                        </td> 
                                        
                                         <td> <?php  echo $form->textField($model,'written_exam['.$k.']',array('maxlength'=>4,'class'=>'m1'));?></td>
                                         <td> <?php  echo $form->textField($model,'periodic_test['.$k.']',array('maxlength'=>4,'class'=>'m2'));?></td>
                                         <td> <?php  echo $form->textField($model,'note_book['.$k.']',array('maxlength'=>4,'class'=>'m3'));?></td>
                                         <td> <?php  echo $form->textField($model,'subject_enrichment['.$k.']',array('maxlength'=>4,'class'=>'m4'));?></td>
                                         <td> <?php  echo $form->textField($model,'total['.$k.']',array('maxlength'=>4,'class'=>'total','readOnly'=>true));?></td>
                                         <td> <?php  echo $form->textField($model,'remarks['.$k.']');?></td>
									</tr>
									<?php 
									echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
									echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d')));
								 
							
								
                               
									
							$i++;
							$k++;
							} 
                        }// END foreach($posts as $posts_1)
                        ?>
                    </table>
                    
                    <br />
                    <?php 
					
                    if($i==1)
                    {
						
                    
                        echo '<div class="notifications nt_green">'.'<i>'.Yii::t('app','Exam Score Entered For All Students').'</i></div>'; 
                        $allscores = CbscExamScores17::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['examid']));
                        $sum=0;
                        foreach($allscores as $allscores1)
                        {
                            $sum=$sum+$allscores1->total;
                        }
                        $avg=$sum/count($allscores);
						 $avg=substr($avg,0,5);
                        echo '<div class="notifications nt_green">'.Yii::t('app','Class Average').' = '.$avg.'</div>';
                        echo '<div style="padding-left:10px;">';
                        echo CHtml::link(Yii::t('app', 'Generate PDF'), array('exam17/pdf','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']),array('target'=>"_blank",'class'=>'pdf_but'));
                        
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
	$checkscores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid']));
	if($checkscores!=NULL)
	{
	?>
        
        
        <?php 
		$model1=new CbscExamScores17('search');
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
                    <?php echo CHtml::link('<span>'.Yii::t('app','Clear All Scores').'</span>', "#", array('submit'=>array('exam17/deleteall','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']), 'confirm'=>Yii::t('app','Are you sure you want to delete all scores ?.'), 'csrf'=>true,'class'=>'addbttn last'));?>
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
	   $exm = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid'])); 
	   $examgroups = CbscExamGroup17::model()->findByAttributes(array('id'=>$exm->exam_group_id));  
		if($exm!=NULL)
		{
			$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
		}  
		$checkscores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$_REQUEST['examid']));
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
				$new_array[]	= 'written_exam'; 	
        		$new_array[]	= 'periodic_test';
				$new_array[]	= 'note_book'; 	
        		$new_array[]	= 'subject_enrichment';
				$new_array[]	= 'total';
				$new_array[]	= array(
						'header'=>Yii::t('app','Grade'),
						'value'=>array($model,'getGrade'), 
					);   
				$new_array[]	= array(
						'header'=>Yii::t('app','Remarks'),
						'value'=>array($model,'getRemarks'), 
					);
                                /*
					$new_array[]= array(
								'header'=>Yii::t('app','Status'),
								'value'=>'$data->is_failed == 1 ? Yii::t("app","Fail") : Yii::t("app","Pass")',
								'name'=> 'is_failed',
						);
                                 * */
                                 
				$new_array[]	= array(
						'header'=>Yii::t('app','Action'),
                        'class'=>'CButtonColumn',
						'deleteConfirmation'=>Yii::t('app','Are you sure you want to delete this score ?'),
                        'buttons' => array(
                                                                 
									'update' => array(
									'label' => Yii::t('app','Update'), // text label of the button
									
									'url'=>'Yii::app()->createUrl("/CBSCExam/exam17/examScoresUpdate", array("sid"=>$data->id,"examid"=>$data->exam_id,"id"=>$_REQUEST["id"]))', // a PHP expression for generating the URL of the button
								  
									),
									'delete' => array(
									'label' => Yii::t('app','Delete'), // text label of the button
									
									'url'=>'Yii::app()->createUrl("/CBSCExam/exam17/examScoresDelete", array("id"=>$data->id))', // a PHP expression for generating the URL of the button
								  
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
$('input[type="text"][name="CbscExamScores17[marks][]"]').blur(function(e) {
    if(isNaN($(this).val())){
  $(this).val('');
 }
}); 
$(document).ready(function(){	
$('.m1').change(function(e) {
	var m1	= $(this).closest('tr').find('input[class=m1]').val();
	var m2	= $(this).closest('tr').find('input[class=m2]').val();
	var m3	= $(this).closest('tr').find('input[class=m3]').val();
	var m4	= $(this).closest('tr').find('input[class=m4]').val();
	var total		= 0;
	if(m1!='')
		var total		= total+parseFloat(m1);
	if(m2!='')
		var total		= total+parseFloat(m2);
	if(m3!='')
		var total		= total+parseFloat(m3);
	if(m4!='')
		var total		= total+parseFloat(m4); 
	 	
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total.toFixed(1));
	}

});
$('.m2').change(function(e) {
	var m1	= $(this).closest('tr').find('input[class=m1]').val();
	var m2	= $(this).closest('tr').find('input[class=m2]').val();
	var m3	= $(this).closest('tr').find('input[class=m3]').val();
	var m4	= $(this).closest('tr').find('input[class=m4]').val();
	var total		= 0;
	if(m1!='')
		var total		= total+parseFloat(m1);
	if(m2!='')
		var total		= total+parseFloat(m2);
	if(m3!='')
		var total		= total+parseFloat(m3);
	if(m4!='')
		var total		= total+parseFloat(m4); 
	 	
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total.toFixed(1));
	}

}); 
$('.m3').change(function(e) {
	var m1	= $(this).closest('tr').find('input[class=m1]').val();
	var m2	= $(this).closest('tr').find('input[class=m2]').val();
	var m3	= $(this).closest('tr').find('input[class=m3]').val();
	var m4	= $(this).closest('tr').find('input[class=m4]').val();
	var total		= 0;
	if(m1!='')
		var total		= total+parseFloat(m1);
	if(m2!='')
		var total		= total+parseFloat(m2);
	if(m3!='')
		var total		= total+parseFloat(m3);
	if(m4!='')
		var total		= total+parseFloat(m4);  
	 	
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total.toFixed(1));
	}

});
$('.m4').change(function(e) {
	var m1	= $(this).closest('tr').find('input[class=m1]').val();
	var m2	= $(this).closest('tr').find('input[class=m2]').val();
	var m3	= $(this).closest('tr').find('input[class=m3]').val();
	var m4	= $(this).closest('tr').find('input[class=m4]').val();
	var total		= 0;
	if(m1!='')
		var total		= total+parseFloat(m1);
	if(m2!='')
		var total		= total+parseFloat(m2);
	if(m3!='')
		var total		= total+parseFloat(m3);
	if(m4!='')
		var total		= total+parseFloat(m4);  
	 	
	if(!isNaN(total) || total!="0.0" ){
		$(this).closest('tr').find('input[class=total]').val(total.toFixed(1));
	}
	if(total ==0){
		$(this).closest('tr').find('input[class=total]').val('');
	}

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
			url:'<?php echo Yii::app()->createUrl("/CBSCExam/exam17/examScores", array("id"=>$_REQUEST['id'], "examid"=>$_REQUEST['examid']));?>',
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
	
	
	