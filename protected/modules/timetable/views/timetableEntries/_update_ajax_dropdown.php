<style>
	.roomStatus{ 
	color: #F00;
	padding: 5px 118px;}
	.capacityStatus{ 
    color: #F00;
    padding: 5px 118px;
	text-align:justify}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="elective_table"> 
<?php
 $subject	= Subjects::model()->findByPk($model->subject_id);
    if($subject->split_subject){ 
    ?>
    <tr> 
        <td>
            <?php   
            echo CHtml::activeLabel($model,Yii::t('app','split_subject'));
            
            ?>
        </td>
        <td>
        <?php
            
			$subject_splits	= SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$subject_id));
			$k=1;
			$subject_split_arr	= array('0'=>'All');
			if(isset($model->split_subject) and  $model->split_subject!=0){
				$val	=	 $model->split_subject;
			}else{
				$val	=	0;
			}
			foreach($subject_splits as $splits){ 
				$subject_split_arr[$splits->id]	= $splits->split_name; 				
			}
			echo CHtml::radioButtonList('split_subject',$val,$subject_split_arr,array('labelOptions'=>array('style'=>'display:inline'),'separator'=>''));
        ?>
        </td>
    </tr>
    <?php
	}?>
<tr>
<td width="30%"><?php
	echo CHtml::activeLabel($model,Yii::t('app','employee_id')); ?></td>
<td width="70%">
<?php 

	$criteria 				= new CDbCriteria;
	$criteria->join 		= "JOIN `employees_subjects` `es` ON `es`.employee_id = `t`.id";
	$criteria->condition 	= "`es`.subject_id=:x";
	$criteria->params		=	array(':x'=>$model->subject_id);
	$employee = Employees::model()->findAll($criteria);
	
	$data=CHtml::listData($employee,'id','concatened');
	echo CHtml::activeDropDownList($model,'employee_id',
				$data,
				array('prompt'=>Yii::t('app','Select Teacher'),'style'=>'width:200px;','id'=>'employee_id0'
				));

?>
<div id="error_emp_sub" style="color:#F00"></div>
</td>
</tr>
<tr>
  	<td colspan="2">&nbsp;
    
    </td>
 </tr>
	<?php echo CHtml::hiddenField($model,'batch_id',array('value'=>$batch_id,'id'=>batch_id)); ?>
    
	<?php echo CHtml::hiddenField($model,'weekday_id',array('value'=>$weekday_id,'id'=>weekday_id)); ?>
	
	<?php echo CHtml::hiddenField($model,'class_timing_id',array('value'=>$class_timing_id,'id'=>class_timing_id)); ?>
<tr>
</tr>
	<tr>
		<td colspan="2"> 
		<div id="roomStatus" class="roomStatus"></div>
		<div id="capacityStatus" class="capacityStatus"></div>
		</td>
	</tr>
	
</table>
<script>
$('#class_room_id0').change(function(){
	$("#roomStatus").hide(); 
	$("#capacityStatus").hide();
   var batchId	= $("#batch_id").val();	
   var weekId   =$("#weekday_id").val();
   var timingId =$("#class_timing_id").val();
   var classId  =$("#class_room_id0").val();
    if(batchId=='' || timingId=='' || classId=='' ||  weekId=='')
		  {
			$("#roomStatus").hide(); 
			$("#capacityStatus").hide();
			
		  }
		$.ajax({
				type: "POST",
				url: <?php echo CJavaScript::encode(Yii::app()->createUrl('timetable/timetableEntries/checkclassavailability'))?>,
				data: {'batchId':batchId,'weekId':weekId,'timingId':timingId,'classId':classId},
				success: function(result){						
				var finalResult = result.split("+");	
				$("#roomStatus").show();
				$("#capacityStatus").show();
				$("#roomStatus").text(finalResult[0]);
				$("#capacityStatus").text(finalResult[1]);
				
				}
		
		});
});
</script>