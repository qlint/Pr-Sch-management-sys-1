<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/configurations'),
	Yii::t('app','Modules'),
);

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
<div id="othleft-sidebar">
<?php $this->renderPartial('//configurations/left_side');?>
  </div>
 </td>
 <td valign="top">
<div class="cont_right formWrapper">

<h1><?php echo Yii::t('app','Manage Modules');?></h1>

<?php $cls = "even"; ?>

 <div class="tablebx">  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="tablebx_topbg">
                                <td><?php echo Yii::t('app','Name');?></td>	
                                <td><?php echo Yii::t('app','Action');?></td>
                                </tr>
<?php 
foreach($modules as $module)
{ 
    if($module->name!='Settings')
    {
?>
		

                                <tr class=<?php echo $cls;?>>
                                
                                <td><?php echo $module->name; ?></td>	
                                <td><?php 
								
								if($module->control=='1')
										{
											echo CHtml::link(Yii::t('app','Disable'), array('disable', 'id'=>$module->id),array('confirm'=>Yii::t('app','Are you sure you want to disable this module?')));
										}
										else
										{
											echo CHtml::link(Yii::t('app','Enable'), array('enable', 'id'=>$module->id),array('confirm'=>Yii::t('app','Are you sure you want to enable this module ?')));
										}
								
								
								?></td>
                                </tr>



<?php 
if($cls=="even")
{
	$cls="odd";
}
else
{
	$cls="even";
}
    }
}

?>
</table>
</div>
</div>
 </td>
  </tr>
</table>