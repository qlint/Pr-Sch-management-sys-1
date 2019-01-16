<style>
.pdtab_Con table td {padding: 10px 12px;}
.pdtab_Con1-scrol { overflow:hidden; overflow:scroll;}
.update-btn{
	position:relative;
    width: 100%;
    height: 39px;
    margin-bottom: 7px;
}
.edit_bttns{
	top:0px;
	right:0px;
}
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

<script language="javascript">
function structureskill()
{
var id = document.getElementById('skill').value;
window.location= 'index.php?r=examination/coScholastic/&skill='+id+"&id="+"<?php echo $batch_id; ?>";	
}
</script>


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
                            <h1><?php echo Yii::t('app','Manage Co-Scholastic Skill'); ?></h1>  
                            <div class="clear"></div>
                                <div class="emp_right_contner">
                                    <div class="emp_tabwrapper">
                                                                <?php  $this->renderPartial('/default/tab'); ?>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            
                            <?php if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2)
                            {?>
                            
                                <?php /*?><div class="edit_bttns" style="top:20px; right:20px;">
                                     <?php 
                                    if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                    {
                                    ?>
                                        <ul>
                                                <li><?php 
                                                $sel="All";
                                                if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                                {
                                                    $sel= $_REQUEST['skill'];                                                                
                                                }
                                                echo CHtml::link('<span>'.Yii::t('app','Update').'</span>', array('/examination/coScholastic/manage','skill'=>$sel,'id'=>$_REQUEST['id']),array('class'=>'addbttn last ')); ?></li>
                                        </ul>
                                    <?php } ?>
                                </div><?php */?>
                            
                                <div class="formCon">
                                    <div class="formConInner"><div>
                                            <span style="font-size:14px; font-weight:bold; color:#666;">                                            
                                                <?php echo Yii::t('app','Select Skill'); ?>
                                            </span>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php 
                                            if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                            {
                                                $sel= $_REQUEST['skill'];
                                            }
                                            $models= CbscCoScholastic::model()->findAllByAttributes(array('batch_id'=>$batch_id));
                                            $data= CHtml::listData($models, 'id', 'skill');
                                            echo CHtml::dropDownList('id','',$data,array('empty'=>Yii::t('app','Select'),'onchange'=>'structureskill()','id'=>'skill','options'=>array($sel=>array('selected'=>true))));                             
                                            ?>
                                        </div>
                                    </div><br><br>
                                </div>
                            
                                <?php 
                                if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                {
                                ?>
								<div class="update-btn">
									<div class="edit_bttns">
                                     <?php 
                                    if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                    {
                                    ?>
                                        <ul>
                                                <li><?php 
                                                $sel="All";
                                                if(isset($_REQUEST['skill']) && $_REQUEST['skill']!="")
                                                {
                                                    $sel= $_REQUEST['skill'];                                                                
                                                }
                                                echo CHtml::link('<span>'.Yii::t('app','Manage').'</span>', array('/examination/coScholastic/manage','skill'=>$sel,'id'=>$_REQUEST['id']),array('class'=>'addbttn last ')); ?></li>
                                        </ul>
                                    <?php } ?>
                                </div>
								</div>
								
								
                                    <div class="pdtab_Con" style="padding-top:0px;">
                                    <div class="pdtab">
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
                                                        echo "<tr>";
                                                        if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                        {
                                                            $name='';
                                                            $name=  $posts_1->studentFullName('forStudentProfile');
                                                            echo '<td>'.CHtml::link($name, array('/students/students/view', 'id'=>$posts_1->id)).'</td>';
                                                            $score_model= CbscCoscholasticScore::model()->findByAttributes(array('student_id'=>$posts_1->id,'coscholastic_id'=>$_REQUEST['skill']));
                                                            if($score_model!=NULL)
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
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

