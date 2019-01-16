<?php
$this->breadcrumbs=array(
	Yii::t('app','System Offline Settings'),
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/configurations/left_side');?>   
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Manage Offline Settings');?></h1>
               
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
                    <ul>
                        <li><?php echo CHtml::link('<span>'.Yii::t('app','Add Schedule').'</span>', array('create'),array('class'=>'a_tag-btn')); ?></li>
                    </ul>
                </div> 
                </div>                           

                <?php $this->widget('zii.widgets.grid.CGridView', array(
                            'id'=>'system-offline-settings-grid',
                            'dataProvider'=>$model->search(),
                           // 'filter'=>$model,
                            'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                            'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                            'template'=>"{items}\n{pager}",
                            'columns'=>array(
                                array('name'=>"offline_message",'header'=>Yii::t('app',"Message")),
                                array('name'=>"start_time",'header'=>Yii::t('app',"Start Time")),
                                array('name'=>"end_time",'header'=>Yii::t('app',"End Time")),
                                array('name'=>"status",'header'=>Yii::t('app',"Status"),'value'=> array($this,'getStatus')),
                                    //'offline_message',
                                   // 'start_time',
                                   // 'end_time',
                                  //  'status',
                                   // 'created_at',
                                    /*
                                    'allowed_users',
                                    */
                                    array(
                                           'class'=>'CButtonColumn',
						'header'=>'<div style="width:70px;"><center>'.Yii::t('app','Action').'</center></div>',
						'template' => '{update}{delete} {activate}',
						'headerHtmlOptions'=>array('style'=>'font-size:12px; font-weight:bold; '),
						'buttons'=>array
                                                        (
                                                            'activate' => array
                                                            (
                                                                'label'=>"<br>".Yii::t('app','Activate'),
                                                                'url'=>'Yii::app()->createUrl("offlineSettings/activate", array("id"=>$data->id))',
                                                                'click'=>'function(){return confirm("'.Yii::t('app','Are you sure you want to activate this schedule?').'");}',
                                                                'visible'=>'$data->status==0',
                                                            ),
                                                    )
						),
                                    
                            ),
                    )); ?>

                
            </div>
        </td>
    </tr>
</table>

