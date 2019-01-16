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
	Yii::t('app','Manage Vendors'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Vendor List');?></h1>

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
                    	<li><?php echo CHtml::link('<span>'.Yii::t('app','New Vendor').'</span>', array('/purchase/vendorDetails/create'),array('class'=>'a_tag-btn')); ?></li>                                    
</ul>
</div> 
</div> 
                
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
                  		<tbody>
                    		<tr class="pdtab-h">
                            	<td align="center" height="18"><?php echo '#';?></td>
                            	<td align="center" height="18"><?php echo Yii::t('app','Name');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Phone');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Email');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Action');?></td>
                            </tr>
<?php
							if(isset($_REQUEST['page'])){
								$i=($pages->pageSize*$_REQUEST['page'])-9;
							}
							else{
								$i=1;
							}
							if($model){
								foreach($model as $data){?>
                                    <tr>
                                        <td align="center" width="40"><?php echo $i; ?></td>
                                        <td align="center" width="200"><?php echo CHtml::link(ucfirst($data->first_name).' '.ucfirst($data->last_name), array('/purchase/productDetails/view','id'=>$data->id)); ?></td>
                                        <td align="center" width="125"><?php echo $data->phone; ?></td>
                                        <td align="center" width="200"><?php echo $data->email; ?></td>
                                        <td align="center" width="200" class="os-button-column">
											<ul>
											<?php 
                                            	echo '<li>'.CHtml::link('', "#",array('submit'=>array('vendorDetails/delete','id'=>$data->id,), 'confirm'=>Yii::t('app','Are you sure you want to delete the vendor?'), 'csrf'=>true, 'class'=>'delete')).'</li>';
                                                ?>
                                            </ul>
										<?php /*?><?php echo CHtml::link(Yii::t('app','Delete'), "#", array('submit'=>array('vendorDetails/delete','id'=>$data->id,), 'confirm'=>Yii::t('app','Are you sure you want to delete the vendor?'), 'csrf'=>true)); ?><?php */?>
                                        
                                        </td>
                                        
                                    </tr>
<?php
									$i++;
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
            </div>
        </td>
    </tr>
</table>        
