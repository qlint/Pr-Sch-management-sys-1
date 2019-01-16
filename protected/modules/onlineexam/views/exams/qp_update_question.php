<?php
$this->breadcrumbs=array(
	Yii::t('app','Online Examination')=>array('/onlineexam/dashboard'),
	Yii::t('app', 'Add Questions')
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">        
        	<?php $this->renderPartial('/default/admin_left');?>        
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app', 'Update Questions for Exam').' - '.$exam_model->name; ?></h1>
                <div class="formCon">
                    <div class="formConInner">
                        <?php
                            $multi_choice_limit=1;
                            if(isset($exam_model))
                            {
                                $multi_choice_limit=    $exam_model->choice_limit;
                            }
                            $this->renderPartial('qp_update_question_form', array('model'=>$model));?>                 
                    </div>
                </div>                
            </div>               
        </td>
    </tr>
</table>



<script>
$(document).ready(function () {
    $('#RadioButtonType input[type="hidden"][name="OnlineExamQuestions[type_answer]"]').attr('disabled', true);
    $('#RadioButtonAnswer input[type="hidden"][name="OnlineExamQuestions[choice_answer_id]"]').attr('disabled', true);
    $('#RadioButtonList input[type="hidden"][name="OnlineExamQuestions[question_type]"]').attr('disabled', true);
    
    $('#RadioButtonList input[name="OnlineExamQuestions[question_type]"]').change(function() 
    {
        $('.err_msg').hide().text('');
        setForm();
        var $val = $(this).val();
        switch($val){
            case '1' :
                $('#multi-row').slideDown('slow');
                $('#multi-row-answer').slideDown('slow');
                
                break;
            case '2' :
                $('#type-row').slideDown('slow');
                
                break;
            case '3' :
                $('#multi-line-row').slideDown('slow');
                break;
            case '4' :
                $('#multi-line-row').slideDown('slow');
                break;                
        }                
    });
});

$('#save_btn,#save_btns').click(function(e) 
{
    e.preventDefault();
    $('#save_btn,#save_btns').attr("disabled", true);
    $('.err_msg').hide().text('');
    var submit_type= $(this).attr('data');   
    save_new_question(submit_type);
});



function setForm()
{
    $('#multi-row').slideUp('slow');
    $('#multi-row-answer').slideUp('slow');
    $('#type-row').slideUp('slow');
    $('#multi-line-row').slideUp('slow');
}

function save_new_question(submit_type)
{
    var error_flag      =   0;
    var question	=   $('#OnlineExamQuestions_question').val();
    var question_type   =   $('#RadioButtonList input[type="radio"][name="OnlineExamQuestions[question_type]"]:checked').val();   
    var type_answer     =   $('#RadioButtonType input[type="radio"][name="OnlineExamQuestions[type_answer]"]:checked').val();    
    var text_answer     =   $('#OnlineExamQuestions_exam_answer').val();
    var mark            =   $('#OnlineExamQuestions_mark').val();
    var right_choice    =   $('#choices label.right');    

    
    if(question=="")
    {
        set_error('Question cannot be blank','question_error');
        error_flag=1;
    }
    if(typeof question_type == "undefined")
    {
        set_error('Question type cannot be blank','type_error');
        error_flag=1;
    }
    if(mark=="")
    {
        set_error('Mark cannot be blank','mark_error');
        error_flag=1;
    }
    if((isNaN(mark)) || !$.isNumeric(mark) || mark <= 0)
    {                 
        set_error('Enter valid mark','mark_error');
        error_flag=1;
    }
    
    if(parseFloat(mark) > 100)
    {                 
        set_error('Mark must be less than or equal to 100','mark_error');
        error_flag=1;
    }
        
    if(question_type)
    {
        if(question_type==1)
        {
            var $option_err=0;
            $("input[type='text'].multi-choice").each(function(index, element)
            { 
                var that            = this;
                $(that).removeClass('option_error');
                var option          = $(that).val();
                if(option==''  || ($.trim(option)).length==0)
                {      
                    error_flag = 1;
                    $option_err=1;
                    set_error('Options cannot be blank','options_error');
                    //$(this).addClass('option_error');
                }
            }); 
            
            if($option_err==0 && right_choice.length=='0')
            {
                error_flag=1;
                set_error('Select correct answer','choice_answer_error');
               
            } 
                
        }    
        if(question_type==2)
        {
            if(typeof type_answer=='undefined')
            {                
                set_error('Answer cannot be blank','type_answer_error');
                error_flag=1;
            }
        }
        if(question_type==3 || question_type==4)
        {
            if(text_answer=='')
            {
                set_error('Answer cannot be blank','text_answer_error');
                error_flag=1;
            }
        }
    }
    
    if(error_flag==1)
    {           
        $('#save_btn,#save_btns').attr("disabled", false);
        return false;
    }
    
    var choice_answer_id='';
    if(question_type==1 && right_choice.length > 0)
    {
        choice_answer_id    =  right_choice.attr('data'); 
    }
        
    var datas   = $('form#online-question-form').serialize();
        
    $.ajax({
		type:'POST',
		url:'<?php echo Yii::app()->createUrl('/onlineexam/exams/updateQuestion');?>',
		data:datas+"&submit_type="+submit_type+"&choice_answer_id="+choice_answer_id,
		cache:false,
		dataType:"json",
		success: function(response)
                {
                    if(response.status=="success")
                    {
                        location.href   =   response.data;
                    }
                    else{
                        alert(response.message);
                    }
		},
                error:function(){
                    alert("<?php echo Yii::t("app", "Some problem found while update questions")?>");
                }
	});
}


function set_error(msg,id)
{
    $('#'+id).text(msg).show();
    window.setTimeout(function(){ $('.err_msg').hide().text(''); }, 30000);
		
	
}

$('#add-choice').on('click', function (event) {
      event.preventDefault();

    if ($(this).hasClass('clicked') ){ 
        return false;
    }
    else{
        add_choices();    
        $(this).addClass('clicked');
    }
	                   
});

var add_choices	= function(){
	var ptrow	= parseInt($("#choices .choice-data").last().attr("data-row")) + 1;
        if(ptrow< "<?php echo $multi_choice_limit; ?>")
        {
	$.ajax({
		url:'<?php echo Yii::app()->createUrl("/onlineexam/exam/addChoice");?>',
		type:'GET',
		data:{ptrow:ptrow},
		dataType:"json",
		success: function(response){
			if(response.status=="success"){
				var data	= $(response.data);
				$("#choices").append(data);
				
				//scroll to new choice
				$('html,body').animate({
					scrollTop: data.offset().top
				}, 'slow');
				$('#add-choice').removeClass('clicked');
				setup_actions();
                                set_events();
			}
			else{
				alert("<?php echo Yii::t("app", "Can't add new choice.");?>");
			}
		}
	});
        }
        else
        {
            alert("<?php echo Yii::t('app',"Can't add new option"); ?>")
        }
};

var remove_choices	= function(that){
	var choice	= "";
	if($(that).closest('.choice-data').find('input.choice-value[type="text"]').length>0 && $(that).closest('.choice-data').find('input.choice-value[type="text"]').val()!=""){
		choice	= "`" + $(that).closest('.choice-data').find('input.choice-value[type="text"]').val() + "`";
	}
        
      
	if($("#choices .choice-data").length>1){
		if(confirm("<?php echo Yii::t("app", "Are you sure");?> ?"))
                {
			$(that).closest(".choice-data").remove();
                        $('#add-choice').removeClass('clicked');
                        setup_actions();
                        set_events();
                        re_arrange_questions_order();
		}
	}
	else{
		alert("<?php echo Yii::t("app", "Can't remove. You need to create atleast one option !!");?>");
	}	
};
var setup_actions	= function(){
	
	$(".remove-choice").unbind('click').click(function(e) {
		var that	= this;
		remove_choices(that);
    });
	//add choice
	$(".add-choice").unbind('click').click(function(e) {
		var that	= this;
		add_choices(that);
    });
	
	
	
};


function re_arrange_questions_order(){
	var start	= 0;
	$('#choices .choice-data').each(function(index, element) 
        {            
            $(this).attr('data-row',start);
            $(this).attr('id','choice-data-'+start);
            $(this).find('input.choice-value[type="text"]').attr('id','OnlineExamQuestions_choice_answer_'+start);
            $(this).find('input.choice-value[type="text"]').attr('name','OnlineExamQuestions[choice_answer]['+start+']');
            var chr = String.fromCharCode(65 + start);             
            $(this).closest("div").find(".main").text(chr);
           
		start++;
        });
}

function set_events()
{
    $('.choice-data .Question-block .number').unbind('click');
    $(".choice-data .Question-block .number").on('click',(function()
    {		     
        $(this).parent().parent().parent().parent().find('.number').removeClass('right');
        $(this).addClass('right');
          		
    }));
}

setup_actions();
set_events();
</script>