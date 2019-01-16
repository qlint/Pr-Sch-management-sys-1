<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('/students'),
	Yii::t('app','Waiting List')=>array('/onlineadmission/waitinglistStudents/list'),
	Yii::t('app','Update')
);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper"> 
            	<div  class="page-header">                       
            		<h1><?php echo Yii::t('app','Update Waiting List'); ?></h1>
                </div>    
            
            <?php $form=$this->beginWidget('CActiveForm', array('id'=>'WaitinglistStudents-form',
			 //'action' => Yii::app()->createUrl('students/waitinglistStudents/manage'),
			//'enableAjaxValidation'=>true,
			)); ?>            
			<div class="formCon">
				<div class="formConInner">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><div class="errorMessage" style="margin:10px 0; font-size:12px;"><?php echo $result;?></div></td>
						</tr>
						<tr>
							<td valign="bottom" width="33%"><?php echo Yii::t('app','Name');?></td>                    		                    		
                            <td valign="bottom"><?php echo $form->labelEx($model,'batch_id'); ?></td>
                            <td valign="bottom" width="33%"><?php echo $form->labelEx($model,'priority'); ?></td>
                    		<td>&nbsp;</td>							
						</tr>  
						<tr>
                        	<?php 
								
								$name = $student->studentFullName('forOnlineRegistration');
							?> 
                        	<td valign="top" style="color: #8e6704;font-size: 13px;font-weight: bold;"><?php echo $name;?>
                    		</td>                    									
                            <td valign="top">
                    	
						<?php						
                        $models = Batches::model()->findAll("is_deleted=:x AND academic_yr_id=:y", array(':x'=>'0',':y'=>Yii::app()->user->year));
                        $data = array();
                        foreach ($models as $model_1)
                        {
                            //$posts=Batches::model()->findByPk($model_1->id);
                            $data[$model_1->id] = $model_1->course123->course_name.'-'.$model_1->name;
                        }
                        ?>
                        
                        <?php 
						
                        if(isset($bid) and $bid!=NULL)
                        {
                            echo $form->dropDownList($model,'batch_id',$data,array('options' => array($bid=>array('selected'=>true)),
                            'style'=>'width:170px;','encode'=>false,'empty'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")
                            )); 
                        }
                        else
                        {
                            echo $form->dropDownList($model,'batch_id',$data,array(
                            'style'=>'width:130px ;','encode'=>false,'empty'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")
                            )); 
                        }
                        ?>
                       
                        <?php echo $form->error($model,'batch_id'); ?>
                       
                    	</td>
                        <td valign="top"><?php echo $form->textField($model,'priority',array('class'=>'priority','size'=>25,'maxlength'=>255)); ?>
                    	<?php echo $form->error($model,'priority'); ?></td>
                            <td valign="top"><?php echo $form->hiddenField($model,'student_id',array('id'=>$_REQUEST['id'])); ?></td>
                   																	                
						</tr>                                      
					</table>
				</div>
                
			</div>    
            <?php echo CHtml::submitButton(Yii::t('app','Save'),array('class'=>'formbut','name'=>'submit')); ?>
			<?php $this->endWidget(); ?>
		</div>	
        </td>
     </tr>
</table>
<!-- Checking the priority is present or not -->  
<script type="text/javascript">
$( document ).ready(function() {   
	$("#WaitinglistStudents_batch_id").change(function(){
		var batch_id	= $("#WaitinglistStudents_batch_id").val();						
		if(batch_id=="" || batch_id==null)
		{			
			location.reload();
		}
		else
		{				
			$.ajax({
				type: "POST",
				url: <?php echo CJavaScript::encode(Yii::app()->createUrl('onlineadmission/waitinglistStudents/priority'))?>,
				data: {'batch_id':batch_id, "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
				success: function(result){						
					$(".priority").val(result);
				}
			});	
		}
	});
});
</script>                              
