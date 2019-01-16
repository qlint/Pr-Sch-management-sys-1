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
	Yii::t('app','Item Vendors'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Item Vendors');?></h1>
                <?php $item = PurchaseItems::model()->findByPk($_REQUEST['id']);?>
                <h3><?php echo Yii::t('app','Item Name').' : '.ucfirst($item->name);?></h3>
                
				<div class="pdtab_Con" style="width:100%">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  		<tbody>
                    		<tr class="pdtab-h">
                            	<td align="center" height="18"><?php echo Yii::t('app','Vendor');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Price');?></td>
                            </tr>
							<?php
							if($products){
								if(isset($_REQUEST['page'])){
									$i=($pages->pageSize*$_REQUEST['page'])-9;
								}
								else{
									$i=1;
								}
								foreach($products as $product){
										$vendor = PurchaseVendors::model()->findByAttributes(array('id'=>$product->vendor_id));
									?>
										<tr>
											<td align="center" width="40"><?php if($vendor)
																				{
																					echo ucfirst($vendor->first_name).' '.ucfirst($vendor->last_name); 
																				}?></td>
											<td align="center" width="40"><?php echo $product->price; ?></td>
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
