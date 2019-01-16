<?php 
$users = array("admin","student","parent","teacher");
$subdomain = explode('.com' , $_SERVER['SERVER_NAME']);
if(in_array(Yii::app()->user->name,$users) && $subdomain[0]=='tryopenschool'){
	throw new CHttpException(404,Yii::t('app','You are not authorized to view this page.'));
}
else{
	$this->pageTitle=Yii::app()->name . ' - '.Yii::t('app',"Change Password");
	$this->breadcrumbs=array(
		Yii::t('app',"Profile") => array('/user/profile'),
		Yii::t('app',"Change Password"),
	);

	?>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'changepassword-form',
		'enableAjaxValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>
	<?php 
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role					
		foreach($roles as $role)
		{
		   if(sizeof($roles)==1 and $role->name == 'parent')
		   {
			 echo $this->renderPartial('application.modules.parentportal.views.default.leftside');
		   }
		   if(sizeof($roles)==1 and $role->name == 'student')
		   { 
			 echo $this->renderPartial('application.modules.studentportal.views.default.leftside');
		   }
		   if(sizeof($roles)==1 and $role->name == 'teacher')
		   { 
			 echo $this->renderPartial('application.modules.teachersportal.views.default.leftside');
		   }
		}

	 ?>
    <div class="pageheader">
      <h2><i class="fa fa-gear"></i> <?php echo Yii::t('app','Settings'); ?> <span><?php echo Yii::t('app','Your settings here'); ?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','Settings'); ?></li>
        </ol>
      </div>
    </div>
    <div class="contentpanel">
    <div class="panel-heading">
    <h3 class="panel-title"><?php echo Yii::t('app','Change password'); ?></h3>
    </div>
		
		
<div class="people-item">
    <div class="opnsl_headerBox">
        <div class="opnsl_actn_box"> </div>
        <div class="opnsl_actn_box">
            <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','View').'</span>',array('/user/accountProfile'),array('class'=>'btn btn-primary'));?></div>
            <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','Edit Profile').'</span>',array('/user/accountProfile/edit'),array('class'=>'btn btn-primary'));?></div>
            <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','Change Preferences').'</span>',array('/user/preferences/edit'),array('class'=>'btn btn-primary'));?></div>
        </div>
    </div>		
	<div class="cont_right formWrapper usertable">
    <div class="row">
	<div class="col-md-6">
    <div class="row settng_block">
		<p class="form_required"><?php echo Yii::t('app','Fields with');?><span>*</span><?php echo Yii::t('app','are required.');?></p>
		<?php //echo $form->errorSummary($model); ?>

        
		<div class="col-md-12">
			<div class="form-group">
                
                <?php echo $form->labelEx($model,Yii::t('app','oldPassword'),array('class'=>'sttngs_label')); ?>
                
                <?php echo $form->passwordField($model,'oldPassword',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'oldPassword'); ?>
        	</div>
		</div>
        <div class="col-md-12">
        <div class="form-group">
       
        <?php echo $form->labelEx($model,Yii::t('app','New password').'<span>*</span>',array('class'=>'sttngs_label')); ?>
       
        <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'password'); ?>
        <p class="hint"><?php echo Yii::t('app',"Minimal password length 4 symbols."); ?></p>
        </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo $form->labelEx($model,Yii::t('app','verifyPassword'),array('class'=>'sttngs_label')); ?>
                <?php echo $form->passwordField($model,'verifyPassword',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'verifyPassword'); ?></div>
            </div>
        <div class="col-md-12">
            <div class="form-group">
				<?php echo CHtml::submitButton(Yii::t('app',"Save"),array('class'=>'btn opnsl_fllBtn')); ?>
            </div>
        </div>			

	</div>
	</div>
	</div>
    </div>
    </div>

		
		
	<?php $this->endWidget(); ?>
	<!-- form -->
<?php } ?>