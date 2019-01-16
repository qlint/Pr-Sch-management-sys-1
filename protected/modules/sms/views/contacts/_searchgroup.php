<?php

	$criteria	= new CDbCriteria;	
	$criteria->order		= '`group_name` ASC';
	
	//for pagination
	$total		= ContactGroups::model()->count($criteria);
	$item_count	= $total;
	$page_size	= 30;
	$pages		= new CPagination($total);
	$pages->setPageSize($page_size);
	$pages->applyLimit($criteria);  // the trick is here!
	
	$groups		= ContactGroups::model()->findAll($criteria);
?>
<div class="clear"></div>
<div class="content-box sent_table" id="groups-box">
<div class="sent_table_image_left"></div>
    <form id="select-group-form">
        <table>
        <tr>
        <td>
        	<div id="groups_pager">
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
			if(count($groups)>0){
			?>
            <ul>
                <li><input type="checkbox" id="check-all-groups" value="" /></li>               
                <li><label for="check-all-groups"><?php echo Yii::t('app', 'Select All');?></label></li>
            </ul>
            <?php
			}			
			else{
			?>
			<div style="padding-top:10px; width:94%; margin-top:10px;" class="notifications nt_red"><i><?php echo Yii::t('app','No groups found').'. '.CHtml::link(Yii::t('app','Create Now'), array('/sms/contactgroups'), array('target'=>'_blank'));?></i></div>
			<?php
			}
			?>
            </div>
			<?php
            foreach($groups as $key=>$group){
            ?>
            <div class="popup_contact">
            <ul>
                <li><input type="checkbox" value="<?php echo $group->id;?>" name="group[]" id="group-<?php echo $group->id;?>" class="select_group" /></li>
                <li><label for="group-<?php echo $group->id;?>"><?php echo $group->group_name.' ('.$group->totalcontacts.')';?></label></li>
            </ul>
            </div>
            <?php
            }
            ?>
            </td></tr>
            <?php
			if(count($groups)>0){
			?>
            <tr>
                <td colspan="2"><input type="button" class="popup_contact_but"  id="add-groups-to-send-box" value="<?php echo Yii::t('app', 'Add');?>" /></td>
            </tr>
            <?php
			}
			?>
        </table>
    </form>
</div>

<script>
$('#add-groups-to-send-box').click(function(e) {
    var sel_groups	= $('#select-group-form .select_group:checked');
	if(sel_groups.length==0){
		alert('<?php echo Yii::t('app', 'Select group');?>');
		return;
	}
	$( '#recipients_tag, #recipients' ).val('');
	$('#recipients_tagsinput span.tag').remove();
	
	
	var groups	= [];
	sel_groups.each(function(index, element) {
		groups.push($(element).val());
    });
	
	if(groups.length){
		$.ajax({
			url:'<?php echo Yii::app()->createUrl('/sms/contactgroups/contacts');?>',
			type:'POST',
			data:{groups:groups, "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
			cache:false,
			dataType:"json",
			success: function(response){
				var numbers	= response.numbers;
				if(numbers.length){
					$( '#recipients_tag, #recipients' ).val('');
					$('#recipients_tagsinput span.tag').remove();
					$.each(numbers, function(index, element) {
						var value	= "";
						if(typeof element.name !== "undefined")
							value		= element.name + ":";					
						value		+= element.number;
						$('#recipients').addTag(value);
					});
				}
			}
		});
	}
	
	$('#add_contacts').html( '' ).hide();
});

//checkbox
$('#check-all-groups').change(function(e) {
    var checked	= $(this).prop('checked');
	$('.popup_contact input[type=checkbox].select_group').prop({checked:checked});
});

$('.popup_contact .select_group').change(function(e) {
    var allchecks		= $('.popup_contact .select_group').length,
		selectedchecks	= $('.popup_contact .select_group:checked').length;
	if(allchecks===selectedchecks){
		$('#check-all-groups').prop({checked:true});
	}
	else{
		$('#check-all-groups').prop({checked:false});
	}
});

//pagination AJAX
$('#groups_pager li a').click(function(e) {
	if(!$(this).parent().hasClass('selected')){		
		var url	= $(this).attr('href');
		$.ajax({
			url:url,
			type:"GET",
			success: function(response){
				$('#add_groups').html(response);
			}
		});
	}
    return false;
});
</script>