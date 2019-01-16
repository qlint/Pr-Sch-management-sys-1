<?php
$this->breadcrumbs=array(
	Yii::t('app','Rooms')=>array('/hostel'),
	Yii::t('app','Manage'),
);
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
)); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/settings/hostel_left');?>
        </td>
        <td valign="top">
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Manage Room Details');?></h1>
                <div class="edit_bttns" style="top:20px; right:20px;">
    <ul>
    <li> <?php echo CHtml::link('<span>'.Yii::t('app','Enter Room Details').'</span>', array('/hostel/Floordetails/create'),array('class'=>'addbttn last ')); ?></li>
    </ul>
    </div>
     <?php $hst=HostelDetails::model()->findAll('is_deleted=:x',array(':x'=>'0'));
	
		?>
        <div class="pdtab_Con">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr class="pdtab-h">
        	<td align="center"><?php echo Yii::t('app','Name');?></td>
        	<td align="center"><?php echo Yii::t('app','Address');?></td>
           <td align="center"><?php echo Yii::t('app','Action');?></td>
        </tr>
        <?php
		if($hst!=NULL)
		{
		foreach($hst as $hostel)
		{
			
			
			?>
                <tr>
                	
                    <td align="center"><?php echo $hostel->hostel_name;?></td>
                    <td align="center"><?php echo $hostel->address ;?></td>
                   
                    <td align="center"><?php echo CHtml::link(Yii::t('app','Edit'),array('/hostel/Hosteldetails/update','id'=>$hostel->id)).' | '.CHtml::link(Yii::t('app','Delete'),array('/hostel/Hosteldetails/deleteall','id'=>$hostel->id),array('confirm'=>Yii::t('app','Are you sure?')));?></td>
                </tr>
                <?php
			
			
		}
	}
	else
	{
		 echo '<tr><td align="center" colspan="3"><strong>'.Yii::t('app','No data available!').'</strong></td></tr>';
	}
	?>
    </table>
        </div>
                </div>
                </td>
                </tr>
                </table>
                <?php $this->endWidget(); ?>