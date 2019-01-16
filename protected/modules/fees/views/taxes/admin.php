<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
		Yii::t('app','Taxes'),
	);
?>
<link href="<?php echo Yii::app()->request->baseUrl;?>/css/formstyle.css" type="text/css" rel="stylesheet" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">    
            <?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="247">
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Taxes'); ?></h1>
                            <div class="edit_bttns" style="top:20px; right:20px;">
                                <ul>
                                	<li><?php echo CHtml::link('<span>'.Yii::t('app','Create').'</span>', array('create'),array('class'=>'addbttn last ')); ?></li>
                                </ul>
                            </div>
							<?php $this->widget('zii.widgets.grid.CGridView', array(
								'id'=>'fee-taxes-grid',
								'dataProvider'=>$model->search(),
								//'filter'=>$model,
								'columns'=>array(
									'label',
									'value',
									array(
										'name'=>'created_by',
										'value'=>'$data->user'
									),
									array(
										'name'=>'created_at',
										'value'=>'$data->formattedDate'
									),
									array(
										'name'=>'is_active',
										'value'=>'($data->is_active==1)?"Active":"Inactive"'
									),
									array(
										'class'=>'CButtonColumn',
										'template'=>'{update}{delete}',
									),
								),
							)); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>