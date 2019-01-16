<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 5px 0 0;
}
.pro-ul{ margin:0px; padding:0px;}
.pro-ul li{ padding:0px 3px; list-style:none; display:inline-block;}
.delete{ width:12px; height:12px; background:url(images/task-dlt.png) no-repeat center;}
.view{ width:12px; height:12px; background:url(assets/1effa1bf/gridview/view.png) no-repeat center;}
.edit{ width:12px; height:12px; background:url(images/task-edit.png) no-repeat center;}
</style>
<?php
$this->breadcrumbs=array(
	$this->module->id => array('/purchase'),
	Yii::t('app','Issue Details'),
);
$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
    	<td valign="top">
        	<div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Issue Details');?></h1>
                 <?php $item = PurchaseItems::model()->findByPk($_REQUEST['id']);?>
                <h3><?php echo Yii::t('app','Item Name').' : '.ucfirst($item->name);?></h3>
                
				<div class="pdtab_Con" style="width:100%">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  		<tbody>
                    		<tr class="pdtab-h">
                            	<td align="center" height="18"><?php echo Yii::t('app','Requested By');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Department');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Quantity');?></td>
                                <td align="center" height="18"><?php echo Yii::t('app','Issued On');?></td>
                            </tr>
						<?php
							if($materials){
								if(isset($_REQUEST['page'])){
									$i=($pages->pageSize*$_REQUEST['page'])-9;
								}
								else{
									$i=1;
								}
									foreach($materials as $material){
											$employee 	= Employees::model()->findByAttributes(array('id'=>$material->employee_id));
											$department = EmployeeDepartments::model()->findByAttributes(array('id'=>$material->department_id));?>
											<tr>
												<td align="center" width="40">
													<?php if($employee!=NULL){
																echo ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name);
															}
														   else{
															 $user = Profile::model()->findByAttributes(array('user_id'=>$material->employee_id));
															   echo ucfirst($user->firstname).' '.ucfirst($user->lastname);
														   }?>
												</td>
												<td align="center" width="40"><?php echo $department->name; ?></td>
												<td align="center" width="40"><?php echo $material->quantity; ?></td>
												<td align="center" width="40">
												<?php if($material->issued_date != NULL and $material->issued_date != '0000-00-00'){
																						if($settings){
																							echo date($settings->displaydate, strtotime($material->issued_date));
																						}
																						else{
																							echo $material->issued_date;
																						}
																					}
																					else{
																						echo '-';
																					} ?>
												</td>
											</tr>
		<?php							$i++;
									}
							}
							else{
?>
								<td colspan="5" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','Nothing Found!'); ?></td>
<?php								
							}
?>                            
                        </tbody>
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
