<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),	
	Yii::t('app','Book Details'),
);?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php 
$mdel_1 = new BorrowBook;
              $roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
					
						  foreach($roles as $role)
						  
						   if(sizeof($roles)==1 and $role->name == 'student')
						   { ?>
                           <div id="parent_Sect">
    <?php $this->renderPartial('/settings/studentleft');?>
    
    <div id="parent_rightSect">
        	<div class="parentright_innercon">
            	<h1>Book List</h1>
                <div class="but_right_con" style="top:34px; right:-48px;"><?php echo CHtml::link(Yii::t('app','Search Books'),array('/library/book/booksearch'),array('class'=>'com_but')); ?></div>
                <div class="profile_details">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>        	

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    
    

<td width="10%">               
<?php 
//echo CHtml::dropDownList('',isset($_REQUEST['id'])? $_REQUEST['id'] : '',CHtml::listData(Subjects::model()->findAll(),'id','name'),
				//array('prompt'=>'select', 'onchange'=>"javascript:booklist();", 'id'=>'id'));
	echo CHtml::label(Yii::t('app','Subject'),'').'</td><td> '; 
	 $criteria = new CDbCriteria;
     $criteria->compare('is_deleted',0);
	echo $form->dropDownList($model_1,'subject',CHtml::listData(Subjects::model()->findAll($criteria),'id','name'),array('prompt'=>Yii::t('app','Select'),'ajax' => array(
	'type'=>'POST',
	'url'=>CController::createUrl('/library/book/subjects'),
	'update'=>'#book_id',
	'data'=>'js:{subject:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',))).'</td></tr><tr><td>&nbsp;</td></tr><tr><td>';
//echo CHtml::dropDownList('subject','',CHtml::listData(Subjects::model()->findAll(),'id','name'),array('prompt'=>'Select',
//'ajax' => array(
//	'type'=>'POST',
//	'url'=>CController::createUrl('/library/book/subjects'),
//	'update'=>'#book_id',
//	'data'=>'js:$(this).serialize()',))).'</td></tr><tr><td>&nbsp;</td></tr><tr><td>';
echo CHtml::label(Yii::t('app','Book'),'').'</td><td>'; 
echo CHtml::dropDownList('book','',array(),array('prompt'=>Yii::t('app','Select'),'id'=>'book_id','submit'=>array('/library/book/booklist')));

                     
                        
						?>
                        </td>
                          </tr>
</table>
  <?php $this->endWidget(); ?>  
</div>



 	</div>
    
     <div class="clear"></div> 
     </div>
     <div class="clear"></div> 
    </div>
                        <?php } else { ?>

 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/library_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
    <h1><?php echo Yii::t('app','Book Details');?></h1> 
    <div class="formCon" >
    <div class="formConInner">    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    
    

<td width="10%">               
<?php 
//echo CHtml::dropDownList('',isset($_REQUEST['id'])? $_REQUEST['id'] : '',CHtml::listData(Subjects::model()->findAll(),'id','name'),
				//array('prompt'=>'select', 'onchange'=>"javascript:booklist();", 'id'=>'id'));
	echo CHtml::label(Yii::t('app','Subject'),'').'</td><td> '; 
	$criteria = new CDbCriteria;
	$criteria->distinct = true;
	$criteria->select = 'subject';
	$criteria->condition = 'is_deleted=:is_deleted';
	$criteria->params = array(':is_deleted'=>0);
	$criteria->order = 'subject ASC';
	$books = Book::model()->findAll($criteria);
echo CHtml::dropDownList('subject','',CHtml::listData($books,'subject','subject'),array('prompt'=>Yii::t('app','Select'),
'ajax' => array(
	'type'=>'POST',
	'url'=>CController::createUrl('/library/book/subjects'),
	'update'=>'#book_id',
	'data'=>'js:{subject:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',))).'</td></tr><tr><td>&nbsp;</td></tr><tr><td>';
echo CHtml::label(Yii::t('app','Book'),'').'</td><td>'; 
echo CHtml::dropDownList('book','',array(),array('prompt'=>Yii::t('app','Select'),'id'=>'book_id','submit'=>array('/library/book/booklist')));

                     
                        
						?>
                        </td>
                          </tr>
</table>
                        </div>
                        </div>
                   </div>     
                        </td>
                        </tr>
                        </table>
                      <?php } ?>
<?php $this->endWidget(); ?>