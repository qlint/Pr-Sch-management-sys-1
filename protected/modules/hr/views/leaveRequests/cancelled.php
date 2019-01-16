<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 5px 0 0;
}
.os-button-column ul li a{display: block;float: left;width: 20px;height: 20px;}
.os-button-column ul{ margin:0px; padding:0px;}
.os-button-column ul li{ padding:0px 3px; list-style:none; display:inline-block;}
.delete{ width:12px; height:12px; background:url(images/os-deleteicon.png) no-repeat center;}
.view{ width:12px; height:12px; background:url(images/os-viewicon.png) no-repeat center;}
.edit{ width:12px; height:12px; background:url(images/os-editicon.png) no-repeat center;}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('/hr/leaveTypes'),
	Yii::t('app','Leave Requests')=>array('/hr/leaveRequests/pending'),
	Yii::t('app','Cancelled')
);

$settings = UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/default/leftside');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Cancelled Leave Requests');?></h1>
            	<div class="search_btnbx">
                	<div id="jobDialog"></div>
                	<div class="contrht_bttns"></div>
				</div>
              	<div class="clear"></div>
				<?php Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
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
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Type');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Requested By');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','From');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','To');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Cancelled By');?></td>
                            <td align="center" height="18" width="175"><?php echo Yii::t('app','Actions');?></td>
                        </tr>
                        <?php if($requests){ ?>
							<?php
                            foreach($requests as $index=>$request){            
                            ?>
                            <tr>			        		
                                <td align="center"><?php echo ($pages->getCurrentPage() * $pages->getPageSize()) + ($i + 1);?></td>
                                <td align="center"><?php echo ($request->leaveType)?$request->leaveType->type:'-'; ?></td>
                                <td align="center">
                                    <?php
                                        $employee	= Staff::model()->findByAttributes(array('uid'=>$request->requested_by));
                                        echo ($employee!=NULL)?$employee->fullname:'-';
                                    ?>
                                </td>
                                <td align="center">
                                    <?php
                                        if($settings){
                                            echo date($settings->displaydate, strtotime($request->from_date));
                                        }
                                        else{
                                            echo date('Y-m-d', $request->from_date);
                                        }
                                    ?>
                                </td>
                                <td align="center">
                                    <?php
                                        if($settings){
                                            echo date($settings->displaydate, strtotime($request->to_date));
                                        }
                                        else{
                                            echo date('Y-m-d', $request->to_date);
                                        }
                                    ?>
                                </td>
                                <td align="center">
                                	<?php
                                    	$user	= User::model()->findByPk($request->handled_by);
										if($user!=NULL)
											echo ($user->profile!=NULL)?$user->profile->fullname:"-";
										else
											echo "-";
									?>
                                </td>
                                <td align="center" class="os-button-column">
                                	<ul>
                                    	<li>
                                        	<?php echo CHtml::link('', array('view', 'id'=>$request->id),array('class'=>'view', 'title'=>Yii::t('app','View'), 'target'=>'_blank'));?>
                                        </li>
                                 	</ul>
                              	</td>
                            </tr>	
                            <?php
                            $i++;
                            }
                            ?>
                        <?php
                        }
                        else{
                        ?>
                            <tr>
                                <td colspan="7" align="center"><?php echo Yii::t('app', 'No cancelled requests');?></td>
                            </tr>
                        <?php
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
                                'htmlOptions'=>array('class'=>'pages'),
                            ));
                        ?>							
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </td>
    </tr>
</table>