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
.reg_bx td{
	vertical-align:top;	
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Purchase')=>array('/purchase/materialRequistion/index'),
	Yii::t('app','Material Requistion'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Material Requistion');?></h1>
                <div class="edit_bttns " style="top:16px; right:16px;">
                    <ul>
                    	<li><?php echo CHtml::link('<span>'.Yii::t('app','Request Material').'</span>', array('/purchase/materialRequistion/create'),array('class'=>'addbttn last')); ?></li>
                    </ul>
            	</div>
                <?php
				Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
			
				if(Yii::app()->user->hasFlash('successMessage')): 
			?>
				<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
					<?php echo Yii::app()->user->getFlash('successMessage'); ?>
				</div>
				<?php endif; ?>
<?php						
				
					
?>            
				<div class="pdtab_Con" style="width:100%">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  		<tbody>
                    		<tr class="pdtab-h">
                            	<td align="center" height="18"><?php echo '#';?></td>
                            	<td align="center" height="18"><?php echo Yii::t('app','Department');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Item Name');?></td>
                                 <td align="center" height="18"><?php echo Yii::t('app','Quantity');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Status');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Issue');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Actions');?></td>
                            </tr>
<?php
						
							if($model){
								if(isset($_REQUEST['page'])){
									$i=($pages->pageSize*$_REQUEST['page'])-9;
								}
								else{
									$i=1;
								}
								foreach($model as $data){
?>
                                    <tr>
                                        <td align="center" width="40"><?php echo $i; ?></td>
                                        <td align="center" width="200"><?php
												$department = EmployeeDepartments::model()->findByAttributes(array('id'=>$data->department_id));
												echo $department->name; ?></td>
                                        <td align="center" width="125"><?php
												$material = PurchaseItems::model()->findByAttributes(array('id'=>$data->material_id));
												echo $material->name; ?></td>
                                         <td align="center" width="125"><?php echo $data->quantity; ?></td>
                                        <td align="center" width="200"><?php if($data->status == 0){ echo 'Pending'; }
																			if($data->status == 1){ echo 'Approved';}
																			if($data->status == 2){ echo 'Rejected';}?></td>
                                        <td align="center" width="200"><?php
														if($data->status == 1){
																		if($data->is_issued== 0)
																		{ 
																			echo Yii::t('app','Not Issued');
																		}
																		if($data->is_issued== 1)
																		{ 
																			echo Yii::t('app','Issued');
																		}
																		if($data->is_issued== 2)
																		{ 
																			echo Yii::t('app','Returned');
																		}
														}
														else{
															echo '-';
														}
																	?></td>
                                        <td align="center" class="button-column">
                            	<ul class="pro-ul">
                               <?php if($data->status == 0){?>
                                	<li><?php echo CHtml::link('Edit',array('update','id'=>$data->id), array('class'=>'edit', 'title'=>Yii::t('app','Edit'))); ?></li>
                                    <li><?php echo '|'; ?></li>
                               <?php } ?>
                                    <li><?php echo CHtml::link('Delete',"#", array("submit"=>array('/purchase/materialRequistion/delete','id'=>$data->id),'confirm' => Yii::t('app', 'Are you sure you want to delete this request ?'), 'csrf'=>true, 'class'=>'delete', 'title'=>Yii::t('app','Delete'))); ?></li>
                                </ul>	                      	                        	
	                        </td>   
<?php
									$i++;
								}
							}
							else{
?>
								<td colspan="7" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','Nothing Found!'); ?></td>
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
