<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('index'),
	Yii::t('app','Generate Payslip'),
);?>
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
        <h1><?php echo Yii::t('app','Generate Payslip');?></h1>
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
                                 <!-- Gender Filter -->
                                <li>
                                    <div onClick="hide('gender')" style="cursor:pointer;"><?php echo Yii::t('app','Gender');?></div>
                                    <div id="gender" style="display:none; width:230px; padding-top:0px; height:33px" class="drop">
                                        <div class="droparrow" style="left:10px;"></div>
                                        <?php
                                        echo CHtml::activeDropDownList($model,'gender',array('M' => Yii::t('app','Male'), 'F' => Yii::t('app','Female')),array('prompt'=>Yii::t('app','All'))); 
                                        ?>
                                        <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                    </div>
                                </li>
                                <!-- End Gender Filter -->
                             	  
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
									if(isset($_REQUEST['Staff']['gender']) and $_REQUEST['Staff']['gender']!=NULL)
                                    { 
										$j++;
										if($_REQUEST['Staff']['gender']=='M')
										$gen=Yii::t('app','Male');
										else
										$gen=Yii::t('app','Female');
                                    ?>
                                    	<li><?php echo Yii::t('app','Staff'); ?> : <?php echo $gen?><a href="<?php echo Yii::app()->request->getUrl().'&Staff[gender]='?>"></a></li>
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
                    
		<?php $this->endWidget(); ?> 	
        <?php
			/* Error Message */
			if(Yii::app()->user->hasFlash('successMessage')): 
			?>
				<br/>
				<div style="color:green; text-align:center; line-height:20px;">
				<?php echo Yii::app()->user->getFlash('successMessage'); ?>
				</div>
			<?php endif;
			/* End Error Message */
			?>
        	
		<?php 
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
                                    									 
										 <?php	
										 	  echo '<li>'.CHtml::link('<span>'.Yii::t('app', 'Generate').'</span>', array('/hr/payslip/generate','id'=>$list_1->id), array('class'=>'genarate')).'</li>'; 
																	
										 	 echo '<li>'. CHtml::link('<span>'.Yii::t('app', 'Payslip').'</span>', array('/hr/payslip/payslips','id'=>$list_1->id), array('class'=>'payslip')).'</li>'; 
                                        
                                         ?>  
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
		}else{
			echo Yii::t('app','No results Found!!!');
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
	$('#gender').hide();
});

$('.filterbxcntnt_inner').click(function(event){
   event.stopPropagation();
});

$('.load_filter').click(function(event){
   event.stopPropagation();
});
 </script>