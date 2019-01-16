<?php
$this->breadcrumbs=array(
	Yii::t('app','Return Books')=>array('/library'),
	Yii::t('app','ReturnBook'),
);


?>
<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'return-book-form',
	'enableAjaxValidation'=>false,
));
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/settings/library_left');?>
        </td>
        <td valign="top">
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Return Book'); ?></h1>
                <div class="formCon">
                    <div class="formConInner">
                        <table width="50%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                <?php
                                echo '<strong>'.Yii::t('app','Student Admission No').'</strong></td><td>';
                                $data = Students::model()->findAll(array(
                                'join' => 'JOIN borrow_book ON t.id 	 = borrow_book.student_id',
                                'condition' => 'borrow_book.status =:x',
                                'distinct'=>true,
                                'params' => array(':x'=>'C'),
                                )) ;
                                
                                echo CHtml::dropDownList('BookID','',CHtml::listData($data,'id','admission_no'),array('prompt'=>Yii::t('app','Select'),'id'=>'book_id','submit'=>array('/library/ReturnBook/manage')));
                                
                                ?>
                                </td>
                            </tr>
                        </table>
                    </div> <!-- END div class="formConInner" -->
                </div> <!-- END div class="formCon" -->
            </div> <!-- END div class="cont_right" -->        
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>