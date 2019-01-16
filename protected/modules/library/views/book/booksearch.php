<?php

$this->breadcrumbs=array(

	Yii::t('app','Library')=>array('/library'),	

	Yii::t('app','Search Books'),

);?>



<?php $form=$this->beginWidget('CActiveForm', array(

	'id'=>'book-form',

	'enableAjaxValidation'=>false,

)); ?>



        <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr>

                <td width="247" valign="top">

                	<?php $this->renderPartial('/settings/library_left');?>

                </td>

                <td valign="top">

                    <div class="cont_right">

                        <h1><?php echo Yii::t('app','Search Books');?></h1>

                        <div class="formCon">

                            <div class="formConInner">

                                <table width="67%" border="0" cellspacing="0" cellpadding="0">

                                    <tr>

                                        <td align="left" style="padding-right:5px;">

                                        <?php

                                        echo CHtml::label(Yii::t('app','Search Book by'),'').'</td><td>'; 

                                        echo CHtml::dropDownList('search','',array('1'=>'Subjects','2'=>'Title','3'=>'Author','4'=>'ISBN'),array('prompt'=>Yii::t('app','Select'),'id'=>'search_id')).'</td><td>';

                                        echo CHtml::textField('text','');

                                        ?>

                                        </td>

                                       

                                    </tr>

                                    <tr>

                                        <td><br /><input type="submit" value="<?php echo Yii::t('app','Search'); ?>" class="formbut" /></td>

                                    </tr>

                                </table>

                            </div> <!-- END div class="formConInner" -->

                        </div> <!-- END div class="formCon" -->

                        <?php

                        

                        if(isset($list))

                        {												

							?>

							

                                <div class="pdtab_Con" style="padding-top:10px;">

                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" >

                                        <tr class="pdtab-h">

                                            <td align="center"><?php echo Yii::t('app','Subject');?></td>

                                            <td align="center"><?php echo Yii::t('app','Book Title');?></td>

                                            <td align="center"><?php echo Yii::t('app','ISBN');?></td>

                                            <td align="center"><?php echo Yii::t('app','Author');?></td>

                                            <td align="center"><?php echo Yii::t('app','Copies Available');?></td>

                                            <td align="center"><?php echo Yii::t('app','Total Copies');?></td>

                                            <td align="center"><?php echo Yii::t('app','Book Position');?></td>

                                            <td align="center"><?php echo Yii::t('app','Shelf No');?></td>

                                    	</tr>

										<?php

										if($list){

											foreach($list as $list_1)

											{                                        											

												$author=Author::model()->findByAttributes(array('auth_id'=>$list_1->author));											

												$copies_available = $list_1->copy - $list_1->copy_taken;

												

												?>

												<tr>

													<td align="center"><?php echo ucfirst($list_1->subject);?></td>

													<td align="center"><?php echo ucfirst($list_1->title);?></td>

													<td align="center"><?php echo $list_1->isbn;?></td>

													<td align="center">

													<?php 

													if($author!=NULL)

													{

														//echo "'id'=>$author->auth_id";

														echo CHtml::link(ucfirst($author->author_name),array('/library/authors/authordetails','id'=>$author->auth_id));

													}

													?>

													</td>

													<td align="center"><?php echo $copies_available;?></td>

													<td align="center"><?php echo $list_1->copy;?></td>

													<td align="center"><?php echo $list_1->book_position;?></td>

													<td align="center"><?php echo $list_1->shelf_no;?></td>

												</tr>

											<?php 

											}

										}

										else{

										?>

                                        	<tr>

                                            	<td colspan="8" class="nothing-found"><?php echo Yii::t('app', 'No books found'); ?></td>

                                            </tr>

                                        <?php	

										}

                                        ?>

                                    </table>

                                </div> <!-- END div class="pdtab_Con" -->

							<?php

                        } // END if(isset($list))

                        ?>

                    </div> <!-- END div class="cont_right" -->

                </td>

            </tr>

        </table>       

<?php $this->endWidget(); ?>