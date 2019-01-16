<tr>
    <td><?php echo $form->labelEx($model,'employee_id'); ?></td>
    <td><?php echo $form->dropDownList($model,'employee_id', array(),array('prompt'=>Yii::t('app','Select Employee'),'style'=>'width:200px;',)); ?></td>
    <?php //echo $form->error($model,'employee_id'); ?>
</tr>