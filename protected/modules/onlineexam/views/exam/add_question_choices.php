<?php 
$option="A";
$alphas = range('A', 'Z');
if(isset($ptrow))
{
    $option=    $alphas[$ptrow];
}
?>

<?php 
if(isset($model->id) && $model->id!=NULL)
{
    $choice_array= array();
    $index_array= array();
    if($model->question_type==1)
    {
        
        $answer	= 	OnlineExamAnswers::model()->findAllByAttributes(array('question_id'=>$model->id));
        if($answer!=NULL)
        {
            foreach($answer as $data)
            {
                $index_array[]=$data->id; 
                $choice_array[$data->id]= $data->answer;
            }                        
        }
        $choice_answer_id = array_search($model->answer_id, ($index_array));                    
    }
    
}
?>

<?php 
if($answer==NULL){
?>
<div class="choice-data" id="choice-data-<?php echo $ptrow;?>"  data-row="<?php echo $ptrow;?>">	                                     
    <div class="Question-block multi_addBlock">
                <div class="multi_List_addBlock qn_actionBtn_brd">
                    <label class="main number main-nm-bg cmd-bg" data="<?php echo $ptrow;?>"><?php echo Yii::t('app',$option); ?></label>                    
                </div>
                <div class="multi_List_addBlock">
                    <?php echo CHtml::activeTextField($model,'choice_answer['.$ptrow.']',array('placeholder'=>Yii::t('app', 'Option Value'), 'class'=>'form-control for multi-choice choice-value','style'=>'')); ?>
                    
                </div>
                <?php if(isset($ptrow) && $ptrow>0){ ?>
                <div class="multi_List_addBlock ">
                    <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to remove particular");?>" class="remove-choice qn_delete_Btn "><?php echo Yii::t("app", "");?></a>                 
                </div>
                <?php } ?>
        
    </div>                        
    <br />                              
</div>
<?php 
}
else
{
    foreach ($answer as $answer_data)
    {
        $right_class='';
        if($model->answer_id==$answer_data->id)
        {
            $right_class='right';
        }
    ?>
    <div class="choice-data" id="choice-data-<?php echo $ptrow;?>"  data-row="<?php echo $ptrow;?>">	                                     
        <div class="Question-block multi_addBlock">
            <div class="multi_List_addBlock qn_actionBtn_brd">
                <label class="main number main-nm-bg cmd-bg <?php echo $right_class; ?>" data="<?php echo $ptrow;?>"><?php echo Yii::t('app',$alphas[$ptrow]); ?></label>                    
            </div>
            <div class="multi_List_addBlock">
                <?php echo CHtml::activeTextField($model,'choice_answer['.$ptrow.']',array('value'=>$answer_data->answer,'placeholder'=>Yii::t('app', 'Option Value'), 'class'=>'form-control for multi-choice choice-value','style'=>'')); ?>

            </div>
            <?php if(isset($ptrow) && $ptrow>0){ ?>
            <div class="multi_List_addBlock ">
                <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to remove particular");?>" class="remove-choice fees-trash"><?php echo Yii::t("app", "");?></a>                 
            </div>
            <?php } ?>
        </div><br />                       
                                   
    </div>           
    <?php
    $ptrow++;
    }
    $ptrow= count($parameters);
}
?>