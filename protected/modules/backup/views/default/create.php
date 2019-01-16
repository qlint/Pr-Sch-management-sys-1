<?php
$this->breadcrumbs=array(
	   Yii::t('app','Settings')=>array('/configurations'),
		Yii::t('app','Backup'),
);
$trans1	= Yii::t('app','View Site');
$trans2 = Yii::t('app','Restore Successfull');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('left_side'); ?>
        </td>
        <td valign="top">
             <div class="cont_right formWrapper">
            <h1> <?php echo Yii::t('app','Manage Database Backups'); ?></h1>
             <!-- END div class="edit_bttns" -->
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li> <?php echo CHtml::link('<span>'.Yii::t('app','List Backups').'</span>', array('/backup/default/index'),array('class'=>'a_tag-btn')); ?></li>                                   
</ul>
</div> 
</div>
            <div class="errorMessage-bg" style="width:650px; height:27px;padding:25px; text-align:center;"><?php echo Yii::t('app','Folder "protected/_backup" need write permission to perform this action ');?></div>

        </td>
    </tr>
</table>
