<table width="100%" border="0" cellspacing="0" cellpadding="0" id="elective_table"> 
<?php 
$i=0;
foreach($electives as $elective){ ?>
    <tr>
        <td><?php echo $elective->name; ?><input type="hidden" name="elective_id[]" value="<?php echo $elective->id ?>"/></td>
        <td>
            <ul>
			<?php 
					$criteria 				= new CDbCriteria;
					$criteria->join 		= "JOIN `employee_elective_subjects` `ees` ON `ees`.employee_id = `t`.id";
					$criteria->condition 	= "`ees`.elective_id=:x";
					$criteria->params		=	array(':x'=>$elective->id);
                    $employee = Employees::model()->findAll($criteria);
					$data=CHtml::listData($employee,'id','concatened');
					echo CHtml::dropDownList('employee_id[]','',
									$data,
									array('id'=>'employee_id'.$i,'prompt'=>Yii::t('app','Select Teacher'), 'class'=>'elective-drop', 'style'=>'width:150px;'
									));
						
                  $i= $i+1; 
            
            ?>
            </ul>
            
        </td>
        
    </tr>
<?php } 
?>
<tr>
	<td colspan="2"><div id="error_emp" style="color:#F00"></div></td>
</tr>
</table>

