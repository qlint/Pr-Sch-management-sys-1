<?php
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 
	if(sizeof($roles)==1 and key($roles) == 'student')
	{
		$this->renderPartial('application.modules.studentportal.views.default.leftside'); 
	}
	if(sizeof($roles)==1 and key($roles) == 'parent')
	{
		$this->renderPartial('application.modules.parentportal.views.default.leftside'); 
	}
	if(sizeof($roles)==1 and key($roles) == 'teacher')
	{
		$this->renderPartial('application.modules.teachersportal.views.default.leftside'); 
	}
?>
<div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-comment"></i><?php echo Yii::t("app",'Complaints');?><span><?php echo Yii::t("app",'View Complaints here');?></span></h2>
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
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
			<?php
                echo CHtml::link(Yii::t('app','Register a Complaint'),array('Complaints/create','id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
            ?>
          </li>
            </ul>
 		</div>
        </div>
    </div> 
    <div class="people-item">  
<?php

$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
if($complaints)
{
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
                            <th><?php echo Yii::t('app','Subject'); ?></th>
                            <th><?php echo Yii::t('app','Date'); ?></th>
                            <th><?php echo Yii::t('app','status'); ?></th>
                            <td align="center"><?php echo Yii::t("app",'Action');?></td>
                        </tr>
                        <?php
					foreach($complaints as $complaint)
					{
						?>
					   <tr> 
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
               
                <td style="text-align:center"><?php
                if($complaint->status == 0 )
				{
					echo CHtml::link(Yii::t("app",'Close'),array('complaints/close','id'=>$complaint->id),array('confirm'=>Yii::t('app','Are you sure you want to close this Complaint ?'))));  
				}
				if($complaint->status == 1)
				{
					echo CHtml::link(Yii::t("app",'Reopen'),array('complaints/reopen','id'=>$complaint->id),array('confirm'=>Yii::t('app','Are you sure you want to reopen this Complaint ?')));  
					
				}
               	echo CHtml::link(Yii::t("app",'View'),array('complaints/feedback','id'=>$complaint->id,);?></td>
            </tr>    
						 </tr>               
					<?php                    
						
					}
					}
					else
					{
						echo Yii::t("app","You have No Complaints And Feedbacks");
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
        </div> <!-- END div class="pagecon"-->
      </div>  
	</div>




