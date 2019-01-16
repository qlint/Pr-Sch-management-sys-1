<style type="text/css">
.pdtab_Con table td{ padding:8px 4px;}
</style>

<?php
$this->breadcrumbs=array(
	'Complaints'=>array('complaints/feedbacklist'),
	'List',
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

            <div class="search_btnbx">
                <div class="contrht_bttns">
                    <ul>                            
                        <li><?php echo CHtml::link('<span>'.Yii::t('app','Register Complaint').'</span>', array('/complaints/create')); ?></li>                            
                    </ul>                    
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


 		
   
    
<?php

$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));

?>
		<div class="pdtab_Con" style="padding-top:0px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                       <tr class="pdtab-h">
                       		<td align="center"><?php echo '#';?></td> 
                            <td align="center"><?php echo Yii::t('app','Subject'); ?></td>
                            <td align="center"><?php echo Yii::t('app','Date'); ?></td>
                            <td align="center"><?php echo Yii::t('app','Status'); ?></td>
                            <td align="center"><?php echo Yii::t("app",'Action');?></td>
                        </tr>
                        <?php
						if($complaints){
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
                       	<td align="center"><?php echo $i; ?></td>
						<td align="center"><?php echo ucfirst($complaint->subject);?></td>
						<td align="center"><?php echo date($settings->displaydate,strtotime($complaint->date));?></td>
						<td align="center"><?php 
									if($complaint->status == 0)
									{
										echo Yii::t('app','Open');
									}
									if($complaint->status == 1)
									{
										echo  Yii::t('app','Close');
									}?>
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
               	echo CHtml::link(Yii::t("app",'View'),array('complaints/feedback','id'=>$complaint->id,));?></td>
            </tr>    
						               
					<?php                    
						$i++;
					}
						}
						else{
?>
							<tr>
                            	<td colspan="5" class="nothing-found"><?php echo Yii::t("app","You have No Complaint And Feedbacks"); ?></td>
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





