<tr class="extra_fields">
	<td>
    	<div style="padding-top:15px;">
    		<label class="required" for="FileCategory_category"><?php echo Yii::t('app','Department');?></label>
        </div>
    </td>
    <td>
    	<div style="padding-top:15px;">
			<?php 
				$data1 = CHtml::listData(EmployeeDepartments::model()->findAll(array('order'=>'name DESC')),'id','name');
				echo CHtml::dropDownList('Compare[employee_department_id]', '', $data1, array('prompt'=>Yii::t('app','Select department')));
			?>
        </div>
    </td>
</tr>
<tr class="extra_fields">
	<td>
    	<div style="padding-top:15px;">
    		<label class="required" for="FileCategory_category"><?php echo Yii::t('app','Category');?></label>
        </div>
    </td>
    <td>
    	<div style="padding-top:15px;">
			<?php
				$data2 = CHtml::listData(EmployeeCategories::model()->findAll(array('order'=>'name DESC')),'id','name');
				echo CHtml::dropDownList('Compare[employee_category_id]', '', $data2, array('prompt'=>Yii::t('app','Select category')));
			?>
        </div>
    </td>
</tr>

<script>
$('select#course_id').change(function(e) {
	var that	= this;
    $.ajax({
		url:"<?php echo Yii::app()->createUrl('/export/default/loadbatches');?>",
		type:"POST",
		data:{course_id:$(that).val()},
		success: function(response){
			$("#batch_id").html(response);
		},
	});
});
</script>