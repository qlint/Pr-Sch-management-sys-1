<style type="text/css">

.loginboxWrapper a{ color:#fff !important;}
.chosen-container a{ color:#444 !important;} 
.chosen-container-single-nosearch{
	width:220px !important;
}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Registration'),
);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/online_register.css" />
    <link rel="icon" type="image/ico" href="<?php echo Yii::app()->request->baseUrl; ?>/uploadedfiles/school_logo/favicon.ico"/>
    <title><?php $college=Configurations::model()->findByPk(1); ?><?php echo $college->config_value ; ?></title>
</head>
<?php /*?><h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p><?php */?>
<?php $logo=Logo::model()->findAll();?>
        	
<div class="loginboxWrapper">
<div class="logo">            
			<?php 
			if($logo!=NULL)
			{
				echo '<img src="'.Yii::app()->request->baseUrl.'/uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" border="0" height="55" />';
			}
			?>
            </div>
	
    <div class="hed"><h1><?php echo Yii::t('app','Application Status'); ?></h1></div>
    <div class="cont_right formWrapper">
	<?php
    
    $admin = User::model()->findByPk(1);
    $school = Configurations::model()->findByPk(1);
    //if($id)
    //{
    ?>
    <?php
	//$profile->status = 0;
	if($profile->status == 0) // Pending
	{
		$status = Yii::t('app','Your application is under review');
		$bg = 'confirm_clock';
		$icon = 'status_clock';
	}
	elseif($profile->status == 1) // Approve
	{
		$status = Yii::t('app','Your application is approved');
		$bg = 'status_top';
		$icon = 'status_tick';
	}
	elseif($profile->status == -1) // Disapprove
	{
		$status = Yii::t('app','Your application is disapproved');
		$bg = 'confirm_cross';
		$icon = 'status_cross';
	}
	elseif($profile->status == -3) // Waiting List
	{
		$status = Yii::t('app','You have been placed on the waiting list.').'<br>'.Yii::t('app','Your priority number is').' '.$waitinglist_details->priority;
		$bg = 'confirm_cross';
		$icon = 'status_cross';
	}
	?>
   
    <div class="confirm_bx">
        <div class="<?php echo $bg; ?>"><?php echo Yii::t('app','Application Status'); ?></div> 
		<div class="status_botom">
		
            <br />
					   
            
<?php 
	$token		= isset($_GET['token'])?$_GET['token']:NULL;
	$student_id	= $this->decryptToken($token);
	
?>
<?php $time = time(); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'center-document-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	//'action'=>CController::createUrl('/onlineadmission/registration/missingDocEdit',array('document_id'=>$model->id,'token'=>$_REQUEST['token']))
)); ?>
<div class="panel panel-default">
	<div class="panel-heading" style="position:relative;">
    	
        <div class="col-sm-8"><h3 class="panel-title"></h3></div>
        <div class="col-sm-4">
        <?php 
			if(Yii::app()->user->id!=NULL)
			{
				echo CHtml::link(Yii::t('app','View Profile'), array('registration/status','id'=>$student_id,'from'=>'parent'),array('class'=>' btn btn-success pull-right')); 
			}
			else
			{
        	 	echo CHtml::link(Yii::t('app','View Profile'), array('registration/status'),array('class'=>' btn btn-success pull-right')); 
			}
		?>	
        </div>
        <div class="clearfix "></div>
        	
            
  </div>
<div class="panel-body">


	<?php /*?><?php 
		if($form->errorSummary($model)){
	?>
        <div class="errorSummary"><?php echo 'Input Error'; ?><br />
        	<span><?php echo 'Please fix the following error(s).'; ?></span>
        </div>
    <?php 
		}
		//var_dump($model->attributes);exit;
	?><?php */?>
    
  	<p class="note" style="float:left"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required.'); ?></p>
    
    
    <?php
	Yii::app()->clientScript->registerScript(
	   'myHideEffect',
	   '$(".error").animate({opacity: 1.0}, 3000).fadeOut("slow");',
	   CClientScript::POS_READY
	);
	if(Yii::app()->user->hasFlash('errorMessage')): 
	?>
        <div class="error1" style="color:#C00; padding-left:200px; ">
            <?php echo Yii::app()->user->getFlash('errorMessage'); ?>
        </div>
	<?php
	endif;	
	?>

    <div style="clear:left;">
        <div  id="innerDiv">
        	<table width="95%" border="0" cellspacing="0" cellpadding="0" id="documentTable">
            	<tr>
                	<th width="40%" colspan="2"><?php echo $form->labelEx($model,Yii::t('app','Document Name').'*'); ?></th>
                   
                    <?php /*?><td>&nbsp;<?php //echo $form->labelEx($model,Yii::t('students','file')); ?></td><?php */?>
                </tr>
              
                <tr>
                	<td>
                    <div  ></a></div>
						<?php //echo $form->textField($model,'title',array('size'=>25,'maxlength'=>225,'class'=>'form-control')); ?>                        
                        <?php
						
							$criteria = new CDbCriteria;
		 $criteria->join = 'LEFT JOIN student_document osd ON osd.title = t.id and osd.student_id = '.$student_id.'';
		 
		  $criteria->addCondition('osd.title IS NULL');
							echo $form->dropDownList($model,'title',CHtml::listData(StudentDocumentList::model()->findAll($criteria), 'id', 'name'),array('prompt' => Yii::t('app','Select Document Type'),'class'=>'form-control mb15')); ?>
                         
                        <?php echo $form->error($model,'title'); ?>
                    </td>
                    
                    <td>
						<?php echo $form->fileField($model,'file'); ?>
                        <?php echo $form->error($model,'file'); ?>
                        
                    </td>
                </tr>
            </table>
			
           
        
            <div class="row" id="file_type">
                <?php //echo $form->labelEx($model,'file_type'); ?>
                <?php echo $form->hiddenField($model,'file_type'); ?>
                <?php echo $form->error($model,'file_type'); ?>
            </div>
        
            <div class="row" id="created_at">
                <?php //echo $form->labelEx($model,'created_at'); ?>
                <?php echo $form->hiddenField($model,'created_at'); ?>
                <?php echo $form->error($model,'created_at'); ?>
            </div>
        </div>
    </div>
    



</div>
<div class="panel-footer">
             
             
        <?php //echo CHtml::button('Add Another', array('class'=>'formbut','id'=>'addAnother','onclick'=>'addRow("documentTable");')); ?>
        <?php echo CHtml::submitButton(Yii::t('app','Update'),array('class'=>'btn btn-orange')); ?>
              
                          </div><!-- form --><?php $this->endWidget(); ?>
           
      </div> <!-- END div class="status_botom" -->
    </div> <!-- END div class="confirm_bx" -->
    
    
    
    
    	
        </div> <!-- END div class="cont_right formWrapper" -->
    <div class="clear"></div>
</div> <!-- END div class="loginboxWrapper" -->
