<script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
	var config =
	    {
		height: 300,
		width : '95%',
		resize_enabled : false,
		toolbar :

		[

		['Bold','Italic','Underline','Strike','-','Subscript','Superscript','-','SelectAll','RemoveFormat'],

		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],

		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],

	/*	['BidiLtr', 'BidiRtl'],

		['Link','Unlink','Anchor'],

		['Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe','-','Save','NewPage','Preview','-','Templates','-','Cut','Copy','Paste','PasteText','PasteFromWord'],

		'/',

		['Undo','Redo','-','Find','Replace','-','Styles','Format','Font','FontSize'],

		['TextColor','BGColor'],*/

		]

	};
        //Set for the CKEditor
		$('#Message_body').ckeditor(config);

    });
</script>
      <div id="parent_Sect">
        <?php $this->renderPartial('leftside');?> 
        <div id="parent_rightSect">
        	<div class="parentright_innercon">
             <h1><?php echo Yii::t('app','View Message'); ?></h1>
                
                <?php 
				$msg=Message::model()->findByAttributes(array('id'=>$_REQUEST['msg_id']));
				?>
                <div class="inbox_filter">
                	<ul>
                    	<li style="padding:2px 5px;">
                        <?php echo CHtml::link(Yii::t('app','Compose Mail'),array('newmessage'));?>
                       </li>
                      
                    </ul>
                    <div class="clear"></div>
                </div>
                <?php
				if($msg!=NULL)
				{
					 $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$data=explode(" ",$msg->created_at);
									
									$date1=date($settings->displaydate,strtotime($data[0]));
									$time=date($settings->timeformat,strtotime($data[1]));
									
		
								}
					
						$user=Profile::model()->findByAttributes(array('user_id'=>$msg->sender_id));
						?>
                         <div class="mail_list_row">
                         <ul>
                         	<li class="rscnd"><?php echo $msg->subject;?><br />

                         <?php echo Yii::t('app','From'); ?>:<?php echo $user->lastname.' '.$user->firstname;?>&nbsp;&nbsp;On &nbsp;<?php echo '<span>'.$date1.' '.$time.'</span>';?>
                         <br />
						
                         </li>
                        
                            	
                         </ul>
                        	 <div class="clear"></div>
                             
                         </div>
                          <div class="mail_Con">
                         <?php echo $msg->body;?>
                         </div>
                            <br /><br />
                         <h3><?php echo Yii::t('app','Reply') ?></h3>
                         <div class="form">
                        <?php $form = $this->beginWidget('CActiveForm', array(
                            'id'=>'message-form',
                            'enableAjaxValidation'=>false,
                        )); ?>
                    
                        <?php echo $form->errorSummary($msg); ?>
                    
                        <div class="row">
                            <?php echo $form->hiddenField($msg,'receiver_id'); ?>
                            <?php echo $form->error($msg,'receiver_id'); ?>
                        </div>
                    
                        <div class="row">
                            <?php echo $form->labelEx($msg,'subject'); ?>
                            <?php echo $form->textField($msg,'subject',array('size'=>60,'maxlength'=>255)); ?>
                            <?php echo $form->error($msg,'subject'); ?>
                        </div>
                    
                        <div class="row">
                            <?php echo $form->labelEx($msg,'Message'); ?>
                            <?php echo $form->textArea($msg,'body',array('class'=>'txtarea')); ?>
                            <?php echo $form->error($msg,'body'); ?>
                        </div>
                    
                        <div style="margin-top:10px;">
                            <?php echo CHtml::submitButton(Yii::t('app',"Reply"),array('class'=>'formbut')); ?>
                        </div>
                    
                        <?php $this->endWidget(); ?>
                    </div>
                        <?php
					
				}
				?>
                 
            </div>
        </div>
        <div class="clear"></div>
      </div>
      <!--innersection ends here-->

