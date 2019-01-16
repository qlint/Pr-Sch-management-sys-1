

<?php echo $this->renderPartial('/default/leftside');?>
<div class="pageheader">
      <h2><i class="fa fa-pencil"></i> <?php echo Yii::t("app", "Results");?> <span><?php echo Yii::t("app", "View your results here");?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t("app", "You are here:");?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t("app", "Results");?></li>
        </ol>
   </div>
</div>
<div class="contentpanel">    
	<div class="panel-heading">
    	
		<h3 class="panel-title"><?php echo Yii::t('app', 'My Class(es) Exam Results'); ?></h3>
	</div>
    <div class="people-item">
<div class="opnsl_headerBox">
                <div class="opnsl_actn_box"> </div>
                    <div class="opnsl_actn_box">
                    <?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'addbttn last'));?>                 
                        
                    </div>
                </div>
    
    
	<?php
	if(!($students)){ // Displaying message
	?>
    <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;margin-top:60px;">
        <div class="y_bx_head">
            <?php echo Yii::t('app','No Students Found!!'); ?>
        </div>      
    </div>
    <?php
	}
	else{
	?>
    	<div class="table-responsive">
            <table width="80%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
                
                <thead>
                    <tr >
                     			<th ><?php echo Yii::t('app','Admission Number');?></th>
                         <?php if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                { ?>
                        		<th ><?php echo Yii::t('app','Student Name');?></th>
                        <?php } ?>
                        		<th ><?php echo Yii::t('app','Actions');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
					if(isset($_REQUEST['page']))
                            {
                            	$i=($pages->pageSize*$_REQUEST['page'])-9;
                            }
                            else
                            {
                            	$i=1;
                            } 
					foreach($students as $student)
					{

						echo '<tr id="batchrow'.$student->id.'">'; 
						echo "<td width='150'>".$student->admission_no."</td>";
						 if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
							echo "<td width='250'>".$student->studentFullName("forStudentProfile")."</td>";
						 }
						
						echo '<td>';
						echo CHtml::link(Yii::t('app','View'), array('/teachersportal/exams/result','id'=>$student->id, 'bid'=>$_REQUEST['bid']), array('class'=>'view_Exmintn_atg Exm_aTgColor_view')); 
						
						echo '</td>';
						
						echo '</tr>';
						$i++;
					}
					?>
                </tbody>
            </table>
            
                <div class="pager">
                <?php                                          
                  $this->widget('CLinkPager', array(
                  'currentPage'=>$pages->getCurrentPage(),
                  'itemCount'=>$item_count,
                  'pageSize'=>$page_size,
                  'maxButtonCount'=>5,
                  'prevPageLabel'=>'< Prev',
                  //'nextPageLabel'=>'My text >',
                  'header'=>'',
                'htmlOptions'=>array('class'=>'pages'),
                ));?>
                </div> <!-- END div class="pagecon" 2 -->
                <div class="clear"></div>
		</div>
<?php } ?>	
    </div>
</div>