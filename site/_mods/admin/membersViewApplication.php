<?php

function generatePassword ($length = 8) {
  // start with a blank password
  $password = "";
  // define possible characters
  $possible = "123456789AbcdfghjkmnPQRSTRUVWXYZ";
  // set up a counter
  $i = 0;   
  // add random characters to $password until $length is reached
  while ($i < $length) { 
    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }
  }
  return $password;
}



$order_sql = "SELECT * FROM mod_members_application WHERE mod_members_application_InvoiceID='{$_GET['id']}'";
$order_result = $db->sql_query($order_sql);
$num_order = $db->sql_numrows($order_result);
$row_order = $db->sql_fetchrow($order_result);

if ($num_order==0) {
  header("Location: ".__SITEURL."admin/?mode=members&view=applicationManagement");
  die();
} else {

  $UPDATE = false;
  $UPDATE_TEXT = '';
  //If there is a POST then process it
  if (isset($_POST['authorisedGo'])) {
    // Student Confirmed and ready for payment
    // send email to Student explaing this and update db
    $db->sql_query("UPDATE mod_members_application SET mod_members_application_OrderStatus='AwaitingPayment', mod_members_application_StudentAuth='1', mod_members_application_StudentEndYear='{$_POST['studentEndDate']}' WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
    
    // Send email to student application with link for payment
    $TO = $row_order['mod_members_application_Username'];
    $CC = '';
    $BCC = 'membership-orders@palaeosoc.org';
    $FROM = '';
    $VAR_ARRAY = array();
    
    $VAR_ARRAY['INVOICE_ID'] = $_GET['id'];
    $VAR_ARRAY['KEY'] = $row_order['mod_members_application_Key'];
    $VAR_ARRAY['NAME'] = $row_order['mod_members_application_Title'].' '.$row_order['mod_members_application_LastName'].', '.$row_order['mod_members_application_FirstNames'];
    
    emailMailer(19, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
    
    $UPDATE_TEXT = 'Order UPDATED. Student Status has been confirmed. Email sent to student asking for payment.';
    $UPDATE = true;
  }
  
  if (isset($_POST['paymentOK'])) {
    // Payment Confirmed - add to database and send emails to thoses concerned
    // send email to new member explaing this and update db
    $db->sql_query("UPDATE mod_members_application SET mod_members_application_OrderStatus='Completed' WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
    
    if ($row_order['mod_members_application_IsRenewal'] == '1') {
      // Update application data with members number
      $db->sql_query("UPDATE mod_members_application SET mod_members_application_OrderStatus='Completed', mod_members_application_PaymentStatusInfo='Account Activated', mod_members_application_PaymentConfirmed='1' WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
      
      // Update application data with members number
      $renewalDate = date("d/m/Y");
      $member_sql = "SELECT * FROM mod_members_users WHERE mod_members_users_ID='{$row_order['mod_members_application_MemberID']}'";
      $member_result = $db->sql_query($member_sql);
      $row_member = $db->sql_fetchrow($member_result);
      $newSubYear = ((int)($row_member['mod_members_users_SubYear']))+1;
      $db->sql_query("UPDATE mod_members_users SET mod_members_users_SubYear='$newSubYear', mod_members_users_LastRenewalOnline='$renewalDate' WHERE mod_members_users_ID='{$row_order['mod_members_application_MemberID']}'");

      
      // Send email to member saying renewal has occured
      $TO = $row_order['mod_members_application_Username'];
      $CC = '';
      $BCC = 'membership-orders@palaeosoc.org';
      $FROM = '';
      $VAR_ARRAY = array();
      
      $VAR_ARRAY['INVOICE_ID'] = $_GET['id'];
      $VAR_ARRAY['MEMBER_ID'] = $row_order['mod_members_application_MemberID'];
      $VAR_ARRAY['USERNAME'] = $row_order['mod_members_application_Username'];
      emailMailer(36, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
      
      $UPDATE_TEXT = 'Order UPDATED. Order complete. Member has had another year added to their membership and has been emailed.';
    } else {
      // New Member
      $password = generatePassword();
      $joinDate = date("d/m/Y");
      list($prefix, $orderDate, $time) = split('-', $row_order['mod_members_application_InvoiceID']);
      list($month, $day, $year) = str_split ($orderDate, 2);
      $subYear = '20'.$year;
      // Add new member into members database
      $sql_insert = "INSERT INTO mod_members_users (
      mod_members_users_Username, mod_members_users_Password, mod_members_users_LastName,
      mod_members_users_FirstNames, mod_members_users_Title, mod_members_users_Address1,
      mod_members_users_Address2, mod_members_users_City, mod_members_users_State,
      mod_members_users_Zip, mod_members_users_Country, mod_members_users_Telephone,
      mod_members_users_Type, mod_members_users_StudentEnd, mod_members_users_JoinedOnline,
      mod_members_users_SubYear, mod_members_users_Newsletter, mod_members_users_PassOn
      ) VALUES (
      '{$row_order['mod_members_application_Username']}', '$password', '{$row_order['mod_members_application_LastName']}',
      '{$row_order['mod_members_application_FirstNames']}', '{$row_order['mod_members_application_Title']}', '{$row_order['mod_members_application_Address1']}',
      '{$row_order['mod_members_application_Address2']}', '{$row_order['mod_members_application_City']}', '{$row_order['mod_members_application_State']}',
      '{$row_order['mod_members_application_Zip']}', '{$row_order['mod_members_application_Country']}', '{$row_order['mod_members_application_Telephone']}',
      '{$row_order['mod_members_application_Type']}', '{$row_order['mod_members_application_StudentEndYear']}', '$joinDate',
      '$subYear', '{$row_order['mod_members_application_Newsletter']}', '{$row_order['mod_members_application_PassOn']}'
      )";
      $db->sql_query($sql_insert);
      $memberNumber = mysql_insert_id();
      // Update application data with members number
      $db->sql_query("UPDATE mod_members_application SET mod_members_application_MemberID='$memberNumber', mod_members_application_OrderStatus='Completed', mod_members_application_PaymentStatusInfo='Account Activated', mod_members_application_PaymentConfirmed='1' WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
      // Send email to new member with username and password
      
      $TO = $row_order['mod_members_application_Username'];
      $CC = '';
      $BCC = 'membership-orders@palaeosoc.org';
      $FROM = '';
      $VAR_ARRAY = array();
      
      $VAR_ARRAY['INVOICE_ID'] = $_GET['id'];
      $VAR_ARRAY['PASSWORD'] = $password;
      $VAR_ARRAY['MEMBER_ID'] = $memberNumber;
      $VAR_ARRAY['USERNAME'] = $row_order['mod_members_application_Username'];
      emailMailer(29, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
      
      $UPDATE_TEXT = 'Order UPDATED. Order complete. New member added to online database. Email sent to new member containing Username and Password.';
    }
    $UPDATE = true;
  }
  
  if (isset($_POST['overideOrderStatusGo'])) {
    // Confirmed and ready to dispatch;
    // send email to STOCK explaing this and update db
    $db->sql_query("UPDATE mod_members_application SET 
    mod_members_application_OrderStatus='{$_POST['overideOrderStatus']}'
    WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
    
    if ($_POST['overideOrderStatus'] != 'AwaitingStudentAuth') {
      $db->sql_query("UPDATE mod_members_application SET 
      mod_members_application_StudentAuth='1'
      WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
    } elseif ($_POST['overideOrderStatus'] == 'AwaitingStudentAuth') {
      $db->sql_query("UPDATE mod_members_application SET 
      mod_members_application_StudentAuth='0'
      WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
    }
    
    if ($_POST['overideOrderStatus'] == 'Completed') {
      $db->sql_query("UPDATE mod_members_application SET 
      mod_members_application_PaymentConfirmed='1'
      WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
    } else {
      $db->sql_query("UPDATE mod_members_application SET 
      mod_members_application_PaymentConfirmed='0'
      WHERE mod_members_application_InvoiceID='{$_GET['id']}'");
    }
    
    $UPDATE_TEXT = 'Order Status UPDATED. No Emails Sent. No Data Added/Updated to any Databases.';
    $UPDATE = true;
  }
  
  // Add Javascript
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  $js->script('js','','
    $(document).ready(function(){
      $("#membersStudentVarified").validate();
    });
  ');
  
  // Tabs
  $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.core.min.js', '');
  $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.datepicker.min.js', '');
  $js->script('js','','
    $(document).ready(function(){
      $("#studentEndDate").datepicker();
    });
  ');
  
  $css->link('css', __SITEURL.'css/jquery/ui.core.css', '', 'screen');
  $css->link('css', __SITEURL.'css/jquery/ui.datepicker.css', '', 'screen');
  $css->link('css', __SITEURL.'css/jquery/ui.theme.css', '', 'screen');

  $order_sql = "SELECT * FROM mod_members_application WHERE mod_members_application_InvoiceID='{$_GET['id']}'";
  $order_result = $db->sql_query($order_sql);
  $row_order = $db->sql_fetchrow($order_result);
 
  // Produce Page
  $main = new xhtml;
  $main->div ('adminRetrunLinksTop','');
  $main->add('<a href="'.__SITEURL.'admin/" title="Link: Return to Dashboard">Return to Dashboard</a>');
  $main->_div();
  $main->hx(2,'Administration - Members - View Application','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the viewing and management of individual online membership applications.');
  $main->_p();
  $main->div ('adminShopOrderView','');
    $main->hx(3,'Order: '.$_GET['id'],'','');$main->_hx(3);
    $main->p('','');
    $main->add('To return to the Applications/Orders List page follow this link: <a href="?mode=members&view=applicationManagement" title="Link: Application/Order List Page">Application/Order List Page</a>');
    $main->_p();
    
    if ($row_order['mod_members_application_IsRenewal'] == '1') {
      $applicationType = 'RENEWAL';
    } else {
      $applicationType = 'NEW APPLICATION';
    }
    $main->add('<p style="text-align:center;border:2px dashed #0033FF;padding:2px;background:#E0F0FF;">Application Type: <strong>'.$applicationType.'</strong></p>');
    
    // Need to get order status
    if ($row_order['mod_members_application_OrderStatus'] == "AwaitingPayment") {
      switch ($row_order['mod_members_application_PaymentStatus']) {
        case 'Pending':
          // Log but no email - payment pending (might need to email palaeosoc staff for manual action)
          $toDoText = 'Order has payment pending. TREASURER to check that the payment does not need any manual interaction before it is processed.<br /><br />The customer has been NOT been informed that the order has payment pending by the PalaeoSoc on-line shop. PayPal will inform the customer of payment status. No further emails required.';
        break;
        
        case 'Processed':
          // Log but no email - payment accpeted waiting for it to clear
          $toDoText = 'TREASURER to check that the payment is on going. No further action required until status changes.';
        break;
        
        default:
          $toDoText = 'Nothing. Awaiting Payment to be confirmed from PayPal.<br /><br />The customer has been informed that the payment is awaitng. No further emails required at this time.';
        break;
      }
    } elseif ($row_order['mod_members_application_OrderStatus'] == "PaymentReceived") {
      switch ($row_order['mod_members_application_PaymentStatus']) {
        case 'Completed':
          // All ok send email to all - ready for dispatch
          $toDoText = 'Payment has been received. TREASURER to confirm payment using action button. This will enter the member\'s data in to the online databse.<br /><br />The customer has been informed that the payment has been received and membership will be activated manually. No further emails required at this time.';
        break;
        
        case 'Canceled_Reversal':
          // Log but no email - payment refund cancled/returned (this would be a manaual problem to solve)
          $toDoText = 'This application will need to be handled manually. TREASURER to inform OTHERS as status changes.';
        break;
        
      }
    } elseif ($row_order['mod_members_application_OrderStatus'] == "Refunded") {
      switch ($row_order['mod_members_application_PaymentStatus']) {
        case 'Refunded':
        case 'Reversed':
          // Order has been refunded - cancel order and send email to this effect
          $toDoText = 'Order has been refunded and canceled. TREASURER to check that the refund has been completed.<br /><br />The customer has been informed that the order has been refunded and canceled. No further emails required at this time.';
        break;
        
        default:
          $toDoText = 'Order has been refunded and canceled. TREASURER to check that the refund has been completed.<br /><br />The customer may NOT have been informed that the order has been refunded and canceled.';
        break;
      }
    } elseif ($row_order['mod_members_application_OrderStatus'] == "Canceled") {
      switch ($row_order['mod_members_application_PaymentStatus']) {        
        case 'Denied':
        case 'Expired':
        case 'Voided':
        case 'Failed':
          // Order has had payment fail - cancel order send email to this effect
          $toDoText = 'Order has been canceled. TREASURER to check that a refund is not needed.<br /><br />The customer has been informed that the order has been canceled. No further emails required at this time.';
        break;
        
        default:
          $toDoText = 'Order has been canceled. TREASURER to check that a refund is not needed.<br /><br />The customer may NOT have been informed that the order has been canceled';
        break;   
      }
    } elseif ($row_order['mod_members_application_OrderStatus'] == "AwaitingStudentAuth") {
      $toDoText = 'Order is awaitng Student Status confirmation. TREASURER to use action button once student application is validated.<br /><br />The student has been informed that they need get their tutor/supervisor to provided details to the TREASURER using the form provided.';
    } else {
      $toDoText = 'Application has been completed.';
    }
    
    $main->div('adminOrderOptionsBox',''); 
    $main->hx(4,'Management Options','','hidden');$main->_hx(4);
    
    // Update Text
    if ($UPDATE) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add($UPDATE_TEXT);
        $main->_div();
      $main->_div();
      $main->br(1);
    }
    
    $main->div('adminOrderOptionsCols','yui-gb');
      $main->div('adminOrderOptionsCol1','yui-u first');
      $main->hx(5,'To Do','','');$main->_hx(5);
        $main->add($toDoText);
      $main->_div();
      $main->div('adminOrderOptionsCol2','yui-u');
      $main->hx(5,'Actions','','');$main->_hx(5);
             
        if ( ($row_order['mod_members_application_Type'] == 'Student') AND ($row_order['mod_members_application_StudentAuth'] != 1)) {
          $main->add('For TREASURER:');
          $main->br(1);
          $main->form('?mode=members&amp;view=viewApplication&amp;id='.$_GET['id'],'post','','membersStudentVarified','');
          $main->add('End Date: ');$main->input('text', 'studentEndDate', '', '', 'studentEndDate', 'required', '', '');
          $main->br(1);
          $main->input('submit', 'submit', 'Student Confirmed', '', '', '', '', '');
          $main->input('hidden', 'authorisedGo', '1', '', '', '', '', '');
          $main->_form();
          $main->br(1);
        }
        
        $main->add('For TREASURER:');
        $main->br(1);
        $main->form('?mode=members&amp;view=viewApplication&amp;id='.$_GET['id'],'post','','membersPaymentOK','');
        if (($row_order['mod_members_application_OrderStatus'] == 'PaymentReceived')) {
          $main->input('submit', 'submit', 'Payment Confirmed', '', '', '', '', '');
        } else {
          $main->input('submit', 'submit', 'Payment Confirmed', '', '', '', '1', '1');
        }
        $main->input('hidden', 'paymentOK', '1', '', '', '', '', '');      
        $main->_form();
        $main->br(1);
        
        $main->div('automationOveride','');
          $main->hx(5,'Status Automation Overide','','');$main->_hx(5);
          $main->form('?mode=members&amp;view=viewApplication&amp;id='.$_GET['id'],'post','','membersAppplicationOveride','');
          $main->add('Mark as: ');
          $main->select('overideOrderStatus', '', '', '', '');
            $main->option('Completed', 'Completed', '');
            $main->option('Refunded', 'Refunded', '');
            $main->option('Canceled', 'Canceled', '');
            $main->option('AwaitingPayment', 'AwaitingPayment', '');
            $main->option('AwaitingStudentAuth', 'AwaitingStudentAuth', '');
            $main->option('PaymentReceived', 'PaymentReceived', '');
          $main->_select();
          $main->input('submit', 'submit', 'Go', '', '', '', '', '');
          $main->input('hidden', 'overideOrderStatusGo', '1', '', '', '', '', '');
          $main->br(1);
          $main->add('[<em class="warning">To be used on as a last resort! May cause problems!</em>]');
          $main->_form();
        $main->_div();
      $main->_div();
      $main->div('adminOrderOptionsCol3','yui-u');
      $main->hx(5,'Action Status','','');$main->_hx(5);
        $main->ul('','');
          if ($row_order['mod_members_application_Type'] == 'Student') {
            if ($row_order['mod_members_application_StudentAuth'] != 1){
              $main->li('Awaiting Student Auth <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
              $main->li('Awaiting Payment <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            } else {
              $main->li('Awaiting Student Auth <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
              $main->li('Awaiting Payment <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
            }
          } else {
            $main->li('Awaiting Payment <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
          }
          if (($row_order['mod_members_application_OrderStatus'] != 'AwaitingPayment') AND ($row_order['mod_members_application_OrderStatus'] != 'AwaitingStudentAuth')) {
            $main->li('Payment Received <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
            if ($row_order['mod_members_application_PaymentConfirmed'] == 1) {
              $main->li('Payment Confirmed <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
            } else {
              $main->li('Payment Confirmed <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            }
            if ($row_order['mod_members_application_OrderStatus'] == 'Completed') {
              $main->li('Completed <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
            } else {
              $main->li('Completed <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            }
          } else {
            $main->li('Payment Received <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            $main->li('Payment Confirmed <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            $main->li('Completed <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
          }
        $main->_ul();
      $main->_div();
    $main->_div();
    $main->_div();
    
    $main->div('shopTemplateCheckoutConfirmationInvoice',''); 
  
    $main->div('shopTemplateCheckoutPayment','');
    $main->hx(4,'Payment Details','','');$main->_hx(4);
    $main->table('', 'checkoutTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order/Invoice N&deg;:');$main->_td();
      $main->td('', '', '', '');
      $main->add($_GET['id']);
      $main->_td();
      $main->_tr();
      
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order Status:');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_order['mod_members_application_OrderStatus']);
      $main->_td();
      $main->_tr();
      
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Payment Status:');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_order['mod_members_application_PaymentStatus']);
      $main->_td();
      $main->_tr();
      
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order Date:');$main->_td();
      $main->td('', '', '', '');
      list($prefix, $orderDate, $time) = split('-', $row_order['mod_members_application_InvoiceID']);
      list($month, $day, $year) = str_split ($orderDate, 2);
      list($hour, $minute, $second) = str_split ($time, 2);
      $main->add($day.'/'.$month.'/20'.$year.' '.$hour.':'.$minute.':'.$second);
      $main->_td();
      $main->_tr();
      
      $main->_tbody();
    $main->_table();
    $main->_div();
  
    $main->div('shopTemplateCheckoutContactDetails','');
    $main->hx(4,'Contact Details','','');$main->_hx(4);
    $main->table('', 'checkoutTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Full Name:');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_order['mod_members_application_Title'].' '.$row_order['mod_members_application_LastName'].', '.$row_order['mod_members_application_FirstNames']);
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Email Address/Username:');$main->_td();
      $main->td('', '', '', '');
      $main->add('<a href="mailto:'.$row_order['mod_members_application_Username'].'?subject=PalaeoSoc - Membership - Your Application: '.$_GET['id'].'" title="Email Customer">'.$row_order['mod_members_application_Username'].'</a>');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Telephone:');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_order['mod_members_application_Telephone']);
      $main->_td();
      $main->_tr();
      $main->_tbody();
    $main->_table();
    $main->_div();
    $main->div('shopTemplateCheckoutAddresses','yui-g');
      $main->div('shopTemplateCheckoutAddressesCol1','yui-u first');
        $main->hx(4,'Invoice Address','','');$main->_hx(4);
        $main->p('','indent');
        $main->add($row_order['mod_members_application_Title'].' '.$row_order['mod_members_application_LastName'].', '.$row_order['mod_members_application_FirstNames']);
        $main->br(1);
        $main->add($row_order['mod_members_application_Address1']);
        $main->br(1);
        if ($row_order['mod_members_application_Address2'] != '') {
          $main->add($row_order['mod_members_application_Address2']);
          $main->br(1);
        }
        $main->add($row_order['mod_members_application_City']);
        $main->br(1);
        if ($row_order['mod_members_application_State'] != '') {
          $main->add($row_order['mod_members_application_State']);
          $main->br(1);
        }
        $main->add($row_order['mod_members_application_Zip']);
        $main->br(1);
        $main->add($row_order['mod_members_application_Country']);
        $main->_p();
  	  $main->_div();
      $main->div('shopTemplateCheckoutAddressesCol2','yui-u');
        $main->hx(4,'Options','','');$main->_hx(4);
        $main->ul('','');
        if ($row_order['mod_members_application_Newsletter'] == 1) {
          $main->li('','','');$main->add('<strong>DO NOT</strong> send Newsletters, Council Reports, and other News by email from the society.');$main->_li();
        } else {
          $main->li('','','');$main->add('<strong>ADD</strong> to Newsletters, Council Reports, and other News by email from the society.');$main->_li();
        }
        if ($row_order['mod_members_application_PassOn'] == 1) {
          $main->li('','','');$main->add('<strong>DOES NOT</strong> consent for details to be passed on to other members of the Society for purposes of palaeontological research.');$main->_li();
        } else {
          $main->li('','','');$main->add('<strong>DOES</strong> consent for details to be passed on to other members of the Society for purposes of palaeontological research.');$main->_li();
        }
        if ($row_order['mod_members_application_DataTerms'] == 1) {
          $main->li('','','');$main->add('<strong>AGREED</strong> to Data Protection statement.');$main->_li();
        } else {
          $main->li('','','');$main->add('<strong>DOES NOT AGREE</strong> to Data Protection statement.');$main->_li();
        }
        $main->_ul();
      $main->_div();
    $main->_div();
    // Basket
    $main->div('shopTemplateBasketContent','');   
    $main->hx(4,'Basket','','');$main->_hx(4);        
      $main->table('','basketTable');
      $main->thead('','');
      $main->tr('','');
      $main->th('','','','');
        $main->add('Item');
        $main->_th();
        $main->th('','','','');
        $main->add('Quantity');
        $main->_th();
        $main->th('','','','');
        $main->add('Each');
        $main->_th();
        $main->th('','','','');
        $main->add('Price');
        $main->_th();
      $main->_tr();
      $main->_thead();
      $main->tbody('','');
      
      $main->tr('','');
      $main->td('','','','');
      $main->add($row_order['mod_members_application_ItemName']);
      $main->_td();
      $main->td('','','','');
      $main->add('1');
      $main->_td();
      $main->td('','','','');
      $main->add(money_format('%n', $num->round2DP($row_order['mod_members_application_ItemPrice'])));
      $main->_td();
      $main->td('','','','');
      $main->add(money_format('%n', $num->round2DP(($row_order['mod_members_application_ItemPrice']))));
      $main->_td();
      $main->_tr();
      // Total Price
      $main->tr('','');
      $main->td('','alignRight','','3');
      $main->add('Item(s) Total:');
      $main->_td();
      $main->td('','','','');
      $main->add('<strong>'.money_format('%n', $num->round2DP($row_order['mod_members_application_Total'])).'</strong>');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
    $main->_div();
    $main->_div();
    
  $main->_div();
}
?>
