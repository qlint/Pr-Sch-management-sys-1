<style>
    .alligner .row label{ display: inline-block;
    width: 180px;}
    
</style>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'system-offline-settings-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="formCon">
<div class="formConInner alligner">
    <table>
        <tr>
            <td width="200"><?php echo $form->labelEx($model,'offline_message'); ?></td>
            <td><?php echo $form->textField($model,'offline_message',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'offline_message'); ?></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td><?php echo $form->labelEx($model,'start_time'); ?></td>
            <td><?php 
                $date = 'dd-mm-yy';
                $this->widget('application.extensions.timepicker.timepicker', array(
                                        'model' => $model,
                                        'options'=>array(
                                                'dateFormat'=>$date,	
                                                
                                        ),
                                        'htmlOptions'=>array('style'=>'width:150px'),
                                        'name'=>'start_time',
                                        'tabularLevel' => "[]",
                                        
                                        )); ?>
		<?php echo $form->error($model,'start_time'); ?></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td><?php echo $form->labelEx($model,'end_time'); ?></td>
            <td><?php $this->widget('application.extensions.timepicker.timepicker', array(
                                        'model' => $model,
                                        'options'=>array(
                                        'dateFormat'=>$date,	
                                                
                                        ),
                                        'htmlOptions'=>array('style'=>'width:150px'),
                                        'name'=>'end_time',
                                        'tabularLevel' => "[]",
                                        
                                        )); ?>
		<?php echo $form->error($model,'end_time'); ?></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td><?php echo $form->labelEx($model,'status'); ?></td>
            <td><?php echo $form->dropDownList($model,'status',array('0'=>Yii::t('app','Inactive'),'1'=>Yii::t('app','Active'),'2'=>Yii::t('app','Completed')), array('empty'=>Yii::t('app','Select'))); ?>
		<?php echo $form->error($model,'status'); ?></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td><?php echo $form->labelEx($model,'allowed_users'); ?></td>
            <td><?php echo $form->textArea($model,'allowed_users',array('rows'=>6, 'cols'=>50,'style'=>'width: 400px','id'=>'udata')); ?>
		<?php echo $form->error($model,'allowed_users'); ?></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td>
                
            </td>
            <td>
                <?php 
                    if (Yii::app()->user->isSuperuser or ModuleAccess::model()->check('Home')) {
                                       $all_roles=new RAuthItemDataProvider('roles', array( 
                                    'type'=>2,
                                    ));
                    $data=$all_roles->fetchData(); }
                    ?>
            <?php  echo CHtml::dropDownList('user_type','',CHtml::listData($data,'name','name'),array('empty'=>Yii::t('app','Select'),
                        'ajax' => array(
                        'type'=>'POST', 
                        'url'=>CController::createUrl('OfflineSettings/users'), //url to call.
                        'update'=>'#user_names', 
                        )));?> 
            <?php  echo CHtml::dropDownList('user_names','',array() ,array()); ?>
                <span onClick="addData()" style="background-color: green; padding: 7px; color: #ffffff; cursor:pointer">
                    <?php echo Yii::t('app','Add'); ?>
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
            </td>
        </tr>
        
    </table>
	

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>
</div>

<script>
function addData()
{
   
    var datas= $("#user_names option:selected").text();          
    var cur_val = $('#udata').val();        
    if(datas == '')
    {
        alert('<?php echo Yii::t('app','Select User First'); ?>');
    }
    else
    {
        if(cur_val)
        {
            var split_str = cur_val.split(",");
            if (split_str.indexOf(datas) !== -1) {
                alert('<?php echo Yii::t('app','User Exist'); ?>');
            }
            else { $('#udata').val(cur_val + "," + datas); }
        }
        else
          $('#udata').val(datas);
  }
}
</script>