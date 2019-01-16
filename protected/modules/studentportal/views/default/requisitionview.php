<?php
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 
	if(sizeof($roles)==1 and key($roles) == 'student')
	{
		$this->renderPartial('application.modules.studentportal.views.default.leftside'); 
	}
?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-compress"></i><?php echo Yii::t("app",'Request Material');?><span><?php echo Yii::t("app",'View Material Requests here');?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label">You are here:</span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app",'Material Requests')?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
<div class="contentpanel"> 
    <div class="panel-heading">    
		<h3 class="panel-title"><?php echo Yii::t('app','Material Requistion');?></h3>
        
</div>        

        


<div class="people-item">

<div class="opnsl_headerBox">
<div class="opnsl_actn_box">
         <?php
		Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
		if(Yii::app()->user->hasFlash('successMessage')): 
	?>
		<div class="flashMessage success_msg">
			<?php echo Yii::app()->user->getFlash('successMessage'); ?>
		</div>
		<?php endif; ?>
</div>
    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">
			<?php
                echo CHtml::link(Yii::t('app','Request Material'),array('requisition','id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
            ?>
			</div>
            </div>
 		</div>
      
<?php
if($materials)
{
	if(isset($_REQUEST['page'])){
		$i=($pages->pageSize*$_REQUEST['page'])-9;
	}
	else{
		$i=1;
	}
?>
	<div class="table-responsive">
<table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered mb30">
    <thead>
    <tr>
                            
                            <th><?php echo Yii::t('app','Item Name'); ?></th>
                            <th><?php echo Yii::t('app','Quantity'); ?></th>
                            <th><?php echo Yii::t('app','Status'); ?></th>
                            <th><?php echo Yii::t('app','Issue'); ?></th>
                            <th><?php echo Yii::t("app",'Action');?></th>
                        </tr>
                        </thead>
                        <?php
					foreach($materials as $material)
					{
						$item 		= PurchaseItems::model()->findByAttributes(array('id'=>$material->material_id)); 
						?>
					   <tr> 
						<td><?php echo ucfirst($item->name);?></td>
						<td><?php echo $material->quantity; ?></td>
                		<td><?php
							if($material->status_tchr== 0)
							{ 
								echo '<span class="opnsl_pending">'.Yii::t('app','Pending').'</span>';
							}
							if($material->status_tchr== 1)
							{ 
								echo '<span class="opnsl_approved">'.Yii::t('app','Approved').'</span>';
							}
							if($material->status_tchr== 2)
							{ 
								echo  '<span class="opnsl_reject">'.Yii::t('app','Rejected').'</span>';
							}
							
							?></td>
                            <td><?php
							if($material->status_tchr== 1){
								if($material->is_issued== 0)
								{ 
									echo '<span class="opnsl_notissued">'.Yii::t('app','Not Issued').'</span>';
								}
								if($material->is_issued== 1)
								{ 
									echo '<span class="opnsl_issued">'.Yii::t('app','Issued').'</span>';
								}
							}else{
								echo '-';
							}
							
							?></td>
                        	<td class="os-button-column" style="text-align:center">
                            <ul>
                            <?php if($material->status_tchr== 0){ ?>
                            		<li><?php echo CHtml::link('',array('RequisitionUpdate','id'=>$material->id), array('class'=>'edit', 'title'=>Yii::t('app','Edit'))); ?></li>
                             <?php } ?>
                                <li><?php echo CHtml::link('',"#", array("submit"=>array('RequisitionDelete','id'=>$material->id),'confirm' => Yii::t('app', 'Are you sure you want to delete this ?'), 'csrf'=>true, 'class'=>'delete', 'title'=>Yii::t('app','Delete'))); ?></li>
                         
						  	 
                            </ul>

                         </td>
            		</tr>           
					<?php                    
						$i++;	
					}
				}
				else
				{
					echo Yii::t("app","No material Requests");
				}
				?>
            </table>
		</div>
        <div  class="pagination-block">
        <div class="dataTables_paginate paging_full_numbers">
			<?php 
              $this->widget('CLinkPager', array(
              'currentPage'=>$pages->getCurrentPage(),
              'itemCount'=>$item_count,
              'pageSize'=>$page_size,
              'maxButtonCount'=>5,
              'prevPageLabel'=>'< Prev',                              
              'prevPageLabel'=>'< Prev',
              'header'=>'',
            'htmlOptions'=>array('class'=>'pages'),
            ));?>
        </div> <!-- End div class="pagecon" -->
        </div> 
      </div>  
	</div>




