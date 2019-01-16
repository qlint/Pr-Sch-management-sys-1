<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 0px 0 0;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),
	Yii::t('app','Authors')=>array('index'),
	Yii::t('app','View')	
);


?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
 <?php $this->renderPartial('/settings/library_left');?>
 
 </td>
    <td valign="top">
    <div class="cont_right formWrapper">
<h1><?php echo Yii::t('app','Author Details');?></h1>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li>
    <?php echo CHtml::link('<span>'.Yii::t('app','View Authors').'</span>',array('/library/authors'),array('class'=>'a_tag-btn'));?>
    </li>                                    
</ul>
</div> 
</div>                                                                                                                                                                                                                                                                                                                                                                          
<?php
$book=Book::model()->findAll('author=:x',array(':x'=>$_REQUEST['id']));
if($book!=NULL)
{
	?>
    <div class="pdtab_Con">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" >
<tr class="pdtab-h">
<td align="center"><?php echo Yii::t('app','Author Name');?></td>
<td align="center"><?php echo Yii::t('app','Subject');?></td>
<td align="center"><?php echo Yii::t('app','Book Title');?></td>
<td align="center"><?php echo Yii::t('app','ISBN');?></td>
<td align="center"><?php echo Yii::t('app','Publication');?></td>
</tr>
    <?php
	foreach($book as $book_1)
	{
		$author=Author::model()->findByAttributes(array('auth_id'=>$_REQUEST['id']));
		$sub=Subjects::model()->findByAttributes(array('id'=>$book_1->subject));
		$publication=Publication::model()->findByAttributes(array('publication_id'=>$book_1->publisher));
		?>
        <tr>
<td align="center"><?php echo ucfirst($author->author_name);?></td>
<td align="center"><?php echo $book_1->subject;?></td>
<td align="center"><?php echo $book_1->title;?></td>
<td align="center"><?php echo $book_1->isbn;?></td>
<td align="center"><?php echo $publication->name;?></td>

</tr>
<?php } ?>
</table>
</div>
</div>
</td>
</tr>
</table>
	<?php
    
    }   
?>
