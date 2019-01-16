<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('index'),
	Yii::t('app','Generate Payslip'),
);


?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/default/leftside');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','My Payslips');?></h1>
                <div class="tablebx"> 
                	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr class="tablebx_topbg">
                                <td width="40">#</td>
                                <td><?php echo Yii::t('app', 'Salary Date');?></td>
                                <td><?php echo Yii::t('app', 'Earn Total');?></td>
                                <td><?php echo Yii::t('app', 'Deduction Total');?></td>
                                <td><?php echo Yii::t('app', 'Net Salary');?></td>
                                <td><?php echo Yii::t('app', 'Action');?></td>
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
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('/hr/myPayslip/download','id'=>$payslip->id), array('class'=>'makeedit'));?>  
                                                  
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
                    
                    <div class="clear"></div>
                    <div class="clear"></div>
                </div> <!-- END div class="tablebx" -->
            </div>
        </td>
    </tr>
</table>




<style type="text/css">
a{ margin:0 2px;}
</style>
<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
?>