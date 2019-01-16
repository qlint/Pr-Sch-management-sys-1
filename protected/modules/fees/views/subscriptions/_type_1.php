<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$dateformat	= $settings->dateformat;
}
else
	$dateformat = 'dd-mm-yy';

$timeid	= time();

$subscription	= new FeeSubscriptions;
?>
<div class="white_bx">
<div class="triangle-up"></div>
<table width="45%">
    <tr>
        <td><label><?php echo $subscription->getAttributeLabel('due_date');?> <span class="required">*</span></label></td>
        <td>
            <?php
			echo CHtml::activeHiddenField($category, 'subscription_type', array('value'=>1));
			
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$subscription,
                'attribute'=>'due_date['.$timeid++.']',
                // additional javascript options for the date picker plugin
                'options'=>array(
                    'showAnim'=>'fold',
                    'dateFormat'=>$dateformat,
                    'changeMonth'=> true,
                    'changeYear'=>true,
                    'yearRange'=>'1900:'.(date('Y')+5)
                ),
                'htmlOptions'=>array(
                    'readonly'=>true
                ),
            ));
            ?>
        </td>
    </tr>
</table>
</div>