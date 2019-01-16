<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('index'),
	Yii::t('app','Salary Details'),
);
$staff = Staff::model()->findByAttributes(array('id'=>$_REQUEST['id']))

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
     <?php $this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
        <h1><?php echo Yii::t('app','Add Salary Details :').' '.$staff->fullname;?></h1>
        <div class="formCon" >
            <div class="formConInner">
                <div class="form">
                	<?php $form=$this->beginWidget('CActiveForm', array(
								'id'=>'staff-form',
								'enableAjaxValidation'=>false,
							)); ?>
                        <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'basic_pay'); ?>
                                <?php echo $form->textField($model,'basic_pay',array('size'=>10,'maxlength'=>6)); ?>
                                <?php echo $form->error($model,'basic_pay'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'TDS'); ?>					 
                                <?php  echo $form->textField($model,'TDS',array('size'=>10,'maxlength'=>5)); ?>
                                <?php echo $form->error($model,Yii::t('app','TDS')); ?>
                            </div>
                                <div class="text-fild-block inputstyle">
                                <label>&nbsp;</label><br />
                                <?php echo $form->radioButtonList($model, 'tds_type',array(0 =>Yii::t('app','Amount'), 1 =>Yii::t('app','Percentage')),
                                array('labelOptions'=>array('style'=>'display:inline'), 'separator'=>'  ',) );?>
                                </div>
                        </div>
                        
                         <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'ESI'); ?>
                                <?php echo $form->textField($model,'ESI',array('size'=>10,'maxlength'=>5)); ?>
                                <?php echo $form->error($model,'ESI'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'EPF'); ?>
                                <?php echo $form->textField($model,'EPF',array('size'=>10,'maxlength'=>5)); ?>
                                <?php echo $form->error($model,'EPF'); ?>
                            </div>
                        </div>
                        <div class="row buttons">
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
                        </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
    </td>
  </tr>
</table>
 

