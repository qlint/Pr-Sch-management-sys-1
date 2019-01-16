<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/configurations'),
	Yii::t('app','Mobile Notifications')=>array('/mobileNotification'),
	Yii::t('app','Manage'),
);
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/custom-selectbox/selectbox.css">
<script type="text/javascript"  src="<?php echo Yii::app()->request->baseUrl; ?>/js/custom-selectbox/jquery.nice-select.min.js"></script> 
<style>
	.container{
		 background:#fff !important;	
	}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>    
    <td valign="top">
    	<div class="full-formWrapper">
			<h1><?php echo Yii::t('app','Manage Mobile Notifications');?></h1>                
			
                <div class="opnsl_noteBox">
                    <ul>
                        <p class="opnsl_noteIcon"><?php echo Yii::t('app', 'Note'); ?></p>
                        <li><?php echo Yii::t('app', 'Do not remove the content within the { }'); ?></li>
                        <li><?php echo Yii::t('app', 'Edit messages in different languages using the language dropdown'); ?></li>
                        <li><?php echo Yii::t('app', 'Use the restore button to restore deleted default messages. (Applicable only to English)'); ?></li>
                    </ul>                
                </div>
                <div class="opnsl_innerBox"> 
				<div class="action_box">
                    <div class="opnsl_headerBox">
                        <div class="opnsl_actn_box">
                            <div class="opnsl_actn_box1">
                            	<?php 
									$criteria	= new CDbCriteria();
									$criteria->condition	= 'language=:language';
									$criteria->params		= array(':language'=>'en_us');
									$criteria->group		= 'type';
									$criteria->order		= 'type ASC';
									$notification_types		= PushNotifications::model()->findAll($criteria);
									echo CHtml::dropDownList('type','',CHtml::listData($notification_types,'id','type'),array('prompt'=>Yii::t('app','Select Type'), 'class'=>'opnsl_selectBox', 'id'=>'type','options'=>array($_REQUEST['type']=>array('selected'=>true)))); 
								?>	                                
                            </div>
                            <div class="opnsl_actn_box2">
                            	<?php
									echo CHtml::dropDownList('lang','',PushNotifications::model()->getSystemLanguages(),array('prompt'=>Yii::t('app','Select Language'), 'class'=>'opnsl_selectBox', 'id'=>'lang','options'=>array($_REQUEST['lang']=>array('selected'=>true))));
								?>                            
                        	</div>
                        </div>
                        <div class="opnsl_actn_box">
                        <div class="opnsl_actn_box1"><?php 
								if(isset($_REQUEST['type']) and $_REQUEST['type'] != NULL and isset($_REQUEST['lang']) and $_REQUEST['lang'] != NULL and $_REQUEST['lang'] == 'en_us'){									
									echo CHtml::link(Yii::t('app', 'Restore'), array('/mobileNotification/restore', 'type'=>$_REQUEST['type'], 'lang'=>$_REQUEST['lang']), array('confirm'=>Yii::t('app','Are you sure you want to restore default messages?'), 'class'=>'opnsl_btn hvr-icon-spin hvr-icon-spin1', 'title'=>Yii::t('app', 'Restore default messages ( English )')));
								}
							?></div>
                        <div class="opnsl_actn_box2"><?php 
								if(isset($_REQUEST['type']) and $_REQUEST['type'] != NULL and isset($_REQUEST['lang']) and $_REQUEST['lang'] != NULL and $_REQUEST['lang'] != 'en_us'){									
									echo CHtml::link(Yii::t('app', 'Delete All'), array('/mobileNotification/deleteAll', 'type'=>$_REQUEST['type'], 'lang'=>$_REQUEST['lang']), array('confirm'=>Yii::t('app','Are you sure you want to delete all?'), 'class'=>'opnsl_btn hvr-icon-spin', 'title'=>Yii::t('app', 'Delete Notifications')));
								}
							?></div>
                                                        
                        </div>
                    </div>
                </div>
                <?php 
					if(isset($_REQUEST['type']) and $_REQUEST['type'] != NULL and isset($_REQUEST['lang']) and $_REQUEST['lang'] != NULL){
						echo CHtml::beginForm(Yii::app()->createUrl('/mobileNotification'),'post',array());
						
						$type	= PushNotifications::model()->findByPk($_REQUEST['type']);
						
						$criteria				= new CDbCriteria();
						$criteria->condition	= 'type=:type AND language=:language';
						$criteria->params		= array(':type'=>$type->type, 'language'=>'en_us');
						$criteria->order		= 'id ASC';
						$model					= PushNotifications::model()->findAll($criteria);						
				?>                               
                        <div class="opnsl_table">                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="35%"><?php echo Yii::t('app', 'Description'); ?></th>
                                        <th width="20%"><?php echo Yii::t('app', 'Title'); ?></th>
                                        <th width="45%"><?php echo Yii::t('app', 'Message'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php										
										if($model != NULL){																						
											foreach($model as $key => $value){	
												if ($key % 2 == 0) {
													$style_class	= 'even';
												}
												else{
													$style_class	= 'odd';
												}
									?>
												<tr class="<?php echo $style_class; ?>">
													<td rowspan="2" align="top">
														<h3><?php echo ucfirst($value->description); ?></h3>
													</td>
													<td>
														<p><?php echo ucfirst($value->title); ?></p>
													</td> 
													<td style=" vertical-align:top;">
														<p><?php echo $value->message; ?></p>
													</td>                                        
												</tr>
												<tr class="<?php echo $style_class; ?> field_tr">
													<td>
														<div class="opnsl_inputField_stl opnsl_inputField_icon">
															<?php
																$notification	= PushNotifications::model()->findByAttributes(array('language'=>$_REQUEST['lang'], 'notification_number'=>$value->notification_number));
																$title		= '';
																$message	= '';
																$id			= '';
																if($notification != NULL){
																	$title		= $notification->title;
																	$message	= $notification->message;
																	$id			= $notification->id;
																}
																echo CHtml::textField('title[]',$title,array('class'=>'opnsl_inputField opnsl_inputField_custom title_field', 'placeholder'=>Yii::t('app', 'Enter Title')));																
															?> 
                                                            <a href="javascript:void(0);" class="inputField_iocn refresh_icon" title="<?php echo Yii::t('app', 'Clear Title'); ?>"><i class="fa fa-refresh" aria-hidden="true"></i></a>  
                                                            <span class="field_Error"></span>                                                         
														</div>
													</td> 
													<td>
													   <div class="opnsl_inputField_stl opnsl_inputField_icon">
														   <?php
															  echo CHtml::textField('message[]',$message,array('class'=>'opnsl_inputField opnsl_inputField_custom message_field', 'placeholder'=>Yii::t('app', 'Enter Message')));  
														   ?>
                                                           <a href="javascript:void(0);" class="inputField_iocn refresh_icon" title="<?php echo Yii::t('app', 'Clear Message'); ?>"><i class="fa fa-refresh" aria-hidden="true"></i></a>
</a>    
                                                           <span class="field_Error"></span>
													   </div>
													</td>                                                    
												</tr>
									<?php
												echo CHtml::hiddenField('id[]', $id);
												echo CHtml::hiddenField('notification_number[]', $value->notification_number);	
												echo CHtml::hiddenField('type[]', $value->type);
												echo CHtml::hiddenField('description[]', $value->description);	
												echo CHtml::hiddenField('language[]', $_REQUEST['lang']);												
											}
										}
										else{
									?>
											<tr>
												<td colspan="3" class="nothing-found"><?php echo Yii::t('app', 'Notifications Not Found'); ?></td>
											</tr>
									<?php	
										}											
									?>
                                </tbody>
                            </table>                
                        </div>
                        <div class="opnsl_headerBox">
                            <div class="opnsl_actn_box"> </div>
                            <div class="opnsl_actn_box">
                            	<?php
									echo CHtml::hiddenField('url_lang', $_REQUEST['lang']);
									echo CHtml::hiddenField('url_type', $_REQUEST['type']);
									
									echo CHtml::submitButton(Yii::t('app','Save'),array('class'=>'opnsl-btn os_btn', 'id'=>'save_btn'));
								?>                            	
                            </div>
                        </div>
            	<?php
						echo CHtml::endForm();
					}
				?>        
			</div>    		
    	</td>
    </tr>
</table>

<script type="text/javascript">
$(document).ready(function(){
	$('.opnsl_selectBox').niceSelect();
	//Reload the page with selected dropdown values
	$('#type, #lang').change(function(){
		var type	= $('#type').val();
		var lang	= $('#lang').val();
		if(type != '' && lang != ''){
			window.location= 'index.php?r=mobileNotification&type='+type+'&lang='+lang;
		}
		else if(type != ''){
			window.location= 'index.php?r=mobileNotification&type='+type;
		}
		else if(lang != ''){
			window.location= 'index.php?r=mobileNotification&lang='+lang;
		}
		else{
			window.location= 'index.php?r=mobileNotification';
		}
	});	
	//Check whether the fileds are filled or not
	$('#save_btn').click(function(){
		var flag	= 0;
		$('.opnsl_inputField_Error').removeClass('opnsl_inputField_Error');
		$('.field_Error').html('');
		if($('.field_tr').length > 0){
			$(".field_tr").each(function() {
				var title 	= $(this).find('.title_field').val();
				var message	= $(this).find('.message_field').val();
				if(title != '' || message != ''){
					if(title == ''){
						$(this).find('.title_field').next('.field_Error').html("<?php echo Yii::t('app', 'Title cannot be blank'); ?>");
						$(this).find('.title_field').closest('div').addClass('opnsl_inputField_Error');
						$(this).find('.title_field').focus();
						flag	= 1;
					}
					if(message == ''){
						$(this).find('.message_field').next('.field_Error').html('<?php echo Yii::t('app', 'Message cannot be blank'); ?>');
						$(this).find('.message_field').closest('div').addClass('opnsl_inputField_Error');
						$(this).find('.message_field').focus();
						flag	= 1;
					}
				}
			});
		}
		if(flag == 1){
			return false;
		}
	});
	
	$('.refresh_icon').click(function(){		
		$(this).prev('input').val('');
	});
	
});
</script>

