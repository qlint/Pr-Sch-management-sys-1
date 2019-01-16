<?php
$configuration  = Configurations::model()->findByPk(5);
$sub_total		= 0;
$discount_total	= 0;
$tax_total		= 0;
foreach($particulars as $key=>$particular){
    $this->renderPartial('_particular', array('index'=>$key + 1, 'count'=>$key, 'configuration'=>$configuration, 'particular'=>$particular));
}
?>

<tr>
    <td colspan="7" align="center">
        <a href="javascript:void(0);" id="add-another-particular"><?php echo Yii::t('app', '+ Add another particular');?></a>
    </td>
</tr>

<script type="text/javascript">
$('#add-another-particular').unbind('click').click(function(){
    var index   = ($('.invoice-particular-edit-bx').last().length>0)?$('.invoice-particular-edit-bx').last().attr('data-row-index'):0;
    $.ajax({
        url:'<?php echo Yii::app()->createUrl('/fees/invoices/addparticular');?>',
        type:'POST',
        data:{count:$('.invoice-particular-edit-bx').length, index:index, "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
        dataType:'json',
        success:function(response){
            if(response.status=="success"){
                $(response.data).insertBefore($('#add-another-particular').closest('tr'));
            }
            else{
                alert("<?php echo Yii::t("app", "Some problem found while trying to add a particular")?>");
            }
        },
        error:function(){
            alert("<?php echo Yii::t("app", "Some problem found while trying to add a particular")?>");
        }
    });
});
</script>