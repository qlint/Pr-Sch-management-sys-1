<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Hostel Details')=>array('/hostel/Hosteldetails/manage'),
	Yii::t('app','View'),
);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hosteldetails-form',
	'enableAjaxValidation'=>false,
)); ?>



<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
<h1><?php echo Yii::t('app','View Hostel Details');?></h1>
<div class="pdtab_Con">
<table width="100%" cellpadding="0" cellspacing="0" border="0" >
		<tr class="pdtab-h">
        	<td align="center"><?php echo Yii::t('app','Hostel Name');?></td>
            <td align="center"><?php echo Yii::t('app','Address');?></td>
          </tr>
         <?php
		 $hostel=Hosteldetails::model()->findByAttributes(array('id'=>$model->id));
		 ?> 
          <tr>
          <td align="center"><?php echo $hostel->hostel_name;?></td>
           <td align="center"><?php echo $hostel->address;?></td>
          </tr>
		</table>
        </div>
	</div>
    </td>
    </tr>
    </table>
    <?php $this->endWidget(); ?>