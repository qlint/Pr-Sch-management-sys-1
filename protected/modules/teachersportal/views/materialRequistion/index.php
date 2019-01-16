<?php
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 
	if(sizeof($roles)==1 and key($roles) == 'teacher')
	{
		$this->renderPartial('application.modules.teachersportal.views.default.leftside'); 
	}
?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-outdent"></i><?php echo Yii::t("app",'Request Material');?><span><?php echo Yii::t("app",'View Material Requests here');?></span></h2>
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
        
         <?php
		Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
		if(Yii::app()->user->hasFlash('successMessage')): 
	?>
		<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
			<?php echo Yii::app()->user->getFlash('successMessage'); ?>
		</div>
		<?php endif; ?>
        
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
			<?php
                echo CHtml::link(Yii::t('app','Request Material'),array('create','id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
            ?>
            </li>
            </ul>
            </div>
 		</div>
    </div> 
    <div class="people-item">
      
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
            <table class="table table-hover mb30">
                    <style>
                        table, th, td {
                        border: 1px solid black;
                        border-collapse: collapse;
                                    }
                    </style>
                        <tr>
                            <th><?php echo Yii::t('app','Department'); ?></th>
                            <th><?php echo Yii::t('app','Item Name'); ?></th>
                            <th><?php echo Yii::t('app','Quantity'); ?></th>
                            <th><?php echo Yii::t('app','Status'); ?></th>
                            <th><?php echo Yii::t('app','Issue'); ?></th>
                            <td align="center"><?php echo Yii::t("app",'Action');?></td>
                        </tr>
                        <?php
					foreach($materials as $material)
					{
						$item 		= PurchaseItems::model()->findByAttributes(array('id'=>$material->material_id)); 
						$department = EmployeeDepartments::model()->findByAttributes(array('id'=>$material->department_id));
						?>
					   <tr> 
                        <td><?php echo ucfirst($department->name);?></td>
						<td><?php echo ucfirst($item->name);?></td>
						<td><?php echo $material->quantity; ?></td>
                		<td style="text-align:center"><?php
							if($material->status== 0)
							{ 
								echo Yii::t('app','Pending');
							}
							if($material->status== 1)
							{ 
								echo Yii::t('app','Approved');
							}
							if($material->status== 2)
							{ 
								echo Yii::t('app','Rejected');
							}
							
							?></td>
                            <td style="text-align:center"><?php
							if($material->status== 1){
								if($material->is_issued== 0)
								{ 
									echo Yii::t('app','Not Issued');
								}
								if($material->is_issued== 1)
								{ 
									echo Yii::t('app','Issued');
								}
								if($material->is_issued== 2)
								{ 
									echo Yii::t('app','Returned');
								}
							}else{
								echo '-';
							}
							
							?></td>
                        	<td class="os-button-column" style="text-align:center">
                            <ul>
                            <?php if($material->status== 0){ ?>
                            		<li><?php echo CHtml::link('',array('update','id'=>$material->id), array('class'=>'edit', 'title'=>Yii::t('app','Edit'))); ?></li>
                             <?php } ?>
                                <li><?php echo CHtml::link('',"#", array("submit"=>array('delete','id'=>$material->id),'confirm' => Yii::t('app', 'Are you sure you want to delete this ?'), 'csrf'=>true, 'class'=>'delete', 'title'=>Yii::t('app','Delete'))); ?></li>
                         
						  	 
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




