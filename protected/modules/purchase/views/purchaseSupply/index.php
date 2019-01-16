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
	$this->module->id,
	Yii::t('app','Purchase Order'),
);
$roles = Rights::getAssignedRoles(Yii::app()->user->Id);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
     <?php $this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
    <h1><?php echo Yii::t('app','Manage Purchase Order');?></h1>

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','Send Order').'</span>', array('create'), array('class'=>'a_tag-btn')); ?></li>                                    
</ul>
</div> 
</div>
    <?php
	
	if(isset($_REQUEST['page'])){
					$i=($pages->pageSize*$_REQUEST['page'])-9;
				}
				else{
					$i=1;
				}
	
	  	if($lists){
         	?>
			<div class="pdtab_Con" style="width:100%">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
			        <tr class="pdtab-h">
                            <td align="center" height="18" width="50"><?php echo '#';?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Item');?></td>	
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Vendor');?></td>				            
                            <td align="center" height="18" width="150"><?php echo Yii::t('app','Quantity');?></td>  
                            <td align="center" height="18" width="150"><?php echo Yii::t('app','Price');?></td>  
                            <td align="center" height="18" width="150"><?php echo Yii::t('app','Amount');?></td>
                        <?php if(key($roles) == 'Admin'){ ?>
                            <td align="center" height="18" width="150"><?php echo Yii::t('app','Action');?></td>
                        <?php } ?>
                        <?php if(key($roles) == 'pm'){ ?>
                            <td align="center" height="18" width="150"><?php echo Yii::t('app','Status');?></td>
                        <?php } ?>
			        </tr>
                    
			        <?php
					
                    foreach($lists as $list){
                  
			        ?>
			        	<tr>			        		
                                 <td align="center"><?php echo $i; ?></td>
                                 <td align="center"><?php $item = PurchaseItems::model()->findByAttributes(array('id'=>$list->item_id));
                                                          echo $item->name;?></td>
                                  <td align="center"><?php $vendor = PurchaseVendors::model()->findByAttributes(array('id'=>$list->vendor_id));
                                                         echo $vendor->first_name.' '.$vendor->last_name; ?></td>	                        
                                  <td align="center"><?php echo $list->quantity; ?></td>
                                        <?php $product = PurchaseProducts::model()->findByAttributes(array('vendor_id'=>$list->vendor_id, 'item_id'=>$list->item_id));
                                                            $value = $product->price;?>
                                  <td align="center"><?php echo $product->price; ?></td>
                                 <td align="center"><?php echo ($list->quantity*$value); ?></td>
                          <?php if(key($roles) == 'Admin'){ ?>
                             <td align="center"><?php	if($list->status == 0){ 
                                                                 echo CHtml::link('<span>'.Yii::t('app','Approve').'</span>', array('approve','id'=>$list->id),array('confirm'=>Yii::t('app','Are you sure you want to approve this order ?'))); 
																 echo ' ';
																 echo CHtml::link('<span>'.Yii::t('app','Reject').'</span>', array('reject','id'=>$list->id),array('confirm'=>Yii::t('app','Are you sure you want to reject this order ?'))); 
														  }
														 elseif($list->status == 1)
														 {
															echo Yii::t('app','Approved'); 
														 }
														 elseif($list->status == 2)
														 {
															echo Yii::t('app','Rejected'); 
														 }
                                 ?>
                              </td>
                          
                          <?php } ?>
                         <?php if(key($roles) == 'pm'){ ?>
                                 <td align="center"><?php if($list->status == 0){ 
                                                            echo Yii::t('app','Pending'); 
                                                          }
                                                          elseif($list->status == 1)
                                                          {
                                                            echo Yii::t('app','Approved'); 
                                                          }
                                                          elseif($list->status == 2)
                                                          {
                                                            echo Yii::t('app','Rejected'); 
                                                          }
                                                         ?></td>
                       <?php } ?>
	                    </tr>	
			        <?php
			        	$i++;
	                   
			        }
					 	
			        ?>
			    </table>
			   
                <div class="clear"></div>
		    </div>
		    <?php
		    	}else{
		    ?>
		    		<div class="pdtab_Con" style="width:100%">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="pdtab-h">                                
                                <td align="center" height="18" width="50"><?php echo '#';?></td>
                                <td align="center" height="18" width="175"><?php echo Yii::t('app','Item Code');?></td>				            
                                <td align="center" height="18" width="150"><?php echo Yii::t('app','Vendor');?></td>  
                                 <td align="center" height="18" width="150"><?php echo Yii::t('app','Quantity');?></td>  
                                <td align="center" height="18" width="80"><?php echo Yii::t('app','Price');?></td>
                                <td align="center" height="18" width="150"><?php echo Yii::t('app','Payment');?></td>
                               
                            </tr>
					        <tr>
					        	<td colspan="6" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','Nothing found!'); ?></td>
					        </tr>
					    </table>
				    </div>    
		    <?php		    		
                }
	?>
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
    </td>
  </tr>
</table>


