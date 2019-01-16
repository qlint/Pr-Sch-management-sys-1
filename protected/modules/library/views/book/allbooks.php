<?php
$this->breadcrumbs=array(
	Yii::t('app','Books')=>array('/library'),
	yii::t('app','AllBooks'),
);?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>
<h3><?php echo Yii::t('app','Book Details');?></h3>
                      
<?php 
    
                        if(isset($book_id))
						{
							$book=Book::model()->findAllByAttributes(array('id'=>$book_id));
							if($book!=NULL)
							{
						
						?>
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:10px; width:100%;">
<tr>
<td>Y<?php echo Yii::t('app','Book Title');?></td>
<td><?php echo Yii::t('app','ISBN');?></td>
<td><?php echo Yii::t('app','Author');?></td>
<td><?php echo Yii::t('app','Copies Available');?></td>
<td><?php echo Yii::t('app','Book Position');?></td>
<td><?php echo Yii::t('app','Shelf No');?></td>
</tr>
<?php foreach($book as $book_1)
{
	?>
<tr>
<td><?php echo $book_1->title;?></td>
<td><?php echo $book_1->isbn;?></td>
<td><?php echo $book_1->author;?></td>
<td><?php echo $book_1->copy;?></td>
<td><?php echo $book_1->book_position;?></td>
<td><?php echo $book_1->shelf_no;?></td>

</tr>
<?php }
				} 
				else
				{
					echo '<tr><tdcolspan="5">'.Yii::t('app','Sorry!!&nbsp;The details are not available now.').'</td></tr>';
				}
				 ?>
</table>
<?php } ?>
<?php $this->endWidget(); ?>