<?php $time = time(); ?>

<div class="col-md-12">    
    <div class="row">    
        <div id="documentTable">          
            <div class="documnt_addlist"> 
                <div class="col-md-6">
                    <?php echo CHtml::activeLabel($model,Yii::t('app','Document')); ?>
                    <?php    
                        $criteria 		= new CDbCriteria;
                        $criteria->join = 'LEFT JOIN student_document osd ON osd.title = t.id and osd.student_id = '.$_REQUEST['id'];
                        $criteria->addCondition('osd.title IS NULL');
                        $criteria->condition	= 'is_required=:is_required';
                        $criteria->params		= array(':is_required'=>1); 
                        $criteria->addCondition('osd.title IS NULL');
                        $criteria->order		= 'name ASC';
                        $student_documents		= StudentDocumentList::model()->findAll($criteria);
                        
                        $document_arr	= array();
                        if($student_documents != NULL){
                            foreach($student_documents as $value){
                                $document_arr[$value->id]	= html_entity_decode(ucfirst($value->name));
                            }
                        }    
                    ?>
                    <div style="padding-right:20px;">
                        <?php echo CHtml::activeDropDownList($model,'title[]',$document_arr,array('prompt' => Yii::t('app','Select Document Type'),'class'=>'form-control mb15 title-field','id'=>$time)); ?>
                        <?php echo CHtml::error($model,'title'); ?>
                        <span class="title-error"></span>
                    </div>        
                </div>
                <div class="col-md-6">
                    <div class="custm_file">
                   <br />
                        <label for="StudentDocument_file_<?php echo $_REQUEST['count']; ?>" class="custom-file-upload"><i class="fa fa-cloud-upload"></i> <?php echo Yii::t('app', 'Upload File'); ?></label>
                        <span class="clearfix"></span>                
                        <?php echo CHtml::activeFileField($model,'file[]', array('class'=>'upload_file', 'id'=>'StudentDocument_file_'.$_REQUEST['count'])); ?>
                        <?php echo CHtml::error($model,'file'); ?>
                        <span class="file-error"></span>
                        <p style="font-size:11px;"><?php echo Yii::t('app','(Only files with these extensions are allowed: jpg, png, pdf, doc, txt.)'); ?></p>        
                    </div>
                </div>
            </div>
        </div> 
    </div>   
</div>                   
<div class="row" id="file_type">                
    <?php echo CHtml::activeHiddenField($model,'file_type[]'); ?>
    <?php echo CHtml::error($model,'file_type'); ?>
</div>

<div class="row" id="created_at">                
    <?php echo CHtml::activeHiddenField($model,'created_at[]'); ?>
    <?php echo CHtml::error($model,'created_at'); ?>
</div>
<script type="text/javascript">
	$("select#<?php echo $time; ?>").chosen({width:"286px"});		
</script>
<script type="text/javascript">
$('.upload_file').change(function(ev){
	var name	= $(this)[0].files[0].name;		
	$(this).closest('.custm_file').find('.clearfix').html(name);	
});   
</script>