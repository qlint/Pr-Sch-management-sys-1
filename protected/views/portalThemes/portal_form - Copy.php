<style>
    .table-responsive label{ display: inline-block;
    width: 200px;}
    
</style>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-themes-form',
	'enableAjaxValidation'=>false,
)); ?>
    <div class="table-responsive">
    
    <table>
        <tr>
            <td>
                <?php echo $form->labelEx($model,'header_logo_background'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'header_logo_background',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model,'header_bar_background'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'header_bar_background',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model,'header_border'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'header_border',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'header_dropdown_background'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'header_dropdown_background',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'header_dropdown_text'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'header_dropdown_text',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
        
         <tr>
            <td>
                <?php echo $form->labelEx($model,'header_dropdown_over'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'header_dropdown_over',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'header_text_color'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'header_text_color',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'page_header_background'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'page_header_background',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'page_header_text'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'page_header_text',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'left_panel_background'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'left_panel_background',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'left_panel_text'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'left_panel_text',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'left_panel_over_background'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'left_panel_over_background',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
         <tr>
            <td>
                <?php echo $form->labelEx($model,'left_panel_over_text'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'left_panel_over_text',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model,'left_panel_active_background'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'left_panel_active_background',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model,'left_panel_active_text'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'left_panel_active_text',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model,'main_panel_background'); ?>
            </td>    
            <td>
                <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'main_panel_background',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array(), // html attributes
		)); ?>
            </td>
        </tr><tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td>
                <div class="form buttons">
		<?php
                if($status==1)
                {
                    echo CHtml::Button(Yii::t('app','Save'),array('submit'=>array('portalThemes/update'),'class'=>'btn btn-danger'));
                }
                else
                {
                    echo CHtml::Button(Yii::t('app','Save'),array('submit'=>array('portalThemes/create'),'class'=>'btn btn-danger'));
                }
?>
	</div>
            </td>
        </tr>
    </table>
    
    </div>
    
    

<span id="success-EventsType_colour_code"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
<?php $this->endWidget(); ?>

</div><!-- form -->