<?php 	$this->breadcrumbs=array(
		Yii::t('app','Notify'),
		Yii::t('app','Sent Emails'),);
	
?>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">        
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td width="75%" valign="top">                                   
                    	<div  class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','View Sent Email');?></h1> 
                         <?php 
						 $criteria = new CDbCriteria;
						 $criteria->condition = 'id=:z';
						 $criteria->params = array(':z'=>$_GET['id']); 
						 $model = EmailDrafts::model()->find($criteria);
						 
						 $criteria = new CDbCriteria;
						 $criteria->compare('mail_id',$_GET['id']); 
						 $attachments = EmailAttachments::model()->findAll($criteria);
						 
						 $criteria = new CDbCriteria;
						 $criteria->condition = 'mail_id=:z';
						 $criteria->params = array(':z'=>$_GET['id']); 
						 $model1 = EmailRecipients::model()->find($criteria);
						 $user = explode(',',$model1->users);
						 $batch = explode(',',$model1->batches);
						 $criteria = new CDbCriteria;
						 foreach($batch as $batches){
							 $criteria->condition = 'id=:x';
							 $criteria->params = array(':x'=>$batches);
							 $model2[] = Batches::model()->find($criteria);
						 }
						 
						 ?>
                            <div class="n-sentnews-con">
                            	<!-- news box starts-->
                                <?php  ?>
								<h1><?php echo Yii::t('app', 'Subject :');?> <?php echo $model->subject; ?></h1>
                            	<div class="to_mail"><span><b><?php echo Yii::t('app', 'To :');?></b><?php if(in_array("1",$user)){ echo Yii::t('app', 'Teachers');?>,<?php } ?>
                                    <?php if(in_array("2",$user)){echo Yii::t('app', 'Parents');?>,<?php } ?>
                                    <?php if(in_array("3",$user)){echo Yii::t('app', 'Students'); } ?></span>
                                <span><b><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.':';?>  </b></b><?php if(in_array("0",$batch)){ echo Yii::t('app', 'All').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); }else{ foreach($model2 as $models){ echo $models->name;?>, &nbsp;<?php }} ?>  </span>
                               <span> <b><?php echo Yii::t('app', 'Attachments :');?> </b>&nbsp;
                                         <?php 	foreach($attachments as $attachment){  
										 echo CHtml::link($attachment->file, array('default/download','id'=>$attachment->id,'mail_id'=>$attachment->mail_id));?>&nbsp; , <?php } ?></span>
                                   </div> 
                                    <div class="n-sentnews-date" style="width:auto"><?php $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
									if($settings!=NULL)
									{	
										$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
										
										date_default_timezone_set($timezone->timezone);
										$date = date($settings->displaydate,strtotime($model->created_on));	
										$time = date($settings->timeformat,strtotime($model->created_on));					
									}
									echo CHtml::encode($date.' '.$time); ?></div>
                                	
                                 
                                    	 <p><?php echo $model->message; ?>
                                         
                                    </p>
                              
                                 <!-- news box ends-->
                            </div>
                        </div>
    				</td></tr>
    			</tbody>
    		</table>
    	</td>
   </tr>
</table>
