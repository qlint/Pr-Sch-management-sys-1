<?php
$find = StudentAttentance::model()->findAll("date=:x AND student_id=:y", array(':x'=>$year.'-'.$month.'-'.$day,':y'=>$emp_id));
if(count($find)==0)
{
echo CHtml::ajaxLink(Yii::t('app','ll'),$this->createUrl('StudentAttentance/addnew'),array(
        'onclick'=>'$("#jobDialog'.$day.$emp_id.'").dialog("open"); return false;',
        'update'=>'#jobDialog123'.$day.$emp_id,'type' =>'GET','data'=>array('day' =>$day,'month'=>$month,'year'=>$year,'emp_id'=>$emp_id),
        ),array('id'=>'showJobDialog'.$day.$emp_id,'class'=>'at_abs'));
		//echo '<div id="jobDialog'.$day.$emp_id.'"></div>';
}
else
echo "<span class='abs'></span>";
		

?>