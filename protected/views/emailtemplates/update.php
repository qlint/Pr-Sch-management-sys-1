<style type="text/css">
 p.red_er{ position:relative;
 color:red;
 top:-50px;
 float:right;
 right:30px;}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('notifications/default/sendmail'),
	Yii::t('app','Email Templates')=>array('index'),
	
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('left_side');?>    
        </td>
        <td valign="top">  
             
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td width="75%" valign="top">
                    	<div style="padding-left:20px;" class="sms-block">
    						<h1><?php echo Yii::t('app','Update Email Template');?></h1>
                            <p class="red_er">*<?php echo Yii::t('app', 'Please do not remove the content within the');?> {{ }}</p>
							<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                            
                            <div class="clear"></div>
                                                        
                        </div>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</table>