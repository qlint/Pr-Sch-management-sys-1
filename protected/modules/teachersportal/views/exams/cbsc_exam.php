<style>
    .test-posn{
        float: left;
        line-height: 39px;
        font-size:14px; 
        font-weight:bold; 
       
}
.form-control{
    float: right;
    width: 84%;
}
    
</style>


<?php echo $this->renderPartial('/default/leftside');?>
<?php 
$batch_id="";
if(isset($_REQUEST['bid']) && $_REQUEST['bid']!=NULL)
{
    $batch_id= $_REQUEST['bid'];
}
?>
<script language="javascript">
function structureskill()
{
var id = document.getElementById('skill').value;
window.location= 'index.php?r=teachersportal/coScholastic/&skill='+id+"&bid="+"<?php echo $batch_id; ?>";	
}
</script>

<div class="pageheader">
      <h2><i class="fa fa-pencil"></i> <?php echo Yii::t("app", "Exams");?> <span><?php echo Yii::t("app", "View Co-Scholastic Skills here");?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t("app", "You are here:");?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t("app", "Exams");?></li>
        </ol>
   </div>
</div>
<div class="contentpanel">    
	<div class="panel-heading">
    	
		<h3 class="panel-title"><?php echo Yii::t('app', 'Co-Scholastic Skills Details'); ?></h3>
	</div>
    <div class="people-item">
    <div class="btn-demo" style="position:relative; top:-8px; right:3px; float:right;">
        <div class="edit_bttns">
    		<ul>       
                
    		
                    <?php 
                    if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2)
                            {?>                                                            
                                     <?php 
                                    if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                    {
                                    ?>                                       
                                        <li><?php                                         
                                        if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                        {
                                            $sel= $_REQUEST['skill'];                                                                
                                        }
                                        echo CHtml::link('<span>'.Yii::t('app','Update').'</span>', array('/teachersportal/coScholastic/manage','skill'=>$sel,'bid'=>$_REQUEST['bid']),array('class'=>'addbttn last ')); ?>
                                        </li>                                       
                                    <?php } ?>                                
                    <?php } ?>
                                        
                                        <li><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/index'),array('class'=>'addbttn last'));?></li>                                
                </ul>
    		<div class="clear"></div>
		</div>
	</div>
    <br />
<div class="table-responsive">
	<?php        
        if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2)
                            {?>
                            
                                
                              
                                        <div style="float:left; width:480px;">
                                            <span class="test-posn">                                            
                                                <?php echo Yii::t('app','Select Skill'); ?>
                                            </span>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php 
                                            if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                            {
                                                $sel= $_REQUEST['skill'];
                                            }
                                            $models= CbscCoScholastic::model()->findAllByAttributes(array('batch_id'=>$batch_id));
                                            $data= CHtml::listData($models, 'id', 'skill');
                                            echo CHtml::dropDownList('id','',$data,array('empty'=>Yii::t('app','Select'),'class'=>'form-control','onchange'=>'structureskill()','id'=>'skill','options'=>array($sel=>array('selected'=>true))));                             
                                            ?>
                                        </div>
                                   <br><br>
                                
                            <div class="clear"></div>
                            <br><br>
                                <?php 
                                if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                {
                                ?>
                                    <table width="80%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
														
                                        <thead>
                                                <tr >
                                                        <th ><?php echo Yii::t('app','Student Name'); ?></th>
                                                        <th ><?php echo Yii::t('app','Score');?></th>                                                        
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                $posts=Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['bid']);
                                                if($posts!=NULL)
                                                {
                                                    foreach($posts as $posts_1)
                                                    {
                                                        echo "<tr>";
                                                        if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                        {
                                                            $name='';
                                                            $name=  $posts_1->studentFullName('forStudentProfile');
                                                            echo '<td>'.CHtml::link($name, array('/teachersportal/course/students/', 'student_id'=>$posts_1->id)).'</td>';
                                                            $score_model= CbscCoscholasticScore::model()->findByAttributes(array('student_id'=>$posts_1->id,'coscholastic_id'=>$_REQUEST['skill']));
                                                            if($score_model!=NULL && $score_model->score!="")
                                                            {
                                                                echo "<td align='center'>".$score_model->score."</td>";
                                                            }
                                                            else
                                                            {
                                                                echo '<td align="center">-</td>';
                                                            }
                                                        }
                                                        echo "</tr>";
                                                    }

                                                }
                                                else
                                                {
                                                    echo "<tr><td colspan='2' align='center'>".Yii::t("app","No Students Found")."</td></tr>";
                                                }
                                                ?>
                                                    </tbody>
                                        </table>
                                    </div>
                            </div>
                                <?php                             
                                }
                                ?>
                            <?php                         
                            }
                            else
                            {
                                ?>
                                <div class="formCon">
                                    <div class="formConInner">
                                        <center><?php echo Yii::t("app", "Cannot manage Co-Scholastic skills for this batch.") ?></center>
                                    </div>
                                </div>
                                    <?php
                            }
                            ?>
    
    
 
</div>
</div>
</div>