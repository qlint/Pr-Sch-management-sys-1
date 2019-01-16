        <div id="parent_Sect">

			<?php 

				echo $this->renderPartial('application.modules.studentportal.views.default.leftside'); 				

			?>

            <div class="pageheader">

              <div class="col-lg-8">

                <h2><i class="fa fa-folder-open"></i><?php echo Yii::t('app','Library'); ?><span><?php echo Yii::t('app','View Library');?> </span></h2>

              </div>

              <div class="col-lg-2"> </div>

              <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:');?></span>

                <ol class="breadcrumb">

                  <li class="active"><?php echo Yii::t('app','Book List');?></li>

                </ol>

              </div>

              <div class="clearfix"></div>

            </div>

            <div class="contentpanel">

              <div>

                <div class="panel-heading">

                  <h3 class="panel-title"><?php echo Yii::t('app','Book List'); ?></h3>

                </div>

                <div class="people-item">
<div class="opnsl_headerBox">
<div class="opnsl_actn_box"></div>
    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">
            <?php echo CHtml::link(Yii::t('app','Search Books'),array('/library/book/booksearch'),array('class'=>'btn btn-primary')); ?>
        </div>
    </div>
 </div>

                  <div class="but_right_con"> </div>

                  <?php

                    $bookdetails=Book::model()->findAll('is_deleted=:x',array(':x'=>0));?>

                  <div class="table-responsive">

<table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered mb30">
    <thead>
    <tr>
                        <th><?php echo Yii::t('app','ISBN');?></th>

                        <th><?php echo Yii::t('app','Book Name');?></th>

                        <th><?php echo Yii::t('app','Author');?></th>

                        <th><?php echo Yii::t('app','Edition');?></th>

                        <th><?php echo Yii::t('app','Publisher');?></th>

                        <th><?php echo Yii::t('app','Copies Available');?></th>

                        <th><?php echo Yii::t('app','Total Copies');?></th>

                      </tr>
                      </thead>

                      <?php

                                            if($bookdetails!=NULL)

                                            {

                                            ?>

                      <?php foreach($bookdetails as $book)

                                                {

                                                    $author=Author::model()->findByAttributes(array('auth_id'=>$book->author));

                                                    $publication=Publication::model()->findByAttributes(array('publication_id'=>$book->publisher));

                                                    $available_copies = $book->copy - $book->copy_taken;

                                                ?>

                      <tr>

                        <td><?php echo $book->isbn;?></td>

                        <td><?php echo $book->title;?></td>

                        <td><?php 

                                                        if($author!=NULL)

                                                        {

                                                            echo CHtml::link(ucfirst($author->author_name), array('/library/authors/authordetails','id'=>$author->auth_id));

                                                        }

                                                        ?></td>

                        <td><?php echo $book->edition;?></td>

                        <td><?php 

                                                        if($publication!=NULL)

                                                        {

                                                            echo $publication->name;

                                                        }

                                                        ?></td>

                        <td><?php echo $available_copies;?></td>

                        <td><?php echo $book->copy; ?></td>

                      </tr>

                      <?php

                                                }

                                            }  // END if($bookdetails!=NULL)

                                            else

                                            {

                                                echo '<tr><td align="center" colspan="7">'.Yii::t('app','No data available').'</td></tr>';

                                            }

                                            ?>

                    </table>

                  </div>

                </div>

                

                <!-- END div class="profile_details" --> 

              </div>

  				<!-- END div class="parentright_innercon" -->

  

      		<div class="clear"></div>

    	</div> <!-- END div id="parent_rightSect" -->

	<div class="clear"></div> 

</div> <!-- END div id="parent_Sect" -->

	

