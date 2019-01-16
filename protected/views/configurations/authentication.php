<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/configurations'),
	Yii::t('app','Manage Authentication'),
);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
<div id="othleft-sidebar">
<?php $this->renderPartial('//configurations/left_side');?>
  </div>
 </td>
 <td valign="top">
<div class="cont_right formWrapper">  
<h1><?php echo Yii::t('app','Manage Authentication');?></h1>

<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'authentication-form',
//'enableAjaxValidation'=>true,
)); ?>
<?php

	Yii::app()->clientScript->registerScript(
	'myHideEffect',
	'$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
	CClientScript::POS_READY
	);
	?>
	<?php
	/* Success Message */
	if(Yii::app()->user->hasFlash('successMessage')): 
	?>
		<div class="flashMessage" style="background:#FFF; color:#C00; padding-left:220px; font-size:13px">
		<?php echo Yii::app()->user->getFlash('successMessage'); ?>
		</div>
	<?php endif;
	 /* End Success Message */
?>
<div class="formCon">

<div class="formConInner">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <h4><?php echo Yii::t('app','Enable or Disable Following Features.');?></h4> 	
    		<tr>
        		<td class="confic-btns"><?php
                                echo $form->radioButtonList($model,'authentication',
                                    array('1'=>  Yii::t("app", "Single Step"),'2'=>Yii::t("app", "2 Step Verification")),
                                    array(
                                        'template'=>'{input}{label}',
                                        'separator'=>'',
                                        'labelOptions'=>array(
                                            'class'=> 'config-auth'),
                                        
                                          )                              
                                      );
                                ?></td>
                               
               
        	</tr> 
           
</table>
</div> 
</div>
<?php echo CHtml::submitButton(Yii::t('app','Apply'),array('class'=>'formbut','name'=>'submit')); ?> 
<?php $this->endWidget(); ?>
</div>
</td>
</tr>
</table>

