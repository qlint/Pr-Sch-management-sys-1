<?php
$this->breadcrumbs=array(
	Yii::t('app','Fees')=>array('/fees'),
	Yii::t('app','Payment Types')=>array('admin'),
	Yii::t('app','Create'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">    
            <?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="247">
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Fees Configurations'); ?></h1>            
                            <div class="edit_bttns" style="top:20px; right:20px;">
                                <ul>
                                </ul>
                            </div>
                            <?php
								Yii::app()->clientScript->registerScript(
									'myHideEffect',
									'$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
									CClientScript::POS_READY
								);
							?>
							
							<?php if(Yii::app()->user->hasFlash('success')){?>
								<div class="flashMessage" style="background:#FFF; color:#C00; padding-left:200px; font-size:16px">
								<?php echo Yii::app()->user->getFlash('success'); ?>
								</div>
							<?php }?>
                            
							<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

