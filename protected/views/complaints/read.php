<?php
 $this->breadcrumbs=array(
	 Yii::t('app','Complaint List')=>array('/complaints/index'),
	 Yii::t('app', 'View')
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">            
        	<?php $this->renderPartial('mailbox.views.default.left_side'); ?>            
        </td>
        <td valign="top">
        	<div class="cont_right formWrapper">

            		<h1><?php echo Yii::t('app','Complaint'); ?></h1>

                <!-- Flash Message -->
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
                    <div class="flashMessage" style="background:#FFF; color:#689569; padding-left:220px; font-size:13px">
                    <?php echo Yii::app()->user->getFlash('successMessage'); ?>
                    </div>
                <?php endif; ?>
<?php
				$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings == NULL){
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
				}					
				$feedbacks 	= ComplaintFeedback::model()->findAllByAttributes(array('complaint_id'=>$_REQUEST['id']));
				$complaint	= Complaints::model()->findByAttributes(array('id'=>$_REQUEST["id"]));
				$category	= ComplaintCategories::model()->findByAttributes(array('id'=>$complaint->category_id));
?> 

                <div class="complaint-block-one">
                    <div class="complaint-open-atagbtn">
<?php
						if($complaint->status == 0){
							echo CHtml::link(Yii::t("app",'Close'),array('/complaints/close','id'=>$complaint->id),array('class'=>'compln-atg','confirm'=>Yii::t('app','Are you sure you want to close this Complaint ?')));  
						}
						else if($complaint->status == 1){
							echo CHtml::link(Yii::t("app",'Reopen'),array('/complaints/reopen','id'=>$complaint->id),array('class'=>'compln-atg','confirm'=>Yii::t('app','Are you sure you want to reopen this Complaint ?')));  						
						}
?>                                        	
                    </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="complaint-profile">
                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="complaint-profile">
                                    <tr>
                                        <th width="15%"><?php echo Yii::t('app', 'Name'); ?></th>
                                        <td width="55%">
<?php
											$created_by	= Complaints::model()->getName($complaint->uid);
											if($created_by){
												echo $created_by;
											}
											else{
												echo '-';
											}
?>                                        	
                                        </td>
                                        <th width="10%"><?php echo Yii::t('app', 'Date'); ?></th>
                                        <td width="20%">
<?php
											if($settings != NULL){
												echo date($settings->displaydate, strtotime($complaint->date));
											}
											else{
												echo date('Y-m-d', strtotime($complaint->date));
											}
?>											
                                        </td>                                        
                                    </tr>            
                                </table>
                                <div class="table-two table-color">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="">
                                        <tr>
                                            <th width="15%"><?php echo Yii::t('app', 'Category'); ?></th>
                                            <td colspan="3">
<?php
												if($category != NULL){
													echo ucfirst($category->category);
												}
												else{
													echo '-';
												}
?>                                            	
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="15%"><?php echo Yii::t('app', 'Subject'); ?></th>
                                            <td colspan="3"><?php echo ucfirst($complaint->subject); ?></td>
                                        </tr>
<?php	
										if($complaint->status == 1){
?>                                        
                                            <tr>
                                                <th width="15%"><?php echo Yii::t('app', 'Closed By'); ?></th>
                                                <td colspan="3">
													<?php 
														$closed_by	= Complaints::model()->getName($complaint->closed_by);
														if($closed_by != NULL){
															echo $closed_by;
														}
														else{
															echo '-';
														}
													?>
                                                </td>
                                            </tr>
<?php
										}
?>                                            
                                        <tr>
                                            <th colspan="4">
                                                <p><?php echo Yii::t('app', 'Complaint'); ?></p>
                                                <div class="highlight"><?php echo ucfirst($complaint->complaint); ?></div>
                                            </th>
                                        </tr>               
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div> 
<?php
				if($feedbacks != NULL){					
?>                 
                    <div class="chat-area-bg">
                    	<div  id="comment"></div>
                        <div class="complain-scroll" id="comment_box">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class=""> 
<?php
								foreach($feedbacks as $feedback){
									$commented_by 	= Complaints::model()->getName($feedback->uid);
									if($settings != NULL){
										$commented_date	= date($settings->displaydate, strtotime($feedback->date));										
									}
									else{
										$commented_date	= date('Y-m-d', strtotime($feedback->date));	
									}
									if($feedback->uid == Yii::app()->user->id){										
?>
										<tr>
                                            <td width="100%">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="">
                                                    <tr>
                                                        <td>
                                                            <div class="icon-left">
                                                                <div class="chat_bg chat_bg-color">
                                                                    <div class="bottom-cht-hr">
                                                                        <h4><?php echo $commented_by; ?> <span class="green-strip"><?php echo $commented_date; ?></span></h4>
                                                                        
                                                                        <?php 
																			if($complaint->status == 0){
																				echo CHtml::ajaxLink('<i class="fa fa-pencil"></i>', Yii::app()->createUrl('complaints/update',array('id'=>$feedback->id)), array('type' =>'GET','dataType' => 'text',  'update' =>'#comment', 'onclick'=>'$("#comment_dialog").dialog("open"); return false;',),array('title'=>'edit','class'=>'help_class pull-right'));
																				echo CHtml::link('<i class="fa fa-trash-o"></i>',"#",array('submit'=>array('delete','id'=>$feedback->id),'title'=>'delete','class'=>'pull-right','style'=>'','confirm'=>Yii::t('app','Are you sure you want to delete this comment ?'), 'csrf'=>true));
																			}
																		?>                                                                        
                                                                        <div class="triangle1-topleft"></div>
                                                                    </div>
                                                                    <p><?php echo ucfirst($feedback->feedback); ?></p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td width="10"></td>
                                                        <td width=" 20"><img src="images/complaint-icon.png" /></td>
                                                    </tr>
                                                </table>	
                                            </td>
                                        </tr>
<?php									
									}
									else{
?>                            
                                        <tr>
                                            <td width="100%">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="">
                                                    <tr>                                                
                                                        <td width=" 20"><img src="images/complaint-icon.png" /></td>
                                                        <td width="10"></td>                                                
                                                        <td>
                                                            <div class="icon-left">
                                                                <div class="chat_bg chat_bg-color-gray">
                                                                    <div class="bottom-cht-hr2">
                                                                        <h4><?php echo $commented_by; ?> <span class="green-strip"><?php echo $commented_date; ?></span></h4>                                                                        
                                                                        <div class="triangle1-topright"></div>
                                                                    </div>
                                                                    <p><?php echo ucfirst($feedback->feedback); ?></p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>	
                                            </td>
                                        </tr>
<?php
									}
								}
?>                                    
                            </table>
                            <div id="div_id"></div>
                        </div>
                    </div> 
<?php
				}
				if($complaint->status == 0){
					$form=$this->beginWidget('CActiveForm', array(
						'enableClientValidation'=>true,
						'clientOptions'=>array(
							'validateOnSubmit'=>true,
						)
					));
?>  
						<div class="complain-chatng-box">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="">
								<tr>
									<td>
										<div class="complaint-textarea-box">
											<label><?php echo Yii::t('app', 'Comments'); ?></label>
											<?php 
												echo $form->textArea($model,'feedback',array('class'=>'form-control', 'rows'=>4, 'placeholder'=>Yii::t("app",'Enter your comment here'))); 
												echo $form->error($model,'feedback');
											?>                                        
										</div>
										<div class="complain-btn">
											<?php echo CHtml::submitButton(Yii::t("app",'Submit'),array('class'=>'formbut')); ?>
										</div>
									</td>
								</tr>
							</table>
						</div> 
<?php
					$this->endWidget();
				}
?>                                                        
            </div>
        </td>
	</tr>
</table>    
<script type="text/javascript">
$(document).ready(function(){  
    $('#comment_box').scrollTop($('#comment_box')[0].scrollHeight);    	               
});

$('.formbut').click(function(ev){
	$('.complaint-textarea-box').removeClass('success');
});
</script>        