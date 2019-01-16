<?php
	$criteria	= new CDbCriteria;	
	$criteria->order		= '`first_name` ASC';
	
	//for pagination
	$total		= Contacts::model()->count($criteria);
	$item_count	= $total;
	$page_size	= 30;
	$pages		= new CPagination($total);
	$pages->setPageSize($page_size);
	$pages->applyLimit($criteria);  // the trick is here!
	
	$contacts	= Contacts::model()->findAll($criteria);
?>
<div class="clear"></div>
<div class="content-box sent_table" id="contacts-box" style="float:none;">
<div class="sent_table_image"></div>
	
    <form id="select-contact-form">
        <table>
        <tr>
        <td>
                
            <div id="contacts_pager">
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
            <div class="popup_contact" style="width:500px;">
            <?php
            if(count($contacts)>0){
			?>
            <ul>
                <li><input type="checkbox" id="check-all-contacts" value="" /></li>
               
                <li><label for="check-all-contacts"><?php echo Yii::t('app', 'Select All');?></label></li>
            </ul>
            <?php
			}			
			else{
			?>
			<div style="padding-top:10px; width:94%; margin-top:10px;" class="notifications nt_red"><i><?php echo Yii::t('app','No contacts found').'. '.CHtml::link(Yii::t('app','Create Now'), array('/sms/contacts/create'), array('target'=>'_blank'));?></i></div>
			<?php
			}
			?>
            </div>
			<?php
            foreach($contacts as $key=>$contact){
            ?>
            <div class="popup_contact">
            
            <ul>
                <li><input type="checkbox" value="<?php echo $contact->id;?>" data-name="<?php echo $contact->first_name.' '.$contact->last_name;?>" data-mobile="<?php echo $contact->mobile;?>" name="contact[]" id="contact-<?php echo $contact->id;?>" class="select_contact" /></li>
               
                <li><label for="contact-<?php echo $contact->id;?>"><?php echo $contact->first_name.' '.$contact->last_name.':'.$contact->mobile?></label></li>
            </ul>
            </div>
            <?php
            }
            ?>
            
            </td></tr>
			<?php
			if(count($contacts)>0){
			?>
            <tr>
                <td >				
                <input type="button"  class="popup_contact_but" id="add-contacts-to-send-box" value="<?php echo Yii::t('app', 'Add');?>" /></td>
            </tr>
            <?php
			}
			?>
        </table>
    </form>
</div>
<script>
$('#add-contacts-to-send-box').click(function(e) {
    var sel_contacts	= $('#select-contact-form .select_contact:checked');
	if(sel_contacts.length==0){
		alert('Select contact');
		return;
	}
	
	$( '#recipients_tag, #recipients' ).val('');
	$( '#recipients_tagsinput span.tag' ).remove();
	
	sel_contacts.each(function(index, element) {
		var name	= $(element).attr('data-name'),
			mobile	= $(element).attr('data-mobile');
		value		= name + ':' + mobile;		
		$('#recipients').addTag(value);
    });
	
	$('#add_contacts').html( '' ).hide();
});

$('#check-all-contacts').change(function(e) {
    var checked	= $(this).prop('checked');
	$('.popup_contact input[type=checkbox]').prop({checked:checked});
});

$('.popup_contact .select_contact').change(function(e) {
    var allchecks		= $('.popup_contact .select_contact').length,
		selectedchecks	= $('.popup_contact .select_contact:checked').length;
	if(allchecks===selectedchecks){
		$('#check-all-contacts').prop({checked:true});
	}
	else{
		$('#check-all-contacts').prop({checked:false});
	}
});

//pagination AJAX
$('#contacts_pager li a').click(function(e) {
	if(!$(this).parent().hasClass('selected')){		
		var url	= $(this).attr('href');
		$.ajax({
			url:url,
			type:"GET",
			success: function(response){
				$('#add_contacts').html(response);
			}
		});
	}
    return false;
});
</script>