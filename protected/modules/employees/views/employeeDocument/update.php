<?php
$this->breadcrumbs=array(
	Yii::t('app','Teacher')=>array('employees/view','id'=>$employee_id),
	$model->title,
	Yii::t('app','Update'),
);

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('application.modules.employees.views.employees.profileleft');?>
        </td>
        <td valign="top">
        	<div class="cont_right formWrapper">
            	<div  class="page-header">
                     <h1>
                        <?php 
                        $employee = Employees::model()->findByAttributes(array('id'=>$employee_id));
                        echo Yii::t('app','Teacher Profile :');?> <?php echo Employees::model()->getTeachername($employee->id); ?><br />
                    </h1>
				</div>                    
				<div class="clear"></div>
                <div class="emp_right_contner">
					<div class="emp_tabwrapper">
						
						<?php $this->renderPartial('application.modules.employees.views.employees.tab');?>
                        
                        <div class="clear"></div>
                        
                        <div class="emp_cntntbx">
                        	<div class="edit_bttns last">
                                <ul>
                                    <li>
                                        <?php echo CHtml::link('<span>'.Yii::t('app','Document List').'</span>', array('employees/document', 'id'=>$employee_id),array('class'=>' edit ')); ?>
                                    </li>
                                </ul>
                        	</div> <!-- END div class="edit_bttns last" -->
                        	<?php echo $this->renderPartial('_formupdate', array('model'=>$model)); ?>
                        </div> <!-- END div class="emp_cntntbx" -->
					</div> <!-- END div class="emp_tabwrapper" -->			
                </div> <!-- END div class="emp_right_contner" -->
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
	</tr>
</table>




