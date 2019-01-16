<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 5px 0 0;
}
.pro-ul{ margin:0px; padding:0px;}
.pro-ul li{ padding:0px 3px; list-style:none; display:inline-block;}
.delete{ width:12px; height:12px; background:url(images/task-dlt.png) no-repeat center;}
.view{ width:12px; height:12px; background:url(assets/1effa1bf/gridview/view.png) no-repeat center;}
.edit{ width:12px; height:12px; background:url(images/task-edit.png) no-repeat center;}
</style>
<?php
$this->breadcrumbs=array(
	$this->module->id => array('/purchase'),
	Yii::t('app','Stock Details'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Stock');?></h1>
                
				<div class="pdtab_Con" style="width:100%">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  		<tbody>
                    		<tr class="pdtab-h">
                            	<td align="center" height="18"><?php echo Yii::t('app','Item Name');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Item Available');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Action');?></td>
                            </tr>
				<?php
							if($stocks){
								if(isset($_REQUEST['page'])){
									$i=($pages->pageSize*$_REQUEST['page'])-9;
								}
								else{
									$i=1;
								}
								foreach($stocks as $stock){
									$item = PurchaseItems::model()->findByAttributes(array('id'=>$stock->item_id));
								?>
                                    <tr>
                                        <td align="center" width="40"><?php echo ucfirst($item->name); ?></td>
                                        <td align="center" width="40"><?php echo $stock->quantity; ?></td>
                                        <td align="center" width="40"><?php echo CHtml::link('<span>'.Yii::t('app','Issue Details ').'</span>', array('issue','id'=>$item->id), array('class'=>'makeedit')); 
										echo '|';
										echo CHtml::link('<span>'.Yii::t('app',' Vendor Details').'</span>', array('vendor','id'=>$item->id), array('class'=>'makeedit')); 
										
										?></td>
                                      
                                    </tr>
<?php							$i++;
								}
							}
							else{
?>
								<td colspan="5" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','Nothing Found!'); ?></td>
<?php								
							}
?>                            
                        </tbody>
                    </table>        
                </div>
                   <div class="pagecon">
                    <?php                                          
                        $this->widget('CLinkPager', array(
                        'currentPage'=>$pages->getCurrentPage(),
                        'itemCount'=>$item_count,
                        'pageSize'=>$page_size,
                        'maxButtonCount'=>5,						
                        'header'=>'',
                        'htmlOptions'=>array('class'=>'pages'),
                    ));?>
                        
                    </div>	
                
            </div>
        </td>
    </tr>
</table>        
