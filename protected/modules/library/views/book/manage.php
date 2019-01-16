<?php

$this->breadcrumbs=array(

	Yii::t('app','Library')=>array('/library'),	

	Yii::t('app','List Books'),

);?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr>

                <td width="247" valign="top">

                <?php $this->renderPartial('/settings/library_left');?>

                </td>

                <td valign="top">

                    <div class="cont_right">

                        <h1><?php echo Yii::t('app','Manage Books');?></h1>

                        <?php

                        $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));

						if(Yii::app()->user->year)

						{

							$year = Yii::app()->user->year;

						}

						else

						{

							$year = $current_academic_yr->config_value;

						}

						$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));

						$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));

						if($year != $current_academic_yr->config_value and ($is_edit->settings_value==0 or $is_delete->settings_value==0))

						{

						?>

							<div>

								<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">

									<div class="y_bx_head" style="width:650px;">

									<?php 

										echo Yii::t('app','You are not viewing the current active year. ');

										if($is_edit->settings_value==0 and $is_delete->settings_value!=0)

										{

											echo Yii::t('app','To edit the book details, enable Edit option in Previous Academic Year Settings.');

										}

										elseif($is_edit->settings_value!=0 and $is_delete->settings_value==0)

										{

											echo Yii::t('app','To delete the book details, enable Delete option in Previous Academic Year Settings.');

										}

										else

										{

											echo Yii::t('app','To manage the book details, enable the required options in Previous Academic Year Settings.');	

										}

									?>

									</div>

									<div class="y_bx_list" style="width:650px;">

										<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>

									</div>

								</div>

							</div><br />

						<?php

						}

						$edit_n_delete = 0;

						if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 and $is_delete->settings_value!=0)))

						{

							$edit_n_delete = 1;

						}

						

						$edit_or_delete = 0;

						if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))

						{

							$edit_or_delete = 1;

						}

				

						?>

                        <?php

                        //$bookdetails=Book::model()->findAll('is_deleted=:x',array(':x'=>0));

						$criteria = new CDbCriteria;

						$criteria->compare('is_deleted',0);

						$criteria->order = 'id DESC';

						$bookdetails = Book::model()->findAll($criteria);

						?>

                        <div class="pdtab_Con" style="padding-top:0px;">

                            <table width="100%" cellpadding="0" cellspacing="0" border="0">

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

                                    <?php 

									if($edit_or_delete == 1)

									{

									?>

									<td align="center"><?php echo Yii::t('app','Action');?></td>

									<?php

									}

									?>

                                </tr>

                                <?php

                                if($bookdetails!=NULL)

                                {

									?>

									

									<?php foreach($bookdetails as $book)

									{

										$author=Author::model()->findByAttributes(array('auth_id'=>$book->author));

										$publication=Publication::model()->findByAttributes(array('publication_id'=>$book->publisher));

										$available_book = $book->copy - $book->copy_taken;

										?>

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

                                            <?php 

											if($edit_or_delete == 1)

											{

											?>

											<td align="center">

												<?php

												if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))

												{

												echo CHtml::link(Yii::t('app','Edit'),array('book/update','id'=>$book->id));

												}

												if($edit_n_delete ==1)

												{

													echo ' | ';

												}

												if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))

												{

												//echo CHtml::link(Yii::t('app','Remove'),array('book/remove','id'=>$book->id),array('onclick'=>'js:if(confirm("'.Yii::t('app','Remove book ?').'")){}else{return false;}'));

												

												echo CHtml::link(Yii::t('app','Remove'), "#", array("submit"=>array('book/remove','id'=>$book->id),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true));

												

												}

												?>

											</td>

											<?php

											}

											?>

										</tr>

									<?php 

									}

                                } 

                                else

                                {

                                	echo '<tr><td align="center" colspan="10">'.Yii::t('app','No data available').'</td></tr>';

                                }

                                ?>

                            </table>

                        </div> <!-- END div class="pdtab_Con" -->

                    </div> <!-- END div class="cont_right" -->

                </td>

            </tr>

        </table>

	

