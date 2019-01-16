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
	Yii::t('app','Leave Requests')=>array('/hr/leaveRequests'),
	Yii::t('app','View')
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
            	<h1><?php echo Yii::t('app','Leave Request');?></h1>
            	<div class="search_btnbx">
                	<div class="contrht_bttns"></div>
				</div>
              	<div class="clear"></div>
                <div class="leaverequest-block">
                	<table class="leaver-table" border="0" cellpadding="0" cellspacing="0" width="100%">
                    	<tbody>
                        <tr>
                            <td width="240" class="leavelft-bg">
                                <div class="leave-rqst-left">
                                    <table class="leaver-table-inner leave-rqst-left-clr" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <th width="80px"><h3><?php echo Yii::t('app', 'LEAVE TYPE');?></h3></th>
                                                <th width="10px">:</th>
                                                 <td><p><?php echo ($model->leaveType!=NULL)?$model->leaveType->type:'-';?></p></td>
                                            </tr>
                                            <tr>
                                                <th width="80px"><h3><?php echo Yii::t('app', ' HALF DAY');?></h3></th>
                                                <th width="10px">:</th>
                                                 <td><p><?php echo ($model->is_half_day==0)?Yii::t('app', 'No'):(($model->is_half_day==1)?Yii::t('app', 'Fore Noon'):Yii::t('app', 'After Noon'));?></p></td>
                                            </tr>
                                       </tbody>
                                    </table>
                                </div>
                            </td>
                            <td width="360" class="leaveright-bg">
                                <div class="leave-rqst-right">
                                    <table class="leaver-table-inner leave-rqst-right-clr" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <th width="90px"><h3><?php echo Yii::t('app', 'REQUESTED BY');?></h3></th>
                                                <th width="10px">:</th>
                                                <td>
                                                	<p>
                                                    	<?php
															$employee	= Staff::model()->findByAttributes(array('uid'=>$model->requested_by));
															echo ($employee!=NULL)?$employee->fullname:'-';
														?>
                                                    </p>
                                               	</td>                                                 
                                            </tr>
                                            <tr>
                                                <th><h3><?php echo Yii::t('app', 'FROM');?></h3></th>
                                                <th width="10px">:</th>
                                                <td>
                                                 	<p>
                                                		<?php
															if($settings){
																echo date($settings->displaydate, strtotime($model->from_date));
															}
															else{
																echo date('Y-m-d', $model->from_date);
															}
														?>    
                                                    </p>
                                               	</td>
                                            </tr>
                                            <tr>
                                                <th><h3><?php echo Yii::t('app', 'TO');?></h3></th>
                                                <th width="10px">:</th>
                                                <td>
                                                	<p>
                                                    	<?php
															if($settings){
																echo date($settings->displaydate, strtotime($model->to_date));
															}
															else{
																echo date('Y-m-d', $model->to_date);
															}
														?>
                                                    </p>
                                             	</td>
                                            </tr>
                                       </tbody>
                                    </table>
                                </div>
                            </td>
                         	<td width="100" class="leave-last-bg">
                                <div class="leave-rqst-right">
                                    <table class="leaver-table-inner " border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <th width="90px"><h2><?php echo Yii::t('app', 'STATUS');?></h2></th>                                                 
                                            </tr>
                                            <tr>
                                                <th>
                                                	<h1>
                                                    	<?php
															switch($model->status){
																case 0:
																echo Yii::t('app', 'Pending');
																break;
																
																case 1:
																echo Yii::t('app', 'Approved');
																break;
																
																case 2:
																echo Yii::t('app', 'Rejected');
																break;
																
																case 3:
																echo Yii::t('app', 'Cancelled');
																break;
																
																default:
																echo '-';
																break;
															}
                                                        ?>
                                                  	</h1>
                                             	</th>
                                            </tr>
                                       </tbody>
                                    </table>
                                </div>
                             </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="leave-reason-brd-bg1">
                        <div class="tablereson-blok leave-reason-brd-bg">
                        	<h3><?php echo Yii::t('app', 'REASON');?></h3>
                            <?php
								if($model->file_name!=NULL)
                            		echo '<span>'.CHtml::link('Download', array('downloadFile', 'id'=>$model->id), array('title'=>Yii::t('app', 'Download Attachment'))).'</span>';
							?>
                        </div>
                        <div class="reason-textarea">
                            <p><?php echo $model->reason;?></p>
                   		 </div> 
                    </div>
                </div>
                <div class="aprived-by">
                    <h4>
                    	<span>
							<?php
								if($model->status==1)
                            		echo Yii::t('app', 'Aproved By');
								else if($model->status==2)
                            		echo Yii::t('app', 'Rejected By');
								else if($model->status==3)
                            		echo Yii::t('app', 'Cancelled By');
							?>
                      	</span>
                        <?php
                        	$user	= User::model()->findByPk($model->handled_by);
							echo ($user!=NULL && $user->profile)?$user->profile->fullname:'-';
						?>
                  	</h4>
                    <p>
                    	<?php
                        	if($model->status==1 || $model->status==2)
								echo $model->response;
							else if($model->status==3)
								echo $model->cancel_reason;
						?>
                   	</p>
                </div>
            </div>
        </td>
    </tr>
</table>