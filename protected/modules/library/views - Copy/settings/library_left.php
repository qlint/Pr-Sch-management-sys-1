<div id="othleft-sidebar">
             <!--<div class="lsearch_bar">
             	<input type="text" value="Search" class="lsearch_bar_left" name="">
                <input type="button" class="sbut" name="">
                <div class="clear"></div>
  </div>-->       
                    <?php
				//echo Yii::app()->controller->id;
			
			function t($message, $category = 'cms', $params = array(), $source = null, $language = null) 
{
    return $message;
}

			$this->widget('zii.widgets.CMenu',array(
			'encodeLabel'=>false,
			'activateItems'=>true,
			'activeCssClass'=>'list_active',
			'items'=>array(
			array('label'=>''.'<h1>'.Yii::t('app','Manage Books').'</h1>'),  
					array('label'=>''.Yii::t('app','Search Books').'<span>'.Yii::t('app','Search all books').'</span>', 'url'=>array('/library/Book/booksearch') ,'linkOptions'=>array('class'=>'search-book_ico'),
                                   'active'=> (Yii::app()->controller->id=='book' and Yii::app()->controller->action->id=='booksearch')
					    ),
						array('label'=>''.Yii::t('app','List Books').'<span>'.Yii::t('app','All Book Details').'</span>', 'url'=>array('/library/Book/manage') ,'linkOptions'=>array('class'=>'list-book_ico'),
                                   'active'=> (Yii::app()->controller->id=='book'  and  (Yii::app()->controller->action->id=='manage' or Yii::app()->controller->action->id=='update'))
					    ),        
						
						array('label'=>''.Yii::t('app','Add Book').'<span>'.Yii::t('app','Add New Book Details').'</span>', 'url'=>array('/library/Book/create') ,'linkOptions'=>array('class'=>'add-book_ico'),
                                   'active'=> (Yii::app()->controller->id=='book' and (Yii::app()->controller->action->id=='create' or Yii::app()->controller->action->id=='view'))
					    ),              
					array('label'=>''.'<h1>'.Yii::t('app','Book Lend').'</h1>'), 
					array('label'=>''.Yii::t('app','Borrow Book').'<span>'.Yii::t('app','Issue Book Here').'</span>', 'url'=>array('/library/BorrowBook/create') ,'linkOptions'=>array('class'=>'borrow-book_ico'),
                                   'active'=> (Yii::app()->controller->id=='borrowBook' and (Yii::app()->controller->action->id=='create' or Yii::app()->controller->action->id=='view'))
								   
					    ),  
						array('label'=>''.Yii::t('app','Return Book').'<span>'.Yii::t('app','Lend Book Here').'</span>', 'url'=>array('/library/ReturnBook/manage') ,'linkOptions'=>array('class'=>'return-book_ico'),
                                   'active'=> (Yii::app()->controller->action->id=='returnbook' or  (Yii::app()->controller->id=='returnBook' and (Yii::app()->controller->action->id=='create' or Yii::app()->controller->action->id=='view' or Yii::app()->controller->action->id=='manage')))
					    ),  
						
						array('label'=>''.Yii::t('app','View Book Details').'<span>'.Yii::t('app','All Book Details').'</span>', 'url'=>array('/library/Book/bookdetails') ,'linkOptions'=>array('class'=>'view-book_ico'),
                                   'active'=> (Yii::app()->controller->id=='book' and (Yii::app()->controller->action->id=='booklist' or Yii::app()->controller->action->id=='bookdetails'))
					    ),  
						array('label'=>''.Yii::t('app','Due Dates').'<span>'.Yii::t('app','All Dues Here').'</span>', 'url'=>array('/library/Settings/settings') ,'linkOptions'=>array('class'=>'due-date_ico'),
                                   'active'=> (Yii::app()->controller->action->id=='settings')
					    ), 
						array('label'=>''.'<h1>'.Yii::t('app','Settings').'</h1>'),  
						array('label'=>''.Yii::t('app','Manage Book Category').'<span>'.Yii::t('app','Add New Book Category').'</span>', 'url'=>array('/library/Category/admin') ,'linkOptions'=>array('class'=>'managebook-ctgry_ico'),
                                   'active'=> (Yii::app()->controller->id=='category')
					    ), 
						array('label'=>''.Yii::t('app','View Student Details').'<span>'.Yii::t('app','All Student Details').'</span>', 'url'=>array('/library/BorrowBook/studentdetails') ,'linkOptions'=>array('class'=>'libry-viewstudent_ico '),
                                   'active'=> (Yii::app()->controller->id=='borrowBook' and Yii::app()->controller->action->id=='studentdetails')
					    ), 
						array('label'=>''.Yii::t('app','View Authors').' <span>'.Yii::t('app','All Author Details').'</span>', 'url'=>array('/library/Authors') ,'linkOptions'=>array('class'=>'lbrt-view-authr_ico'),
                                   'active'=> (Yii::app()->controller->id=='authors')
					    ), 
				),
			)); ?>
		
		</div>
        <script type="text/javascript">

	$(document).ready(function () {
            //Hide the second level menu
            $('#othleft-sidebar ul li ul').hide();            
            //Show the second level menu if an item inside it active
            $('li.list_active').parent("ul").show();
            
            $('#othleft-sidebar').children('ul').children('li').children('a').click(function () {                    
                
                 if($(this).parent().children('ul').length>0){                  
                    $(this).parent().children('ul').toggle();    
                 }
                 
            });
          
            
        });

    </script>