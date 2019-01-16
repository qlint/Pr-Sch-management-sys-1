<head><meta http-equiv="refresh" content="300"></head>

<style type="text/css">


</style>
 <?php

if($this->getAction()->getId()!='inbox') 
$this->breadcrumbs=array(
		Yii::t('app',ucfirst($this->module->id))=>array('inbox'),
		Yii::t('app',ucfirst($this->getAction()->getId())) 
);
else
	$this->breadcrumbs=array(Yii::t('app',$this->module->id));
?>

 <?php $this->renderPartial('/default/left_side');?>
 
 <div class="pageheader">
      <h2><i class="fa fa-envelope-o"></i> <?php echo Yii::t('app','Mailbox'); ?> <span><?php echo Yii::t('app','Check your mails here.'); ?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','Mailbox'); ?></li>
        </ol>
      </div>
    </div>
    
<div class="contentpanel">
    	<!--<div class="col-sm-9 col-lg-12">-->
       <div>
                <div class="panel panel-default">
                <div class="panel-heading">   
                 <h3 class="panel-title">
							<?php 
								if(Yii::app()->controller->action->id == 'inbox'){
									echo Yii::t('app','Inbox'); 
								}
								else if(Yii::app()->controller->action->id == 'sent'){
									echo Yii::t('app','Sent Mail'); 
								}
								else if(Yii::app()->controller->action->id == 'trash'){
									echo Yii::t('app','Trash'); 
									
								}
							?>
                        </h3>
                        </div>
                    <div class="people-item people_itemNospc">
                        
                     
                        
                       
                        <!--<p class="text-muted">Showing 1 - 15 of 230 messages</p>-->
                        
                        <div class="table-responsive mail_toparea">
                        	<?php
$this->renderpartial('_menu');

if(isset($_GET['Message_sort']))
	$sortby = $_GET['Message_sort'];
elseif(isset($_GET['Mailbox_sort']))
	$sortby = $_GET['Mailbox_sort'];
else
	$sortby = '';

echo '<div id="mailbox-list" class="mailbox-list ui-helper-clearfix" sortby="'.$sortby.'">';


$this->renderpartial('_flash');

$ie6br = <<<EOD
<!--[if lt IE 6]>
<br clear="all" />
<![endif]-->
EOD;

//disable jquery autoload
Yii::app()->clientScript->scriptMap=array(
	'jquery.js'=>false,
);
if($dataProvider->getItemCount() > 0) {
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'message-list-form',
	'action'=>$this->createUrl($this->getId().'/'.$this->getAction()->getId()),
)); ?>
<?php /*?><form id="message-list-form" action="<?php echo $this->createUrl($this->getId().'/'.$this->getAction()->getId()); ?>" method="post"><?php */?>
	<input type="hidden" class="mailbox-count" name="ui[]" value="<?php echo $dataProvider->getItemCount(); ?>" />
	<input type="hidden" class="mailbox-sortby" name="ui[]" value="<?php echo $sortby; ?>" />
	<div class="mailbox-clistview-container ui-helper-clearfix">
    
   <?php
	//if($dataProvider->getItemCount() > 1 && $this->getAction()->getId() != 'sent') : ?>
     <?php if($this->getAction()->getId()!='sent') : ?>
		<div class="btn-group mailbox-checkall-buttons">
        	<input type="checkbox"  name="ch1" class="chkbox checkall" />
             
		</div>
	<?php endif; ?>
    
   
    <?php if($this->getAction()->getId()!='sent') : ?>
<div class="m-toplink" id="top" style="padding:left" > <span class="mailbox-buttons-label"></span>
		<?php if($this->getAction()->getId()=='trash') : ?>
	<input type="submit" id="mailbox-action-restore" class="btn btn-success"  name="button[restore]" value="<?php echo Yii::t('app','Restore'); ?>" onclick="return item();"/> 
	<input type="submit" id="mailbox-action-delete" class="btn btn-danger" name="button[delete]" value="<?php echo Yii::t('app','Delete forever'); ?>" onclick="return del();" />
		<?php else: ?>
			<?php if(!$this->module->readOnly || ( $this->module->readOnly && !$this->module->isAdmin())): ?>
	<input type="submit" id="mailbox-action-delete" class="btn btn-danger" name="button[delete]" value="<?php echo Yii::t('app','Delete'); ?>" onclick="return del();" /> 
			<?php endif; ?>
	<input type="submit" id="mailbox-action-read" class="btn btn-info" name="button[read]" value="<?php echo Yii::t('app','Read'); ?>" onclick="return item();" /> 
	<input type="submit" id="mailbox-action-unread" class="btn btn-warning" name="button[unread]" value="<?php echo Yii::t('app','Unread'); ?>" onclick="return item();" /> 
		<?php endif; ?>
</div>
   <?php endif; ?> 
  
	
	<?php

$this->widget('zii.widgets.CListView', array(
    'id'=>'mailbox',
    'dataProvider'=>$dataProvider,
    'itemView'=>'_list',
	/*'summaryText'=>Yii::t('zii','Result {start}-{end} of {count}.'),*/
    'itemsTagName'=>'table',
    'template'=>'<div class="mailbox-summary">{summary}</div>{sorter}'.$ie6br.'<div id="mailbox-items" class="ui-helper-clearfix">{items}</div>{pager}',
    'sortableAttributes'=>$this->getAction()->getId()=='sent'?
	array('created'=>Yii::t('app','Sort by')) :
	array('modified'=>Yii::t('app','Sort by')),
    'loadingCssClass'=>'mailbox-loading',
    'ajaxUpdate'=>'mailbox-list',
    'afterAjaxUpdate'=>'initdraggable',//'$.yiimailbox.updateMailbox',
    'emptyText'=>'<div style="width:100%"><h3>'.Yii::t('app','You have no mail in your '.$this->getAction()->getId().' folder.').'</h3></div>',
    //'htmlOptions'=>array('class'=>'ui-helper-clearfix'),
    'sorterHeader'=>'', 
    'sorterCssClass'=>'mailbox-sorter',
    'itemsCssClass'=>'mailbox-items-tbl ui-helper-clearfix',
    'pagerCssClass'=>'mailbox-pager',
	
    //'updateSelector'=>'.inbox',
));
?>
	<?php
	//if($dataProvider->getItemCount() > 1 && $this->getAction()->getId() != 'sent') : ?>
		<!--<div class="btn-group mailbox-checkall-buttons">
        	<input type="checkbox"  name="ch1" class="chkbox checkall" /> Select All
			<!--<button class="btn checkall" onclick="s()" />Check All</button>
			<button class="btn uncheckall" onclick="return item();" />Uncheck All</button>
		</div>-->
	<?php
	//endif; ?>
     <?php if($this->getAction()->getId()!='sent') : ?>
<div class="m-toplink footer_mailboxBtn"> <span class="mailbox-buttons-label btm-mailbox-btns"></span> 
		<?php if($this->getAction()->getId()=='trash') : ?>
	<input type="submit" id="mailbox-action-restore" class="btn btn-success" name="button[restore]" value="<?php echo Yii::t('app','Restore'); ?>" onclick="return item();"/> 
	<input type="submit"  id="mailbox-action-delete" class="btn btn-danger" name="button[delete]" value="<?php echo Yii::t('app','Delete forever'); ?>" onclick="return del();" />
		<?php else: ?>
			<?php if(!$this->module->readOnly || ( $this->module->readOnly && !$this->module->isAdmin())): ?>
	<input type="submit"  id="mailbox-action-delete" class="btn btn-danger" name="button[delete]" value="<?php echo Yii::t('app','Delete'); ?>"  onclick="return del();"  /> 
			<?php endif; ?>
	<input type="submit"  id="mailbox-action-read" class="btn btn-info" name="button[read]" value="<?php echo Yii::t('app','Read'); ?>" onclick="return item();"   /> 
	<input type="submit"  id="mailbox-action-unread" class="btn btn-warning" name="button[unread]" value="<?php echo Yii::t('app','Unread'); ?>" onclick="return item();" />
		<?php endif; ?>
</div>
	
    	<?php endif; ?>
	</div>
<!--</form>-->
<?php $this->endWidget(); ?>
<?php

}
else {
	$this->renderpartial('_empty');
} 
?>
                            
                        </div><!-- table-responsive -->
                        
                    </div><!-- panel-body -->
                </div><!-- panel -->
                
            </div>
    
      
      
      
      
    </div><!-- contentpanel -->



<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
	$('.message-subject').hide();
});
/*]]>*/
</script>
<script type="text/javascript">
function del()
{
	 var chks	=	$("[type='checkbox']");
	 var checked	=	false;
	for(var i=0; i<chks.length; i++){
		if(chks[i].checked){checked=true;
		}
	}
	if(checked==false){
		alert('<?php echo Yii::t('app','No item selected'); ?>');return false;
	}
	else{
		if(confirm('<?php echo Yii::t('app','Are you sure ?'); ?>')){
			return true;
		}
		else{
			return false;
		}
	}
	return true;
	
}
</script>
<script type="text/javascript">
function item()
{
	 var chks	=	$("[type='checkbox']");
	 var checked	=	false;
	for(var i=0; i<chks.length; i++){
		if(chks[i].checked){checked=true;
		}
	}
	if(checked==false){
		alert('<?php echo Yii::t('app','No item selected'); ?>');return false;
	}
	return true;
	
}
</script>
<script type="text/javascript">
function enable()
{
	$(".mailbox-check").on("change", function(e){
  if($(".mailbox-check").attr("checked")){
    $(".btn mailbox-button").submit("enable");
  } else {
    $(".btn mailbox-button").submit("disable");
  }
  
});
}

</script>

