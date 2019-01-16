<table width="100%" border="0" cellspacing="0" cellpadding="0" id="elective_table"> 
<?php 

$i=0;

foreach($models as $model){ 

	$elective =  Electives::model()->findByAttributes(array('id'=>$model->subject_id,'is_deleted'=>0));
	
	  ?>
    <tr>
        <td width="30%"><?php echo CHtml::activeLabel($model,Yii::t('app',$elective->name));?><input type="hidden" name="elective_id[]" value="<?php echo $elective->id ?>"/></td>
        <td width="70%">
           
			<?php 
					$criteria 				= new CDbCriteria;
					$criteria->join 		= "JOIN `employee_elective_subjects` `ees` ON `ees`.employee_id = `t`.id";
					$criteria->condition 	= "`ees`.elective_id=:x";
					$criteria->params		=	array(':x'=>$elective->id);
                    $employee = Employees::model()->findAll($criteria);
					$data=CHtml::listData($employee,'id','concatened');
					echo CHtml::activeDropDownList($model,'employee_id[]',
					$data,
					array('encode'=>false,'prompt'=>Yii::t('app','Select Teacher'),'style'=>'width:200px;','id'=>'employee_id'.$i,'class'=>'elective-drop',
					'options' => array($model->employee_id=>array('selected'=>true),)));
            ?>
           
            
        </td>
	 </tr>
	<?php echo CHtml::hiddenField($model,'batch_id',array('value'=>$batch_id,'id'=>batch_id)); ?>
	<?php echo CHtml::hiddenField($model,'weekday_id',array('value'=>$weekday_id,'id'=>weekday_id)); ?>
	<?php echo CHtml::hiddenField($model,'class_timing_id',array('value'=>$class_timing_id,'id'=>class_timing_id)); ?>		
     
	
		<script>
			$('#class_room_id<?php echo $i;?>').change(function(){
				$('#duplicate_error').hide();
				$('#duplicate_error').html("");
				var myarray = new Array();
				var flag = 0;
				$(".classroom-drop").each(function(index,element){
					if(jQuery.inArray($(this).val(), myarray)) {
						myarray.push($(this).val());
					}
					else{
						flag = 1;
					}	
				});
				if(flag == 1){	
					$('#duplicate_error').show();
					$('#duplicate_error').html("<?php echo 'Same classroom selected'; ?>");
					
				}
			var batchId	= $("#batch_id").val();	
			var weekId   =$("#weekday_id").val();
			var timingId =$("#class_timing_id").val();
			var classId  =$("#class_room_id<?php echo $i;?>").val();
			if(batchId=='' || timingId=='' || classId=='' ||  weekId=='')
			  {
				$("#roomStatus<?php echo $i;?>").hide(); 
				$("#capacityStatus<?php echo $i;?>").hide();
				
			  }
			$.ajax({
				    
					type: "POST",
					url: <?php echo CJavaScript::encode(Yii::app()->createUrl('timetable/timetableEntries/checkclassavailability'))?>,
					data: {'batchId':batchId,'weekId':weekId,'timingId':timingId,'classId':classId},
					success: function(result){						
					var finalResult = result.split("+");	
					$("#roomStatus<?php echo $i;?>").show();
					$("#capacityStatus<?php echo $i;?>").show();
					$("#roomStatus<?php echo $i;?>").text(finalResult[0]);
					$("#capacityStatus<?php echo $i;?>").text(finalResult[1]);
					
					}
			
			});
			});
		</script>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
</tr>
<?php 
  $i= $i+1;
} 
?>
<tr>
	<td colspan="2"><div id="error_emp" style="color:#F00"></div></td>
</tr>
<tr>
	<td colspan="2"><div id="error_class" style="color:#F00"></div></td>
</tr>
</table>

