<style type="text/css">
a{ margin:0 2px;}
</style>
<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
?>
<?php $this->renderPartial('/default/leftside');?>
<div class="right_col"  id="req_res123">
    <div class="pageheader">
        <div class="col-lg-8">
            <h2><i class="fa fa-file-text"></i><?php echo Yii::t('app', 'Salary and Payslips');?><span><?php echo Yii::t('app', 'View your salary and payslips here');?> </span></h2>
        </div>
        <div class="col-lg-2"></div>
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
            <ol class="breadcrumb">
                <li class="active"><?php echo Yii::t('app', 'Payslips');?></li>
            </ol>
        </div>    
        <div class="clearfix"></div>    
    </div>
    <div class="contentpanel">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app','Payslips'); ?></h3>
        </div>
        <div class="people-item">
        	<div class="row">
            	<div class="col-md-12">
               <div class="pull-right portal-btnns">
				<?php   echo CHtml::link('<span>'.Yii::t("app",'View My Payslips').'</span>',array('/teachersportal/payslip'),array('class'=>'btn btn-primary pull-right')); ?>
                <?php   echo CHtml::link('<span>'.Yii::t("app",'View My Salary Details').'</span>',array('/teachersportal/salary/view'),array('class'=>'btn btn-primary pull-right')); ?>
            </div>
                </div>
            </div>
        	
        	<div class="table-responsive">		
                <table width="100%" cellpadding="0" cellspacing="0" class="table table-hidaction table-hover mb30">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th><?php echo Yii::t('app', 'Salary Date');?></th>
                            <th><?php echo Yii::t('app', 'Earn Total');?></th>
                            <th><?php echo Yii::t('app', 'Deduction Total');?></th>
                            <th><?php echo Yii::t('app', 'Net Salary');?></th>
                            <th><?php echo Yii::t('app', 'Action');?></th>
                        </tr>
                        <?php 
						if(count($payslips)>0){
							foreach($payslips as $i=>$payslip){
								$employee	=	Staff::model()->findByPk($payslip->employee_id);
							?>
                            <tr class=<?php echo $cls;?>>
                                <td><?php echo ($pages->getCurrentPage() * $pages->getPageSize()) + ($i + 1);?></td>
                                <td>
									<?php
										if($settings!=NULL)
											echo date($settings->displaydate,strtotime($payslip->salary_date));											
										else
                                    		echo date('Y-m-d', strtotime($payslip->salary_date));
									?>
                              	</td>  
                                <td><?php echo $payslip->earn_total; ?></td>
                                <td><?php echo $payslip->deduction_total; ?></td>
                                <td><?php echo $payslip->net_salary; ?></td>
                                <td> 
                                    <div class="tt-wrapper-new">
                                    	<a href="<?php echo Yii::app()->baseUrl."/payslips/".$payslip->filename; ?>" target="_blank" class="makeedit"><span><?php echo Yii::t('app','View'); ?></span></a> 								 
                                        <?php echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('/teachersportal/payslip/download','id'=>$payslip->id), array('class'=>'makeedit'));?>  
                                              
                                    </div>                                          
                                 </td>
                          	</tr>
							<?php
							}
						}
						else{
						?>
                        <tr>
                            <td colspan="6" align="center"><?php echo Yii::t('app', 'No payslips found');?></td>
                        </tr>
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
					//'nextPageLabel'=>'My text >',
					'header'=>'',
					'htmlOptions'=>array('class'=>'pages'),
                ));?>
                </div>
        	</div>
        </div>
    </div>
</div>