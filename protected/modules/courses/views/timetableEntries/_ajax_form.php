<table width="100%" border="0" cellspacing="0" cellpadding="0" id="elective_table"> 
<input type="hidden" name="batch_id" value="<?php echo $_REQUEST['id'] ?>"/>
<?php

$i=0;
foreach($electives as $elective){ 
    
    ?>
    <input type="hidden" name="elective_id[]" value="<?php echo $elective->id ?>"/>
    <tr>
        <td width="30%"><label><?php echo $elective->name; ?></label></td>
        <td width="70%">
            
			<?php 
					$criteria 				= new CDbCriteria;
					$criteria->join 		= "JOIN `employee_elective_subjects` `ees` ON `ees`.employee_id = `t`.id";
					$criteria->condition 	= "`ees`.elective_id=:x";
					$criteria->params		=	array(':x'=>$elective->id);
                    $employee = Employees::model()->findAll($criteria);
					$data=CHtml::listData($employee,'id','concatened');
					echo CHtml::dropDownList('employee_id[]','',
									$data,
									array('id'=>'employee_id'.$i,'prompt'=>Yii::t('app','Select Teacher'),'class'=>'elective-drop','style'=>'width:200px;'
									));
						
                   
            $i= $i+1;
            ?> 
            
        </td>
    </tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
<?php } ?>
<tr>
	<td colspan="2"><div id="error_emp" style="color:#F00"></div></td>
</tr>
</table>

