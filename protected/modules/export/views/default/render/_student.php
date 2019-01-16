<tr class="extra_fields">
	<td>
    	<div style="padding-top:15px;">
    		<label class="required" for="FileCategory_category"><?php echo Yii::t('app','Course');?></label>
        </div>
    </td>
    <td>
    	<div style="padding-top:15px;">
			<?php
                if(Yii::app()->user->year)
                    $year = Yii::app()->user->year;
                else{
                    $current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
                    $year 					= $current_academic_yr->config_value;
                }
                    
                $criteria	= new CDbCriteria;
                $criteria->compare('is_deleted', 0);
                $criteria->compare('academic_yr_id', $year);
                $courses	= Courses::model()->findAll($criteria);
                $courses	= CHtml::listData($courses, 'id', 'course_name');
			
                echo CHtml::dropDownList(
                    'Compare[course_id]',
                    '',
                    $courses,
                    array(
                        'prompt'=>Yii::t('app','Select course'),
						'encode'=>false,
                        'id'=>'course_id',
                        'ajax' => array(
                            'type'	=> 'POST', 
                            'url'	=> Yii::app()->createUrl('/export/default/batches'),
                            'update'=> '#batch',
							'data'=>'js:{course_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
                        )
                    )
                );
            ?>
        </div>
    </td>
</tr>
<tr class="extra_fields">
	<td>
    	<div style="padding-top:15px;">
    		<label class="required" for="FileCategory_category"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></label>
        </div>
    </td>
    <td>
    	<div style="padding-top:15px;">
			<?php
                echo CHtml::dropDownList('Compare[batch_id]', '', array(), array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), 'id'=>'batch_id'));
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
		data:{course_id:$(that).val() ,"<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
		success: function(response){
			$("#batch_id").html(response);
		},
	});
});
</script>