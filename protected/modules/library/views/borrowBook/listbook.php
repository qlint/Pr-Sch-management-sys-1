
<?php
$this->breadcrumbs=array(
	'Borrow Books'=>array('/library'),
	'ListBok',
);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/library_left');?>
 </td>
    <td valign="top"> 
   <div class="cont_right">
    <h1><?php echo Yii::t('library','Book List');?></h1>
     <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
                    <div class="y_bx_head" style="width:90%">
<?php

 echo '<strong>'.Yii::t('library','Sorry!!&nbsp;This book is not available now.').'</strong>&nbsp;';
 echo '<strong>'.Yii::t('library','Click Here to view the ').'</strong> &nbsp;&nbsp;'. CHtml::link(Yii::t('library','Book details'),array('/library/book/view','id'=>$bid));
 
 ?>
 </div>
 </div>
 </div>
 </td>
 </tr>
 </table>
 
 
 