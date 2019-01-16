<style>

.tr_color{color:#F00;}

ul.pages{ float:right}

ul.pages li{ float:left;

	list-style:none;

	margin:2px;}



ul.pages li a{ padding:5px 10px; ;

	border:1px solid #ccc;

	

	}

	

</style>

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
                  <h3 class="panel-title"><?php echo Yii::t('app','Borrowed Book List'); ?></h3>
                </div>

                <div class="people-item">
<div class="opnsl_headerBox">
    <div class="opnsl_actn_box"></div>
    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">
            <?php echo CHtml::link(Yii::t('app','Books List'),array('/library/book/manage'),array('class'=>'btn btn-primary')); ?> 
        </div>
    </div>
</div>

                    <div class="but_right_con"> </div>                    

                    <div class="table-responsive">

<table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered mb30">
    <thead>
    <tr>

                                <th><?php echo Yii::t('app','Sl.No');?></th>

                                <th><?php echo Yii::t('app','Book Name');?></th>

                                <th><?php echo Yii::t('app','Subject');?></th>

                                <th><?php echo Yii::t('app','Issued Date');?></th>

                                <th><?php echo Yii::t('app','Due Date');?></th>

                                <th><?php echo Yii::t('app','Status');?></th>                                

                      		</tr>
                            </thead>

<?php

					if($bookLists!=NULL){

						$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));

						if(isset($_REQUEST['page'])){

                            $i=($pages->pageSize*$_REQUEST['page'])-9;

						}else{

							$i=1;

						}

                    	foreach($bookLists as $bookList){

							if($bookList->due_date < date('Y-m-d') and $bookList->status == 'C'){

								$class = 'tr_color'; 

							}else{

								$class = ''; 

							}

							if($settings){

								$bookList->issue_date = date($settings->displaydate,strtotime($bookList->issue_date));

								$bookList->due_date = date($settings->displaydate,strtotime($bookList->due_date));

							}

                                                    

?>                                                	

							<tr class="<?php echo $class; ?>">

                            	<td><?php echo $i; ?></td>

                                <td><?php echo ucfirst($bookList->book_name); ?></td>

                                <td><?php echo ucfirst($bookList->subject); ?></td>

                                <td><?php echo $bookList->issue_date; ?></td>

                                <td><?php echo $bookList->due_date; ?></td>

                                <td>

                                	<?php

										if($bookList->status == 'C'){

											echo Yii::t('app','Not Returned');

										}elseif($bookList->status == 'R'){

											echo Yii::t('app','Returned');

										}

									?>

                                </td>

                            </tr>                      

<?php

							$i++;                      

						}

					}else{

						echo '<tr><td align="center" colspan="6">'.Yii::t('app','No Books Borrowed').'</td></tr>';

					}

?>					

                    </table>

                  </div>

                  <div class="pagecon">

                        <?php                                          

							$this->widget('CLinkPager', array(

							'currentPage'=>$pages->getCurrentPage(),

							'itemCount'=>$item_count,

							'pageSize'=>$page_size,

							'maxButtonCount'=>5,

							//'nextPageLabel'=>'My text >',

							'header'=>'',

							'htmlOptions'=>array('class'=>'pages'),

							));

						?>

                        

                  </div> 

                </div>

                

                <!-- END div class="profile_details" --> 

              </div>

  				<!-- END div class="parentright_innercon" -->

  

      		<div class="clear"></div>

    	</div> <!-- END div id="parent_rightSect" -->

	<div class="clear"></div> 

</div> <!-- END div id="parent_Sect" -->

	

