<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
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
                            <h1><?php echo Yii::t('app','Create Fees'); ?></h1>            
                            <div class="edit_bttns" style="width:175px; top:15px;"></div>
                            <?php $this->renderPartial('_form',array('category'=>$category, 'particular'=>$particular));?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>