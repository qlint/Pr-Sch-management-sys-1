<?php
$this->breadcrumbs=array(
	Yii::t('app','Exam Scores')=>array('/examination'),
	Yii::t('app',$model->id)=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    
   <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
     <div class="cont_right formWrapper">
    <!--<div class="searchbx_area">
    <div class="searchbx_cntnt">
    	<ul>
        <li><a href="#"><img src="images/search_icon.png" width="46" height="43" /></a></li>
        <li><input class="textfieldcntnt"  name="" type="text" /></li>
        </ul>
    </div>
    
    </div>-->
    
    <h1><?php echo Yii::t('app','Exams');?></h1>
        
    <div class="edit_bttns">
    <ul>
    <?php /*?><li>
    <a class=" edit last" href="#">Edit</a>    </li><?php */?>
    </ul>
    </div>
    
    
    <div class="clear"></div>
    <div class="emp_right_contner">
    <div class="emp_tabwrapper">
     <?php $this->renderPartial('/default/tab');?>
        
    <div class="clear"></div>
    <div class="emp_cntntbx">

<?php echo $this->renderPartial('_form1', array('model'=>$model)); ?>

</div></div></div></div>
    </td>
  </tr>
</table>