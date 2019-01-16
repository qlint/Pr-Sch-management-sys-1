<div align="left" id="othleft-sidebar">
<!--<div class="lsearch_bar">
             	<input type="text" value="Search" class="lsearch_bar_left" name="">
                <input type="button" class="sbut" name="">
                <div class="clear"></div>
  </div>-->
<h1><?php echo Yii::t('app','Attendance Register'); ?></h1>
 <ul>
 <li><?php echo CHtml::link(Yii::t('app','Teacher Register').'<span>'.Yii::t('app','Teacher Attendance Register').'</span>',array('#'),array('class'=>'sbook_ico'));
?>
</li>
 <li><?php echo CHtml::link(Yii::t('app','Student Register').'<span>'.Yii::t('app','Student Attendance Register').'</span>',array('#'),array('class'=>'lbook_ico'));
?>
</li>
<h1><?php echo Yii::t('app','Attendance Reports'); ?></h1>
 <li>
<?php echo CHtml::link(Yii::t('app','Teacher Attendance').'<span>'.Yii::t('app','Teacher Attendance Report').'</span>',array('#'),array('class'=>'abook_ico'));
?>
<?php echo CHtml::link(Yii::t('app','Student Attendance').'<span>'.Yii::t('app','Student Attendance Report').'</span>',array('#'),array('class'=>'abook_ico'));
?>
</li>
 <h1><?php echo Yii::t('app','Teacher Attendance Settings'); ?></h1>
 <li>
<?php echo CHtml::link(Yii::t('app','Add Leave Type').'<span>'.Yii::t('app','Manage Teacher Leave Type').'</span>',array('#'),array('class'=>'abook_ico'));
?>
</li>

</ul>

</div>