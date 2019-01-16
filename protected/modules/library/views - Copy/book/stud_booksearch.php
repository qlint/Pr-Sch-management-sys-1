<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>

        <div id="parent_Sect">
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
        	<?php echo $this->renderPartial('application.modules.studentportal.views.default.leftside'); ?>
            <div class="contentpanel">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo Yii::t('app','Book List'); ?></h3>
                </div>
                <div class="panel-body">
                  <div class="row" style="margin-bottom:20px;">
                   <div class="col-sm-4 col-4-reqst">
                      <div class="form-group">
                        <?php                                         
                            echo CHtml::dropDownList('search','',array('1'=>'Subjects','2'=>'Title','3'=>'Author','4'=>'ISBN'),array('prompt'=>Yii::t('app','Select'),'id'=>'search_id','class'=>'form-control mb15')).'';            
                        ?>
                            
                       </div>
                   </div>
                     <div class="col-sm-4 col-4-reqst">
                         <div class="form-group">
                       
                          <?php
                           echo CHtml::textField('text','',array('class'=>'form-control mb15'));
                                                    ?>
                            
                         </div>
                      </div>
                      <div class="col-sm-4 col-4-reqst">
                            <input type="submit" value="search" class="btn btn-primary" />
                         </div>   
                    
                  </div>
                  <!-- END div class="form_wrapper" -->
                  <?php 
                                    if(isset($list))
                                    {
                                        if($list==NULL)
                                        {
                                            echo '<div align="center"><strong>'.Yii::t('app','OOPS!! Its an invalid search.Try again..').'</strong></div>';
                                        }
                                        else
                                        {
                                        ?>
                  <div class="table-responsive">
                   <table width="100%" class="table table-bordered mb30" cellpadding="0" cellspacing="0">
                          <thead>
                      <tr>
                        <th><?php echo Yii::t('app','Subject');?></th>
                        <th><?php echo Yii::t('app','Book Title');?></th>
                        <th><?php echo Yii::t('app','ISBN');?></th>
                        <th><?php echo Yii::t('app','Author');?></th>
                        <th><?php echo Yii::t('app','Copies Available');?></th>
                        <th><?php echo Yii::t('app','Total Copies');?></th>
                        <th><?php echo Yii::t('app','Book Position');?></th>
                        <th><?php echo Yii::t('app','Shelf No');?></th>
                      </tr>
                      </thead>
                      <?php
                                                foreach($list as $list_1)
                                                {
                                                    $sub=Subjects::model()->findByAttributes(array('id'=>$list_1->subject));
                                                    $author=Author::model()->findByAttributes(array('auth_id'=>$list_1->author));
                                                    $total_copies = $list_1->copy + $list_1->copy_taken;
                                                
                                                ?>
                      <tr>
                        <td><?php echo $list_1->subject;?></td>
                        <td><?php echo $list_1->title;?></td>
                        <td><?php echo $list_1->isbn;?></td>
                        <td><?php 
                                                    if($author!=NULL)
                                                    {
                                                        echo CHtml::link($author->author_name,array('/library/authors/authordetails','id'=>$author->auth_id));
                                                    }
                                                    ?></td>
                        <td><?php echo $list_1->copy;?></td>
                        <td><?php echo $total_copies;?></td>
                        <td><?php echo $list_1->book_position;?></td>
                        <td><?php echo $list_1->shelf_no;?></td>
                      </tr>
                      <?php 
                                                }
                                                ?>
                    </table>
                  </div>
                  <!-- END div class="pdtab_Con" -->
                  <?php
                                        }
                                    } // END if(isset($list))
                                    ?>
                </div>
                <!-- END div class="profile_details" --> 
                
              </div>
  <!-- END div id="parent_rightSect" --> 
  
</div> <!-- END div id="parent_rightSect" -->
            <div class="clear"></div> 
        </div> <!-- END div id="parent_Sect" -->
        
<?php $this->endWidget(); ?>