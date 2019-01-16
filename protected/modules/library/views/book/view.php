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

 <div class="pdtab_Con">

 <table width="99%" cellpadding="0" cellspacing="0" border="0" >

						<tr class="pdtab-h">

						<td align="center"><?php echo Yii::t('app','ISBN');?></td>

                        <td align="center"><?php echo Yii::t('app','Book Name');?></td>

                        <td align="center"><?php echo Yii::t('app','Author');?></td>

                        <td align="center"><?php echo Yii::t('app','Edition');?></td>

                        <td align="center"><?php echo Yii::t('app','Publisher');?></td>

                        <td align="center"><?php echo Yii::t('app','Copies Available');?></td>

                        <td align="center"><?php echo Yii::t('app','Book Position');?></td>

                        <td align="center"><?php echo Yii::t('app','Shelf No.');?></td>

                        <td align="center"><?php echo Yii::t('app','Total Copies');?></td>

                        </tr>

                        <tr>

                          <td align="center"><?php echo $book->isbn;?></td>

                                            <td align="center"><?php echo ucfirst($book->title);?></td>

                                            <td align="center"><?php 

                                            echo CHtml::link(ucfirst($author->author_name), array('/library/authors/authordetails','id'=>$author->auth_id));

                                            ?></td>

                                            <td align="center"><?php echo $book->edition;?></td>

                                            <td align="center"><?php echo ucfirst($publication->name);?></td>

                                            <td align="center"><?php echo $available_book;?></td>

                                            <td align="center"><?php echo $book->book_position;?></td>

                                            <td align="center"><?php echo $book->shelf_no;?></td>

                                            <td align="center"><?php echo $book->copy;?></td>

                        </tr>

                   </table>     



</div>

</div>

</td>

</tr>

</table>

<?php $this->endWidget(); ?>