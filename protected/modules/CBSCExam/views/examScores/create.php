<?php
$this->breadcrumbs=array(
	Yii::t('app','Exams')=>array('/examination'),
	//$model->id=>array('view','id'=>$model->id),
	Yii::t('app','ExamScores'),
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	 <?php $this->renderPartial('examination.views.default.left_side');?>      
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <!--<div class="searchbx_area">
                <div class="searchbx_cntnt">
                <ul>
                <li><a href="#"><img src="images/search_icon.png" width="46" height="43" /></a></li>
                <li><input class="textfieldcntnt"  name="" type="text" /></li>
                </ul>
                </div>
                
                </div>-->
                <div class="page-header"> 
                	<div class="header-box">
                		<div class="header-box-one"> <h1><?php echo Yii::t('app','Exams');?></h1></div>
                        <div class="header-box-two">
                             <div class="back-btn">
                                 <?php 
								 $exam_id	=	$_GET['examid'];
								 $exam=CbscExams::model()->findByPk($exam_id);
								 echo CHtml::link("<span>".Yii::t("app", "Back")."</span>", array('/CBSCExam/exams/create','exam_group_id'=>$exam->exam_group_id,'id'=>$_REQUEST['id']), array('class'=>'back-bttn fa ')); ?>                                
                             </div>
                        </div>
                	</div>
               	
                </div> 
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
						<?php $this->renderPartial('/default/tab');?>
                        <div class="clear"></div>
                        <div class="emp_cntntbx" style="padding-top:0px;">
                            <?php echo $this->renderPartial('_form', array('model'=>$model)); ?> 
                                               
                        </div> <!-- END div class="emp_cntntbx" -->
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" -->
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
    </tr>
</table>