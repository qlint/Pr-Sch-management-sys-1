<?php $this->renderPartial('studentleft');?>
<div class="pageheader">
	<div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Online Exam Result'); ?><span><?php echo Yii::t('app','View your Online Exam Result here'); ?></span></h2>
    </div>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
            <ol class="breadcrumb">
            <!--<li><a href="index.html">Home</a></li>-->
            
            <li class="active"><?php echo Yii::t('app','Online Exam Result'); ?></li>
        </ol>
    </div>
    <div class="clearfix"></div>
</div>
  
<div class="contentpanel">
    <div class="people-item">
        <div class="row">
        	<div class="col-md-12">
        		<div class="tocken-error-bg">
            		<div class="tocken-icon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
                    <h2><?php echo Yii::t('app','Error'); ?></h2>
                    <p><?php echo Yii::t('app','You Are Not Authorised To Perform This Action..Invalid Request'); ?></p>
            	</div>
            </div>
        </div>
	</div> 
</div>

