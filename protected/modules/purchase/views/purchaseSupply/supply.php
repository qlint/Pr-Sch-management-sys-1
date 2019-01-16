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
	Yii::t('app','Supply Order'),
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
    <h1><?php echo Yii::t('app','Manage Supply Order');?></h1>
    <?php
		if(isset($_REQUEST['page'])){
					$i=($pages->pageSize*$_REQUEST['page'])-9;
				}
				else{
					$i=1;
				}
	
	  	if($lists){
         	?>
            <?php
	Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
				if(Yii::app()->user->hasFlash('successMessage')): 
?>
				<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
					<?php echo Yii::app()->user->getFlash('successMessage'); ?>
				</div>
<?php endif; ?>
			<div class="pdtab_Con" style="width:100%">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
			        <tr class="pdtab-h">
                            <td align="center" height="18" width="50"><?php echo '#';?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Item');?></td>	
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Vendor');?></td>				            
                            <td align="center" height="18" width="150"><?php echo Yii::t('app','Quantity');?></td>
                            <td align="center" height="18" width="150"><?php echo Yii::t('app','Action');?></td>
                            <td align="center" height="18" width="150"><?php echo Yii::t('app','Verify');?></td>
                     
			        </tr>
                    
			        <?php
                    foreach($lists as $list){
			        ?>
			        	<tr>			        		
                                 <td align="center"><?php echo $i; ?></td>
                                 <td align="center"><?php $item = PurchaseItems::model()->findByAttributes(array('id'=>$list->item_id));
                                                          echo ucfirst($item->name);?></td>
                                 <td align="center"><?php $vendor = PurchaseVendors::model()->findByAttributes(array('id'=>$list->vendor_id));
                                                         echo ucfirst($vendor->first_name).' '.ucfirst($vendor->last_name); ?></td>	                        
                                 <td align="center"><?php echo $list->quantity; ?></td>
                                 <?php $product = PurchaseProducts::model()->findByAttributes(array('vendor_id'=>$list->vendor_id, 'item_id'=>$list->item_id));
                                                            $value = $product->price;?>
                            
                          <?php  if($list->send_mail ==0){?>
                                 <td align="center"><?php echo CHtml::link('<span>'.Yii::t('app','Send Order').'</span>', array('sendmail','id'=>$list->id),array('confirm'=>Yii::t('app','Are you sure you want to send order to vendor ?'))); 
                                                         ?></td>
                        	<?php } else{?>
                            		<td align="center"><?php echo Yii::t('app','Already send'); ?> </td>
							<?php }?>
                           
                         <?php  if($list->send_mail ==1 and $list->is_verify ==0){?>
                                 <td align="center"><?php echo CHtml::link('<span>'.Yii::t('app','Verify').'</span>', array('verify','id'=>$list->id),array('confirm'=>Yii::t('app','Are you sure you verified this ?'))); ?></td><?php
								 }
								 if($list->send_mail ==0 and $list->is_verify ==0){?>
									 <td align="center"><?php echo '-';?></td>
							<?php }
								  if($list->is_verify ==1){?>
									 <td align="center"><?php echo Yii::t('app','Verified'); ?></td>
							<?php }
                                 ?>
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
                                <td align="center" height="18" width="150"><?php echo Yii::t('app','Action');?></td>  
                                <td align="center" height="18" width="150"><?php echo Yii::t('app','Verify');?></td> 
                            </tr>
					        <tr>
					        	<td colspan="6" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','Nothing found!'); ?></td>
					        </tr>
					    </table>
				    </div>    
		    <?php		    		
                }
	?>
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


