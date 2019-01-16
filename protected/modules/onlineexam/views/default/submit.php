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
<?php
$student	=	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
?>    
<div class="contentpanel">
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo Yii::t('app','Online Exam Result');?></h3>
    </div>
    <div class="people-item">
		<div class="row">
        	<div class="col-md-2"></div>
        		<div class="col-md-8">
                <div class="row">
                	<div class="col-md-2"><p class="image-scr"><img src="images/exam-score.png" class="img-responsive" /></p></div>
                    <div class="col-md-10 bordr-left-Exmscor-bg">
                    	<div class="bordr-left-Exmscor">
                            <div class="your-Exm-resul-p">
                             <p><?php echo Yii::t('app','Hello,').' ';?><span><?php echo $student->first_name.' '.$student->middle_name.' '.$student->last_name;?></span></p>
                            </div>
                            <div class="your-Exm-resul">
                             <p><span><?php echo Yii::t('app','Your Exam Was Finished Successfully..');?></span></p>
                              <h2><?php echo Yii::t('app','Thank You..');?></h2>
                            </div>    
                            <div class="onlie-btn">
                                 <?php echo CHtml::link('<span>'.Yii::t('app','Online Exams').'</span>',array('/studentportal/default/exams'),array('class'=>'online-Exm-btn'));?>
                            </div>
                        </div>
                   	</div>
                </div>	                
            </div>
       <div class="col-md-2"></div>
        </div>
        </div> 
    </div> 
    <div class="clear"></div>
</div>
