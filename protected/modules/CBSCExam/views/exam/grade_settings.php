<?php
$this->breadcrumbs=array(
	Yii::t('app','Exam Grade Settings')=>array('/examination'),
	Yii::t('app','Details'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
   <?php $this->renderPartial('examination.views.default.left_side');?>    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
    
    <h1 style="margin-top:.67em;"><?php echo Yii::t('app','CBSE Exam Settings');?> <br /></h1>    
    
<?php
if(isset($model))
{
?>
 
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php 
   
        echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('settings','id'=>$model->id), array('class'=>'a_tag-btn')); 
    
        ?></li>                                    
</ul>
</div> 

</div>
     
    <div class="emp_right_contner">    
    <div class="table_listbx">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  

  
  <tr class="listbxtop_hdng">
    <td><?php echo Yii::t('app','Weightage Settings');?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="listbx_subhdng"><?php echo Yii::t('app','FA 1 Weightage');?></td>
    <td class="subhdng_nrmal"><?php echo $model->fa1_weightage."%"; ?></td>
    <td class="listbx_subhdng"><?php echo Yii::t('app','FA 2 Weightage');?></td>
    <td class="subhdng_nrmal"><?php echo $model->fa2_weightage."%"; ?></td>
  </tr>
  <tr>
    <td class="listbx_subhdng"><?php echo Yii::t('app','SA 1 Weightage');?></td>
    <td class="subhdng_nrmal"><?php echo $model->sa1_weightage."%"; ?></td>
    <td class="listbx_subhdng"><?php echo Yii::t('app','SA 2 Weightage');?></td>
    <td class="subhdng_nrmal"><?php echo $model->sa2_weightage."%"; ?></td>
  </tr>
  </table>
    </div>
    </div>
<?php }
else
{
    echo "<center><span style='color:red'>".Yii::t("app", "No Result Found")."</span></center>";
}
?>
    </div>
   
    </td>
  </tr>
</table>