<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('/notifications/default/sendmail'),
	Yii::t('app','SMS Contacts'),
);
?>
<style>
.contact_box.selected {
	background-color: #FEC42C;
}
.extra_options {
	margin-top: 10px;
	margin-bottom: 10px;
	display: none;
}
.remain-contact-bx {
	background: url("images/gray_chek_untik.png") no-repeat scroll 8px 6px rgba(0, 0, 0, 0);
	border-bottom: 1px solid #E5E5E5;
	color: #000000;
	cursor: pointer;
	float: left;
	margin: 0 5px 0px 7px;
	padding: 10px 14px 11px 42px;
	width: 88%;
}
.remain-contact-bx.selected {
	background: #FFFFCC url("images/gray_chek.png") no-repeat scroll 8px 6px;
	border-bottom: 1px solid #E5E5E5;
	color: #000000;
	cursor: pointer;
	float: left;
	margin: 0 5px 0px 7px;
	padding: 10px 14px 11px 42px;
	width: 88%;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top" id="port-left"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top">
    <div class="cont_right formWrapper">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td width="75%" valign="top"><div class="sms-block">
                <h1><?php echo Yii::t('app','Contacts');?></h1>
                <div class="button-bg">
                  <div class="top-hed-btn-left"> </div>
                  <div class="top-hed-btn-right">
                    <ul>
                      <li> <?php echo CHtml::link('<span>'.Yii::t('app', 'Create new contact').'</span>', array('create'), array('class'=>'a_tag-btn'));?> </li>
                      <li> <a href="javascript:void(0);" class="a_tag-btn" id="add_contacts_to_groups"><?php echo Yii::t('app', 'Add to groups');?></a></li>
                    <li>
                      <?php
                                if(isset($_GET['group']) and $_GET['group']!=""){		//for group contacts only
								?>
                      <a href="javascript:void(0);" class="a_tag-btn" id="remove_contacts_from_group"><?php echo Yii::t('app', 'Remove from group');?></a>
                      <?php
								}
								?>
                    </li>
                    <li> <a href="javascript:void(0);" class="a_tag-btn" id="delete_contacts"><?php echo Yii::t('app', 'Delete');?></a></li>
                    </ul>
                  </div>
                </div>

                <div class="clear"></div>
                <?php
                            foreach($contacts as $contact){
								$this->renderPartial('_view', array('data'=>$contact));						
							}							
							
							if(count($contacts)==0){
							?>
                <div style="padding-top:10px" class="notifications nt_red"><i><?php echo Yii::t('app','No contacts found');?></i></div>
                <?php
							}
							?>
                <div class="clear"></div>
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
                
                <!-- remaining contacts -->
                <div class="clear"></div>
                <?php
                            if(isset($_GET['group'])){
								$group	= $_GET['group'];
								$this->renderPartial('_remaining', array('group'=>$group));						
							}
							?>
                <!-- remaining contacts --> 
              </div></td>
          </tr>
        </tbody>
      </table>
      </div>
      </td>
  </tr>
</table>
<script>
$('.contact_box').click(function(e) {
	
	if($(this).hasClass('selected'))
    	$(this).removeClass('selected');
	else
		$(this).addClass('selected');
});

$('#add_contacts_to_groups').click(function(e) {
	var contactIds	= [];
    $('.contact_box').each(function(index, element) {
		if($(element).hasClass('selected')){
        	contactIds.push($(element).attr('data-contact-id'));
		}
    });
	
	if(contactIds.length==0){
		alert('<?php echo Yii::t('app', 'Select contacts first');?>');
	}
	else{
		if($('#contact_groups').is(':visible')){
			$('#contact_groups').slideUp(function(){
				$(this).html('');
			});
		}
		else{
			$.ajax({
				url:'<?php echo Yii::app()->createUrl('/sms/contacts/addtogroups');?>',
				cache:false,
				data:{"<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
				beforeSend: function(){
					var img	= $('<img />');
					img.attr({
						src:'<?php echo Yii::app()->request->baseUrl;?>/images/loadinfo.gif',
					});
					$('#contact_groups').html(img);
				},
				success: function(response){
					$('#contact_groups').html( response ).slideDown();
				},
				error:function(){
					document.location.reload();
				}
			});
		}
	}
});

$('#delete_contacts').click(function(e) {   
	var contactIds	= [];
    $('.contact_box').each(function(index, element) {
		if($(element).hasClass('selected')){
        	contactIds.push($(element).attr('data-contact-id'));
		}
    });
	
	if(contactIds.length==0){
		alert('<?php echo Yii::t('app', 'Select contacts first');?>');
	}
	else{
		if(confirm('<?php echo Yii::t('app', 'Are you sure ?');?>')){
			//ajax
			$.ajax({
				type:"POST",
				url:"<?php echo Yii::app()->createUrl('/sms/contacts/deletecontacts');?>",
				data:{contacts:contactIds ,"<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
				success: function(){
					document.location.reload();
				},
				error:function(){
					document.location.reload();
				}
			});	
		}
	}
});

//only for group contacts
$('#remove_contacts_from_group').click(function(e) {
    var contactIds	= [];
    $('.contact_box').each(function(index, element) {
		if($(element).hasClass('selected')){
        	contactIds.push($(element).attr('data-contact-id'));
		}
    });
	
	if(contactIds.length==0){
		alert('<?php echo Yii::t('app', 'Select contacts first');?>');
	}
	else{
		if(confirm('<?php echo Yii::t('app', 'Are you sure ?');?>')){
			//ajax
			$.ajax({
				type:"POST",
				url:"<?php echo Yii::app()->createUrl('/sms/contacts/removefromgroups');?>",
				data:{contacts:contactIds, groups:[<?php echo $group;?>], "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
				success: function(response){
					$('.contact_box.selected').remove();
					window.location.reload();
				},
				error:function(){
					document.location.reload();
				}
			});	
		}
	}
});

$('.remove_from_group').click(function(e) {
	if(confirm('<?php echo Yii::t('app', 'Are you sure ?');?>')){
		var contactIds	= [];
		if($(this).attr('data-contact-id')){
			$(this).addClass('selected');
			contactIds.push($(this).attr('data-contact-id'));
			//ajax
			$.ajax({
				type:"POST",
				url:"<?php echo Yii::app()->createUrl('/sms/contacts/removefromgroups');?>",
				data:{contacts:contactIds, groups:[<?php echo $group;?>], "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
				success: function(response){
					$('.contact_box.selected').remove();
					window.location.reload();
				},
				error:function(){
					document.location.reload();
				}
			});	
		}
	}
});

$('.remain-contact-bx').click(function(e) {
	if($(this).hasClass('selected')){
		$(this).removeClass('selected');
	}
	else{
		$(this).addClass('selected');
	}    
});

$('#add_to_this_group').click(function(e) {
    var contactIds	= [];
    $('.remain-contact-bx').each(function(index, element) {
		if($(element).hasClass('selected')){
        	contactIds.push($(element).attr('data-contact-id'));
		}
    });
	
	if(contactIds.length==0){
		alert('<?php echo Yii::t('app', 'Select contacts first');?>');
	}
	else{
		//ajax
		$.ajax({
			type:"POST",
			url:'<?php echo Yii::app()->createUrl('/sms/contacts/addtogroups');?>',
			cache:false,
			data:{contacts:contactIds, groups:[<?php echo $group;?>], "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
			beforeSend: function(){
				
			},
			success: function(response){
				$('.remain-contact-bx.selected').remove();
				window.location.reload();
			},
			error:function(){
				document.location.reload();
			}
		});
	}
});
</script>