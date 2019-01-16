<style type="text/css">
table.detail-view th{ padding:10px 10px 10px 20px;}

table.detail-view td{ padding:10px;}

 table th:first-child { width:100px !important; }
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('notifications/default/sendmail'),
	Yii::t('app', 'Email Templates')=>array('index'),	
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('left_side');?>    
        </td>
        <td valign="top"> 
         <div class="cont_right formWrapper">
        	<table width="100%" >
                <tbody><tr>
                    <td width="75%" valign="top">
                    	
    						<h1><?php echo Yii::t('app','Email Template');?></h1>
							<?php $this->widget('zii.widgets.CDetailView', array(
								'data'=>$model,
								'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
								'attributes'=>array(
									'subject',
									'template',
									//'created_at',
									array('name'=>'create_at','label'=>Yii::t('app', 'Edited At')),
									
								),
							)); ?>
                            
								<?php echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('update', 'id'=>$model->id),array('class'=>'formbut','style'=>'display:block; width:50px; padding:5px 0px; height:15px; text-align:center; margin-top:10px;'));?>                            
                                  
                        
                    </td>
                </tr>
            </tbody></table>
            </div>
        </td>
    </tr>
</table>