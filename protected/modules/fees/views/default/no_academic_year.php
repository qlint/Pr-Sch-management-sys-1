<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('index'),
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
                    <td valign="top" width="75%">
                        <div class="cont_right">
                            <h1><?php echo Yii::t('app','Fees Dashboard'); ?></h1>            
                            <div class="edit_bttns" style="width:175px; top:15px;">
                            </div>
                            <div class="yellow_bx" style="background-image:none;width:670px;padding-bottom:45px;">
                                <div class="y_bx_head">
									<?php echo Yii::t("app", "You are not created an academic year !!");?>
                                    <?php echo CHtml::link(Yii::t("app", "Create Now"), array("/academicYears/create"));?>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>