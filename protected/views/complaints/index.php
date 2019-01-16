<script language="javascript">
function hide(id)
{	
	$(".drop_search").not('#'+id).hide();
	if($('#'+id).is(':visible')){
		$('#'+id).hide();	
	}
	else{
		$('#'+id).show();
	}
}
</script>
<style>	

</style>
<?php
 $this->breadcrumbs=array(
	 Yii::t('app','Complaint List')
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
   		<div id="othleft-sidebar">
			<?php 
                $leftside = 'mailbox.views.default.left_side'; 
                $this->renderPartial($leftside);
           ?>
    	</div>
 	</td>
 	<td valign="top">
        <div class="cont_right formWrapper">  
        	<h1><?php echo Yii::t('app','Complaint List'); ?></h1>  

<div class="button-bg">
<div class="top-hed-btn-right">
<ul>                                    
<li>
</li>
<li>
</li>                                    
</ul>
</div> 
<div class="top-hed-btn-left">
<?php echo CHtml::link('<span>'.Yii::t('app','Clear Filter').'</span>', array('/complaints/index'), array('class'=>'a_tag-btn')); ?>
</div>
</div>
        
        <!-- Flash Message -->
		<?php
        Yii::app()->clientScript->registerScript(
            'myHideEffect',
            '$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
            CClientScript::POS_READY
        );
        ?>
        <?php
        /* Success Message */
        if(Yii::app()->user->hasFlash('successMessage')): 
        ?>
            <div class="flashMessage" style="background:#FFF; color:#689569; padding-left:220px; font-size:13px">
            <?php echo Yii::app()->user->getFlash('successMessage'); ?>
            </div>
        <?php endif; ?>
        
 		<div class="complaint-count-box">
        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td>
                        <table width="33%" border="0" cellpadding="0" cellspacing="0" class="table-complnt-posctn">
                            <tr>
                                <td> 
                                    <div class="overviewbox-table cmplnt-total">
                                    <h1><?php echo Yii::t('app', 'Total Complaints'); ?></h1>
                                    <div class="ovrBtm-table">
                                        <p>
                                        	<?php
												$criteria 				= new CDbCriteria;		
												$criteria->join 		= 'LEFT JOIN users t1 ON t.uid = t1.id';
												$criteria->condition 	= 't1.status=:x';
												$criteria->params 		= array(':x'=>1);													
												$total_complaints		= Complaints::model()->findAll($criteria);
												echo count($total_complaints);
											?>
                                        </p>
                                    </div>
                                     </div>
                                </td>
                            </tr>
                        </table>
						<table width="33%" border="0" cellpadding="0" cellspacing="0" class="table-complnt-posctn">
                            <tr>
                                <td>
                                    <div class="overviewbox-table cmplnt-pending">
                                    <h1><?php echo Yii::t('app', 'Pending Complaints'); ?></h1>
                                    <div class="ovrBtm-table">
                                        <p>
                                        	<?php
												$criteria 				= new CDbCriteria;		
												$criteria->join 		= 'LEFT JOIN users t1 ON t.uid = t1.id';
												$criteria->condition 	= 't1.status=:x AND t.status=:y';
												$criteria->params 		= array(':x'=>1, ':y'=>0);		
												$pending_complaints		= Complaints::model()->findAll($criteria);
												echo count($pending_complaints);
											?>
                                        </p>
                                    </div>
                                     </div>
                                </td>
                            </tr>
                        </table>
                        <table width="33%" border="0" cellpadding="0" cellspacing="0" class="table-complnt-posctn">
                            <tr>
                                <td>
                                    <div class="overviewbox-table cmplnt-closed">
                                    <h1><?php echo Yii::t('app', 'Closed Complaints'); ?></h1>
                                    <div class="ovrBtm-table">
                                        <p>
                                        	<?php
												$criteria 				= new CDbCriteria;		
												$criteria->join 		= 'LEFT JOIN users t1 ON t.uid = t1.id';
												$criteria->condition 	= 't1.status=:x AND t.status=:y';
												$criteria->params 		= array(':x'=>1, ':y'=>1);		
												$closed_complaints		= Complaints::model()->findAll($criteria);
												echo count($closed_complaints);
											?>
                                        </p>
                                    </div>
                                     </div>
                                </td>
                            </tr>
                        </table>  
                    </td>
                </tr>
            </table>
        </div>       
        
        
         <!-- Filters Box -->
                <div class="filtercontner">
                    <div class="filterbxcntnt">
                    	<!-- Filter List -->
                        <div class="filterbxcntnt_inner" style="border-bottom:#ddd solid 1px;">
                            <ul>
                                <li style="font-size:12px"><?php echo Yii::t('app','Filter Your Names:');?></li>
                                
                                <?php $form=$this->beginWidget('CActiveForm', array(
                                'method'=>'get',
                                )); ?>
                                <!-- Name Filter -->
                                <li>
                                    <div onClick="hide('user_name')" style="cursor:pointer;"><?php echo Yii::t('app','Name');?></div>
                                    <div id="user_name" style="display:none; width:230px; padding-top:0px; " class="drop_search" >
                                        <div class="droparrow" style="left:10px;"></div>
                                        <div class="filter_ul">
                                        	<ul>
                                            	<li class="Text_area_Box"><input type="search" placeholder="<?php echo Yii::t('app','search'); ?>" name="user_name" value="<?php echo isset($_GET['user_name']) ? CHtml::encode($_GET['user_name']) : '' ; ?>" /></li>
                                            	<li class="Btn_area_Box"> <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" /></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <!-- End Name Filter -->
                                 <!-- Subject Filter -->
                                <li>
                                    <div onClick="hide('subject')" style="cursor:pointer;"><?php echo Yii::t('app','Subject');?></div>
                                    <div id="subject" style="display:none; width:230px;" class="drop_search" >
                                        <div class="droparrow" style="left:10px;"></div>
                                            <div class="filter_ul">
                                                <ul>
                                                    <li class="Text_area_Box"><input type="search" placeholder="<?php echo Yii::t('app','search'); ?>" name="subject" value="<?php echo isset($_GET['subject']) ? CHtml::encode($_GET['subject']) : '' ; ?>" /></li>
                                                    <li class="Btn_area_Box"> <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" /></li>
                                                </ul>
                                            </div>
                                    </div>
                                </li>
                                <!-- End Subject Filter -->
                                
                                <!-- status Filter -->
                                <li>
								
								  
                                <div onClick="hide('status')" style="cursor:pointer;"><?php echo Yii::t('app','Status');?></div>
                                    <div id="status" style="display:none; width:248px;" class="drop_search">
                                    <div class="droparrow"  style="left:24px"></div>
                                       <div class="filter_ul">
                                                <ul>
                                                    <li class="Text_area_Box"><?php 
									if(isset($_REQUEST['Complaints']['status']) and $_REQUEST['Complaints']['status'] != NULL){
										$model->status	= $_REQUEST['Complaints']['status'];
									}
									else{
										$model->status = '';
									}									
									echo CHtml::activeDropDownList($model,'status',array("1" => Yii::t('app','Close'), "0" => Yii::t('app','Open')),array('prompt'=>Yii::t('app','All')));                                     
                                    ?></li>
                                                    <li class="Btn_area_Box"> <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" /></li>
                                                </ul>
                                            </div>
									
                                    
                                    </div>
                                </li>
                                <!-- END status Filter -->
                                <!-- user type Filter -->
                                <li>
								
								  
                                <div onClick="hide('role_type')" style="cursor:pointer;"><?php echo Yii::t('app','User Type');?></div>
                                    <div id="role_type" style="display:none; width:252px; " class="drop_search">
                                    <div class="droparrow"  style="left:24px"></div>
                                            <div class="filter_ul">
                                                <ul>
                                                    <li class="Text_area_Box"><?php 
									 if (Yii::app()->user->isSuperuser or ModuleAccess::model()->check('Home')) {
										   $all_roles=new RAuthItemDataProvider('roles', array( 
										'type'=>2,
										));
                    				$data=$all_roles->fetchData(); }
                                    echo CHtml::dropDownList('role_type','',CHtml::listData($data,'name','name'),array('empty'=>Yii::t('app','Select')));
                                    ?>
                                    </li>
                                                    <li class="Btn_area_Box"> <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" /></li>
                                                </ul>
                                            </div>
                                    
                                    
                                    
                                    </div>
                                </li>
                                <!-- END user type Filter -->
                                <?php $this->endWidget(); ?>
                                
                            </ul>
                            
                                
                                <div class="clear"></div>
                        </div> <!-- END div class="filterbxcntnt_inner" -->
                        <!-- END Filter List -->
                        
                        
                        
                       
                        <div class="clear"></div>
                        
                        <!-- Active Filter List -->
                        <div class="filterbxcntnt_inner_bot">
                            <div class="filterbxcntnt_left"><strong><?php echo Yii::t('app','Active Filters:');?></strong></div>
                            <div class="clear"></div>
                            <div class="filterbxcntnt_right">
                                <ul>
                        
                        
                       
                                	
                                    <!-- Name Active Filter -->
									<?php 
									if(isset($_REQUEST['user_name']) and $_REQUEST['user_name']!=NULL)
                                    {
                                    	$j++; 
									?>
                                    	<li><?php echo Yii::t('app','Name'); ?> : <?php echo $_REQUEST['user_name']?><a href="<?php echo Yii::app()->request->getUrl().'&user_name='?>"></a></li>
                                    <?php 
									}
									?>
                                    <!-- END Name Active Filter -->
                                     <!-- Subject Active Filter -->
									<?php 
									if(isset($_REQUEST['subject']) and $_REQUEST['subject']!=NULL)
                                    {
                                    	$j++; 
									?>
                                    	<li><?php echo Yii::t('app','Subject'); ?> : <?php echo $_REQUEST['subject']?><a href="<?php echo Yii::app()->request->getUrl().'&subject='?>"></a></li>
                                    <?php 
									}
									?>
                                    <!-- END Subject Active Filter -->
                                    <!-- Status Active Filter -->
                                    <?php  
									if(isset($_REQUEST['Complaints']['status']) and $_REQUEST['Complaints']['status']!=NULL)
                                    {
                                                        $j++;
                                                        if($_REQUEST['Complaints']['status']==1)
                                                        {
                                                                $status=Yii::t('app','Close');
                                                        }
                                                        else
                                                        {
                                                                $status=Yii::t('app','Open');
                                                        }
                                                        ?>
                                                        <li><?php echo Yii::t('app','Status'); ?> : <?php echo $status?><a href="<?php echo Yii::app()->request->getUrl().'&Complaints[status]='?>"></a></li>
                                    <?php 
									}
									?> 
                                    <!-- END status Active Filter -->
                                    <!-- User type Active Filter -->
                                    <?php  
									if(isset($_REQUEST['role_type']) and $_REQUEST['role_type']!=NULL)
                                    {
                                                        $j++;
                                                        
                                                        ?>
                                                        <li><?php echo Yii::t('app','User Type'); ?> : <?php echo $_REQUEST['role_type']?><a href="<?php echo Yii::app()->request->getUrl().'&role_type='?>"></a></li>
                                    <?php 
									}
									?> 
                                    <!-- END User type  Active Filter -->
                                     <?php if($j==0)
                                    {
                                    	echo '<div style="padding-top:4px; font-size:11px;"><i>'.Yii::t('app','No Active Filters').'</i></div>';
                                    }
									?> 
                                    
                                    <div class="clear"></div>
                                </ul>
                            </div> <!-- END div class="filterbxcntnt_right" -->
                            
                            <div class="clear"></div>
                        </div> <!-- END div class="filterbxcntnt_inner_bot" -->
                        <!-- END Active Filter List -->
                    </div> <!-- END div class="filterbxcntnt" -->
                </div> <!-- END div class="filtercontner"-->
                
                <!-- END Filter Box -->
                <div class="clear"></div>
                                    
                                    
                                    
                                
        <?php
		
        
        ?>              
            <div class="pdtab_Con" style="padding-top:0px;">
           	<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr class="pdtab-h">
              <td width="35" align="center"><?php echo '#';?></td>  
              <td width="200" align="center"><?php echo Yii::t("app",'Name');?></td>
              <td width="150" align="center"><?php echo Yii::t("app",'Subject');?></td>              
              <td width="75" align="center"><?php echo Yii::t("app",'Status');?></td>
              <td width="100" align="center"><?php echo Yii::t("app",'User Type');?></td>
              <td width="100" align="center"><?php echo Yii::t("app",'Action');?></td>
            </tr>  
        <?php
			if($complaints){
				if(isset($_REQUEST['page'])){
					$i	= ($pages->pageSize*$_REQUEST['page'])-9;
				}
				else{
					$i	= 1;
				}
				foreach($complaints as $complaint){
						
						?>
				<tr>
                <td align="center"><?php echo $i; ?></td>
                <td align="center">
                                    <?php 
                                    	$created_by	= Complaints::model()->getName($complaint->uid);
											if($created_by){
												echo $created_by;
											}
											else{
												echo '-';
											}
                                     ?>
                                    </td>
					<td align="center"><?php echo ucfirst($complaint->subject);?></td>                                        
					<td align="center"><?php 
					if($complaint->status == 0)
					{
						echo Yii::t("app","Open");
					}
					if($complaint->status == 1)
					{
						echo Yii::t("app","Close");
					}
					?>
					
						</td>
                                        <td align="center">
                                            <?php 
                                            $roles = Rights::getAssignedRoles($complaint->uid);			
                                            if(key($roles)!=NULL)
                                            {
                                                echo ucfirst(key($roles));
                                            }
                                            else
                                            {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
				  
					<td style="text-align:center"><?php
					if($complaint->status == 0 )
					{
						echo CHtml::link(Yii::t("app",'Close'),array('complaints/close','id'=>$complaint->id),array('confirm'=>Yii::t('app','Are you sure you want to close this Complaint ?'))); 
					}
					if($complaint->status == 1)
					{
						echo CHtml::link(Yii::t("app",'Reopen'),array('complaints/reopen','id'=>$complaint->id),array('confirm'=>Yii::t('app','Are you sure you want to reopen this Complaint ?'))); 
						
					}
					
					echo " | "; 
					echo CHtml::link(Yii::t("app",'View'),array('complaints/read','id'=>$complaint->id));
					?></td>
				</tr>    
				
<?php 
				$i++;
						
			}
        }
        else{
?>			
            <tr>
            	<td colspan="6" class="nothing-found"><?php echo Yii::t('app', 'No Complaints'); ?></td>
			</tr>
<?php            
        }
        ?>
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
<script>
$('body').click(function() {
	$('#osload').hide();
	
	$('#status').hide();
 
});

$('.filterbxcntnt_inner').click(function(event){
   event.stopPropagation();
});

$('.load_filter').click(function(event){
   event.stopPropagation();
});
</script>

