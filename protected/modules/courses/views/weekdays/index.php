<!--<script language="javascript">
function getid()
{
var id= document.getElementById('drop').value;
window.location = "index.php?r=weekdays/index&id="+id;
}
</script>-->
<?php if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){  ?>
	<style>
    .container{ background:#fff;}
    </style>
<?php } ?>
<style>

.flash-success {
    color: #f30;
    font-size: 12px;
    margin-bottom: 10px;
}
</style>
<?php
$batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
$this->breadcrumbs=array(
	Yii::t('Courses','Courses')=>array('/courses'),
	//Yii::t('Batch',$batch->name)=>array('/courses/batches/batchstudents','id'=>$_REQUEST['id']),
	Yii::t('app','Weekdays'),
);
?>

<?php
$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
if(Yii::app()->user->year)
{
	$year = Yii::app()->user->year;
}
else
{
	$year = $current_academic_yr->config_value;
}
$disabled = array();
$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
if($year!=$current_academic_yr->config_value and $is_insert->settings_value==0)
{
	$disabled['disabled']='disabled';
}

?>
<?php 
if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL) 
{
?>
<div style=" background-color:#fff;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top">
                <div  class="full-formWrapper">

                    
                    
                    <div class="clear"></div>
                    <div class="emp_right_contner">
                        <div class="emp_tabwrapper">
							<?php
                            $this->renderPartial('/batches/tab');
                            ?>
                            
                            <div class="clear"></div>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
                                        <ul>
                                            <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Time Table').'</span>', array('weekdays/timetable','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn'));?>
                                            
                                            </li>
                                        </ul>
                                        </div>
                                        </div>


                                
                                
                                <div>                                
                                    <div style="padding-top:10px; font-size:14px; font-weight:bold; position:relative;">
                                        <h3><?php echo Yii::t('app','Week Days'); ?></h3>
                                        <?php
                                        Yii::app()->clientScript->registerScript(
                                        'myHideEffect',
                                        '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                                        CClientScript::POS_READY
                                        );
                                        ?>
                                        
                                        <?php if(Yii::app()->user->hasFlash('notification')):?>
                                            <div class="flash-success">
                                            	<?php echo Yii::app()->user->getFlash('notification'); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php
										if(($year!=$current_academic_yr->config_value and $is_insert->settings_value==0))
										{
										?>											
                                        <span style="color: #008000;font-size: 9px;position: absolute;top: 46px">
											<?php echo Yii::t('app','You are not viewing the current active year. To set the weekdays, enable Insert option in Previous Academic Year Settings.');?>
										</span>
                                        <?php
										}
										?>
                                        
                                        <?php                                         
                                        $models = Batches::model()->findAll("is_deleted=:x", array(':x'=>'0'));
                                        $data = array();
                                        $data['NULL'] = 'common';
                                        foreach ($models as $model_1)
                                        {
											$posts=Batches::model()->findByPk($model_1->id);
											$data[$model_1->id] = $model_1->name;
                                        }
                                        ?>
                                        <div>
                                        <!--<h3>Set Weekdays For :&nbsp;-->
                                        <?php
                                        //echo CHtml::dropDownList('mydropdownlist','mydropdownlist',$data,array('onchange'=>'getid();','id'=>'drop','options'=>array($_REQUEST['id']=>array('selected'=>true))));
                                        ?> <!--</h3>-->
                                        <?php $form=$this->beginWidget('CActiveForm', array('id'=>'courses-form','enableAjaxValidation'=>false,)); ?>
                                        <?php
                                        if($_REQUEST['id']!='NULL')
                                        {
                                        
											$batch = $model->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
											if(count($batch)==0)
											{
												$batch = $model->findAll("batch_id IS NULL");
											}
											?>
											<div>
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td width="4%">
														<?php
                                                       
                                                        if($batch[0]['weekday']==1)
                                                        	echo $form->checkBox($model,'sunday',array_merge($disabled,array('value'=>'1','checked'=>'checked','class'=>'styled')));
                                                        else
                                                        	echo $form->checkBox($model,'sunday',array_merge($disabled,array('value'=>'1','class'=>'styled'))); 
														?>
														</td>
                                                        <td width="85%" ><?php echo Yii::t('app','Sunday');?></td>
                                                        <td width="11%"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
														<?php
														
                                                        if($batch[1]['weekday']==2)
                                                        	echo $form->checkBox($model,'monday',array_merge($disabled,array('value'=>'2','checked'=>'checked','class'=>'styled')));
                                                        else
                                                        	echo $form->checkBox($model,'monday',array_merge($disabled,array('value'=>'2','class'=>'styled'))); 
														?>
                                                        </td>
                                                        <td ><?php echo Yii::t('app','Monday');?></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
														<?php
                                                        if($batch[2]['weekday']==3)
                                                        	echo $form->checkBox($model,'tuesday',array_merge($disabled,array('value'=>'3','checked'=>'checked','class'=>'styled'))); 
                                                        else
                                                        	echo $form->checkBox($model,'tuesday',array_merge($disabled,array('value'=>'3','class'=>'styled'))); 
                                                        ?>
                                                        </td>
                                                        <td ><?php echo Yii::t('app','Tuesday');?></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                        <?php
                                                        if($batch[3]['weekday']==4)
                                                        echo $form->checkBox($model,'wednesday',array_merge($disabled,array('value'=>'4','checked'=>'checked','class'=>'styled')));
                                                        else
                                                        echo $form->checkBox($model,'wednesday',array_merge($disabled,array('value'=>'4','class'=>'styled')));
                                                        ?>
                                                        </td>
                                                        <td ><?php echo Yii::t('app','Wednesday');?></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                        <?php
                                                        if($batch[4]['weekday']==5)
                                                            echo $form->checkBox($model,'thursday',array_merge($disabled,array('value'=>'5','checked'=>'checked','class'=>'styled')));
                                                        else
                                                            echo $form->checkBox($model,'thursday',array_merge($disabled,array('value'=>'5','class'=>'styled')));
                                                        ?>
                                                        </td>
                                                        <td ><?php echo Yii::t('app','Thursday');?></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
														<?php
                                                        if($batch[5]['weekday']==6)
                                                        	echo $form->checkBox($model,'friday',array_merge($disabled,array('value'=>'6','checked'=>'checked','class'=>'styled')));
                                                        else
                                                        	echo $form->checkBox($model,'friday',array_merge($disabled,array('value'=>'6','class'=>'styled')));
                                                        ?>
                                                        </td>
                                                        <td><?php echo Yii::t('app','Friday');?></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                        <?php
                                                        if($batch[6]['weekday']==7)
                                                        	echo $form->checkBox($model,'saturday',array_merge($disabled,array('value'=>'7','checked'=>'checked','class'=>'styled')));
                                                        else
                                                        	echo $form->checkBox($model,'saturday',array_merge($disabled,array('value'=>'7','class'=>'styled')));
                                                        ?>
                                                        </td>
                                                        <td><?php echo Yii::t('app','Saturday');?></td>
                                                        <td></td>
                                                    </tr>
                                                </table>
											</div>
											<br />
                                        <?php    
                                        } // END if($_REQUEST['id']!='NULL')
                                        ?> 
                                        </div>
                                       
                                        <?php 
										if(($year==$current_academic_yr->config_value) or ($year!=$current_academic_yr->config_value and $is_insert->settings_value!=0))
										{
											echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('class'=>'formbut')); 
										}
										?>          
                                        <?php $this->endWidget(); ?>              
                                    </div>
                                </div>
                            </div> <!-- END div class="emp_cntntbx" -->
                        </div> <!-- END div class="emp_tabwrapper" -->
                    </div> <!-- END div class="emp_right_contner" -->

            </td>
        </tr>
    </table>
</div>    
<?php 
} 
else
{ 
?>
<div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="247" valign="top">
				<?php 
                $this->renderPartial('//configurations/left_side');
                ?>
            </td>
            <td valign="top">
                <div class="cont_right formWrapper">

                    
                    
                    <div class="clear"></div>
                    <div class="emp_right_contner">
                        <div class="emp_tabwrapper">                            
                            <div class="clear"></div>
                            <div class="emp_cntntbx" style="padding-top:10px;">
                                <div class="formCon">
                                    <div class="formConInner" style="padding-top:10px; font-size:14px; font-weight:bold;">
                                        <h3><?php echo Yii::t('app','Week Days'); ?></h3>
                                        <?php
                                        Yii::app()->clientScript->registerScript(
                                        'myHideEffect',
                                        '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                                        CClientScript::POS_READY
                                        );
                                        ?>
                                        
                                        <?php if(Yii::app()->user->hasFlash('notification')):?>
                                            <div class="flash-success">
                                            	<?php echo Yii::app()->user->getFlash('notification'); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php
										if(($year!=$current_academic_yr->config_value and $is_insert->settings_value==0))
										{
										?>											
                                        <span style="color:#008000; font-size:9px; position: absolute; top:13px; left:100px;">
											<?php echo Yii::t('app','You are not viewing the current active year. To set the weekdays, enable Insert option in Previous Academic Year Settings.');?>
										</span>
                                        <?php
										}
										?>
                                        
                                        <?php 
                                        $models = Batches::model()->findAll("is_deleted=:x", array(':x'=>'0'));
                                        $data = array();
                                        $data['NULL'] = 'common';
                                        foreach ($models as $model_1)
                                        {
											$posts=Batches::model()->findByPk($model_1->id);
											$data[$model_1->id] = $model_1->name;
                                        }
                                        ?>
                                        <div>
                                        <!--<h3>Set Weekdays For :&nbsp;-->
                                        <?php
                                        //echo CHtml::dropDownList('mydropdownlist','mydropdownlist',$data,array('onchange'=>'getid();','id'=>'drop','options'=>array($_REQUEST['id']=>array('selected'=>true))));
                                        ?> <!--</h3>-->
                                        <?php $form=$this->beginWidget('CActiveForm', array('id'=>'courses-form','enableAjaxValidation'=>false,)); ?>
                                        
                                        <!-- Default one -->
                                        <?php
                                        $batch = $model->findAll("batch_id IS NULL");
                                        ?>
                                        <div style="color:#000; font-size:14px;">
                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="4%"><?php
                                                    if($batch[0]['weekday']==1)
                                                    	echo $form->checkBox($model,'sunday',array_merge($disabled,array('value'=>'1','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    	echo $form->checkBox($model,'sunday',array_merge($disabled,array('value'=>'1','class'=>'styled'))); ?></td>
                                                    <td width="85%"><?php echo Yii::t('app','Sunday');?></td>
                                                    <td width="11%">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
													<?php
                                                    if($batch[1]['weekday']==2)
                                                    	echo $form->checkBox($model,'monday',array_merge($disabled,array('value'=>'2','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    	echo $form->checkBox($model,'monday',array_merge($disabled,array('value'=>'2','class'=>'styled'))); 
													?>
                                                    </td>
                                                    <td><?php echo Yii::t('app','Monday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
													<?php
                                                    if($batch[2]['weekday']==3)
                                                    	echo $form->checkBox($model,'tuesday',array_merge($disabled,array('value'=>'3','checked'=>'checked','class'=>'styled'))); 
                                                    else
                                                    	echo $form->checkBox($model,'tuesday',array_merge($disabled,array('value'=>'3','class'=>'styled'))); 
                                                    ?>
                                                    </td>
                                                    <td><?php echo Yii::t('app','Tuesday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <?php
                                                    if($batch[3]['weekday']==4)
                                                        echo $form->checkBox($model,'wednesday',array_merge($disabled,array('value'=>'4','checked'=>'checked','class'=>'styled')));
                                                    else
                                                        echo $form->checkBox($model,'wednesday',array_merge($disabled,array('value'=>'4','class'=>'styled')));
                                                    ?>
                                                    </td>
                                                    <td><?php echo Yii::t('app','Wednesday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
													<?php
                                                    if($batch[4]['weekday']==5)
                                                    	echo $form->checkBox($model,'thursday',array_merge($disabled,array('value'=>'5','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    	echo $form->checkBox($model,'thursday',array_merge($disabled,array('value'=>'5','class'=>'styled')));
                                                    ?>
                                                    </td>
                                                    <td><?php echo Yii::t('app','Thursday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <?php
                                                    if($batch[5]['weekday']==6)
                                                    	echo $form->checkBox($model,'friday',array_merge($disabled,array('value'=>'6','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    	echo $form->checkBox($model,'friday',array_merge($disabled,array('value'=>'6','class'=>'styled')));
                                                    ?>
                                                    </td>
                                                    <td><?php echo Yii::t('app','Friday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <?php
                                                    if($batch[6]['weekday']==7)
                                                        echo $form->checkBox($model,'saturday',array_merge($disabled,array('value'=>'7','checked'=>'checked','class'=>'styled')));
                                                    else
                                                        echo $form->checkBox($model,'saturday',array_merge($disabled,array('value'=>'7','class'=>'styled')));
                                                    ?>
                                                    </td>
                                                    <td><?php echo Yii::t('app','Saturday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div> <!--END div class="bbtb" -->
                                        <br />
                                        
                                        <?php 
										if(($year==$current_academic_yr->config_value) or ($year!=$current_academic_yr->config_value and $is_insert->settings_value!=0))
										{
										echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('class'=>'formbut'));
										}
										 ?>          
                                        <?php $this->endWidget(); ?>              
                                        </div>
                                    </div> <!-- END div class="formConInner" -->
                                </div> <!-- END div class="formCon" -->
                            </div> <!-- END div class="emp_cntntbx" -->
                        </div> <!-- END div class="emp_tabwrapper" -->
                    </div> <!-- END div class="emp_right_contner" -->
                </div> <!-- END div class="cont_right formWrapper" -->
            </td>
        </tr>
    </table>
</div>    
<?php 
}
?>
