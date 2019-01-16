<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
	);
	
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings!=NULL){
		$dateformat	= $settings->displaydate;
	}
	else
		$dateformat = 'd M Y';
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
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Create Subscription'); ?></h1>            
                            <div class="edit_bttns" style="width:175px; top:15px;"></div>
                            <div class="formCon">
                                <div class="formConInner">
                                    <div><strong><?php echo Yii::t("app", "Fee Category")."</strong> : ".$category->name;?></div>
                                    <br />
                                    <div><strong><?php echo Yii::t("app", "Date Created")."</strong> : ".date($dateformat, strtotime($category->created_at));?></div>
                                    <br />
                                    <div><strong><?php echo Yii::t("app", "Description")."</strong> : ".$category->description;?></div>
                                </div>
                            </div>
                            
                            <?php $this->renderPartial('_form',array('category'=>$category));?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>