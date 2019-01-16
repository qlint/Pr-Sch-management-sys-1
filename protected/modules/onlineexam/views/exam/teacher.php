<?php
$this->breadcrumbs=array(
	 Yii::t('app','Online Examination'),
);
?>
<?php $this->renderPartial('/default/teacherleft');?>    
<div class="pageheader">
    <h2><i class="fa fa-pencil"></i><?php echo  Yii::t('app','Online Examination') .'<span>'.Yii::t('app','create online exams here').'</span>'?></h2>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
            <li class="active"><?php echo Yii::t('app','Online Examination');?></li>
        </ol>
    </div>
</div>

<div class="contentpanel">
    <div class="panel-heading" style="position:relative;">
        <div class="clear"></div>
        <h3 class="panel-title"><?php 
        if(Yii::app()->controller->action->id=='update')
        {
            echo Yii::t('app','Update Exam');
        }
        else
            echo Yii::t('app','Create Exam'); ?> </h3>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
            <?php
                echo CHtml::link(Yii::t('app','Back'),array('/onlineexam/exam/index','bid'=>$_REQUEST['bid']),array('class'=>'btn btn-primary'));
            ?>
            </li>
            </ul>
        </div>
        </div>
    </div>
    <div class="people-item">                
        <?php $this->renderPartial('exam_form', array('model'=>$model));?>      
    </div>
</div>

