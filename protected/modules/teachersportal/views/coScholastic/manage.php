<style>
    .test-posn{
        float: left;
        line-height: 39px;
        font-size:14px; 
        font-weight:bold; 
       
}
.form-control-n{
    float: right !important;
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
                <li><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/index'),array('class'=>'addbttn last'));?></li>                                
    		
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
                                        echo CHtml::link('<span>'.Yii::t('app','Update').'</span>', array('/teachersportal/coScholastic/manage','skill'=>$sel,'id'=>$_REQUEST['bid']),array('class'=>'addbttn last ')); ?>
                                        </li>                                       
                                    <?php } ?>                                
                    <?php } ?>
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
                        echo CHtml::dropDownList('id','',$data,array('empty'=>Yii::t('app','Select'),'class'=>'form-control form-control-n','onchange'=>'structureskill()','id'=>'skill', 'encode'=> false, 'options'=>array($sel=>array('selected'=>true))));                             
                        ?>
                    </div>
                    <br><br>                                
                    <div class="clear"></div>
                    <br><br>
                        <?php 
                        if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                        {
                        ?>
                            <?php $form=$this->beginWidget('CActiveForm', array(
                                                            'method'=>"POST",
                                                            'id'=>'co-scholastic-form',
                                                    )); ?>
                            <?php echo CHtml::hiddenField("batch_id",$_REQUEST['bid'],array()); ?>
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
                                                $student_id= $posts_1->id;
                                                $skill_id= $_REQUEST['skill'];
                                                $field_name= "data[$skill_id][$student_id]";
                                                $id= $post_1->id."data".$_REQUEST['skill'];

                                                echo "<tr>";
                                                if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                {
                                                    $name='';
                                                    $score="";
                                                    $name=  $posts_1->studentFullName('forStudentProfile');
                                                    echo '<td>'.CHtml::link($name, array('/teachersportal/course/students/', 'student_id'=>$posts_1->id)).'</td>';
                                                    $score_model= CbscCoscholasticScore::model()->findByAttributes(array('student_id'=>$posts_1->id,'coscholastic_id'=>$_REQUEST['skill']));
                                                    if($score_model!=NULL)
                                                    {
                                                       $score= $score_model->score;
                                                    }
                                                    ?>                                                                
                                                    <td align="center" width="30%"><?php echo CHtml::textField($field_name,$score,array('id'=>$id,'class'=>'form-control','style'=>'width:150px; border: 1px solid #C2CFD8;')); ?></td>
                                                    <?php
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
                                <?php echo CHtml::submitButton(Yii::t("app", "Save"), array("class"=>"btn btn-danger"));?>
                                <?php $this->endWidget(); ?>

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

<script>
    $("form#co-scholastic-form").submit(function(e) 
    {

        var a=0;
        $('input:text').each(function() 
        {
            var that= this;
            $(that).css('border-color','');
            if(this.value)
            {
                var val_id= this.value;                
                if(val_id!="")
                {                    
                    if(Math.floor(val_id) != val_id || val_id > 5 || val_id < 1)
                    {                        
                        a =1;                        
                        $(that).css('border-color','red');
                    }
                    else
                    {
                        $(that).css('border-color','');
                    }                    
                }
            }           
        });
        if(a==1)
        {
            alert("<?php echo Yii::t('app','Score must be within the range 1-5'); ?>");
            return false;
        }
        else
        {
            
            var that	= this;
            var data	= $(that).serialize();
            $.ajax({
			url:'<?php echo Yii::app()->createUrl("/teachersportal/coScholastic/add");?>',
			type:'POST',
			data:data,
			dataType:"json",
			success: function(response)
                        {				
                            if(response.status=="success"){
                                    window.location.href	= response.redirect;
                            }
                            else{
                                    alert("<?php echo Yii::t("app", "Error");?>");
                            }
			}
		});
		
		return false;
      }
        
        
    });
    </script>
    
    