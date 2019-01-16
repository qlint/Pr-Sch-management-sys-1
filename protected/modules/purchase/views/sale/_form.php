<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'sale-details-form',
'enableAjaxValidation'=>false,
)); ?>

<p class="note"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required.'); ?></p>

<?php
	$criteria	= new CDbCriteria;
	$criteria->order	= '`t`.`name` ASC';
	$items		= PurchaseItems::model()->findAll($criteria);
?>

<div class="formCon-block">
    <div class="formConInner-block">    
        <h3><?php echo Yii::t('app', 'Sale Details'); ?> </h3> 
        <div class="text-fild-bg-block">
            <div class="text-fild-block inputstyle">
                <?php echo $form->labelEx($model, 'material_id'); ?>                
                <?php echo $form->dropDownList($model, 'material_id', CHtml::listData($items, 'id', 'name'), array('prompt'=>Yii::t('app', 'Select Item Name'))); ?>
                <?php echo $form->error($model, 'material_id'); ?>
            </div>
            
            <div class="text-fild-block inputstyle">
                <?php echo $form->labelEx($model,'quantity'); ?>
                <?php echo $form->textField($model,'quantity', array('maxlength'=>255)); ?>
                <?php echo $form->error($model,'quantity'); ?>
            </div>
        </div>
        
        <div class="text-fild-bg-block">            
            <div class="text-fild-block inputstyle">
                <?php echo $form->labelEx($model,'purchaser'); ?>
                <?php echo $form->dropDownList($model,'purchaser', array(1=>Yii::t('app', 'Student'), 2=>Yii::t('app', 'Teacher'), 3=>Yii::t('app', 'Parent')), array('prompt'=>Yii::t('app', 'Select Purchaser Type'), 'ajax' => array('type'=>'POST', 'url'=>CController::createUrl('sale/users'),'update'=>'#PurchaseSale_employee_id'))); ?>
                <?php echo $form->error($model,'purchaser'); ?>
            </div>
            
            <div class="text-fild-block inputstyle">
            	<?php
					$users	= array();
                	if(isset($model->purchaser) and $model->purchaser!=NULL){
						$criteria		= new CDbCriteria;
						$criteria->join	= 'JOIN `profiles` `p` ON `p`.`user_id`=`user`.`id`';
						switch($model->purchaser){
							case 1:
								$criteria->join			.= ' JOIN `students` `s` ON `s`.`uid`=`user`.`id`';
								$criteria->condition	= '`s`.`is_deleted`=:is_deleted AND `s`.`is_active`=:is_active';
								$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1);
							break;
							
							case 2:
								$criteria->join			.= ' JOIN `employees` `e` ON `e`.`uid`=`user`.`id`';
								$criteria->condition	= '`e`.`is_deleted`=:is_deleted';
								$criteria->params		= array(':is_deleted'=>0);
							break;
							
							case 3:
								$criteria->join			.= ' JOIN `guardians` `g` ON `g`.`uid`=`user`.`id`';
								$criteria->condition	= '`g`.`is_delete`=:is_deleted';
								$criteria->params		= array(':is_deleted'=>0);					
							break;
						}
						
						$criteria->order		= '`p`.`lastname` ASC';
						$users		= User::model()->findAll($criteria);
						$users		= CHtml::listData($users, 'id', 'profile.fullname');
					}
				?>
                <?php echo $form->labelEx($model, 'employee_id'); ?>
                <?php echo $form->dropDownList($model, 'employee_id', $users, array('prompt'=>Yii::t('app', 'Select Purchased By'))); ?>
                <?php echo $form->error($model, 'employee_id'); ?>
            </div>
        </div> 
        <div class="clear"></div>
    </div>
</div>

<div style="padding:0px 0 0 0px; text-align:left">
	<?php echo CHtml::submitButton(Yii::t('app','Add Sale'),array('id'=>'submit_button_form','class'=>'formbut')); ?>
</div>

<?php $this->endWidget(); ?>