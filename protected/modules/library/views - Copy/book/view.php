<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 0;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),
	Yii::t('app','Books')=>array('/library/book/manage'),
	Yii::t('app','View'),
);


?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
)); ?>



<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
   <?php $this->renderPartial('/settings/library_left');?>
 </td>
    <td valign="top">  
    <div class="cont_right">
    <h1><?php echo Yii::t('app','View Book');?></h1>

<?php
$book=Book::model()->findByAttributes(array('id'=>$model->id));
$author=Author::model()->findByAttributes(array('auth_id'=>$book->author));
$publication=Publication::model()->findByAttributes(array('publication_id'=>$book->publisher));
$available_book = $book->copy - $book->copy_taken;
?>
<div class="table-responsive">
                          <table width="100%" class="table table-bordered mb30" cellpadding="0" cellspacing="0">
                          <thead>
                            <tr>
                            <th><?php echo Yii::t('app','ISBN');?></th>
                            <th><?php echo Yii::t('app','Book Name');?></th>
                            <th><?php echo Yii::t('app','Author');?></th>
                            <th><?php echo Yii::t('app','Edition');?></th>
                            <th><?php echo Yii::t('app','Publisher');?></th>
                            <th><?php echo Yii::t('app','Copies Available');?></th>
                            <th><?php echo Yii::t('app','Book Position');?></th>
                            <th><?php echo Yii::t('app','Shelf No.');?></th>
                            <th><?php echo Yii::t('app','Total Copies');?></th>
                            </tr>
                        </thead>
                        <tr>
                          <td><?php echo $book->isbn;?></td>
                            <td><?php echo $book->title;?></td>
                            <td><?php 
                            echo CHtml::link(ucfirst($author->author_name), array('/library/authors/authordetails','id'=>$author->auth_id));
                            ?></td>
                            <td><?php echo $book->edition;?></td>
                            <td><?php echo $publication->name;?></td>
                            <td><?php echo $available_book;?></td>
                            <td><?php echo $book->book_position;?></td>
                            <td><?php echo $book->shelf_no;?></td>
                            <td><?php echo $book->copy;?></td>
                        </tr>
                   </table>     

</div>
</div>
</td>
</tr>
</table>
<?php $this->endWidget(); ?>