<div class="logedit_form">
                               
                                <?php $form=$this->beginWidget('CActiveForm', array(
									'id'=>'log-form',
									'enableAjaxValidation'=>true,
									'enableClientValidation'=>true,
								)); ?>
                                
                                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                        <td width="83%"><?php 
										$data_1 = CHtml::listData(LogCategory::model()->findAllByAttributes(array('editable'=>1)),'id','name');
										echo $form->dropDownList($model1,'category_id',$data_1,array('class'=>'form-control','style'=>'width:200px','prompt'=>'Select Category','id'=>'category','options'=>array()));  ?>
                                        <?php echo $form->error($model1,'category_id'); ?>
                                        </td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                      <tr>
                                        <td width="83%"><?php echo $form->textArea($model1,'comment',array('class'=>'form-control','id'=>'comment_text'));?>
                                         <?php echo $form->error($model1,'comment'); ?></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                        <td >
                                        <?php echo $form->hiddenField($model1,'user_id');?>
                                        <?php echo $form->hiddenField($model1,'id');?>
                                        <input type="hidden" name="batch_id" value="<?php echo $_REQUEST['batch_id']; ?>" />
                       					<?php 
					   
										echo CHtml::ajaxSubmitButton(Yii::t('students','Submit'),
										CHtml::normalizeUrl(array('logcomment/create')),
										 array('dataType'=>'json', 'type'=>'post','success'=>'js: function(data) {
											 
											
											if (data.status == "error")
											{
												
												$.each(data.error, function(key, val) {
													
													$("#delete_div_'.$model1->id.' #"+key+"_em_").html(String(val)).show();
												});
												
											}
											else
											{
												
												$( "#delete_div_'.$model1->id.'").replaceWith(data.content);
												
											}
											    
										}'),array('id'=>'cmnt_button_'.$model1->id.'_'.time(),'name'=>'','class'=>'btn btn-primary','style'=>'')); 
							
										?>
	
                                        
                                        
                                        </td>
                                      </tr>
                                    </table>
								 <?php $this->endWidget(); ?>
                                </div>