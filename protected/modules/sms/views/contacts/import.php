<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('/notifications/default/sendmail'),
	Yii::t('app','SMS Contacts')=>array('index'),
	Yii::t('app','Import'),
);
?>
<style>
span.error{
	color:#F00;
}
span.ok{
	color:#093;
}
.csv_links {
	margin-top:10px;
	margin-bottom:10px;
	padding:0px 0px !important;
	width:96%;
}
.yb_import{
	width:90.5%;
}
</style>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
                <div class="cont_right formWrapper">
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                	<tr>
                    <td width="75%" valign="top">                    
                    	<div class="sms-block">                        
                        	<h1><?php echo Yii::t('app', 'Import Contacts');?></h1>
                            <div class="yb_import">
                            	<div class="head">
                                	<b><h2><?php echo Yii::t('app', 'Instructions for importing Contacts'); ?> :</h2></b>
                                    <ul>
                                    	<li><?php echo Yii::t('app','Download or open the appropriate sample CSV file').' : '.CHtml::link('<span>'.Yii::t('app','Contacts Import').'</span>', array('/sms/contacts/download'), array('class'=>'contactcsv')); ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="csv_links"></div>
                            <div class="formCon" style="width:96%">
                                <div class="formConInner">
                                    <div id="message_block"></div>
                                    <div id="secondStep"></div>
                                    <div id="firstStep">
                                        <div class="select_csv"><?php echo Yii::t('app', 'Please select a .csv/.xls file');?></div>
                                        <input type="hidden" value="<?php echo Yii::app()->request->csrfToken;?>" name="<?php echo Yii::app()->request->csrfTokenName;?>"/>
                                        <input type="button" class="formbut" id="select_file_with_contacts" value="<?php echo Yii::t('app', 'select file');?>" />
                                    </div>                                                                                    
                                </div>
                        	</div>
						</div>
                    </td>
                </tr>
            </tbody></table>
            </div>
        </td>
    </tr>
</table>
