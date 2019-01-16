<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
		Yii::t('app','Payment Types'),
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
                            <h1><?php echo Yii::t('app','Payment Types'); ?></h1>            

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','Create').'</span>', array('create'),array('class'=>'a_tag-btn')); ?></li>                                
</ul>
</div> 
</div>
		
                            <div class="pdtab_Con">
                                <div style="font-size:13px; padding:5px 0px">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr class="pdtab-h">
                                                <td height="18" align="center"><?php echo Yii::t('app','Type'); ?></td>
                                                <td align="center"><?php echo Yii::t('app','Created By'); ?></td>
                                                <td align="center"><?php echo Yii::t('app','Created At'); ?></td>
                                                <td align="center"><?php echo Yii::t('app','Status'); ?></td>                                            
                                                <td align="center"><?php echo Yii::t('app','Actions'); ?></td>                            
                                            </tr>
                                            <?php
                                            foreach($types as $key=>$type){
                                            ?>
                                            <tr>
                                                <td><?php echo $type->type;?></td>
                                                <td><?php echo $type->user;?></td>
                                                <td><?php echo $type->formattedDate;?></td>
                                                <td align="center"><?php echo ($type->is_active==1)?Yii::t('app', 'Active'):Yii::t('app', 'Inactive');?></td>
                                                <td align="center">
                                                	<div class="tt-wrapper-new">
														<?php
                                                            if($type->is_editable==1){
                                                                echo CHtml::link('<span>'.Yii::t('app', 'Edit').'</span>', array('update', 'id'=>$type->id), array('class'=>'makeedit'));
																echo CHtml::link('<span>'.Yii::t('app', 'Delete').'</span>', '#', array('class'=>'makedelete', 'confirm'=>Yii::t('app', 'Are you sure ?'), 'submit'=>array('delete', 'id'=>$type->id), 'params'=>array(), 'csrf'=>true));
                                                            }
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                              	</div>
                          	</div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

