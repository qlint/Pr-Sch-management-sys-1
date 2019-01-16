<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Devices')=>array('/transport/devices'),
	Yii::t('app','Assign'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
            	<h1><?php echo Yii::t('app','Update Device Information');?></h1>
                <div class="edit_bttns" style="top:20px; right:20px;">
                    <ul>
                    	<li>
                        	<?php echo CHtml::link("<span>".Yii::t("app", "Devices")."</span>", array('/transport/devices'), array('class'=>'addbttn last'));?>
                       	</li>
                    </ul>
               	</div>
                <?php $this->renderPartial('_form', array('model'=>$model));?>
            </div>
        </td>
    </tr>
</table>