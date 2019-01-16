<style type="text/css">
.mailbox-menu-item a {
    display: block;
    padding: 13px 7px 13px 20px !important;
}

.mailbox-menu-item a:hover {
    display: block;
    padding: 13px 7px 13px 20px !important;
}

.mailbox-checkall-buttons {
    float: left;
    margin-left: 26px;
    margin-top: 10px;
    width: 32px;
}

.mailbox-checkall-buttons {
    float: left;
    margin-left: 13px;
    margin-top: 18px;
    width: 32px;
}

.m-toplink {
    float: left;
    width: auto;
}

.mailbox-menu-newmsg {
    background:#428bca !important;
    border: 1px solid #357ebd !important;
    border-radius: 3px !important;
    box-shadow: 0 0 0 0 #ffffff inset !important;
    color: #ffffff !important;
    display: inline-block;
    font-family: arial;
    font-size: 12px;
    font-weight: bold;
    padding: 6px 14px !important;
    position: absolute;
    right: 20px;
    text-decoration: none;
    top: 5px;
}

.mailbox-menu {
    height: auto;
    margin: 0;
    padding: 0;
}

button, input[type="submit"]{ font-weight:100;
	font-size:13px;}
	
.mailbox-menu-item a{ font-weight:100;}

</style>
 <?php

if($this->getAction()->getId()!='inbox') 
$this->breadcrumbs=array(
		Yii::t('app',ucfirst($this->module->id))=>array('inbox'),
		Yii::t('app',ucfirst($this->getAction()->getId())) 
);
else
	$this->breadcrumbs=array($this->module->id);
?>

 <?php $this->renderPartial('/default/left_side');?>
 
 <div class="pageheader">
      <h2><i class="fa fa-newspaper-o"></i> <?php echo Yii::t('app','News'); ?> <span><?php echo Yii::t('app','View news here'); ?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','News'); ?></li>
        </ol>
      </div>
    </div>
    
<div class="contentpanel">
    	<!--<div class="col-sm-9 col-lg-12">-->
        <div>
          
                <div class="panel panel-default">
                    <div class="panel-body">
                        
                        <div class="table-responsive">
                        	<?php


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
<form id="message-list-form" action="<?php echo $this->createUrl($this->getId().'/'.$this->getAction()->getId()); ?>" method="post">
	<input type="hidden" class="mailbox-count" name="ui[]" value="<?php echo $dataProvider->getItemCount(); ?>" />
	<input type="hidden" class="mailbox-sortby" name="ui[]" value="<?php echo $sortby; ?>" />
	<div class="mailbox-clistview-container ui-helper-clearfix">
   
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
    'emptyText'=>'<div style="width:100%"><h3>'.Yii::t('app','You have no mail in your ').$this->getAction()->getId().Yii::t('app',' folder.').'</h3></div>',
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

	</div>
</form>

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

