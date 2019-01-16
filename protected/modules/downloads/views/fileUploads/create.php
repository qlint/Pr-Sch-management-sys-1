<?php
$this->breadcrumbs=array(
	Yii::t('app','Downloads')=>array('/downloads'),
	Yii::t('app','File Uploads')=>array('index'),
	Yii::t('app','Create'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" valign="top" id="port-left">
     <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
        <div class="cont_right">
        	<h1><?php echo Yii::t('app','Create File Uploads'); ?></h1>
<?php /*?>            <div style="position:absolute; top:16px; right:30px;">
                <?php 
                echo CHtml::link(Yii::t('app','All Uploads'),array('index'),array('class'=>'mailbox-menu-newup'));
                echo CHtml::link(Yii::t('app','Manage Uploads'),array('admin'),array('class'=>'mailbox-menu-mangeup'));
                ?>
            </div><?php */?>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li>                <?php 
                echo CHtml::link(Yii::t('app','All Uploads'),array('index'),array('class'=>'mailbox-menu-newup'));
				?></li>
<li>                <?php
                echo CHtml::link(Yii::t('app','Manage Uploads'),array('admin'),array('class'=>'mailbox-menu-mangeup'));
                ?></li>
                                   
</ul>
</div> 

</div> 
        
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
		$is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
		
		if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
		{
			echo $this->renderPartial('/fileUploads/_form', array('model'=>$model)); 
		}
		else
		{
		?>
			<div style="padding-left:20px;">
				<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px; padding-left:10px;">
					<div class="y_bx_head" style="width:650px;">
					<?php 
						echo Yii::t('app','You are not viewing the current active year. ');
						echo Yii::t('app','To upload a new file, enable Create option in Previous Academic Year Settings.');
					   
					?>
					</div>
					<div class="y_bx_list" style="width:650px;">
						<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
					</div>
				</div>
			</div><br />
		<?php	
		}
		?>
		</div>
    </td>
  </tr>
</table>
