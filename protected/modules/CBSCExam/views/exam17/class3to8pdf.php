
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css">

table{
		border-collapse:collapse;
}

table th{
	 font-size:12px;	
}
.table-grade-box th{
	 padding:8px; 	
}
.table-grade-box td{
	 padding:8px; 	
}
.table-head th{
	 padding:8px;
}
.table-box-mrgn{
	margin-top:8px;	
}
.vertcl-aln{ vertical-align:top;}
.tabl-thr-rght h4 span{
	margin-left:15px;	
}
.tabl-thr-rght h4{
	font-size:10px;	
}
.tabl-thr-rght p{
	margin:0px;
}
.table-grade-box p, h4{
	 margin:0px;
}
.table-grade-box p{
	   font-weight:400;
	    margin:5px 0px;
}

.main-tble-hd h2{
	font-size:30px;
	font-size: 28px;
	margin: 0px;	
}
.table-head{ border-bottom:0px;}

.report-hed td{ text-align:center; font-size:10px;}
.report-stu-dtls-table table{
	border-collapse:collapse;

	   	
}
.report-stu-dtls-table .inner-table{
	 border:1px solid #000;	
}
.report-stu-dtls-table .inner-table td{

	 padding:8px;
	 font-size:11px;	
}
.report-stu-dtls-table .br-td{
	 border-right:1px solid #000;

}
.tablegrade-spc td{
	 padding:4px;
}
</style>

<?php
$students = Students::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<!-- Header -->
	
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first " width="100">
                           <?php $logo=Logo::model()->findAll();?>
                            <?php
                            if($logo!=NULL)
                            {
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone:')." ".$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
    <br />
    <!-- End Header -->
    
    <table class="report-hed" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tbody>
    <tr>
    <td><h2>REPORT CARD - 2017-2018</h2></td>
    </tr>
    </tbody>
    </table>
    <table class="table" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><th height="10"></th></tr></table>   
    
<div class="report-stu-dtls-table">

              <table cellspacing="0" cellpadding="0" border="0" width="100%" class="inner-table">
            	<tbody>
                <tr>
                <td width="100">Student Name:</td>
                <td width="2">:</td>
                <td class="br-td">Name </td>
                <td width="100">Date Of Birth</td>
                <td width="2">:</td>
                <td>date_of_birth</td>
                </tr>
                <tr>
                <td width="100">Admission No</td>
                <td width="2">:</td>
                <td class="br-td">admission_no</td>
                <td width="100">Course</td>
                <td width="2">:</td>
                <td>Course</td>
                </tr>                
 <tr>
                <td width="100">batch</td>
                <td width="2">:</td>
                <td class="br-td">batch </td>
                <td width="100">Roll No</td>
                <td width="2">:</td>
                <td>Roll No</td>
                </tr> 

              
              
              
              </tbody>
              </table>

        </div>
    
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><th height="10"></th></tr></table>              
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
<tr>
<td>
<table class="table-grade-box" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
    <tr><th class="main-tble-hd" colspan="8" width="720">SCHOLASTIC AREA</th></tr>
    <tr>
        <th>Sl no</th>
        <th>Subjects</th>
        <th>Periodic Test</th>
        <th>Note Book</th>
        <th>Subject Enrichment</th>
        <th>Third Term Exam</th>
        <th>Mark Obtained</th>   
        <th>Grade</th>               
    </tr>
</thead> 
<tbody>
	<tr>
    	<td>1</td>
    	<td>English</td>
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>                
    </tr>
	<tr>
    	<td>2</td>
    	<td>Hindi</td>
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>                
    </tr> 
	<tr>
    	<td>3</td>
    	<td>Malayalam</td>
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>                
    </tr> 
	<tr>
    	<td>4</td>
    	<td>Mathematics</td>
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>                
    </tr>
	<tr>
    	<td>5</td>
    	<td>EVS</td>
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>        
    	<td>--</td>
    	<td>--</td>                
    </tr>              
</tbody>
</table>
    </td>
</tr>


<tr>
	<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="348" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
    <tr>
        <th align="left" width="100%">General Knowledge</th>
        <td width="100%">-------</td>
    </tr> 
     <tr>   
        <th align="left" width="100%">Moral Science</th>
        <td width="100%">-------</td>
    </tr> 
     <tr>         
        <th align="left" width="100%">Computer Science</th>
        <td width="100%">-------</td>
    </tr> 
     <tr>         
        <th align="left" width="100%">Discipling</th>
        <td width="100%">-------</td>        
    </tr>
</thead> 

</table>
</td>
<td width="10"></td>

<td width="348" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
    <tr>
        <th  colspan="2">CO-SCHOLASTIC AREA</th>
    </tr> 
     <tr>   
        <th align="left" width="100%">Work Education</th>
        <td width="100%">-------</td>
    </tr> 
     <tr>         
        <th align="left" width="100%">Art Education</th>
        <td width="100%">-------</td>
    </tr> 
     <tr>         
        <th align="left" width="100%">Health & Physical Education</th>
        <td width="100%">-------</td>        
    </tr>
</thead> 

</table>
</td>

</tr>
</table>
    </td>
</tr>






<tr>
<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="345" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
        <tr>
        <th  colspan="2" align="left" width="345">Attendance</th>
    </tr> 
     
</thead> 
</table>
</td>
<td width="10"></td>

<td width="350" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn tabl-thr-rght" width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
 
     <tr>   
        <th align="left" width="180"><h4>Height(cm)<span>-----------</span></h4></th>
        <th align="left" width="180"><h4>Weight(kg)<span>-----------</span></h4></th>
    </tr> 
</thead> 

</table>
</td>

</tr>
</table>
    </td>
    
</tr>
<!-----------------table-footer---------------->
<tr>
<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td height="20px"></td></tr></table>
</td>
</tr>

<tr>
<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<table class="table" width="100%" cellpadding="0" cellspacing="0" border="0">
<thead>
	<tr>
    	<th align="center"><h4>Grading scal for scholastic area</h4>
        	<h4>[Grade are awarded on a 8-point scale as follows]</h4>
        	
        </th>
    </tr>
</thead>
</table>
</td>
</tr>

<tr>
<td width="280" class="">
<table class="table-grade-box table-box-mrgn tablegrade-spc" width="100%" cellpadding="0" cellspacing="0" border="1">
	<tbody>
    <tr>
        <td align="center" width="100%">MARKS RANGE</td>
        <td width="100%">GRADE</td>
    </tr> 
     <tr>   
        <td align="center" width="100%">91-100</td>
        <td width="100%" align="center">A1</td>
    </tr> 
     <tr>         
        <td  align="center" width="100%">81-90</td>
        <td width="100%" align="center">A2</td>
    </tr> 
     <tr>         
        <td align="center" width="100%">71-80</td>
        <td width="100%" align="center">B1</td>        
    </tr>
    <tr>
        <td align="center" width="100%">61-70</td>
        <td width="100%" align="center">B2</td>
    </tr> 
     <tr>   
        <td align="center" width="100%">51-60</td>
        <td width="100%" align="center">C1</td>
    </tr> 
     <tr>         
        <td  align="center" width="100%">41-50</td>
        <td width="100%" align="center">C2</td>
    </tr> 
     <tr>         
        <td align="center" width="100%">33-40</td>
        <td width="100%" align="center">D</td>        
    </tr>    
     <tr>         
        <td align="center" width="100%">32 & Below</td>
        <td width="100%" align="center">E(Needs Improvement)</td>        
    </tr>    
    
    
    
    
</tbody> 

</table>
</td>
<td width="10"></td>
<td width="280" class="vertcl-aln">
<table class="table-grade-box table-box-mrgn tablegrade-spc " width="100%" cellpadding="0" cellspacing="0" border="1">
	<thead>
    <tr>
        <th  colspan="2">
        <h4>Grading scal for co-scholastic and Discipline CI.III-VII</h4>
        <p>[On a 3 point(AC) Gradinf scale]
        </th>
    </tr> 
     <tr>   
        <th align="center" width="100%">A</th>
        <td width="100%" align="center">Outstanding</td>
    </tr> 
     <tr>         
        <th align="center" width="100%">B</th>
        <td width="100%" align="center">Very good</td>
    </tr> 
     <tr>         
        <th align="center" width="100%">B</th>
        <td width="100%" align="center">Fair</td>        
    </tr>
    <tr>
        <th  colspan="2" height="50"></th>
    </tr> 
</thead> 

</table>
</td>

</tr>
</table>
    </td>
</tr>

 
    </tbody>
</table> 
    
