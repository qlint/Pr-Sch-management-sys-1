<style type="text/css">
.pdtab_Con {
    margin: 0;
    padding: 5px 0 0;
}

</style>
<script language="javascript">
function hide(id)
{
    $(".drop").hide();
    $('#'+id).toggle(); 
}
</script>
<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('leaveTypes/index'),
	Yii::t('app','Manage Leave Types')=>array('index'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
     <?php $this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
    
    
  
    <h1><?php echo Yii::t('app','Manage Leave Types');?></h1>
     	<!-- Save Filter, Load Filter, Clear All -->
                <div class="search_btnbx">
                    <?php $j=0; ?>
                    <div id="jobDialog"></div>
                    
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
 <li><?php echo CHtml::link('<span>'.Yii::t('app','Add Leave Type').'</span>', array('create'),array('class'=>'a_tag-btn')); ?></li>                                  
</ul>
</div> 
</div> 
                    
                    
                     <!-- END div class="contrht_bttns" -->
                     <!-- END div class="bttns_addstudent" -->
                </div> <!-- END div class="search_btnbx" -->
                <!-- END Save Filter, Load Filter, Clear All -->
                <div class="clear"></div>
        
        <?php
		Yii::app()->clientScript->registerScript('myHideEffect','$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',CClientScript::POS_READY);
	
		if(Yii::app()->user->hasFlash('successMessage')): 
	?>
		<div class="flashMessage" style="color:#C00; padding-left:220px; font-size:13px">
			<?php echo Yii::app()->user->getFlash('successMessage'); ?>
		</div>
		<?php endif; ?>
 
    <?php
	$leaves = LeaveTypes::model()->findAllByattributes(array('is_deleted'=>0),array('order'=>'id DESC'));
	  	if($leaves){ 
         	?>
			<div class="pdtab_Con" style="width:100%">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
			        <tr class="pdtab-h">
			            
			            <td align="center" height="18" width="50"><?php echo '#';?></td>
			            <td align="center" height="18" width="175"><?php echo Yii::t('app','Type');?></td>				             
			            <td align="center" height="18" width="80"><?php echo Yii::t('app','Category');?></td>
                        <td align="center" height="18" width="80"><?php echo Yii::t('app','Gender');?></td>
                        <td align="center" height="18" width="150"><?php echo Yii::t('app','Count');?></td>
                         <td align="center" height="18" width="150"><?php echo Yii::t('app','Action');?></td>
			        </tr>
                    
			        <?php
					  $i=1;
                    foreach($leaves as $leave){
                  
			        ?>
			        	<tr>			        		
	                        <td align="center"><?php echo $i; ?></td>
	                        <td align="center"><?php echo ucfirst($leave->type); ?></td>	
                            <td align="center"><?php if($leave->category == 1){echo Yii::t('app','Per Quarter');}
													 if($leave->category == 2){echo Yii::t('app','Per Year');}
													 if($leave->category == 3){echo Yii::t('app','Whole Carrer');  }?></td>
                            <td align="center"><?php if($leave->gender == 0){echo Yii::t('app','All');}
													 if($leave->gender == 1){echo Yii::t('app','Male');}
													 if($leave->gender == 2){echo Yii::t('app','Female');  }?></td>
                            <td align="center"><?php echo $leave->count; ?></td>	
	                        <td align="center" class="os-button-column">
                            	<ul>
                                	<li tt-wrapper>
										<?php echo CHtml::ajaxLink('',$this->createUrl('view'),
                                        array('onclick'=>'$("#jobDialog_view").dialog("open"); return false;','update'=>'#jobDialog_view_div'.$leave->id,'type' =>'GET','data' => array('id' =>$leave->id),'dataType' => 'text',),array('id'=>'showJobDialog_view'.$leave->id,'class'=>'view', 'title'=>Yii::t('app','View'))); ?></li>
                                	<li><?php echo CHtml::link('',array('update','id'=>$leave->id), array('class'=>'edit', 'title'=>Yii::t('app','Edit'))); ?></li>
                                    <li><?php echo CHtml::link('',"#", array("submit"=>array('delete','id'=>$leave->id),'confirm' => Yii::t('app', 'Are you sure you want to delete this leave type ?'), 'csrf'=>true, 'class'=>'delete', 'title'=>Yii::t('app','Delete'))); ?></li>
                                </ul>	
                                <div id="jobDialog_view_div<?php echo $leave->id; ?>"></div>	                        	                        	
	                        </td>   
	                    </tr>	
			        <?php
			        	$i++;
			        }
					 	
			        ?>
			    </table>
                <div class="clear"></div>
		    </div>
		    <?php
		    	}else{
		    ?>
		    		<div class="pdtab_Con" style="width:100%">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="pdtab-h">                                
                                <td align="center" height="18" width="50"><?php echo '#';?></td>
                                <td align="center" height="18" width="175"><?php echo Yii::t('app','Type');?></td>				            
                                <td align="center" height="18" width="150"><?php echo Yii::t('app','Category');?></td>  
                                 <td align="center" height="18" width="150"><?php echo Yii::t('app','Gender');?></td>  
                                <td align="center" height="18" width="80"><?php echo Yii::t('app','Count');?></td>
                                <td align="center" height="18" width="150"><?php echo Yii::t('app','Action');?></td>
                               
                            </tr>
					        <tr>
					        	<td colspan="6" style="text-align:center; font-style:italic;"><?php echo Yii::t('app','Nothing found!'); ?></td>
					        </tr>
					    </table>
				    </div>    
		    <?php		    		
                }
	?>

    </div>
       </div>
    </td>
  </tr>
</table>


