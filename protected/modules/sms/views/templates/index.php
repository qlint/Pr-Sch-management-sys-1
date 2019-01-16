
<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('/notifications/default/sendmail'),
	Yii::t('app','SMS Templates'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">        
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td width="75%" valign="top">
                    <div class="cont_right">
                   <h1>Template</h1>
        
                   <?php $this->renderPartial('_tab');?> 

                            
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
 <li>
	<?php echo CHtml::link('<span>'.Yii::t('app','Create Template').'</span>', array('/sms/templates/create'), array('class'=>'a_tag-btn'));?>
    </li>                                   
</ul>
</div> 

</div>
                            
                            
                    	<div class="sms-block">
    						
							<?php
                            foreach($templates as $template){
								$this->renderPartial('_view', array('data'=>$template));						
							}
							
							if(count($templates)==0){
							?>
							<div style="padding-top:10px" class="notifications nt_red"><i><?php echo Yii::t('app','No templates found');?></i></div>
							<?php
							}
							
							//pagination
							$this->widget('CLinkPager', array(
								'currentPage'=>$pages->getCurrentPage(),
								'itemCount'=>$item_count,
								'pageSize'=>$page_size,
								'maxButtonCount'=>5,
								//'nextPageLabel'=>'My text >',
								'header'=>'',
								'htmlOptions'=>array('class'=>'pages'),
							));
							?>  
                            
                            <div class="clear"></div>
                            
                            <div class="clear"></div>
                        </div>
                         </div>
                    </td>
                    
                </tr>
            </tbody></table>
        </td>
    </tr>
</table>


  <div class="clear"></div>