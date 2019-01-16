<style>
.pdtab_Con table td {padding: 8px 7px;}
.pdtab_Con1-scrol { overflow:hidden; overflow:scroll;}

</style>


<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/'),
	
	Yii::t('app','Co-Scholastic Skills'),
);
?>
<?php 
$batch_id="";
if(isset($_REQUEST['id']) && $_REQUEST['id']!=NULL)
{
    $batch_id= $_REQUEST['id'];
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">    
            <?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="247">
                        <div class="cont_right formWrapper">
                            
                                
                            <h1><?php echo Yii::t('app','Update Co-Scholastic Skills'); ?></h1>            
                                <div class="clear"></div>
                                <div class="emp_right_contner">
                                    <div class="emp_tabwrapper">
                                                                <?php  $this->renderPartial('/default/tab'); ?>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                           
                                
                            <?php 
                            if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2)
                            {
                                if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                {
                                ?>
                                    <div class="pdtab_Con" style="padding-top:0px;">
                                        <?php $form=$this->beginWidget('CActiveForm', array(
                                                            'method'=>"POST",
                                                            'id'=>'co-scholastic-form',


                                                    )); ?>
                                               <?php echo CHtml::hiddenField("batch_id",$_REQUEST['id'],array()); ?>
                                            <div> 
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr class="pdtab-h">
                                                            <td rowspan="" align="center"><?php echo Yii::t('app','Student Name');?></td>
                                                            <td rowspan="" align="center"><?php echo Yii::t('app','Score');?></td>                                                
                                                        </tr>
                                                        <?php 
                                                        $posts=Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['id']);
                                                        if($posts!=NULL)
                                                        {
                                                            foreach($posts as $posts_1)
                                                            {
                                                                $student_id= $posts_1->id;
                                                                $skill_id= $_REQUEST['skill'];
                                                                $field_name= "data[$skill_id][$student_id]";
                                                                $id= $posts_1->id."data".$_REQUEST['skill'];

                                                                echo "<tr>";
                                                                if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                                {
                                                                    $name='';
                                                                    $score="";
                                                                    $name=  $posts_1->studentFullName('forStudentProfile');
                                                                    echo '<td>'.CHtml::link($name, array('/students/students/view', 'id'=>$posts_1->id)).'</td>';
                                                                    $score_model= CbscCoscholasticScore::model()->findByAttributes(array('student_id'=>$posts_1->id,'coscholastic_id'=>$_REQUEST['skill']));
                                                                    if($score_model!=NULL)
                                                                    {
                                                                       $score= $score_model->score;
                                                                    }
                                                                    ?>                                                                
                                                                    <td align="center" width="30%"><?php echo CHtml::textField($field_name,$score,array('id'=>$id,'style'=>'width:100px; border: 1px solid #C2CFD8;')); ?></td>
                                                                    <?php
                                                                }
                                                                echo "</tr>";
                                                            }

                                                        }
                                                        ?>
                                                </table>
                                            </div>
                                        <br>
                                        <?php echo CHtml::submitButton(Yii::t("app", "Save"), array("class"=>"formbut"));?>
                                         <?php $this->endWidget(); ?>
                                    </div>	
                                <?php }
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
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<script>
    $("form#co-scholastic-form").submit(function(e) 
    {
        
        var a=0;
        var b=0;
        $('input:text').each(function() 
        {
            if(this.value)
            {
                var val_id= this.value;                
                if(val_id!="")
                {
                    var id= $(this).attr('id');                   
                    if(Math.floor(val_id) != val_id)
                    {                        
                        a =1;                        
                        $("#"+id).css('border-color','red');
                    }
                    else if(val_id > 5 || val_id < 1)
                    {
                        b =1;                        
                        $("#"+id).css('border-color','red');                        
                    } 
                    else
                    {
                        $("#"+id).css('border-color','');
                    }
                }
            }           
        });
        if(a==1)
        {
            alert("<?php echo Yii::t('app','Allows interger values only'); ?>");
            return false;
        }
        else if(b==1)
        {
            alert("<?php echo Yii::t('app','Score must be less than 6 and greater than 0'); ?>");
            return false;
        }
        else
        {
            var that	= this;
            var data	= $(that).serialize();
            $.ajax({
			url:'<?php echo Yii::app()->createUrl("/examination/coScholastic/add");?>',
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
    
    