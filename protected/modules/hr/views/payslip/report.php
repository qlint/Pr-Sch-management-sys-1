<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('index'),
	Yii::t('app','Generate Payslip'),
);
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL)
{
	$date=$settings->displaydate;
}
else
	$date = 'd-m-Y';

?>
<style>
.drop select { width:159px;}

.bttns_addstudent{
	top:0px;
	left:98px;
}
</style>
<script language="javascript">
function details(id)
{
	
	var rr= document.getElementById("dropwin"+id).style.display;
	
	 if(document.getElementById("dropwin"+id).style.display=="block")
	 {
		 document.getElementById("dropwin"+id).style.display="none"; 
	 }
	 if(  document.getElementById("dropwin"+id).style.display=="none")
	 {
		 document.getElementById("dropwin"+id).style.display="block"; 
	 }
	 //return false;
	

}
</script>

<script language="javascript">
function hide(id)
{
	$(".drop").hide();
	$('#'+id).toggle();	
}


</script>

<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
     <?php $this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
        <h1><?php echo Yii::t('app','Reports');?></h1>
        <?php $form=$this->beginWidget('CActiveForm', array(
                'method'=>'get',
            )); ?>
        		<div class="filtercontner">
                    <div class="filterbxcntnt">
                    	<!-- Filter List -->
                        <div class="filterbxcntnt_inner" style="border-bottom:#ddd solid 1px;">
                            <ul>
                                <li style="font-size:12px"><?php echo Yii::t('app','Filter Your Reports:');?></li>
                                
                                 <!-- Name Filter -->
                                <li>
                                    <div onClick="hide('name')" style="cursor:pointer;"><?php echo Yii::t('app','Employee Name');?></div>
                                    <div id="name" style="display:none; width:214px; padding-top:0px; height:33px" class="drop" >
                                        <div class="droparrow" style="left:10px;"></div>
                                        <input type="search" placeholder="<?php echo Yii::t('app','search'); ?>" name="name" value="<?php echo isset($_GET['name']) ? CHtml::encode($_GET['name']) : '' ; ?>" />
                                        <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                    </div>
                                </li>
                                <!-- End Name Filter -->
                                
                                <!-- Admission Number Filter -->
                                <li>
                                    <div onClick="hide('employeenumber')" style="cursor:pointer;"><?php echo Yii::t('app','Employee Number');?></div>
                                    <div id="employeenumber" style="display:none;width:214px; padding-top:0px; height:33px" class="drop">
                                        <div class="droparrow" style="left:10px;"></div>
                                        <input type="search" placeholder="<?php echo Yii::t('app','search'); ?>" name="employee_number" value="<?php echo isset($_GET['employee_number']) ? CHtml::encode($_GET['employee_number']) : '' ; ?>" />
                                        <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                    </div>
                                </li>
                                <!-- End Admission Number Filter -->
                                
                                <!-- Month Filter -->
                                <li>
                                    <div onClick="hide('month')" style="cursor:pointer;"><?php echo Yii::t('app','Month');?></div>
                                    <div id="month" style="display:none; width:230px; padding-top:0px; height:33px" class="drop">
                                        <div class="droparrow" style="left:10px;"></div>
                                        <?php
										$month[0]	=	Yii::t('app','January');
										$month[1]	=	Yii::t('app','February');
										$month[2]	=	Yii::t('app','March');
										$month[3]	=	Yii::t('app','April');
										$month[4]	=	Yii::t('app','May');
										$month[5]	=	Yii::t('app','June');
										$month[6]	=	Yii::t('app','July');
										$month[7]	=	Yii::t('app','August');
										$month[8]	=	Yii::t('app','September');
										$month[9]	=	Yii::t('app','October');
										$month[10]	=	Yii::t('app','November');
										$month[11]	=	Yii::t('app','December');
										$month_array=	array();
										$i=0;
										foreach($month as $data){
											$month_array[$i]["id"] 		=	($i<9) ? "0".($i+1) : $i+1;
											$month_array[$i++]["name"] 	=	$data;
										}
                                        echo CHtml::activeDropDownList($model,'salary_date',CHtml::listData($month_array,"id","name"),array('prompt'=>Yii::t('app','Select '))); 
                                        ?>
                                        <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                    </div>
                                </li>
                                <!-- End Month Filter -->
                                
                                </ul>
                            </div> <!-- END div class="filterbxcntnt_right" -->
                            
                            <div class="clear"></div>
                        </div> <!-- END div class="filterbxcntnt_inner_bot" -->
                        <!-- END Active Filter List -->
                        <div class="filterbxcntnt_inner_bot">
                            <div class="filterbxcntnt_left"><strong><?php echo Yii::t('app','Active Filters:');?></strong></div>
                            <div class="clear"></div>
                            <div class="filterbxcntnt_right">
                                <ul>
                                	
                                    <!-- Name Active Filter -->
									<?php 
									if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL)
                                    {
                                    	$j++; 
									?>
                                    	<li><?php echo Yii::t('app','Name'); ?> : <?php echo $_REQUEST['name']?><a href="<?php echo Yii::app()->request->getUrl().'&name='?>"></a></li>
                                    <?php 
									}
									?>
                                    <!-- END Name Active Filter -->
                                    
                                    <!-- Admission Number Active Filter -->
                                    <?php 
									if(isset($_REQUEST['employee_number']) and $_REQUEST['employee_number']!=NULL)
                                    { 
                                    	$j++; 
									?>
                                    	<li><?php echo Yii::t('app','Employee Number'); ?> : <?php echo $_REQUEST['employee_number']?><a href="<?php echo Yii::app()->request->getUrl().'&employee_number='?>"></a></li>								
									<?php 
									}
									?>
                                     <!-- END Admission Number Active Filter -->
                                     
                                    
                                    
                                    <!-- Gender Active Filter -->
                                    <?php 
									if(isset($_REQUEST['SalaryDetails']['salary_date']) and $_REQUEST['SalaryDetails']['salary_date']!=NULL)
                                    { 
										$j++;
										$mo	=	$_REQUEST['SalaryDetails']['salary_date'];
                                    ?>
                                    	<li><?php echo Yii::t('app','Month'); ?> : <?php echo $month_array[$mo-1]["name"]; ?><a href="<?php echo Yii::app()->request->getUrl().'&SalaryDetails[salary_date]='?>"></a></li>
                                    <?php 
									}
									?>
                                    <!-- END Gender Active Filter -->
                                    
                                    <div class="clear"></div>
                                </ul>
                            </div> <!-- END div class="filterbxcntnt_right" -->
                            
                            <div class="clear"></div>
                        </div>
                    </div> <!-- END div class="filterbxcntnt" -->
                    <div class="box-two" style="margin-bottom:10px;">
                         <div class="pdf-div">
                            <?php if(count($list) > 0){ ?>                                                                 
                                    <button  type="submit" class="pdf_but-input" name= "print" formtarget="_blank" style="outline:none;">
                                        <?php echo Yii::t('app','Generate PDF')?>
                                    </button>                                    
                            <?php } ?>                              
                         </div>
                    </div>
		<?php $this->endWidget(); ?> 
        
		<?php 
		
		if($list){
			
			?>        	
             <div class="tablebx"> 
               <div class="clear"></div> 
                                             
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="tablebx_topbg">
                                
                                <td width="40"><?php echo '#';?></td> 
                                <td><?php echo Yii::t('app','Name'); ?></td>
                                <td><?php echo Yii::t('app','Employee Number'); ?></td>
                                <td><?php echo Yii::t('app','Month'); ?></td>
                                <td><?php echo $model->getAttributeLabel('basic_pay'); ?></td>
                                <td><?php echo $model->getAttributeLabel('earn_total'); ?></td>   
                                <td><?php echo $model->getAttributeLabel('deduction_total'); ?></td>   
                                <td><?php echo $model->getAttributeLabel('net_salary'); ?></td> 
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
							$basic_total 	=	$earn_total	= $deduct_total	=	$net_total	=	0;
							foreach($list as $list_1)
                            {
								$employee		 =	Staff::model()->findByPk($list_1->employee_id);
								$basic_total 	+=	$list_1->basic_pay;
								$earn_total 	+=	$list_1->earn_total;
								$deduct_total	+=	$list_1->deduction_total; 
								$net_total		+= 	$list_1->net_salary;
							?>
                                <tr class=<?php echo $cls;?>>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $employee->fullname;	?></td> 
                                    <td><?php echo $employee->employee_number;	?></td> 
                                    <td><?php echo date('F Y',strtotime($list_1->salary_date));	?></td> 
                                    <td><?php echo $list_1->basic_pay; ?></td> 
                                    <td><?php echo $list_1->earn_total; ?></td>
                                    <td><?php echo $list_1->deduction_total; ?></td>
                                    <td><?php echo $list_1->net_salary; ?></td>
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
                            <tr class=<?php echo $cls;?>>
                                <td colspan="4"><strong><?php echo Yii::t('app','Total'); ?></strong></td>
                                <td><strong><?php echo number_format($basic_total, 2, '.', ''); ?></strong></td> 
                                <td><strong><?php echo number_format($earn_total, 2, '.', ''); ?></strong></td>
                                <td><strong><?php echo number_format($deduct_total, 2, '.', ''); ?></strong></td>
                                <td><strong><?php echo number_format($net_total, 2, '.', ''); ?></strong></td>
                            </tr>
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
			  echo Yii::t('app','No Data Found!!');
		}
?>
    </div>
    </td>
  </tr>
</table>
 <script type="text/javascript">
 $('body').click(function() {
	$('#name').hide();
	$('#employeenumber').hide();
	$('#month').hide();
});

$('.filterbxcntnt_inner').click(function(event){
   event.stopPropagation();
});

$('.load_filter').click(function(event){
   event.stopPropagation();
});
 </script>

