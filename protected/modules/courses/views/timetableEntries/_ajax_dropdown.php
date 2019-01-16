<table width="100%" border="0" cellspacing="0" cellpadding="0" id="elective_table"> 
	<?php
	
    $model		= new TimetableEntries; 
    $subject	= Subjects::model()->findByPk($subject_id);
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
			foreach($subject_splits as $splits){
				if($k==1){
					$id	=	$splits->id;
				}$k++;
				$subject_split_arr[$splits->id]	= $splits->split_name; 				
			}
			echo CHtml::radioButtonList('split_subject',0,$subject_split_arr,array('labelOptions'=>array('style'=>'display:inline'),'separator'=>''));
        ?>
        </td>
    </tr>
    <?php
	}?>
    <tr>
        <td>
        <?php  echo CHtml::activeLabel($model,Yii::t('app','employee_id')); ?></td>
        <td>
        <?php 
        
            $criteria 				= new CDbCriteria;
            $criteria->join 		= "JOIN `employees_subjects` `es` ON `es`.employee_id = `t`.id";
            $criteria->condition 	= "`es`.subject_id=:x";
            $criteria->params		=	array(':x'=>$subject_id);
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
</table>