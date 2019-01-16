<table> 
    <tr>
        <td>
            <?php echo CHtml::activeTextField($access, "[".$ptrow."]admission_no[".$acrow."]", array('style'=>'width:364px !important', 'placeholder'=>Yii::t('app', 'Admission Numbers seperated by commas')));?>
        </td>
    </tr>
</table>