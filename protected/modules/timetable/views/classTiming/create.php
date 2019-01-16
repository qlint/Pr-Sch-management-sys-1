<?php
$this->breadcrumbs=array(
	Yii::t('app','Timetable')=>array('/timetable'),
	Yii::t('app','Create Class Timings')
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>        
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
            	<div class="clear"></div>
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
                    	<?php $this->renderPartial('/default/tab');?>
                    	<div class="clear"></div>
                        <div class="emp_cntntbx" style="padding-top:10px;">
                            <div style="width:100%">
                                <div class="pdf-box">
                                    <div class="box-one">
                                        <div class="sub-header">
                                            <h3><?php echo Yii::t('app','Create Class Timings'); ?></h3>
                                        </div>
                                    </div>
                                    <div class="box-two">
                                        <div class="pdf-div">
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Class Timings').'</span>', array('/timetable/classTiming','id'=>$_REQUEST['id']),array('class'=>'formbut-n'));?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php $this->renderPartial('_form', array('model'=>$model));?>
                                    
                            </div>
                        </div> <!-- END div class="emp_cntntbx" -->
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" -->
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
    </tr>
</table>