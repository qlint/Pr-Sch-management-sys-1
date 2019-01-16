<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('/hr'),
	Yii::t('app','Leave Requests')=>array('/hr/leaves/index'),
	Yii::t('app','Create'),
);
$leave_types	= LeaveTypes::model()->findAllByAttributes(array('is_deleted'=>0));
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    	<?php $this->renderPartial('/default/leftside');?>
    </td>
    <td valign="top">
        <div class="cont_right formWrapper">
            <h1><?php echo Yii::t('app','Request Leave');?></h1>
         
          <div class="rqst-leave-count">
            	<ul>
<?php foreach($leave_types as $leave_type){
		$leave_taken	=  LeaveRequests::model()->findAllByAttributes(array('leave_type_id'=>$leave_type->id, 'requested_by'=>Yii::app()->user->id, 'status'=>1)); 
	  	$leave_count  	=  count($leave_taken);
	  	$remaining    	=  ($leave_type->count)-($leave_count);?>    
                	<li>
                    	<div class="rqst-blok">
                        	<p><?php echo ucfirst($leave_type->type); ?></p>
                            <h2><?php if($remaining>=0){
									echo $remaining;
								} 
								else{
									 echo '0';
								}?></h2>
                        </div>
                    </li>
<?php } ?>  
                </ul>
            </div>
         
            <div class="formCon">
					<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                    <div class="formConInner"></div>
            </div>
        </div>
    </td>
  </tr>
</table>
