
<?php
$this->breadcrumbs=array(
Yii::t('app','Timetable')=>array('/timetable'),
Yii::t('app','Weekdays'),

);?>


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
if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL and !isset($_REQUEST['type'])) 
{
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="247" valign="top">
            	<?php $this->renderPartial('/default/left_side');?>
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
                    
                    <div class="clear"></div>
                    <div class="emp_right_contner">
                        <div class="emp_tabwrapper">
							
                            <?php
                            if($year != $current_academic_yr->config_value and $is_insert->settings_value==0)
							{
							?>
                                <div>
                                    <div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
                                        <div class="y_bx_head" style="width:95%;">
                                        <?php 
                                            echo Yii::t('app','You are not viewing the current active year. ');
                                            echo Yii::t('app','To set the weekdays, enable the Insert option in Previous Academic Year Settings.');	
                                        ?>
                                        </div>
                                        <div class="y_bx_list" style="width:95%;">
                                            <h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
                                        </div>
                                    </div>
                                </div> <br/>
							<?php	
                            }							 
							?>
                            
                            <div class="clear"></div>
                            <div class="formCon" style="margin-bottom:0px;">
                                <div class="formConInner" style="width:97%">
                                <?php $this->renderPartial('/default/tab');?>
                                    <div class="c_subbutCon" align="right" >
                                    <div class="edit_bttns edtbtnss timetable-btnn">
                                        <ul>
                                        <li><?php echo CHtml::link('<span>'.Yii::t('app','Time Table').'</span>',array('weekdays/timetable','id'=>$_REQUEST['id']),array('class'=>'addbttn last'));?></li>
                                        </ul>
                                        <div class="clear"></div>
                                    </div> <!-- END div class="edit_bttns" -->
                                    </div> <!-- END div class="c_subbutCon" -->
                                    
                                    <div>                                    
                                        <div style="padding-top:10px; font-size:14px; font-weight:bold;">
                                            <span class="time-h3">
                                            <h3 style="border-bottom:none;"><?php echo Yii::t('app','Week Days'); ?></h3></span>
                                            <?php
                                            Yii::app()->clientScript->registerScript(
                                            'myHideEffect',
                                            '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                                            CClientScript::POS_READY
                                            );
                                            ?>
                                            
                                            <?php if(Yii::app()->user->hasFlash('notification')):?>
                                            	<div class="flash-success" style="color:#F00; padding-left:150px; font-size:12px">
                                            	<?php echo Yii::app()->user->getFlash('notification'); ?>
                                            	</div>
                                            <?php endif; ?>
                                            <div style="border-bottom:1px dashed #DDDDDD;">
                                            &nbsp;
                                            </div>
                                            <br />
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
													<div style="clear:left" >
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="4%">
																<?php
                                                                if($batch[0]['weekday']==1)
                                                                	echo $form->checkBox($model,'sunday',array_merge($disabled,array('value'=>'1','checked'=>'checked','class'=>'styled')));
                                                                else
                                                                	echo $form->checkBox($model,'sunday',array_merge($disabled,array('value'=>'1','class'=>'styled')) ); 
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
                                                                <td><?php echo Yii::t('app','Thursday');?></td>
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
                                                                <td><?php
                                                                if($batch[6]['weekday']==7)
                                                                	echo $form->checkBox($model,'saturday',array_merge($disabled,array('value'=>'7','checked'=>'checked','class'=>'styled')));
                                                                else
                                                                	echo $form->checkBox($model,'saturday',array_merge($disabled,array('value'=>'7','class'=>'styled')));
                                                                 ?></td>
                                                                <td ><?php echo Yii::t('app','Saturday');?></td>
                                                                <td></td>
                                                            </tr>
                                                        </table>
													</div>
													<br />
                                                <?php    
                                                } // END if($_REQUEST['id']!='NULL')
                                                ?> 
                                                           
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- END div class="formConInner" -->
                            </div> <!-- END div class="formCon" -->
                            <br />
                            <?php 
							if(($year == $current_academic_yr->config_value) or ($year!=$current_academic_yr->config_value and $is_insert->settings_value!=0))
							{
								echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('class'=>'formbut')); 
							}
							?>          
							<?php $this->endWidget(); ?>  
                        </div> <!-- END div class="emp_tabwrapper" -->
                    </div> <!-- END div class="emp_right_contner" -->
                </div> <!-- END div class="cont_right formWrapper" -->
            </td>
        </tr>
    </table>
<?php 
} else
{ 
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        <?php $this->renderPartial('/default/left_side');?>      
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
            	<div class="clear"></div>
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
                    	<?php
						if($year != $current_academic_yr->config_value and $is_insert->settings_value==0)
						{
						?>
							<div>
								<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
									<div class="y_bx_head" style="width:95%;">
									<?php 
										echo Yii::t('app','You are not viewing the current active year. ');
										echo Yii::t('app','To set the system default weekdays, enable the Insert option in Previous Academic Year Settings.');	
									?>
									</div>
									<div class="y_bx_list" style="width:95%;">
										<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
									</div>
								</div>
							</div> <br/>
						<?php	
						}							 
						?>
                    	<div class="clear"></div>
                        <div class="emp_cntntbx" style="padding-top:10px;">
                            <div class="formCon">
                                <div class="formConInner" style="padding-top:10px; font-size:14px; font-weight:bold;">
                                    <h3><?php echo Yii::t('app','System Default Week Days'); ?></h3>
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
                                    
                                    $models = Batches::model()->findAll("is_deleted=:x", array(':x'=>'0'));
                                    $data = array();
                                    $data['NULL'] = 'common';
                                    foreach ($models as $model_1)
                                    {
                                    $posts=Batches::model()->findByPk($model_1->id);
                                    $data[$model_1->id] = $model_1->name;
                                    }
                                    ?>
                                    <div class="bbtb">
                                        <!--<h3>Set Weekdays For :&nbsp;-->
                                        <?php
                                        //echo CHtml::dropDownList('mydropdownlist','mydropdownlist',$data,array('onchange'=>'getid();','id'=>'drop','options'=>array($_REQUEST['id']=>array('selected'=>true))));
                                        ?> <!--</h3>-->
                                        <?php $form=$this->beginWidget('CActiveForm', array('id'=>'courses-form','enableAjaxValidation'=>false,)); ?>
                                        
                                        <!-- Default one -->
                                        <?php
                                        
                                        
                                        $batch = $model->findAll("batch_id IS NULL");
                                        ?>
                                        <div class="bbtb" style="color:#000; font-size:14px;">
                                        
                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="4%">
													<?php
                                                    if($batch[0]['weekday']==1)
                                                    	echo $form->checkBox($model,'sunday',array_merge($disabled,array('value'=>'1','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    	echo $form->checkBox($model,'sunday',array_merge($disabled,array('value'=>'1','class'=>'styled'))); ?>
													</td>
                                                    <td width="85%"><?php echo Yii::t('app','Sunday');?></td>
                                                    <td width="11%">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><?php
                                                    if($batch[1]['weekday']==2)
                                                    echo $form->checkBox($model,'monday',array_merge($disabled,array('value'=>'2','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    echo $form->checkBox($model,'monday',array_merge($disabled,array('value'=>'2','class'=>'styled'))); ?></td>
                                                    <td><?php echo Yii::t('app','Monday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><?php
                                                    if($batch[2]['weekday']==3)
                                                    echo $form->checkBox($model,'tuesday',array_merge($disabled,array('value'=>'3','checked'=>'checked','class'=>'styled'))); 
                                                    else
                                                    echo $form->checkBox($model,'tuesday',array_merge($disabled,array('value'=>'3','class'=>'styled'))); 
                                                    ?></td>
                                                    <td><?php echo Yii::t('app','Tuesday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><?php
                                                    if($batch[3]['weekday']==4)
                                                    echo $form->checkBox($model,'wednesday',array_merge($disabled,array('value'=>'4','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    echo $form->checkBox($model,'wednesday',array_merge($disabled,array('value'=>'4','class'=>'styled')));
                                                    ?></td>
                                                    <td><?php echo Yii::t('app','Wednesday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><?php
                                                    if($batch[4]['weekday']==5)
                                                    echo $form->checkBox($model,'thursday',array_merge($disabled,array('value'=>'5','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    echo $form->checkBox($model,'thursday',array_merge($disabled,array('value'=>'5','class'=>'styled')));
                                                    ?></td>
                                                    <td><?php echo Yii::t('app','Thursday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><?php
                                                    if($batch[5]['weekday']==6)
                                                    echo $form->checkBox($model,'friday',array_merge($disabled,array('value'=>'6','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    echo $form->checkBox($model,'friday',array_merge($disabled,array('value'=>'6','class'=>'styled')));
                                                    ?></td>
                                                    <td><?php echo Yii::t('app','Friday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><?php
                                                    if($batch[6]['weekday']==7)
                                                    echo $form->checkBox($model,'saturday',array_merge($disabled,array('value'=>'7','checked'=>'checked','class'=>'styled')));
                                                    else
                                                    echo $form->checkBox($model,'saturday',array_merge($disabled,array('value'=>'7','class'=>'styled')));
                                                    ?></td>
                                                    <td><?php echo Yii::t('app','Saturday');?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <br />
                                    
                                           
                                    </div> <!-- END div class="bbtb" -->
                                </div> <!-- END div class="formConInner" -->
                            </div> <!-- END div class="formCon" -->
                            <?php
							if(($year == $current_academic_yr->config_value) or ($year!=$current_academic_yr->config_value and $is_insert->settings_value!=0))
							{ 
								echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('class'=>'formbut')); 
							}
							?>          
							<?php $this->endWidget(); ?>      
                        </div> <!-- END div class="emp_cntntbx" -->
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" -->
            </div> <!-- END div class="cont_right formWrapper"-->
        </td>
    </tr>
</table>


<?php }
?>

