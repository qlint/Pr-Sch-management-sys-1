<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('index'),
	Yii::t('app','Generate Payslip'),
);
$employee	=	Staff::model()->findByPk($_REQUEST['id']);
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL)
{
	$date=$settings->displaydate;
}
else
	$date = 'd-m-Y';
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
     <?php $this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
        <h1><?php echo Yii::t('app','View Payslips of ').$employee->fullname;?></h1>
		<?php 
		$model	=	new SalaryDetails; 
		if($list){?>
             <div class="tablebx"> 
               <div class="clear"></div> 
                                             
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="tablebx_topbg">
                                
                                <td width="40"><?php echo '#';?></td> 
                                <td><?php echo $model->getAttributeLabel('salary_date'); ?></td>
                                <td><?php echo $model->getAttributeLabel('earn_total'); ?></td>   
                                <td><?php echo $model->getAttributeLabel('deduction_total'); ?></td>   
                                <td><?php echo $model->getAttributeLabel('net_salary'); ?></td> 
                                <td><?php echo Yii::t('app','Action');?></td>
                                <!--<td style="border-right:none;">Task</td>-->
                            </tr>
                            <?php 
                            if(isset($_REQUEST['page']))
                            {
                            	$i=($pages->pageSize*($_REQUEST['page']-1))+1;
                            }
                            else
                            {
                            	$i=1;
                            }
                            $cls="even";
                            ?>
                            
                            <?php 
							foreach($list as $list_1)
                            {
								$employee	=	Staff::model()->findByPk($list_1->employee_id);
							?>
                                <tr class=<?php echo $cls;?>>
                                <td><?php echo $i; ?></td>
                               	<td><?php echo date($date,strtotime($list_1->salary_date));	?></td>  
                                <td><?php echo $list_1->earn_total; ?></td>
                                <td><?php echo $list_1->deduction_total; ?></td>
                                <td><?php echo $list_1->net_salary; ?></td>
                                <td> 
                                 	<div class="tt-wrapper-new">
                                    <a href="<?php echo Yii::app()->baseUrl."/payslips/".$list_1->filename; ?>" target="_blank" class="makeview"><span><?php echo Yii::t('app','View'); ?></span></a> 								 
										 <?php	
										 	  echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('/hr/payslip/download','id'=>$list_1->id), array('class'=>'makedownload'));  			 ?>  
                                              
									</div>                                          
                                 </td>
                                
                                <!--<td style="border-right:none;">Task</td>-->
                                </tr>
								<?php
                                if($cls=="even")
                                {
                                	$cls="odd" ;
                                }
                                else
                                {
                                	$cls="even"; 
                                }
                                $i++;
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
                          //'nextPageLabel'=>'My text >',
                          'header'=>'',
                        'htmlOptions'=>array('class'=>'pages'),
                        ));?>
                        
                        </div> <!-- END div class="pagecon" 2 -->
                        <div class="clear"></div>
                    </div> <!-- END div class="tablebx" -->
                    <?php 
		}else{
			echo Yii::t('app','No results Found!!!');
		}
					 ?>

    </div>
    </td>
  </tr>
</table>
 

