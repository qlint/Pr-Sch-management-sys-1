<?php
$this->breadcrumbs=array(
	Yii::t('app','Online Examination')=>array('/onlineexam'),
	Yii::t('app', 'New')
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">        
        	<?php $this->renderPartial('/default/admin_left');?>        
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Update Online Exam');?></h1>
                <div class="edit_bttns " style="top:16px; right:16px;">
                    <ul>
                    	<li><?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>', array('/onlineexam/exams/'),array('class'=>'addbttn last')); ?></li>
                    </ul>
            	</div>
                <div class="formCon">
                    <div class="formConInner">
                   		
                        <?php $this->renderPartial('exam_form', array('model'=>$model));?> 
                                          
                    </div>
                </div>                
            </div>            
        </td>
    </tr>
</table>