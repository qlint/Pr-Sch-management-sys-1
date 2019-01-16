              
  
   <div class="pageheader">
      <div class="col-lg-8">
        <h2><i class="fa fa-folder-open"></i><?php echo Yii::t('app','Library');?><span><?php echo Yii::t('app','View Library');?> </span></h2>
      </div>
      <div class="col-lg-2"> </div>
      <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
          <li class="active"><?php echo Yii::t('app','Library');?></li>
        </ol>
      </div>
      <div class="clearfix"></div>
    </div>
 <?php echo $this->renderPartial('application.modules.studentportal.views.default.leftside'); ?>
<div class="contentpanel">
  <div class="col-sm-9 col-lg-12">
    <div id="parent_Sect">
    
    <div id="parent_rightSect">
        	<div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Author Details');?></h3>
    </div>
        	<div class="people-item">
<?php
$book=Book::model()->findAll('author=:x',array(':x'=>$_REQUEST['id']));
if($book!=NULL)
{
	?>
    <div class="table-responsive">
    <table class="table table-hover mb30">
    <thead>
<tr class="pdtab-h">
<th><?php echo Yii::t('app','Author Name');?></th>
<th><?php echo Yii::t('app','Subject');?></th>
<th><?php echo Yii::t('app','Book Title');?></th>
<th><?php echo Yii::t('app','ISBN');?></th>
<th><?php echo Yii::t('app','Publication');?></th>
</tr>
	</thead>
    <?php
	foreach($book as $book_1)
	{
		$author=Author::model()->findByAttributes(array('auth_id'=>$_REQUEST['id']));		
		$publication=Publication::model()->findByAttributes(array('publication_id'=>$book_1->publisher));
		?>
        <tr>
<td><?php echo ucfirst($author->author_name);?></td>
<td><?php echo $book_1->subject;?></td>
<td><?php echo $book_1->title;?></td>
<td><?php echo $book_1->isbn;?></td>
<td><?php echo $publication->name;?></td>

</tr>
<?php } ?>
</table>
</div>
</div>
</td>
</tr>
</table>
        <?php
	
		}?>

 </div>
    
     <div class="clear"></div> 
     </div>
      </div>
     </div>
     
                          