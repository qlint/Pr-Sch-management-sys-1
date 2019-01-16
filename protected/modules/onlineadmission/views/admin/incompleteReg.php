<style type="text/css">
.list_contner_hdng{ 
	margin:8px;
}
.pdtab_Con {
    margin: 0px;
    padding: 15px 0px 0px 0px;
}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('/students'),
	Yii::t('app','Incomplete Registrations'),
);
?>
<script type="text/javascript">
	function details(id)
	{	
		var rr = document.getElementById("dropwin"+id).style.display;	
		if(document.getElementById("dropwin"+id).style.display=="block"){
			document.getElementById("dropwin"+id).style.display="none"; 
		}
		if(document.getElementById("dropwin"+id).style.display=="none"){
			document.getElementById("dropwin"+id).style.display="block"; 
		}	 
	}
	
	function hide(id)
	{
		$(".drop_search").hide();
		$('#'+id).toggle();	
	}
	
	function MM_jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
</script>
                           
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
        <td valign="top">
        	<div class="cont_right formWrapper">
            		<h1><?php echo Yii::t('app','Incomplete Registrations'); ?></h1>
                <div class="search_btnbx">
                    
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    

  <li><?php echo CHtml::link('<span>'.Yii::t('app','Clear Filters').'</span>', array('incompleteReg'), array('class'=>'a_tag-btn')); ?></li>                                  
</ul>
</div> 

</div>

                    <!-- Filter Start -->
                    <div class="filtercontner">
                    	<div class="filterbxcntnt">                    	
                        	<div class="filterbxcntnt_inner" style="border-bottom:#ddd solid 1px;">
                            	<ul>
                                	<li style="font-size:12px"><?php echo Yii::t('app','Filter Your Students:');?></li>
                                    <?php $form=$this->beginWidget('CActiveForm', array('method'=>'get')); ?>
                                    	<!-- Name Filter -->
                                        <li>
                                            <div onClick="hide('name')" style="cursor:pointer;"><?php echo Yii::t('app','Name');?></div>
                                            <div id="name" style="display:none; width:230px;" class="drop_search" >
                                                <div class="droparrow" style="left:10px;"></div>
                                                    <div class="filter_ul">
                                                    <ul>
                                                        <li class="Text_area_Box">
                                                         <input type="search" placeholder="<?php echo Yii::t('app','search');?>" name="name" value="<?php echo isset($_GET['name']) ? CHtml::encode($_GET['name']) : '' ; ?>" />
                                                        </li>
                                                        <li class="Btn_area_Box">
                                                          <input type="submit" value="<?php echo Yii::t('app','Apply');?>" />
                                                        </li>
                                                    </ul>
                                                    </div>
                                                
                                              
                                            </div>
                                        </li> 
                                        
                                        <!-- Email Filter -->
                                        <li>
                                            <div onClick="hide('email')" style="cursor:pointer;"><?php echo Students::model()->getAttributeLabel('email');?></div>
                                            <div id="email" style="display:none;width:230px;" class="drop_search">
                                                <div class="droparrow" style="left:10px;"></div>
                                                    <div class="filter_ul">
                                                    <ul>
                                                        <li class="Text_area_Box">
                                                        	<input type="search" placeholder="<?php echo Yii::t('app','search'); ?>" name="email" value="<?php echo isset($_GET['email']) ? CHtml::encode($_GET['email']) : '' ; ?>" />
                                                        </li>
                                                        <li class="Btn_area_Box">
 															<input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                                        </li>
                                                    </ul>
                                                    </div>
                                                
                                               
                                            </div>
                                        </li>
                                        <!-- End Email Filter -->
                                        
                                        <!-- Phone Filter -->
                                        <li>
                                            <div onClick="hide('phone_no')" style="cursor:pointer;"><?php echo Students::model()->getAttributeLabel('phone1'); ?></div>
                                            <div id="phone_no" style="display:none;width:230px;" class="drop_search">
                                                <div class="droparrow" style="left:10px;"></div>
                                                    <div class="filter_ul">
                                                    <ul>
                                                        <li class="Text_area_Box">
                                                        	<input type="search" placeholder="<?php echo Yii::t('app','search'); ?>" name="phone_no" value="<?php echo isset($_GET['phone_no']) ? CHtml::encode($_GET['phone_no']) : '' ; ?>" />
                                                        </li>
                                                        <li class="Btn_area_Box">
 															 <input type="submit" value="<?php echo Yii::t('app','Apply'); ?>" />
                                                        </li>
                                                    </ul>
                                                    </div>
                                                
                                               
                                            </div>
                                        </li>
                                        <!-- End Phone Filter -->                                       
                                                                              
                                    <?php $this->endWidget(); ?>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                            
                            <div class="filterbxcntnt_inner_bot">
                                <div class="filterbxcntnt_left"><strong><?php echo Yii::t('app','Active Filters:');?></strong></div>
                                <div class="clear"></div>
                                <div class="filterbxcntnt_right">
                                    <ul>
                                        <!-- Name Active Filter -->
    <?php									 
                                        if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL){
                                            $j++; 
    ?>									
                                            <li><?php echo Yii::t('app','Name'); ?> : <?php echo $_REQUEST['name']?><a href="<?php echo Yii::app()->request->getUrl().'&name='?>"></a></li>
    <?php                                     
                                        }
    ?>									
                                        <!-- END Name Active Filter -->                                                                                
                                         
                                        <!-- Email Active Filter -->
    <?php                                     
                                        if(isset($_REQUEST['email']) and $_REQUEST['email']!=NULL){ 
                                            $j++; 
    ?>									
                                            <li><?php echo Students::model()->getAttributeLabel('email'); ?> : <?php echo $_REQUEST['email']?><a href="<?php echo Yii::app()->request->getUrl().'&email='?>"></a></li>								
    <?php									 
                                        }
    ?>									
                                         <!-- END Email Active Filter -->
                                        
                                        <!-- Phone Number Active Filter -->
    <?php                                     
                                        if(isset($_REQUEST['phone_no']) and $_REQUEST['phone_no']!=NULL){ 
                                            $j++; 
    ?>									
                                            <li><?php echo Students::model()->getAttributeLabel('phone1'); ?> : <?php echo $_REQUEST['phone_no']?><a href="<?php echo Yii::app()->request->getUrl().'&phone_no='?>"></a></li>								
    <?php									 
                                        }
    ?>									
                                         <!-- END Phone Number Active Filter -->  
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>  
                    <!-- Filter End -->   
                    <div class="clear"></div>
                    
                    <!-- Alphabetic Sort -->
					<?php $this->widget('application.extensions.letterFilter.LetterFilter', array(
                        //parameters
                        'outerWrapperClass'=>'list_contner_hdng',
                        'innerWrapperId'=>'letterNavCon',
                        'innerWrapperClass'=>'letterNavCon',
                        'activeClass'=>'ln_active',
                    )); ?>
                    <!-- END Alphabetic Sort -->
                    
                    <!-- Flash Message -->
                    <?php
					Yii::app()->clientScript->registerScript(
						'myHideEffect',
						'$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
						CClientScript::POS_READY
					);
					?>
                	<?php
					/* Success Message */
					if(Yii::app()->user->hasFlash('successMessage')): 
					?>
						<div class="flashMessage" style="background:#FFF; color:#689569; padding-left:220px; font-size:13px">
						<?php echo Yii::app()->user->getFlash('successMessage'); ?>
						</div>
					<?php endif; ?>
                    
                    <div class="qurdn-not">
                        <div class="head">
                            <b><h2><?php echo Yii::t('app','Note').' :'; ?></h2></b>
                        </div>
                        <div class="not-bullet">
                            <ul>
                                <li><?php echo Yii::t('app','Applications submitted upto 5 hours ago from now.'); ?></li>                                
                            </ul>
                        </div>
                    </div>
                    
<?php 
					echo CHtml::beginForm('','post',array('id'=>'students_list_form')); 
					$academic_yr = AcademicYears::model()->findByAttributes(array('id'=>Yii::app()->user->year));
?>
                    	
<div class="button-bg">
<div class="top-hed-btn-left"><div class="year-timing"><h3><?php echo Yii::t('app','Academic Year'); ?> - <span><?php echo ucfirst($academic_yr->name); ?></span></h3></div> </div>
<div class="top-hed-btn-right">
<ul> 
<?php if($students){ ?>                                   
	<li><?php echo CHtml::submitButton(Yii::t('app','Delete All'),array('submit' =>CController::createUrl('/onlineadmission/admin/delete'), 'id'=>'delete_btn','class'=>'comn-input-btn','name'=>'delete_btn')); ?></li>
<?php } ?>                                    
</ul>
</div> 

</div>

                    
                        <div class="pdtab_Con">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr class="pdtab-h">
                                    <td align="center" width="25"><?php echo CHtml::checkBox('all_student','',array('class'=>'check_all')); ?></td>
                                    <td align="center" width="25"><?php echo Yii::t('app','#'); ?></td>
                                    <td align="center" width="150"><?php echo Yii::t('app','Name'); ?></td>                                
                                    <td align="center" width="75"><?php echo Students::model()->getAttributeLabel('email'); ?></td>
                                    <td align="center" width="75"><?php echo Students::model()->getAttributeLabel('phone1'); ?></td> 
                                    <td align="center" width="65"><?php echo Yii::t('app','Action'); ?></td>                                                         
                                </tr>
    <?php
                            if($students){
                                if(isset($_REQUEST['page'])){
                                    $i	= ($pages->pageSize*$_REQUEST['page'])-19;
                                }
                                else{
                                    $i	= 1;
                                }
                                foreach($students as $student){								
    ?>
                                    <tr>
                                        <td align="center"><?php echo CHtml::checkBox('student_id[]','',array('value'=>$student->id, 'class'=>'student_checkbox')); ?></td>
                                        <td align="center"><?php echo $i; ?></td>
                                        <td align="center"><?php echo $student->studentFullName('forStudentProfile'); ?></td>  
                                        <td align="center">
                                            <?php
                                                if($student->email){
                                                    echo $student->email; 
                                                }
                                                else{
                                                    echo '-';
                                                }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                                if($student->phone1){
                                                    echo $student->phone1; 
                                                }
                                                else{
                                                    echo '-';
                                                }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <div class="tt-wrapper-new"> 
    <?php                                                                                    
                                                echo CHtml::link('<span>'.Yii::t('app','Permanent Delete').'</span>', "#", array('submit'=>array('/onlineadmission/admin/delete','id'=>$student->id, 'flag'=>2), 'confirm'=>Yii::t('app','Are you sure?'),'class'=>'makedelete', 'csrf'=>true));                                                 
    ?>                                            	
                                            </div>
                                        </td>  
                                    </tr>
    <?php								
                                    $i++;
                                }
                            }
                            else{
    ?>
                                <tr>
                                    <td colspan="6" class="nothing-found"><?php echo Yii::t('app','No Incomplete Registrations Found!'); ?></td>
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
                        </div>
                        <input type="hidden" name="flag" value="2" />    
                    <?php echo CHtml::endForm(); ?>                                                 
                    
                </div>
                <div class="clear"></div>  
            </div>
        </td>
    </tr>
</table>        
<script type="text/javascript">
$('body').click(function() {	
	$('#name').hide();
	$('#admissionnumber').hide();
	$('#email').hide();
	$('#phone_no').hide();	
});
$('.filterbxcntnt_inner').click(function(event){
   event.stopPropagation();
});

$('.load_filter').click(function(event){
   event.stopPropagation();
});
$(".check_all").change(function(){
	if(this.checked) {
		$('.student_checkbox').attr('checked', true);
	}
	else{
		$('.student_checkbox').attr('checked', false);
	}
});

$(".student_checkbox").change(function(){ 
	if($('.student_checkbox:checked').length == $('.student_checkbox').length){
		$('.check_all').attr('checked', true);
	}
	else{
		$('.check_all').attr('checked', false);
	}
});

$('#delete_btn').click(function(ev){
	if(confirm("<?php echo Yii::t('app','Are you sure?'); ?>")){
		var chks	=	$("[type='checkbox'][name='student_id[]']:checked");
		if(chks.length==0){
			alert("<?php echo Yii::t('app','Select any Student'); ?>");
			return false;
		}
	}
	else{
		return false;
	}	
});	
</script>