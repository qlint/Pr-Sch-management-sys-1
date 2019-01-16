<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),	
	Yii::t('app','Borrowed Book Details'),
);
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php 
$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
					
foreach($roles as $role)
{
	if(sizeof($roles)==1 and $role->name == 'student')
	{ 
	?>
	
	<?php 
	} 
	else 
	{ 
	?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="247" valign="top">
                	<?php $this->renderPartial('/settings/library_left');?>
                </td>
                <td valign="top">  
                    <div class="cont_right">  
                    	<h1><?php echo Yii::t('app','Borrowed Book Details');?></h1>              
						<?php 
                        if(isset($book_id))
                        {
							$book=BorrowBook::model()->findAllByAttributes(array('book_id'=>$book_id,'status'=>'C'));
							$bookdetails=Book::model()->findByAttributes(array('id'=>$book_id));							
							?>
                  <div class="table-responsive">
                          <table width="100%" class="table table-bordered mb30" cellpadding="0" cellspacing="0">
                          <thead>
                                    <tr >
                                    	<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <th><?php echo Yii::t('app','Student Name');?></th>
                                        <?php } ?>
                                        <th><?php echo Yii::t('app','Book Name');?></th>
                                        <th><?php echo Yii::t('app','ISBN');?></th>
                                        <th><?php echo Yii::t('app','Author');?></th>
                                        <th><?php echo Yii::t('app','Edition');?></th>
                                        <th><?php echo Yii::t('app','Publisher');?></th>
                                        <th><?php echo Yii::t('app','Copies Available');?></th>
                                        <th><?php echo Yii::t('app','Total Copies');?></th>                                   
                                         </tr>
                                       </thead>
                                    <?php 
                                    if($book==NULL)
                                    {
                                    	echo '<tr><td align="center" colspan="8"><strong>'.Yii::t('app','No data available').'</strong></td></tr>';
                                    }
                                    else
                                    {
										foreach($book as $book_1)
										{
											$student=Students::model()->findByAttributes(array('id'=>$book_1->student_id));
											$author=Author::model()->findByAttributes(array('auth_id'=>$bookdetails->author));
											$publication=Publication::model()->findByAttributes(array('publication_id'=>$bookdetails->publisher));
											$copies_available = $bookdetails->copy - $bookdetails->copy_taken;
											?>
											<tr>
                                            	<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                                <td><?php echo $student->studentFullName("forStudentProfile");?></td>
                                                <?php } ?>
                                                <td><?php echo $bookdetails->title;?></td>
                                                <td><?php echo $bookdetails->isbn;?></td>
                                                <td><?php echo $author->author_name;?></td>
                                                <td><?php echo $bookdetails->edition;?></td>
                                                <td><?php echo $publication->name;?></td>
                                                <td><?php echo $copies_available;?></td>
                                                <td><?php echo $bookdetails->copy;?></td>											
											</tr>
										<?php 
										}
                                    } 
                                    
                                    ?>
                                </table>
							</div> <!-- END div class="pdtab_Con" -->
                                    
                        <?php 
                        } // END if(isset($book_id))
                        ?>
                	</div> <!-- END div class="cont_right" -->

                </td>
            </tr>
        </table>
    <?php
	}
}
?>

<?php $this->endWidget(); ?>