<style type="text/css">
.formCon input[type="text"], input[type="password"], textArea, select {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #C2CFD8;
    border-radius: 2px;
    box-shadow: -1px 1px 2px #D5DBE0 inset;
    padding: 6px 3px;
    width: 175px !important;
}

.select-style select{ width:135% !important}

.formCon select{background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #C2CFD8;
    border-radius: 2px;
    box-shadow: -1px 1px 2px #D5DBE0 inset;
    padding: 6px 3px;
    width: 78% !important;}
	
	.formCon input[type="text"] {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #C2CFD8;
    border-radius: 2px;
    box-shadow: -1px 1px 2px #D5DBE0 inset;
    padding: 6px 3px;
    width: 175px !important;
}
</style>


<div class="captionWrapper">
    <ul>
        <li><h2 class="cur"><?php if(isset($_REQUEST['id'])){ echo CHtml::link(Yii::t('app','Vendor Details'),array('students/update','id'=>$_REQUEST['id'],'status'=>0)); } else{ echo Yii::t('app','Vendor Details'); } ?></h2></li>
        <li><h2 ><?php if(isset($_REQUEST['id'])){ echo CHtml::link(Yii::t('app','Product Details'),array('productDetails/create','id'=>$_REQUEST['id'])); } else{ echo Yii::t('app','Product Details'); } ?></h2></li>
    </ul>
</div>


<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'vendor-details-form',
'enableAjaxValidation'=>false,
)); ?>

	<?php 
	if($form->errorSummary($model)){
	?>
        <div class="errorSummary"><?php echo Yii::t('app','Input Error'); ?><br />
        	<span><?php echo Yii::t('app','Please fix the following error(s).'); ?></span>
        </div>
    <?php 
	}
	?>
    <p class="note"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required.'); ?></p>
    
    <div class="formCon">
        <div class="formConInner">
            
            <h3><?php echo Yii::t('app','Vendor Details'); ?> </h3>
            <div class="row">
            	<div class="col-md-4">bjhb</div>
                <div class="col-md-4">bjhb</div>
                <div class="col-md-4">bjhb</div>
            </div>
                        
			<div class="txtfld-col">
				<?php echo $form->labelEx($model,'first_name'); ?>
				<?php echo $form->textField($model,'first_name',array('size'=>30,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'first_name'); ?>
			</div>
            
			<div class="txtfld-col">
				<?php echo $form->labelEx($model,'last_name'); ?>
				<?php echo $form->textField($model,'last_name',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'last_name'); ?>
			</div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'address_1'); ?>
				<?php echo $form->textField($model,'address_1',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'address_1'); ?>
			</div>
            
             <div class="txtfld-col">
				<?php echo $form->labelEx($model,'address_2'); ?>
				<?php echo $form->textField($model,'address_2',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'address_2'); ?>
			</div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'city'); ?>
				<?php echo $form->textField($model,'city',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'city'); ?>
			</div>
            
             <div class="txtfld-col">
				<?php echo $form->labelEx($model,'state'); ?>
				<?php echo $form->textField($model,'state',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'state'); ?>
			</div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'country_id'); ?>
				<?php echo $form->dropDownList($model,'country_id',CHtml::listData(Countries::model()->findAll(),'id','name'),array(
                        'style'=>'width:140px;','empty'=>Yii::t('app','Select Country')
                        )); ?>
                <?php echo $form->error($model,'country_id'); ?>
			</div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'email'); ?>
			</div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'phone'); ?>
				<?php echo $form->textField($model,'phone',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'phone'); ?>
			</div>
          
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'currency'); ?>
				<?php 
                    $currency = Configurations::model()->findByPk(5);
                    $data="";
                    if($currency->config_value!=NULL)
                    {
                        $data= $currency->config_value;
                    }
                    $criteria= new CDbCriteria;
                    $criteria->condition= 'code<>:val';
                    $criteria->params= array(':val'=>"");
                    $list= CHtml::listData(Currency::model()->findAll($criteria),'code','code');
                    echo CHtml::dropDownList('currency',$data,$list);
					?>
                    
                   
                    
                   
                <?php echo $form->error($model,'currency'); ?>
			</div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'company_name'); ?>
				<?php echo $form->textField($model,'company_name',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'company_name'); ?>
			</div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'vat_number'); ?>
				<?php echo $form->textField($model,'vat_number',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'vat_number'); ?>
			</div>
            
             <div class="txtfld-col">
				<?php echo $form->labelEx($model,'cst_number'); ?>
				<?php echo $form->textField($model,'cst_number',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'cst_number'); ?>
			</div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'office_phone'); ?>
				<?php echo $form->textField($model,'office_phone',array('size'=>25,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'office_phone'); ?>
			</div>
            
			<div class="clear"></div>
            
        </div>
    </div>
    
    <div style="padding:0px 0 0 0px; text-align:left">
    	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Product Details') : Yii::t('app','Save'),array('id'=>'submit_button_form','class'=>'formbut')); ?>
    </div>
   
<?php $this->endWidget(); ?>
