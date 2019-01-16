<?php
$this->breadcrumbs=array( 
	Yii::t('app','Timetable')=>array('/timetable'),
	Yii::t('app','Update Class Timings')
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>        
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
               <h1><?php echo Yii::t('app','Update Class Timings'); ?></h1>            
            	<div class="clear"></div>
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
                    	<?php $this->renderPartial('/default/tab');?>
                    	<div class="clear"></div>
                        <div class="emp_cntntbx" style="padding-top:10px;">
                            <div style="width:100%">
                                
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li> <?php echo CHtml::link('<span>'.Yii::t('app','Class Timings').'</span>', array('/timetable/classTiming','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn'));?></li>                                    
</ul>
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