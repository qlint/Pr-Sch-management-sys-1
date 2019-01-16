<?php
$this->breadcrumbs=array( 
 Yii::t('app','Create Class Timings')
);
?>
<div style="background:#FFF;min-height: 1000px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top">
                <div style="padding:20px;">
                	<div class="clear"></div>
                    <div class="emp_right_contner">
                        <div class="emp_tabwrapper">
                        	<?php $this->renderPartial('/batches/tab');?>
                        	<div class="clear"></div>
                            <div class="emp_cntntbx" style="padding-top:10px;">
                                <div  align="right" style="position:relative;" >
                                    <div class="edit_bttns">
                                        <ul>
                                        <li>
                                        <?php echo CHtml::link('<span>'.Yii::t('app','Time Table').'</span>', array('/courses/weekdays/timetable','id'=>$_REQUEST['id']),array('class'=>'addbttn last'));?>
                                        </li>
                                        <li>
                                        <?php echo CHtml::link('<span>'.Yii::t('app','Class Timings').'</span>', array('/courses/classTiming','id'=>$_REQUEST['id']),array('class'=>'addbttn last'));?>
                                        </li>
                                        
                                        </ul>
                                        <div class="clear"></div>
                                    </div> <!-- END div class="edit_bttns" -->
								</div>
                                <div style="width:100%">
                                    <div>
                                        <h3><?php echo Yii::t('app','Create Class Timing');?></h3>
									</div>
                                    
                                    <?php $this->renderPartial('_form', array('model'=>$model));?>
                                    
                            	</div>
                            </div> <!-- END div class="emp_cntntbx" -->
                        </div> <!-- END div class="emp_tabwrapper" -->
                    </div> <!-- END div class="emp_right_contner" -->
                </div>
            </td>
        </tr>
    </table>
</div>