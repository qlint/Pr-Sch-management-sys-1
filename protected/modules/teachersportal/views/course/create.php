<?php $this->renderPartial('/default/leftside');?> 
 <div class="pageheader">
      <h2><i class="fa fa-list-alt"></i> <?php echo Yii::t('app', 'My Course');?> <span><?php echo Yii::t('app', 'View courses here');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
         <li class="active"><?php echo Yii::t('app', 'Course');?></li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
<div class="col-sm-9 col-lg-12">
<div class="panel panel-default">
         <?php $this->renderPartial('changebatch');?>
<div class="panel-body">
 
            <?php $this->renderPartial('batch');?>
            <div class="edit_bttns" style="top:100px; right:25px">
                <ul>
                    <li>
                    <?php //echo CHtml::link('<span>'.Yii::t('teachersportal','My Courses').'</span>', array('/teachersportal/course'),array('class'=>'addbttn last'));?>
                    </li>
                </ul>
            </div>
            
            <!-- Examination Area -->
          <div style="padding:20px;">


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'course',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with');?> <span class="required">*</span> <?php echo Yii::t('app', 'are required.');?></p>
<?php
if(isset($_REQUEST['ids']))
{
	$ids=$_REQUEST['ids'];
}
?>
	<?php echo $form->errorSummary($model); ?>
    
    <div class="form-group">
              <label class="col-sm-3 control-label">
			  <?php echo Yii::t('app', 'Name');?> <span class="required">*</span></label>
              <div class="col-sm-6">
               <?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
        <?php echo $form->hiddenField($model,'batch_id'); ?>
		
              </div>
            </div>
            
            
            <div class="form-group">
           <label class="col-sm-3 control-label">
            <?php echo Yii::t('app', 'Exam Type');?> <span class="required">*</span></label>
             
              <div class="col-sm-6">
                <?php echo $form->dropDownList($model,'exam_type',array('Marks'=>Yii::t('app', 'Marks'),'Grades'=>Yii::t('app', 'Grades'),'Marks And Grades'=>Yii::t('app', 'Marks And Grades')),array('class'=>'form-control mb15')); ?>
		<?php echo $form->error($model,'exam_type'); ?>
        
              </div>
            </div>
            
            
            
            <div class="form-group">
              <label class="col-sm-3 control-label"><?php echo Yii::t('app', 'Default Input');?></label>
              <div class="col-sm-6">
               <?php echo $form->checkBox($model,'is_published'); ?>
         <?php echo $form->labelEx($model,Yii::t('examination','is_published')); ?>
         <?php echo $form->error($model,'is_published'); ?>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label"></label>
              <div class="col-sm-6">
                <?php echo $form->checkBox($model,'result_published'); ?>
         <?php echo $form->labelEx($model,Yii::t('examination','result_published')); ?>
         <?php echo $form->error($model,'result_published'); ?>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label">
              	<?php echo Yii::t('app', 'Exam Date');?><span class="required">*</span>
              </label>
              <div class="col-sm-6">
                <?php
                 $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
                        if($settings!=NULL)
                        {
                            $date=$settings->dateformat;
							if($model->exam_date)
							{
								$model->exam_date=date($settings->displaydate,strtotime($model->exam_date));
							}
                        }
                        else
                        $date = 'dd-mm-yy';	
		
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				//'name'=>'Students[admission_date]',
				'model'=>$model,
				'attribute'=>'exam_date',
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>$date,
					'changeMonth'=> true,
					'changeYear'=>true,
					'yearRange'=>'1900:'.(date('Y')+2),
				),
				'htmlOptions'=>array(
					'class'=>'form-control'
				),
			));
?>
<?php echo $form->error($model,'exam_date'); ?>
              </div>
            </div>
  
	
    
   <!-- <div class="row">
		<?php //echo $form->checkBox($model,'is_published'); ?>
         <?php //echo $form->labelEx($model,Yii::t('examination','is_published')); ?>
         <?php //echo $form->error($model,'is_published'); ?>
	</div>-->
    
    <div class="form-group">
              <label class="col-sm-3 control-label"></label>
              <div class="col-sm-6">
              <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),array('class'=>'btn btn-danger')); ?>
                
              </div>
            </div>
   
    

<?php $this->endWidget(); ?>

</div>
</div>
</div>
</div>
</div>
</div><!-- form -->





