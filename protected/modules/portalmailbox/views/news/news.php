<style>
.mailbox-link span{ width:0px !important; word-break:break-all;}
.mailbox-summary{ left:0px !important;}
.list-view .summary{text-align:left !important;}
tr.mailbox-item > td > div{padding:4px 4px 15px !important;}
.msg-new .mailbox-subject a{text-align:left;}


</style>

<?php

if($this->getAction()->getId()!='index') 
$this->breadcrumbs=array(
		Yii::t('app',ucfirst($this->module->id))=>array('news/'),
		Yii::t('app',ucfirst($this->getAction()->getId())) 
);
else
	$this->breadcrumbs=array('Site News'); ?>

<?php $this->renderPartial('/default/left_side');?>
<div class="pageheader">
      <h2><i class="fa fa fa-book"></i><?php echo Yii::t('app','Site News'); ?>  <span><?php echo Yii::t('app','Latest news listed here'); ?></span></h2>
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
		 <div class="panel panel-default panel-alt widget-messaging">
          <div class="panel-heading">
              <div class="panel-btns">
                <a class="sender" href="#"><?php echo Yii::t('app','Sort by'); ?><i class="fa fa-filter fa-2x"></i></a>
              </div><!-- panel-btns -->
              <h2 class="panel-title"></h2>
            </div>
            <div class="panel-body">
           <div class="table-responsive"> 	
              
    <?php 

//$this->renderpartial('_menu');

if(isset($_GET['Mailbox_sort']))
	$sortby = $_GET['Mailbox_sort'];
else
	$sortby = '';

echo '<div class="news-list ui-helper-clearfix" sortby="'.$sortby.'">';

$this->renderpartial('../message/_flash');

if($dataProvider->getItemCount() > 0) {
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'message-list-form',
	'action'=>$this->createUrl($this->getId().'/'.$this->getAction()->getId()),
)); ?>

<?php /*?><form id="message-list-form" action="<?php echo $this->createUrl($this->getId().'/'.$this->getAction()->getId()); ?>" method="post"><?php */?>
	<div class="mailbox-clistview-container ui-helper-clearfix">
	<?php
	if($this->module->isAdmin() && $dataProvider->getItemCount() > 1) : ?>
		<div class="btn-group mailbox-checkall-buttons">
            <input type="checkbox"  name="ch1" class="chkbox checkall" /> <?php echo Yii::t('app','Select All');?> 
            
			<!--<button class="checkall" />Check All</button>
			<button class="uncheckall" />Uncheck All</button>-->
		</div>
        
	<?php
	endif;

$this->widget('zii.widgets.CListView', array(
    'id'=>'mailbox',
    'dataProvider'=>$dataProvider,
    'itemView'=>'_news_list',
	/*'summaryText'=>Yii::t('zii','Result {start}-{end} of {count}.'),*/
    'itemsTagName'=>'table',
    'template'=>'<div class="mailbox-summary">{summary}</div>{sorter}<div id="mailbox-items" class="ui-helper-clearfix ">{items}</div>{pager}',
    'sortableAttributes'=>array('modified'=>'Sort by'),
    'loadingCssClass'=>'mailbox-loading',
    'ajaxUpdate'=>'mailbox-list',
    'afterAjaxUpdate'=>'$.yiimailbox.updateMailbox',
   'emptyText'=>'<div style="width:100%"><h3>'.Yii::t('app','No news to report.').'</h3></div>',
    //'htmlOptions'=>array('class'=>'ui-helper-clearfix'),
    'sorterHeader'=>'', 
    'sorterCssClass'=>'mailbox-sorter',
    'itemsCssClass'=>'mailbox-items-tbl ui-helper-clearfix',
    'pagerCssClass'=>'mailbox-pager',
    //'updateSelector'=>'.inbox',
));
?>

	<?php if($this->module->isAdmin()) : ?>
<div style="clear:left; padding-left:20px;"> <span class="mailbox-buttons-label">  </span> 
	<input type="submit" id="mailbox-action-delete" class="btn mailbox-button" name="button[delete]" value="delete"  onclick="return del();"/> 
	
</div>	<?php endif; ?>
	</div>
<!--</form>-->
<?php $this->endWidget(); ?>

<?php

}
else {
	echo '<div class="mailbox-empty">'.Yii::t('app','No news to report.').'</div>';
}

echo '</div>';

?>
       </div>     </div><!-- panel-body -->
          </div>
                <!-- panel -->
                
            </div>

    </div>    
   
<script type="text/javascript">
	function del()
{
	 var chks	=	$("[type='checkbox']");
	 var checked	=	false;
	for(var i=0; i<chks.length; i++){
		if(chks[i].checked){checked=true;}
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
$(".chkbox").change(function() {
    var val = $(this).val();
  if( $(this).is(":checked") ) {

    $(":checkbox[value='"+val+"']").attr("checked", true);
  }
    else {
        $(":checkbox[value='"+val+"']").attr("checked", false);
    }
});
</script>

