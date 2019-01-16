<style>
.edit_bttns li a{ padding:6px;}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/configurations'),
	Yii::t('app','View Terms'),
);
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php  $this->renderPartial('//configurations/left_side');?>
    
    </td>
    <td valign="top">
    
     <div class="cont_right formWrapper">
    
    <!--<div class="searchbx_area">
    <div class="searchbx_cntnt">
    	<ul>
        <li><a href="#"><img src="images/search_icon.png" width="46" height="43" /></a></li>
        <li><input class="textfieldcntnt"  name="" type="text" /></li>
        </ul>
    </div>
    
    </div>-->
   
   
  
    <h1><?php echo Yii::t('app','Terms');?></h1>
     	<div class="edit_bttns " style="top:16px; right:16px;">
            <ul>
            	<li><?php echo CHtml::link(Yii::t('app','Create Terms'), array('create'),array('class'=>'addbttn last')); ?></li>
            </ul>
            
        </div>
        <?php $terms = Terms::model()->findAll();
				if($terms){?>
        
        <div class="tablebx"> 
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
			        <tr class="tablebx_topbg">
			            <td style="text-align:center">
			            <td><?php echo Yii::t('app','Sl. No.');?></td>
			            <td><?php echo Yii::t('app','Term Name');?></td>	
			            <td><?php echo Yii::t('app','Academic year');?></td>  
                        <td><?php echo Yii::t('app','Start date');?></td>  
			            <td><?php echo Yii::t('app','End date');?></td>
                         <td><?php echo Yii::t('app','Action');?></td>
			       
			        </tr>
			        <?php
			        if(isset($_REQUEST['page']))
		            {
		            	$i=($pages->pageSize*$_REQUEST['page'])-9;
		            }
		            else
		            {
		            	$i=1;
		            }
		            $cls="even";

			        foreach($terms as $term){
			        ?>
			        	<tr class=<?php echo $cls;?>>
			        		<td>&nbsp;</td>
	                        <td><?php echo $i; ?></td>
	                        <td><?php if($term->term_id == 1)
										{
											echo "Term 1";
										}
										elseif($term->term_id == 2)
										{
											echo "Term 2";
										}?></td>
                           
                            <?php $academic_year = AcademicYears::model()->findByAttributes(array('id'=>$term->academic_yr_id)); ?>
	                        <td><?php echo $academic_year->name; ?></td>
                             <td><?php 
										if($settings!=NULL){	
											$date1=date($settings->displaydate,strtotime($term->start_date));
											echo $date1;
										}
										else{
											echo $term->start_date; 
										}
									?>		</td>
	                        <td>
                            <?php 
										if($settings!=NULL){	
											$date1=date($settings->displaydate,strtotime($term->end_date));
											echo $date1;
										}
										else{
											echo $term->end_date; 
										}
									?>		
	                        </td>
	                        <td>
	                        	<?php echo CHtml::link(Yii::t('app','Edit'),array('update','id'=>$term->id)).' | '.CHtml::link(Yii::t('app','Delete'),"#", array("submit"=>array('delete','id'=>$term->id),'confirm' => Yii::t('app', 'Are you sure you want to delete this term?'), 'csrf'=>true));
								?>	                        	
	                        </td>   
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
			   <?php /*?> <div class="pagecon">
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
                </div> <!-- END div class="pagecon" 2 --><?php */?>
                <div class="clear"></div>
		    </div>
    <?php } 
        else{
		    ?>
		    		<div class="tablebx"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="hod-table">
					        <tr class="tablebx_topbg">
					            <td class="hod-srl-fld"><?php echo Yii::t('app','Sl. No.');?></td>
					            <td class="hod-name-fld"><?php echo Yii::t('app','Term Name');?></td>	
					            <td class="hod-name-fld"><?php echo Yii::t('app','Academic Year');?></td>  
					            <td class="hod-deprtmnt-fld"><?php echo Yii::t('app','Start Date');?></td>
					            <td class="hod-edit-fld"><?php echo Yii::t('app','End Date');?></td>
                                <td class="hod-edit-fld"><?php echo Yii::t('app','Action');?></td>
					        </tr>
					        <tr>
					        	<td colspan="5" style="text-align:center"><?php echo Yii::t('app','No data found !'); ?></td>
					        </tr>
					    </table>
				    </div>    
		    <?php		    		
                }
				?>
        
        
        
        
        </div>
   
    
    </td>
  </tr>
</table>
