<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('index'),
	Yii::t('app','Salary Details'),
);


?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
     <?php $this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
        <h1><?php echo Yii::t('app','Salary Details');?></h1>
		<?php 
		$model	=	new Staff; 
		if($list){?>
             <div class="tablebx"> 
               <div class="clear"></div> 
                                             
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="tablebx_topbg">
                                
                                <td width="40"><?php echo '#';?></td>
                                <td><?php echo Yii::t('app','Name');?></td>
                                <td><?php echo $model->getAttributeLabel('employee_number'); ?></td>   
                                <td><?php echo $model->getAttributeLabel('employee_department_id'); ?></td> 
                                <td><?php echo $model->getAttributeLabel('gender'); ?></td> 
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
							?>
                                <tr class=<?php echo $cls;?>>
                                <td><?php echo $i; ?></td>
                               	<td><?php echo $list_1->fullname;	?></td>  
                                <td><?php echo $list_1->employee_number; ?></td>
                                <td><?php 
									$department	=	EmployeeDepartments::model()->findByPk($list_1->employee_department_id);
									echo ($department) ? $department->name : '-'; ?></td>
                                <td>
									<?php 
                                    if($list_1->gender=='M')
                                    {
                                        echo Yii::t('app','Male');
                                    }
                                    elseif($list_1->gender=='F')
                                    {
                                        echo Yii::t('app','Female');
                                    }
                                    ?>
                                    
                                </td>   
                                <td align="center" class="os-button-column">
                                  	<ul class="tt-wrapper">
                                    	<li>
                                         <?php
										 	if($list_1->basic_pay==0 and $list_1->TDS==0 and $list_1->ESI==0 and $list_1->EPF==0) //add button
											{ 
											 echo '<li>'.CHtml::link('<span>'.Yii::t('app', 'Add').'</span>', array('/hr/staff/addsalarydetails','id'=>$list_1->id), array('class'=>'add-icon')).'</li>';  
											 
											}else{  //edit button
										 	  echo '<li>'.CHtml::link('<span>'.Yii::t('app', 'Edit').'</span>', array('/hr/staff/addsalarydetails','id'=>$list_1->id), array('class'=>'edit')).'</li>';  
											}
                                             
                                         ?>  
                                        </li>
                                    </ul>
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
		}
					 ?>

    </div>
    </td>
  </tr>
</table>
 

