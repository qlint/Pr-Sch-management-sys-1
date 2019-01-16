
<?php
                    $this->breadcrumbs=array(
                           Yii::t('app','Settings')=>array('/configurations'),
                            Yii::t('app','Backup'),
                    );?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('left_side'); ?>
        </td>
        <td valign="top">
			<div class="cont_right formWrapper">
           
                            <h1><?php echo Yii::t('app',ucfirst($this->action->id)); ?></h1>
	
				<p>
						<?php if(isset($error)) echo $error; else echo Yii::t('app','Done');?>
				</p>
				<p> <?php echo CHtml::link(Yii::t('app','View Site'),Yii::app()->HomeUrl,array('class'=>'formbut'))?></p>

			</div>
        </td>
    </tr>
</table>