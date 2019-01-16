<?php
	$leftside = 'mailbox.views.default.left_side';
	
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 
	if(sizeof($roles)==1 and key($roles) == 'student')
	{
		$leftside = 'application.modules.studentportal.views.default.leftside'; 
		
	}
	if(sizeof($roles)==1 and key($roles) == 'parent')
	{
		$leftside = 'application.modules.parentportal.views.default.leftside'; 
		
	}
	if(sizeof($roles)==1 and key($roles) == 'teacher')
	{
		$leftside = 'application.modules.teachersportal.views.default.leftside'; 
		
	}
	
	$this->renderPartial($leftside);
?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-comment"></i><?php echo Yii::t("app",'Complaints');?><span><?php echo Yii::t("app",'Complaint List');?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label">You are here:</span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app",'Complaints')?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
<div class="contentpanel"> 
    <div class="panel-heading">    
		<h3 class="panel-title"><?php echo Yii::t('app','Complaints');?></h3>
        </div> 
    <div class="people-item"> 
        <div class="opnsl_headerBox">
        <div class="opnsl_actn_box"> </div>
        <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1"><?php
        echo CHtml::link(Yii::t('app','Register a Complaint'),array('Complaints/create','id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
        ?></div>
        <div class="opnsl_actn_box2"></div>
        </div>
        
        </div>
     
<?php
//$complaints=Complaints::model()->findAllByAttributes(array('uid'=>Yii::app()->user->id),array('order'=>'id DESC'));
$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
if($complaints)
{
?>
		<div class="table-responsive">
            <table class="table table-bordered mb30" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                        <tr>
                        	<th><?php echo '#'; ?></th>
                            <th><?php echo Yii::t('app','Subject'); ?></th>
                            <th><?php echo Yii::t('app','Date'); ?></th>
                            <th><?php echo Yii::t('app','Status'); ?></th>
                            <th><?php echo Yii::t("app",'Action');?></th>
                        </tr>
                        </thead>
                        <?php
						if(isset($_REQUEST['page'])){
							$i	= ($pages->pageSize*$_REQUEST['page'])-9;
						}
						else{
							$i	= 1;
						}
					foreach($complaints as $complaint)
					{
						?>
					   <tr>
                       	<td><?php echo $i; ?></td> 
						<td><?php echo ucfirst($complaint->subject);?></td>
						<td><?php echo date($settings->displaydate,strtotime($complaint->date));?></td>
						<td><?php 
									if($complaint->status == 0)
									{
										echo "Open";
									}
									if($complaint->status == 1)
									{
										echo  "Close";
									}?>
										</td>
                                        </td>
               
                <td><?php
                if($complaint->status == 0 )
				{
					echo CHtml::link(Yii::t("app",'Close'),array('complaints/close','id'=>$complaint->id),array('class'=>'view_Exmintn_atg opnsl_closeBtn'),array('confirm'=>Yii::t('app','Are you sure you want to close this Complaint ?')));  
				}
				if($complaint->status == 1)
				{
					echo CHtml::link(Yii::t("app",'Reopen'),array('complaints/reopen','id'=>$complaint->id),array('class'=>'view_Exmintn_atg opnsl_reopenBtn'),array('confirm'=>Yii::t('app','Are you sure you want to reopen this Complaints ?')));  
					
				}

               	echo CHtml::link(Yii::t("app",'View'),array('complaints/feedback','id'=>$complaint->id,),array('class'=>'view_Exmintn_atg opnsl_viewBtn'));?></td>
               
            </tr>    
						 </tr>               
					<?php                    
						$i++;						
					}
					}
					else
					{
						echo Yii::t("app","You have No Complaint And Feedbacks");
					}
					?>
            </table>
             <div class="pagecon">
			<?php                                          
            $this->widget('CLinkPager', array(
            'currentPage'=>$pages->getCurrentPage(),
            'itemCount'=>$item_count,
            'pageSize'=>$page_size,
            'maxButtonCount'=>5,
            'header'=>'',
            'htmlOptions'=>array('class'=>'pagination'),
            ));?>
        </div> <!-- END div class="pagecon"-->
		</div>
	</div>
</div>