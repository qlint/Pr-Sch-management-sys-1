<style type="text/css">
 p.red_er{ position:relative;
 color:red;
 top:-50px;
 float:right;
 right:30px;}
 
.temp_div p{margin: 2px;
    padding: 2px;}
</style>
<?php
$this->breadcrumbs=array(
    Yii::t('app','Report'),
	Yii::t('app','Notification'),
);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?> 
        </td>
        <td valign="top"> 
        <?php
		$student_id   = $_REQUEST['id'];
		
		?>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td width="75%" valign="top">
                    <h1 style="margin-left:10px;"><?php echo Yii::t('app','Email Notification');?></h1>
                    
                    <p class="red_er">*<?php echo Yii::t('app','Please do not remove the content within the').'<>'.Yii::t('app','and').'{{ }}';?></p>
<div class="temp_view">
<div class="temp_div" style="padding: 0 5px 19px 0;">
	
    <div style=" margin: 2px 0 0 8px;
    position: absolute;
    right: 0;
    top: -4px;">			
        <?php echo CHtml::link(Yii::t('app',''), array('update', 'id'=>$data->id,'student_id'=>$_GET['student_id']),array('class'=>'temp_edit'));?>
        
    </div>
    <div>    
    
    <p><?php echo $data->template; ?></p>
    
    
    <?php echo CHtml::link(Yii::t('app','Send Reminder'),array('/report/notification/mailreminder','student_id'=>$_GET['student_id']),array('class'=>'formbut-n','style'=>''));?>
   </div>
<?php
				if(count($data)==0)
				{
?>
				<div style="padding-top:10px" class="notifications nt_red"><i><?php echo Yii::t('app','No templates found');?></i></div>
<?php
				}
?> 
                            
<div class="created_box">

<div class="created_box_r"><b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
									
	if($settings!=NULL)
	{	
		$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
		
		date_default_timezone_set($timezone->timezone);
		$date = date($settings->displaydate,strtotime($data->created_at));	
		$time = date($settings->timeformat,strtotime($data->created_at));					
	}
	?>
	<?php echo CHtml::encode($date.' '.$time); ?></div>
</div>

<div class="clear"></div>

</div>
</div>
                  </td>
                </tr>
            </tbody>
           </table>
        </td>
    </tr>
</table>


<div class="clear"></div>