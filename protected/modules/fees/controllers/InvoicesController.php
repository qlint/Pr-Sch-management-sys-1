 <?php
class InvoicesController extends RController
{
    public function filters()
    {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function actionIndex()
    {
        if (Yii::app()->user->year) {
            $year = Yii::app()->user->year;
        } else {
            $current_academic_yr = Configurations::model()->findByAttributes(array('id' => 35));
            $year = $current_academic_yr->config_value;
        }
        //user config
        $settings = UserSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));

        //all invoices
        $criteria = new CDbCriteria;
        $criteria->condition = 'academic_year_id=:yr';
        $criteria->params[':yr'] = $year;
        $total_invoices = FeeInvoices::model()->count($criteria);

        //filtered invoices
        $search = new FeeInvoices;
        $search->uid = "";
        $search->is_paid = "";

        $page_size = 20;
        $criteria = new CDbCriteria;
        $criteria->condition = 'academic_year_id=:yr';
        $criteria->params[':yr'] = $year;
        //conditions

        //fee id
        if (isset($_REQUEST['FeeInvoices']['fee_id']) and $_REQUEST['FeeInvoices']['fee_id'] != null) {

            $search->fee_id = $_REQUEST['FeeInvoices']['fee_id'];
            $criteria->compare('t.fee_id', $search->fee_id);
            //var_dump($criteria);exit;

        }

        //invoice id
        if (isset($_REQUEST['FeeInvoices']['id']) and $_REQUEST['FeeInvoices']['id'] != null) {
            $search->id = $_REQUEST['FeeInvoices']['id'];
            $criteria->compare('t.id', $search->id);
        }

        //course
        if (isset($_REQUEST['FeeInvoices']['course']) and $_REQUEST['FeeInvoices']['course'] != null) {
            $search->course = $_REQUEST['FeeInvoices']['course'];
        }

        //batch
        if (isset($_REQUEST['FeeInvoices']['batch']) and $_REQUEST['FeeInvoices']['batch'] != null) {

            $search->batch = $_REQUEST['FeeInvoices']['batch'];
            if ($criteria->join != "") {
                $criteria->join .= " JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
            } else {
                $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
            }

            $criteria->compare('`bs`.`batch_id`', $search->batch);
            $criteria->compare('`bs`.`status`', 1);
            $criteria->compare('`s`.`is_active`', 1);
            $criteria->compare('`s`.`is_deleted`', 0);
        } else if (isset($_REQUEST['FeeInvoices']['course']) and $_REQUEST['FeeInvoices']['course'] != null) {
            $search->course = $_REQUEST['FeeInvoices']['course'];
            $search->batch = $_REQUEST['FeeInvoices']['batch'];
            if ($criteria->join != "") {
                $criteria->join .= " JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
            } else {
                $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
            }

            $criteria->compare('`b`.`course_id`', $search->course);
            $criteria->compare('`bs`.`status`', 1);
            $criteria->compare('`s`.`is_active`', 1);
            $criteria->compare('`s`.`is_deleted`', 0);
        }
        //invoice recipient
        if (isset($_REQUEST['FeeInvoices']['uid']) and $_REQUEST['FeeInvoices']['uid'] != null) {
            $search->uid = $_REQUEST['FeeInvoices']['uid'];
            if ($criteria->join == "") {
                $criteria->join = "JOIN `students` `s` ON `s`.`id`=`t`.`table_id`";
            }

            if ((substr_count($_REQUEST['FeeInvoices']['uid'], ' ')) == 0) {
                if ($criteria->condition != "") {
                    $criteria->condition .= " AND ";
                }

                $criteria->condition .= '(s.first_name LIKE :name or s.last_name LIKE :name or s.middle_name LIKE :name)';
                $criteria->params[':name'] = $_REQUEST['FeeInvoices']['uid'] . '%';
            } else if ((substr_count($_REQUEST['FeeInvoices']['uid'], ' ')) >= 1) {
                $name = explode(" ", $_REQUEST['FeeInvoices']['uid']);
                if ($criteria->condition != "") {
                    $criteria->condition .= " AND ";
                }

                $criteria->condition .= '(s.first_name LIKE :name or s.last_name LIKE :name or s.middle_name LIKE :name) and (s.first_name LIKE :name1 or s.last_name LIKE :name1 or s.middle_name LIKE :name1)';
                $criteria->params[':name'] = $name[0] . '%';
                $criteria->params[':name1'] = $name[1] . '%';
            }
        }

        //invoice status
        if (isset($_REQUEST['FeeInvoices']['is_paid']) and $_REQUEST['FeeInvoices']['is_paid'] != null) {
            $search->is_paid = $_REQUEST['FeeInvoices']['is_paid'];
            if ($search->is_paid == -1) {
                $criteria->compare('t.is_canceled', 1);
            } else {
                $criteria->compare('t.is_paid', $search->is_paid);
                $criteria->compare('t.is_canceled', "=0");
            }
        }

        //invoice date
        if (isset($_REQUEST['FeeInvoices']['created_at']) and $_REQUEST['FeeInvoices']['created_at'] != null) {
            $search->created_at = date("Y-m-d", strtotime($_REQUEST['FeeInvoices']['created_at']));
            $criteria->compare('STR_TO_DATE(t.created_at, "%Y-%m-%d")', $search->created_at);
            if ($settings != null and $settings->displaydate != null) {
                $dateformat = $settings->displaydate;
            } else {
                $dateformat = 'd M Y';
            }

            $search->created_at = date($dateformat, strtotime($search->created_at));
        }
        $criteria->order = "`t`.`id` DESC";
        $criteria->distinct = true;
        $total = FeeInvoices::model()->count($criteria);
        $pages = new CPagination($total);
        $pages->setPageSize($page_size);
        $pages->applyLimit($criteria);
        $invoices = FeeInvoices::model()->findAll($criteria);

        //paid invoices
        $criteria = new CDbCriteria;
        $criteria->condition = 'academic_year_id=:yr';
        $criteria->params[':yr'] = $year;
        $criteria->compare("t.is_paid", 1);
        $paid_invoices = FeeInvoices::model()->findAll($criteria);

        //canceled
        $criteria = new CDbCriteria;
        $criteria->condition = 'academic_year_id=:yr';
        $criteria->params[':yr'] = $year;
        $criteria->compare("is_canceled", 1);
        $canceled = FeeInvoices::model()->count($criteria);

        $this->render('index', array('search' => $search, 'invoices' => $invoices, 'canceled' => $canceled, 'pages' => $pages, 'item_count' => $total, 'total_invoices' => $total_invoices, 'page_size' => $page_size, 'paid_invoices' => $paid_invoices));
    }

    public function actionGenerate($id)
    {
        $category = FeeCategories::model()->findByPk($id);
        if ($category != null) {
            if ($category->invoice_generated == 0) {
                //fetch particulars
                $criteria = new CDbCriteria;
                $criteria->compare("fee_id", $category->id);
                $particulars = FeeParticulars::model()->findAll($criteria);

                $invoices = array();

                foreach ($particulars as $particular) {
                    //accesses
                    $criteria = new CDbCriteria;
                    $criteria->compare("particular_id", $particular->id);
                    $accesses = FeeParticularAccess::model()->findAll($criteria);
                    foreach ($accesses as $access) {

                        switch ($access->access_type) {
                            case 1:
                                $students = array();
                                //course , batch , student category
                                if ($access->student_category_id != null) {
                                    if ($access->course != null) {
                                        if ($access->batch != null) {
                                            // $priority - 2
                                            $priority = 2;
                                            $criteria = new CDbCriteria;
                                            $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";
                                            $criteria->compare("`t`.`student_category_id`", $access->student_category_id);
                                            $criteria->compare("`bs`.`batch_id`", $access->batch);
                                            $criteria->compare("`bs`.`status`", 1);
                                        } else {
                                            // all batches in $access->course
                                            // $priority - 3
                                            $priority = 3;
                                            $criteria = new CDbCriteria;
                                            $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id`";
                                            $criteria->compare("`t`.`student_category_id`", $access->student_category_id);
                                            $criteria->compare("`b`.`course_id`", $access->course);
                                            $criteria->compare("`bs`.`status`", 1);
                                        }
                                    } else {
                                        // all batches in current academic year
                                        // $priority - 4
                                        $priority = 4;
                                        $criteria = new CDbCriteria;
                                        $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id` JOIN `courses` `c` ON `c`.`id`=`b`.`course_id`";
                                        $criteria->compare("`t`.`student_category_id`", $access->student_category_id);
                                        $criteria->compare("`c`.`academic_yr_id`", $access->course);
                                        $criteria->compare("`bs`.`status`", 1);
                                    }
                                } else {
                                    // all categories
                                    if ($access->course != null) {
                                        if ($access->batch != null) {
                                            // $priority - 5
                                            $priority = 5;
                                            $criteria = new CDbCriteria;
                                            $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";
                                            $criteria->compare("`bs`.`batch_id`", $access->batch);
                                            $criteria->compare("`bs`.`status`", 1);
                                        } else {
                                            // all batches in $access->course
                                            // $priority - 6
                                            $priority = 6;
                                            $criteria = new CDbCriteria;
                                            $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id`";
                                            $criteria->compare("`b`.`course_id`", $access->course);
                                            $criteria->compare("`bs`.`status`", 1);
                                        }
                                    } else {
                                        // all batches in current academic year
                                        // $priority - 7
                                        $priority = 7;
                                        $criteria = new CDbCriteria;
                                        $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id` JOIN `courses` `c` ON `c`.`id`=`b`.`course_id`";
                                        $criteria->compare("`c`.`academic_yr_id`", $access->course);
                                        $criteria->compare("`bs`.`status`", 1);
                                    }
                                }

                                $criteria->compare("`t`.`is_active`", 1);
                                $criteria->compare("`t`.`is_deleted`", 0);
                                //fetch students using the generated $criteria
                                $students = Students::model()->findAll($criteria);                                
                                //if students are there in current access group
                                foreach ($students as $student) {
                                    if ($student != null) {
                                        if ((!isset($invoices[$student->id][$particular->id]['priority'])) or (isset($invoices[$student->id][$particular->id]['priority']) and $invoices[$student->id][$particular->id]['priority'] > $priority)) {
                                            $invoices[$student->id]['uid'] = $student->uid;
                                            $invoices[$student->id]['user_type'] = 1; //student
                                            $invoices[$student->id][$particular->id] = array(
                                                'access_id' => $access->id,
                                                'priority' => $priority,
                                            );
                                        }
                                    }
                                }
                                break;

                            case 2:
                                // check by admission number
                                // $priority - 1
                                $priority = 1;
                                if ($access->admission_no != null and $access->admission_no != 0) {
                                    $student = Students::model()->findByAttributes(array(
                                        'admission_no' => $access->admission_no,
                                        'is_active' => 1,
                                        'is_deleted' => 0,
                                    ));

                                    if ($student != null) {
                                        $invoices[$student->id]['uid'] = $student->uid;
                                        $invoices[$student->id]['user_type'] = 1; //student
                                        $invoices[$student->id][$particular->id] = array(
                                            'access_id' => $access->id,
                                            'priority' => $priority,
                                        );
                                    }
                                }
                                break;
                        }
                    }
                }

                //check if there is students in the current category
                if (count($invoices) > 0) {
                    //subscriptions
                    $subscriptions = FeeSubscriptions::model()->findAllByAttributes(array('fee_id' => $category->id));
                    //generate invoices here
                    foreach ($invoices as $id => $invoice) {
                        //repeat each invoice for each student
                        foreach ($subscriptions as $subscription) {
                            //repeat for due dates
                            $feeinvoice = new FeeInvoices;
                            $feeinvoice->academic_year_id = $category->academic_year_id;
                            $feeinvoice->uid = $invoice['uid'];
                            $feeinvoice->user_type = $invoice['user_type'];
                            $feeinvoice->table_id = $id;
                            $feeinvoice->fee_id = $category->id;
                            $feeinvoice->subscription_id = $subscription->id;
                            $feeinvoice->name = $category->name;
                            $feeinvoice->description = $category->description;
                            $feeinvoice->subscription_type = $category->subscription_type;
                            $feeinvoice->start_date = $category->start_date;
                            $feeinvoice->end_date = $category->end_date;
                            $feeinvoice->due_date = $subscription->due_date;
                            $feeinvoice->created_at = date("Y-m-d h:i:s");
                            $feeinvoice->created_by = Yii::app()->user->id;

                            if ($feeinvoice->save()) {
                                $total_amount = 0;
                                //save particulars for this invoice
                                foreach ($invoice as $particular_id => $access) {
                                    if (isset($access['access_id'])) {
                                        $particular = FeeParticulars::model()->findByPk($particular_id);
                                        $particular_access = FeeParticularAccess::model()->findByPk($access['access_id']);
                                        if ($particular != null and $particular_access != null) {
                                            $invoiceparticular = new FeeInvoiceParticulars;
                                            $invoiceparticular->invoice_id = $feeinvoice->id;
                                            $invoiceparticular->name = $particular->name;
                                            $invoiceparticular->description = $particular->description;
                                            $invoiceparticular->tax = $particular->tax; //percentage
                                            $invoiceparticular->discount_type = $particular->discount_type;

                                            //generate discount and amount based on "amount_divided" field
                                            if ($category->amount_divided == 1) {
                                                if ($particular->discount_type == 2) {
                                                    // amount
                                                    $invoiceparticular->discount_value = number_format(($particular->discount_value / count($subscriptions)), 2);
                                                } else {
                                                    $invoiceparticular->discount_value = $particular->discount_value;
                                                }
                                                $invoiceparticular->amount = number_format(($particular_access->amount / count($subscriptions)), 2);
                                            } else {
                                                $invoiceparticular->discount_value = $particular->discount_value;
                                                $invoiceparticular->amount = $particular_access->amount;
                                            }

                                            if ($invoiceparticular->save()) {
                                                $amount = $invoiceparticular->amount;
                                                //apply discount
                                                if ($invoiceparticular->discount_type == 1) {
                                                    //percentage
                                                    $amount = $invoiceparticular->amount - (($invoiceparticular->amount * $invoiceparticular->discount_value) / 100);
                                                } else if ($invoiceparticular->discount_type == 2) {
                                                    //amount
                                                    $amount = $invoiceparticular->amount - $invoiceparticular->discount_value;
                                                }

                                                //apply tax
                                                if ($invoiceparticular->tax != 0) {
                                                    $tax = FeeTaxes::model()->findByPk($invoiceparticular->tax);
                                                    if ($tax != null) {
                                                        $amount = $amount + (($amount * $tax->value) / 100);
                                                    }
                                                }

                                                $total_amount += $amount;
                                            }
                                        }
                                    }
                                }
                                //save total amount
                                $feeinvoice->total_amount = $total_amount;
                                if ($feeinvoice->save()) {
                                    //send Email and SMS
                                    Yii::app()->getModule('fees')->sendInvoiceAsEmail($feeinvoice->id);
                                    Yii::app()->getModule('fees')->sendInvoiceAsSms($feeinvoice->id);
                                    Yii::app()->getModule('fees')->sendInvoiceAsMessage($feeinvoice->id);
                                }
                            }
                        }
                    }

                    //change status of invoice generation
                    $category->invoice_generated = 1;
                    $category->save();

                    //redirect to invoices page
                    $this->redirect(array("/fees/invoices", "FeeInvoices" => array("fee_id" => $category->id)));
                } else {
                    //set up a flash message
                    Yii::app()->user->setFlash('error', Yii::t("app", "Can't generate Invoices. There is no students found in selected category !"));
                    //redirect after procesing
                    $this->redirect(array("/fees/dashboard"));
                }
            } else {
                $this->redirect(array("/fees/dashboard"));
            }
        } else {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }
    }
    public function actionInvoicepdf()
    {
        $filename = Yii::t('app', ' Invoice') . '.pdf';
        Yii::app()->osPdf->generate("application.modules.fees.views.invoices.invoicepdf", $filename, array());
    }
    public function actionExportcsv()
    {
        //file names
        $filename_uid = null;
        $filename_id = null;
        $filename_course = null;
        $filename_batch = null;
        $filename_status = null;
        $filename_date = null;

        //fetch datas
        $criteria = new CDbCriteria;
        //conditions
        //invoice recipient
        /*if(isset($_REQUEST['FeeInvoices']['uid']) and $_REQUEST['FeeInvoices']['uid']!=NULL){
        $search->uid         = $_REQUEST['FeeInvoices']['uid'];
        $criteria->join        = "JOIN `profiles` `p` ON `p`.`user_id`=`t`.`uid`";
        if((substr_count( $search->uid,' '))==0){
        $criteria->condition        = '(p.firstname LIKE :name or p.lastname LIKE :name)';
        $criteria->params[':name']     = $search->uid.'%';
        }
        else if((substr_count( $_REQUEST['name'],' '))<=1){
        $name                        = explode(" ", $search->uid);
        $criteria->condition        = '(p.firstname LIKE :name or p.lastname LIKE :name)';
        $criteria->params[':name']     = $name[0];
        $criteria->condition        = $criteria->condition.' and '.'(p.firstname LIKE :name1 or p.lastname)';
        $criteria->params[':name1'] = $name[1];
        }

        $filename_uid    = $search->uid;
        }*/

        //fee id
        if (isset($_REQUEST['FeeInvoices']['fee_id']) and $_REQUEST['FeeInvoices']['fee_id'] != null) {
            $search->fee_id = $_REQUEST['FeeInvoices']['fee_id'];
            $criteria->compare('t.fee_id', $search->fee_id);
            $filename_fee_id = $search->fee_id;
        }

        //invoice id
        if (isset($_REQUEST['FeeInvoices']['id']) and $_REQUEST['FeeInvoices']['id'] != null) {
            $search->id = $_REQUEST['FeeInvoices']['id'];
            $criteria->compare('t.id', $search->id);
            $filename_id = $search->id;
        }

        //course
        if (isset($_REQUEST['FeeInvoices']['course']) and $_REQUEST['FeeInvoices']['course'] != null) {
            $search->course = $_REQUEST['FeeInvoices']['course'];
            $filename_course = $search->course;
        }

        //batch
        if (isset($_REQUEST['FeeInvoices']['batch']) and $_REQUEST['FeeInvoices']['batch'] != null) {
            $search->batch = $_REQUEST['FeeInvoices']['batch'];
            if ($criteria->join != "") {
                $criteria->join .= " JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
            } else {
                $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
            }

            $criteria->compare('`bs`.`batch_id`', $search->batch);
            $criteria->compare('`bs`.`status`', 1);
            $criteria->compare('`s`.`is_active`', 1);
            $criteria->compare('`s`.`is_deleted`', 0);
            $filename_batch = $search->batch;
        } else if (isset($_REQUEST['FeeInvoices']['course']) and $_REQUEST['FeeInvoices']['course'] != null) {
            $search->course = $_REQUEST['FeeInvoices']['course'];
            $search->batch = $_REQUEST['FeeInvoices']['batch'];
            if ($criteria->join != "") {
                $criteria->join .= " JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
            } else {
                $criteria->join = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`table_id` JOIN `batches` `b` ON `b`.`id`=`bs`.`batch_id` JOIN `students` `s` ON `s`.`id`=`bs`.`student_id`";
            }

            $criteria->compare('`b`.`course_id`', $search->course);
            $criteria->compare('`bs`.`status`', 1);
            $criteria->compare('`s`.`is_active`', 1);
            $criteria->compare('`s`.`is_deleted`', 0);
        }

        if (isset($_REQUEST['FeeInvoices']['uid']) and $_REQUEST['FeeInvoices']['uid'] != null) {
            $search->uid = $_REQUEST['FeeInvoices']['uid'];
            if ($criteria->join == "") {
                $criteria->join = "JOIN `students` `s` ON `s`.`id`=`t`.`table_id`";
            }

            if ((substr_count($_REQUEST['FeeInvoices']['uid'], ' ')) == 0) {
                if ($criteria->condition != "") {
                    $criteria->condition .= " AND ";
                }

                $criteria->condition .= '(s.first_name LIKE :name or s.last_name LIKE :name or s.middle_name LIKE :name)';
                $criteria->params[':name'] = $_REQUEST['FeeInvoices']['uid'] . '%';
            } else if ((substr_count($_REQUEST['FeeInvoices']['uid'], ' ')) >= 1) {
                $name = explode(" ", $_REQUEST['FeeInvoices']['uid']);
                if ($criteria->condition != "") {
                    $criteria->condition .= " AND ";
                }

                $criteria->condition .= '(s.first_name LIKE :name or s.last_name LIKE :name or s.middle_name LIKE :name) and (s.first_name LIKE :name1 or s.last_name LIKE :name1 or s.middle_name LIKE :name1)';
                $criteria->params[':name'] = $name[0] . '%';
                $criteria->params[':name1'] = $name[1] . '%';
            }
            $filename_uid = $search->uid;
        }

        //invoice status
        if (isset($_REQUEST['FeeInvoices']['is_paid']) and $_REQUEST['FeeInvoices']['is_paid'] != null) {
            $search->is_paid = $_REQUEST['FeeInvoices']['is_paid'];
            $criteria->compare('t.is_paid', $search->is_paid);
            $filename_status = $search->is_paid;
        }

        //invoice date
        if (isset($_REQUEST['FeeInvoices']['created_at']) and $_REQUEST['FeeInvoices']['created_at'] != null) {
            $search->created_at = date("Y-m-d", strtotime($_REQUEST['FeeInvoices']['created_at']));
            $criteria->compare('STR_TO_DATE(t.created_at, "%Y-%m-%d")', $search->created_at);
            if ($settings != null and $settings->displaydate != null) {
                $dateformat = $settings->displaydate;

            } else {
                $dateformat = 'd M Y';
            }

            $search->created_at = date($dateformat, strtotime($search->created_at));
            $filename_date = $search->created_at;
        }

        $invoices = FeeInvoices::model()->findAll($criteria);
        $filename = DocumentUploads::model()->generateSalt();
        //$filename    = 'invoice';
        if ($filename_fee_id != null) {
            $fee_category = FeeCategories::model()->findByPk($filename_fee_id);
            if ($fee_category != null) {
                $filename .= '_fee-' . $fee_category->name;
            }
        }

        if ($filename_id != null) {
            $filename .= '_id-' . $filename_id;
        }

        if ($filename_uid != null) {
            $filename .= '_recipient-' . $filename_uid;
        }

        if ($filename_course != null) {
            $sel_course = Courses::model()->findByPk($filename_course);
            if ($sel_course != null) {
                $filename .= '_course-' . $sel_course->course_name;
            }
        }

        if ($filename_batch != null) {
            $sel_batch = Batches::model()->findByPk($filename_batch);
            if ($sel_batch != null) {
                $filename .= '_batch-' . $sel_batch->name;
            }
        }

        if ($filename_status != null) {
            $filename .= '_status-' . (($filename_status == 1) ? 'paid' : 'unpaid');
        }

        if ($filename_date != null) {
            $filename .= '_date-' . $filename_date;
        }

        $filename .= ".csv";
        $filename = preg_replace('/\s+/', '-', strtolower($filename));

        $this->download_send_headers($filename);
        echo $this->export2csv($invoices);
        die();
    }

    public function actionView($id)
    {
        $invoice = FeeInvoices::model()->findByPk($id);
        if ($invoice) {
            $criteria = new CDbCriteria;
            $criteria->compare("invoice_id", $id);
            $particulars = FeeInvoiceParticulars::model()->findAll($criteria);
            $transaction = new FeeTransactions;
            $transaction->invoice_id = $invoice->id;
            $criteria = new CDbCriteria;
            $criteria->compare('invoice_id', $transaction->invoice_id);
            $alltransactions = FeeTransactions::model()->findAll($criteria);
            $this->render('view', array('invoice' => $invoice, 'particulars' => $particulars, 'transaction' => $transaction, 'alltransactions' => $alltransactions));
        } else {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionEdit()
    {
        $response = array('status' => 'error');
        $errors = array();
        $has_error = false;
        $existingids = array();
        if (Yii::app()->request->isAjaxRequest) {
            $id = $_POST['invoice_id'];
            if (isset($_POST['FeeInvoiceParticulars']['name'])) {
                //save
                foreach ($_POST['FeeInvoiceParticulars']['name'] as $i => $name) {
                    $particular_id = (isset($_POST['FeeInvoiceParticulars']['id'][$i]) and $_POST['FeeInvoiceParticulars']['id'][$i] != null) ? $_POST['FeeInvoiceParticulars']['id'][$i] : null;
                    $particular = null;
                    if ($particular_id != null) {
                        $existingids[] = $particular_id;
                        $particular = FeeInvoiceParticulars::model()->findByPk($particular_id);
                        if ($particular != null) {
                            $particular->name = $name;
                            $particular->description = $_POST['FeeInvoiceParticulars']['description'][$i];
                            $particular->amount = $_POST['FeeInvoiceParticulars']['amount'][$i];
                            $particular->tax = $_POST['FeeInvoiceParticulars']['tax'][$i];
                            $particular->discount_type = $_POST['FeeInvoiceParticulars']['discount_type'][$i];
                            $particular->discount_value = $_POST['FeeInvoiceParticulars']['discount_value'][$i];
                        }
                    } else {
                        $particular = new FeeInvoiceParticulars;
                        $particular->invoice_id = $id;
                        $particular->name = $name;
                        $particular->description = $_POST['FeeInvoiceParticulars']['description'][$i];
                        $particular->amount = $_POST['FeeInvoiceParticulars']['amount'][$i];
                        $particular->tax = $_POST['FeeInvoiceParticulars']['tax'][$i];
                        $particular->discount_type = $_POST['FeeInvoiceParticulars']['discount_type'][$i];
                        $particular->discount_value = $_POST['FeeInvoiceParticulars']['discount_value'][$i];
                    }

                    if ($particular != null and !$particular->validate()) {
                        $has_error = true;
                        //get error from particular
                        foreach ($particular->getErrors() as $attribute => $error) {
                            $key = "FeeInvoiceParticulars_" . $attribute . "_" . $i;
                            $errors[$key][$i] = $error[0];
                        }
                    }
                }

                if ($has_error == true) {
                    $response['errors'] = $errors;
                } else {
                    //remove the remaining rows
                    $criteria = new CDbCriteria;
                    $criteria->compare('invoice_id', $id);

                    if (count($existingids) > 0) {
                        $criteria->addNotInCondition('id', $existingids);
                    }

                    FeeInvoiceParticulars::model()->deleteAll($criteria);

                    //save particulars here
                    foreach ($_POST['FeeInvoiceParticulars']['name'] as $i => $name) {
                        $particular_id = (isset($_POST['FeeInvoiceParticulars']['id'][$i]) and $_POST['FeeInvoiceParticulars']['id'][$i] != null) ? $_POST['FeeInvoiceParticulars']['id'][$i] : null;
                        $particular = null;
                        if ($particular_id != null and is_numeric($particular_id)) {
                            $particular = FeeInvoiceParticulars::model()->findByPk($particular_id);
                            if ($particular != null) {
                                $particular->name = $name;
                                $particular->description = $_POST['FeeInvoiceParticulars']['description'][$i];
                                $particular->amount = $_POST['FeeInvoiceParticulars']['amount'][$i];
                                $particular->tax = (isset($_POST['FeeInvoiceParticulars']['tax'][$i]) and $_POST['FeeInvoiceParticulars']['tax'][$i] != null) ? $_POST['FeeInvoiceParticulars']['tax'][$i] : 0;
                                $particular->discount_type = (isset($_POST['FeeInvoiceParticulars']['discount_value'][$i]) and $_POST['FeeInvoiceParticulars']['discount_value'][$i] != 0) ? $_POST['FeeInvoiceParticulars']['discount_type'][$i] : 0;
                                $particular->discount_value = $_POST['FeeInvoiceParticulars']['discount_value'][$i];
                            }
                        } else {
                            $particular = new FeeInvoiceParticulars;
                            $particular->invoice_id = $id;
                            $particular->name = $name;
                            $particular->description = $_POST['FeeInvoiceParticulars']['description'][$i];
                            $particular->amount = $_POST['FeeInvoiceParticulars']['amount'][$i];
                            $particular->tax = (isset($_POST['FeeInvoiceParticulars']['tax'][$i]) and $_POST['FeeInvoiceParticulars']['tax'][$i] != null) ? $_POST['FeeInvoiceParticulars']['tax'][$i] : 0;
                            $particular->discount_type = (isset($_POST['FeeInvoiceParticulars']['discount_value'][$i]) and $_POST['FeeInvoiceParticulars']['discount_value'][$i] != 0) ? $_POST['FeeInvoiceParticulars']['discount_type'][$i] : 0;
                            $particular->discount_value = $_POST['FeeInvoiceParticulars']['discount_value'][$i];
                        }

                        if ($particular != null and $particular->save()) {}
                    }

                    //change status paid / unpaid
                    $invoice = FeeInvoices::model()->findByPk($id);
                    $invoice->is_paid = ($invoice->getAmountPayable($id) > 0) ? 0 : 1;
                    $invoice->save();

                    $response['status'] = 'success';
                }
            } else {
                //render edit form
                $invoice = FeeInvoices::model()->findByPk($id);
                if ($invoice) {
                    $criteria = new CDbCriteria;
                    $criteria->compare("invoice_id", $id);
                    $particulars = FeeInvoiceParticulars::model()->findAll($criteria);
                    $data = $this->renderPartial('_edit', array('invoice' => $invoice, 'particulars' => $particulars), true);
                    $response['status'] = "success";
                    $response['data'] = $data;
                }
            }
        }

        echo json_encode($response);
        Yii::app()->end();
    }

    public function actionAddparticular()
    {
        $response = array('status' => 'error');
        if (Yii::app()->request->isAjaxRequest and isset($_POST['count']) and isset($_POST['index'])) {
            $index = $_POST['index'] + 1;
            $count = $_POST['count'];
            $configuration = Configurations::model()->findByPk(5);
            $particular = new FeeInvoiceParticulars;
            $data = $this->renderPartial('_particular', array('index' => $index, 'count' => $count, 'configuration' => $configuration, 'particular' => $particular), true);
            $response['status'] = 'success';
            $response['data'] = $data;
        }

        echo json_encode($response);
        Yii::app()->end();
    }

    /*public function actionMarkaspaid(){
    $response    = array('status'=>'error');
    if(Yii::app()->request->isAjaxRequest and isset($_POST['invoice-check']) and count($_POST['invoice-check'])){
    foreach($_POST['invoice-check'] as $invoice_id){
    $invoice    = FeeInvoices::model()->findByPk($invoice_id);
    if($invoice!=NULL){
    $invoice->is_paid    = 1;
    $invoice->save();
    }
    }

    $response["status"]    = "success";
    }

    echo json_encode($response);
    Yii::app()->end();
    }

    public function actionMarkasunpaid(){
    $response    = array('status'=>'error');
    if(Yii::app()->request->isAjaxRequest and isset($_POST['invoice-check']) and count($_POST['invoice-check'])){
    foreach($_POST['invoice-check'] as $invoice_id){
    $invoice    = FeeInvoices::model()->findByPk($invoice_id);
    if($invoice!=NULL){
    $invoice->is_paid    = 0;
    $invoice->save();
    }
    }

    $response["status"]    = "success";
    }

    echo json_encode($response);
    Yii::app()->end();
    }*/

    public function actionMarkascancel()
    {
        $response = array('status' => 'error');
        if (Yii::app()->request->isAjaxRequest and isset($_POST['invoice-check']) and count($_POST['invoice-check'])) {
            foreach ($_POST['invoice-check'] as $invoice_id) {
                $invoice = FeeInvoices::model()->findByPk($invoice_id);
                if ($invoice != null) {
                    $invoice->is_canceled = 1;
                    $invoice->save();
                }
            }

            $response["status"] = "success";
        }

        echo json_encode($response);
        Yii::app()->end();
    }

    public function actionSendreminder()
    {
        $response = array('status' => 'error');
        $notification = NotificationSettings::model()->findByPk(8);
        if ($notification != null and $notification->parent_1 == 1) {
            if (Yii::app()->request->isAjaxRequest and isset($_POST['invoice-check']) and count($_POST['invoice-check'])) {
                $college = Configurations::model()->findByPk(1);
                $date_config = UserSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));

                //send email and sms to parent
                $email_template = EmailTemplates::model()->findByPk(13);

                $sms_template = SystemTemplates::model()->findByPk(14);

                foreach ($_POST['invoice-check'] as $invoice_id) {
                    $invoice = FeeInvoices::model()->findByPk($invoice_id);
                    if ($invoice != null) {
                        if ($invoice->user_type == 1) {
                            $student_id = $invoice->table_id;
                            $guardian = Students::model()->getPrimaryGuardian($student_id);
                            if ($guardian != null) {
                                //email
                                if ($notification->mail_enabled == 1 and $guardian->email != null) {
                                    $subject = $email_template->subject;
                                    $message = $email_template->template;
                                    $subject = str_replace("{{SCHOOL NAME}}", $college->config_value, $subject);
                                    $message = str_replace("{{SCHOOL NAME}}", $college->config_value, $message);
                                    $message = str_replace("{{FEE CATEGORY NAME}}", $invoice->name, $message);
                                    $message = str_replace("{{DUE DATE}}", ($date_config != null) ? date($date_config->displaydate, strtotime($invoice->due_date)) : $invoice->due_date, $message);
                                    UserModule::sendMail($guardian->email, $subject, $message);
                                }

                                //SMS
                                if ($notification->sms_enabled == 1 and $guardian->mobile_phone != null) {
                                    $message = $sms_template->template;
                                    $message = str_replace("<School Name>", $college->config_value, $message);
                                    $message = str_replace("<Due Date>", ($date_config != null) ? date($date_config->displaydate, strtotime($invoice->due_date)) : $invoice->due_date, $message);
                                    SmsSettings::model()->sendSms($guardian->mobile_phone, $college->config_value, $message);
                                }

                                //internal message
                                if ($notification->msg_enabled == 1 and $guardian->uid != null) {
                                    $to = $guardian->uid;
                                    $subject = Yii::t('app', 'Fees Reminder');
                                    $message = Yii::t('app', 'Your fee is pending. To avoid fine please pay  the amount.');
                                    $message .= "<br />" . Yii::t('app', 'Fee Category') . " : " . $invoice->name;
                                    $message .= "<br />" . Yii::t('app', 'Due Date') . " : " . (($date_config != null) ? date($date_config->displaydate, strtotime($invoice->due_date)) : $invoice->due_date);
                                    NotificationSettings::model()->sendMessage($to, $subject, $message);
                                }
                            }
                        }
                    }
                }

                $response["status"] = "success";
            }
        }

        echo json_encode($response);
        Yii::app()->end();
    }

    protected function download_send_headers($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        //header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        //header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    protected function export2csv($invoices)
    {
        $headers = array(
            Yii::t("app", 'Invoice ID'),
            Yii::t("app", 'Recipient'),
            Yii::t("app", 'Invoice Date'),
            Yii::t("app", 'Due Date'),
            Yii::t("app", 'Invoice Amount'),
            Yii::t("app", 'Adjustments'),
            Yii::t("app", 'Payment Details'),
            Yii::t("app", 'Amount Payable'),
            Yii::t("app", 'Status'),
        );

        if (!FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")) {
            unset($headers[1]);
        }

        if (count($headers) > 0) {
            $settings = UserSettings::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
            if ($settings != null and $settings->displaydate != null) {
                $dateformat = $settings->displaydate;
            } else {
                $dateformat = 'd M Y';
            }

            $handle = fopen("php://output", 'w');
            fputcsv($handle, $headers, ',', '"');
            foreach ($invoices as $invoice) {
                $row = array();
                $row[] = $invoice->id;

                $display_name = "-";

                if (FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")) {
                    if ($invoice->table_id != null and $invoice->table_id != 0) {
                        if ($invoice->user_type == 1) {
                            //student
                            $student = Students::model()->findByPk($invoice->table_id);
                            if ($student != null) {
                                $display_name = $student->studentFullName("forStudentProfile");
                            }

                        }
                    }
                    $row[] = $display_name;
                }

                if ($invoice->created_at != null) {
                    $row[] = date($dateformat, strtotime($invoice->created_at));
                } else {
                    $row[] = "-";
                }

                if ($invoice->due_date != null) {
                    $row[] = date($dateformat, strtotime($invoice->due_date));
                } else {
                    $row[] = "-";
                }

                $invoice_amount = 0;
                $criteria = new CDbCriteria;
                $criteria->compare("invoice_id", $invoice->id);
                $particulars = FeeInvoiceParticulars::model()->findAll($criteria);
                foreach ($particulars as $key => $particular) {
                    $amount = $particular->amount;
                    //apply discount
                    if ($particular->discount_type == 1) {
                        //percentage
                        $idiscount = (($particular->amount * $particular->discount_value) / 100);
                        $amount = $amount - $idiscount;
                    } else if ($particular->discount_type == 2) {
                        //amount
                        $amount = $amount - $particular->discount_value;
                    }

                    //apply tax
                    if ($particular->tax != 0) {
                        $tax = FeeTaxes::model()->findByPk($particular->tax);
                        if ($tax != null) {
                            $itax = (($amount * $tax->value) / 100);
                            $amount = $amount + $itax;
                        }
                    }
                    $invoice_amount += $amount;
                }

                $row[] = number_format($invoice_amount, 2);

                $amount_payable = 0;
                $payments = 0;
                $adjustments = 0;
                $criteria = new CDbCriteria;
                $criteria->compare('invoice_id', $invoice->id);
                $alltransactions = FeeTransactions::model()->findAll($criteria);
                foreach ($alltransactions as $index => $ctransaction) {
                    if ($ctransaction->is_deleted == 0 and $ctransaction->status == 1) {
                        if ($ctransaction->amount < 0) {
                            $adjustments += $ctransaction->amount;
                        } else {
                            $payments += $ctransaction->amount;
                        }
                    }
                }

                $row[] = number_format($adjustments, 2);
                $row[] = number_format($payments, 2);

                $amount_payable = $invoice_amount - ($payments + $adjustments);

                $row[] = number_format($amount_payable, 2);
                $row[] = ($invoice->is_canceled == 1) ? Yii::t("app", "Canceled") : (($invoice->is_paid == 1) ? Yii::t("app", "Paid") : Yii::t("app", "Unpaid"));

                if (implode('', $row) != "") {
                    fputcsv($handle, $row, ',', '"');
                }
            }

            fclose($handle);
            return ob_get_clean();
        }
        return;
    }

    public function actionTransactionspdf()
    {
        $filename = Yii::t('app', ' Transactions') . '.pdf';
        Yii::app()->osPdf->generate("application.modules.fees.views.invoices.transactionspdf", $filename, array());
    }
    public function actionPrint($id)
    {
        $invoice = FeeInvoices::model()->findByPk($id);
        if ($invoice) {
            $criteria = new CDbCriteria;
            $criteria->compare("invoice_id", $id);
            $particulars = FeeInvoiceParticulars::model()->findAll($criteria);
            $criteria = new CDbCriteria;
            $criteria->compare('invoice_id', $transaction->invoice_id);
            $alltransactions = FeeTransactions::model()->findAll($criteria);
            $filename = "invoice.pdf";

            $output = '';
            $template = 1;

            //fetch from fee settings
            $config = FeeConfigurations::model()->find();
            if ($config != null) {
                if ($config->invoice_template != null) {
                    $template = $config->invoice_template;
                }

                $template_path = "application.modules.fees.views.invoices.pdf._template_" . $template;
            }

            $template_params = isset(Yii::app()->getModule('fees')->invoice_templates[$template]) ? Yii::app()->getModule('fees')->invoice_templates[$template] : null;

            // PDF params
            $landscape = ($template_params != null and isset($template_params['landscape'])) ? $template_params['landscape'] : 0;
            $format = ($template_params != null and isset($template_params['format'])) ? $template_params['format'] : 'A4';
            $margin_left = ($template_params != null and isset($template_params['margin_left'])) ? $template_params['margin_left'] : 15;
            $margin_right = ($template_params != null and isset($template_params['margin_right'])) ? $template_params['margin_right'] : 15;
            $margin_top = ($template_params != null and isset($template_params['margin_top'])) ? $template_params['margin_top'] : 16;
            $margin_bottom = ($template_params != null and isset($template_params['margin_bottom'])) ? $template_params['margin_bottom'] : 16;

            Yii::app()->osPdf->generate($template_path, $filename, array('invoice' => $invoice, 'particulars' => $particulars), $landscape, $output, $format, $margin_left, $margin_right, $margin_top, $margin_bottom);
        } else {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionGetBatches($course)
    {
        $criteria = new CDbCriteria;
        $criteria->compare("course_id", $course);
        $criteria->compare("is_active", 1);
        $data = Batches::model()->findAll($criteria);
        echo CHtml::tag('option', array('value' => ""), Yii::t('app', "All Batches"), true);
        $data = CHtml::listData($data, 'id', 'name');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionRtf($id)
    {
        $invoice = FeeInvoices::model()->findByPk($id);
        if ($invoice) {
            $criteria = new CDbCriteria;
            $criteria->compare("invoice_id", $id);
            $particulars = FeeInvoiceParticulars::model()->findAll($criteria);
            $criteria = new CDbCriteria;
            $criteria->compare('invoice_id', $transaction->invoice_id);
            $alltransactions = FeeTransactions::model()->findAll($criteria);
            $filename = "invoice.doc";
            $output = '';
            $template = 1;

            //fetch from fee settings
            $config = FeeConfigurations::model()->find();
            if ($config != null) {
                if ($config->invoice_template != null) {
                    $template = $config->invoice_template;
                }

            }
            $template_path = "application.modules.fees.views.invoices.rtf._template_" . $template;

            $this->renderPartial($template_path, array('title' => $title, 'invoice' => $invoice, 'particulars' => $particulars));
            Yii::app()->end();
        } else {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
