<?php
	$criteria	= new CDbCriteria;
	$criteria->condition	= '`created_by`=:created_by';
	$criteria->params		= array(':created_by'=>Yii::app()->user->id);
	$criteria->order		= '`created_at` DESC, `id` DESC';
	
	//for pagination
	$total		= SmsTemplates::model()->count($criteria);
	$item_count	= $total;
	$page_size	= 15;
	$pages		= new CPagination($total);
	$pages->setPageSize($page_size);
	$pages->applyLimit($criteria);  // the trick is here!
	
	$smstemplates	= SmsTemplates::model()->findAll($criteria);
?>
<div class="sent_table_image"></div>
<div class="sms-templates">
	<div id="templates_pager">
		<?php
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
    </div>
    <div class="clear"></div>
	<?php
    if(count($smstemplates)>0){
        foreach($smstemplates as $smstemplate){
        ?>
        <div class="sms-template" data-template="<?php echo $smstemplate->template;?>">
        <?php 
            //echo str_replace(array("\n", "\r" , "\s"), array("<br/>", "", "&nbsp;"), $smstemplate->name);
            echo '<b>'.$smstemplate->name.'...</b> '.((strlen($smstemplate->template)>120)?substr($smstemplate->template, 0, 118).'...':$smstemplate->template);								
        ?>                            
        </div>
        <?php
        }
    }
    else{
    ?>
    <div style="padding-top:10px; width:94%; margin-top:10px;" class="notifications nt_red"><i><?php echo Yii::t('app', 'No templates found, create a template').' '.CHtml::link(Yii::t('app', 'now'), array('/sms/templates/create'), array('target'=>'_blank'));?></i></div>
    <?php
    }
    ?>
    
</div>
<script>
$('.sms-template').click(function(){
	var template	= $(this).attr('data-template');
	$('#message').val(template);
});

//pagination AJAX
$('#templates_pager li a').click(function(e) {
	if(!$(this).parent().hasClass('selected')){		
		var url	= $(this).attr('href');
		$.ajax({
			url:url,
			type:"GET",
			success: function(response){
				$('#add_templates').html(response);
			}
		});
	}
    return false;
});
</script>