<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Mess Details')=>array('/hostel/foodInfo/manage'),
	Yii::t('app','View'),
);


?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'foodinfo-form',
	'enableAjaxValidation'=>false,
)); ?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
<h1><?php echo Yii::t('app','Food');?></h1>
<div class="pdtab_Con">
	<table width="100%" cellpadding="0" cellspacing="0" border="0" >
		<tr class="pdtab-h">
        	<td align="center"><?php echo Yii::t('app','Food Preferance');?></td>
            <td align="center"><?php echo Yii::t('app','Amount');?></td>
          </tr>
         <?php
		 $food=FoodInfo::model()->findByAttributes(array('id'=>$model->id));
		 ?> 
          <tr>
          <td align="center"><?php echo $food->food_preference;?></td>
           <td align="center"><?php echo $food->amount;?></td>
          </tr>
		</table>
</div>

	</div>
    </td>
    </tr>
    </table>
<?php $this->endWidget(); ?>

