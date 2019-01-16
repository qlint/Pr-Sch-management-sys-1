<style type="text/css">
.cke_skin_kama .cke_wrapper{ width:100% !important;}
.ui-state-default{
	 width:100% !important;	
}
.ui-combobox{
		 width:100% !important;
}
 </style>

<?php
$this->breadcrumbs=array(
	Yii::t('app',ucfirst($this->module->id))=>array('inbox'),
	Yii::t('app',ucfirst($this->getAction()->getId()))
);
?>

<script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript">
  window.parent.CKEDITOR.tools.callFunction(CKEditorFuncNum, 
    url, errorMessage);
</script>
<script type="text/javascript">
$(document).ready(function () {
	var config =
	    {
		height: 300,
		width : '95%',
		resize_enabled : false,
		language:'<?php echo Yii::app()->language;?>',
		toolbar :

		[

		['Bold','Italic','Underline','Strike','-','Subscript','Superscript','-','SelectAll','RemoveFormat'],

		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],

		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],

		]

	};
        //Set for the CKEditor
		$('#DashboardMessage_text').ckeditor(config);

    });


  
</script>

    <?php $this->renderPartial('/default/left_side');?>

<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-envelope-o"></i><?php echo Yii::t('app','Message'); ?><span><?php echo Yii::t('app','Compose new message here.'); ?></span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Messages'); ?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    
    
    
    
    
    
    <div class="contentpanel">
<div class="col-sm-9 col-lg-12">

<div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Compose new message here.'); ?></h3>
                        </div>
<div class="people-item">


<!--     inbox, sent mwessage, trash menu     -->     
<?php /*?><?php
$this->renderpartial('_menu');
?><?php */?>
<div class="mailbox-compose ui-helper-clearfix">

<?php

$this->renderPartial('_flash');


$form=$this->beginWidget('CActiveForm', array(
'id'=>'message-form',
'enableAjaxValidation'=>false,
'htmlOptions'=>array('autocomplete'=>$this->createUrl('ajax/auto')),
)); ?>
	
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	
	<label class="col-sm-3 control-label"><?php echo '<strong>'.CHtml::activeLabelEx($conv,'to').'</strong>'; ?>
    </label></td>
    <td>
	
	<div class="form-group">
    
    <div class="col-sm-4 col-4-reqst">
	
	<?php echo $form->textField($conv,'to',array('style'=>'', 'id'=>'message-to','class'=>'form-control', 'edit'=>$this->module->editToField? '1' : null)); ?>
    </div>
    
    <div class="col-sm-3">
				<?php echo $form->error($conv,'to'); ?>
				<?php

					if($this->module->userSupportList)
					{

						$reps = $this->module->getUserSupportList();
						echo '<select name="ajax[to]" class="mailbox-support-list form-control" '.(($this->module->editToField)? '1' : null).'" >';
						foreach($reps as $key => &$label)
						{
						?>
                       
						<option type="hidden" value="<?php echo $key; ?>"><?php echo $label; ?></option>
						<?php
						}
						echo '</select>';
				}
				?>
                </div>
                </div>
               </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo '<strong>'.CHtml::activeLabelEx($conv,'subject',array('class'=>'col-sm-3 control-label')).'</strong>'; ?></td>
    <td>
	<div class="col-sm-4 col-4-reqst">
	<?php echo $form->textField($conv,'subject',array('class'=>'form-control','style'=>'','placeholder'=>Yii::t('app',$this->module->defaultSubject),'id'=>'subjectid')); ?>
				<?php echo $form->error($conv,'subject'); ?>
                </div>
                </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
    	
    	<?php echo  $form->textArea($msg,'text',array('cols'=>50,'rows'=>5, 'class'=>'form-control','style'=>'width:100%; margin-bottom:10px','placeholder'=>Yii::t('app','Enter message here...'))); ?>
		<?php echo $form->error($msg,'text'); ?>
    </td>
  </tr>
</table>

		<br />
		<button class="btn btn-danger" onclick=" return no_recieve()" onclick=" no_subject();"><?php echo Yii::t('app','Send Message'); ?></button>
		
<?php $this->endWidget(); ?><!-- form --> 




</div>
</div>
</div>
</div>

<script type="text/javascript">
function no_recieve()
{
	
	if(document.getElementById("message-to").value=='')
	{
		alert("<?php echo Yii::t('app','Add any recipient'); ?>");
		return false;
	}
}

function no_subject()
{
	
	if(document.getElementById("subjectid").value=='')
	{
		confirm("<?php echo Yii::t('app','Do you want to sent this message without subject?'); ?>");
	}
}
</script>
<!-- mailbox -->