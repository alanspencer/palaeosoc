<?php
function shopConfig ($VAR, $db) { 
	$sql_file_config = "SELECT * FROM mod_shop_config WHERE mod_shop_config_Var='$VAR'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config_num = $db->sql_numrows($result_file_config); 
  if ($row_file_config_num != 0) {
    $row_file_config = $db->sql_fetchrow($result_file_config);
    return $row_file_config['mod_shop_config_Val']; 
  } else {
    return false;
  }
}


function getUniqueKey($length = "18") {
  $code = md5(uniqid(rand(), true));

  if ($length != "") {
    return substr($code, 0, $length);
  } else {
    return $code;
  }
}

if (!isset($_GET['stage'])) { 
  $STAGE = "";
} else {
  $STAGE = $_GET['stage'];
}
switch ($STAGE) {
  default:
    if (!$MEMBERVERIFIED) {
      // Login - Member
      $main = new xhtml;
      
      $main->div('memberLoginTemplate','');
        // Login Box
        $main->div('memberLoginBox','');
          $main->hx(2,'Members\' - Renew as an Individual Member - Login','','');$main->_hx(2);
          $main->br(1);
          if ((isset($_GET['status'])) AND ($_GET['status'] == 'login')) {
            $main->div('memberLoginError','');
            $main->add('Your Username and/or Password combination could not be validated. Please try again.');
            $main->_div();
          }
               
          $main->form('?membership=individual&amp;status=login','POST','','membersLoginForm','');
          $main->br(1);
          $main->add('Username:&nbsp;');
          $main->input('text', 'MEMBER_UN', '', '', '', '', '', '');
          $main->br(2);
          $main->add('Password:&nbsp;');
          $main->input('password', 'MEMBER_PW', '', '', '', '', '', '');
          $main->br(2);
          $main->input('Submit', '', 'Login', '', '', '', '', '');
          $main->_form();
          $main->br(2);
          $main->div('memberLoginLinks','');
          $main->add('Have you forgotten your password? Folow this link to <a href="?mode=forgot" title="Recover your Password">Recover your Password</a>.');
          $main->_div();
        $main->_div();
      $main->_div();
    } else {
      // Get members details
      $sql_member = "SELECT * FROM mod_members_users WHERE mod_members_users_ID='{$_SESSION['MEMBER_ID']}'";
      $result_member = $db->sql_query($sql_member);  
      $row_member = $db->sql_fetchrow($result_member);
      
      // Member is logged in
      switch ($STAGE) {
        default:
        case "application":
          // Add Javascript
          $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
          
          $js->script('js','','
            $(document).ready(function(){
              $("#membersRenewIndividualForm").validate();
            });
          ');
          
          // Produce Page
          $main = new xhtml;
          $main->div ('membersRetrunLinksTop','');
          $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
          $main->_div();
          $main->hx(2,'Members\' - Renew as an Individual Member','','');$main->_hx(2);
          $main->div ('membersJoinIndividualPage','');
            $main->add('<p>To submit an application to the society to renew an individual membership please fill in the form below.</p>
            <p>Once you have completed the form press the "Confirm Renewal and Transfer to PayPal" button. You will then be directed to the PayPal Secure payment site, once the payment has cleared your membership period will be updated.</p>
            <p>This version of the subscription renewal form must only be used by those who wish to pay by <strong>Credit or Debit Card</strong>. The payment service is provided by PayPal, but does not require a PayPal account.</p>');
            
            // If Updated
            // Form
            $main->form('?membership=individual&amp;stage=transfer','post','','membersRenewIndividualForm','');
            
            $sql_selection = "SELECT * FROM mod_members_membership WHERE mod_members_membership_Type!='institutional'";
            $changeOfStatus = false;
            if ($row_member['mod_members_users_Type'] == 'Student') {
              if ((isset($row_member['mod_members_users_StudentEnd'])) AND ($row_member['mod_members_users_StudentEnd'] != '')) {
               // Americanise the date
               list($day, $month, $year) = explode('/',$row_member['mod_members_users_StudentEnd']);
               $new_date = $month.'/'.$day.'/'.$year;
               if (strtotime($new_date) > time()) {
                $sql_selection .= " AND mod_members_membership_IsStudent='1'";
               } else {
                $sql_selection .= " AND mod_members_membership_IsStudent!='1'";
                $changeOfStatus = true;
               }
              } else {
                $sql_selection .= " AND mod_members_membership_IsStudent!='1'";
                $changeOfStatus = true;
              }
            } else {
              $sql_selection .= " AND mod_members_membership_IsStudent!='1'";
            }
            $result_selection = $db->sql_query($sql_selection);
            $row_selection = $db->sql_fetchrow($result_selection);
            
            // Membership type
            $main->hx(4,'Membership Type','','');$main->_hx(4);
            $main->table('', 'membersTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Type:');$main->_td();
            $main->td('', '', '', '');
            if ($changeOfStatus) {
              $main->add('<p><strong>You can no longer receive Student Membership rates. If you think this is a mistake please contact <a href="mailto:membership-enquiry@palaeosoc.org">membership-enquiry@palaeosoc.org</a> and DO NOT complete this form.</strong></p>');
            }
            // Instutional or Institutional (Agency)
            $main->select('membershipType', 'membershipType', '', '', '');
            $main->option($row_selection['mod_members_membership_ID'], $row_selection['mod_members_membership_Name'].' ('.money_format('%n', $num->round2DP(($row_selection['mod_members_membership_Price']))).')', '');
            $membershipDescription = $row_selection['mod_members_membership_Description'];
            $main->_select();
            $main->br(1);
            $main->add(' - ');$main->span('membershipTypeDescription','');$main->add($membershipDescription);$main->_span();
            $main->_td();
            $main->_tr();
            $main->_tbody();
            $main->_table();
            
            // Membership Details
            $main->hx(4,'Personal Details','','');$main->_hx(4);
            $main->table('', 'membersTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Name:');$main->_td();
            $main->td('', '', '', '');
            $main->add($row_member['mod_members_users_Title'].' '.$row_member['mod_members_users_LastName'].', '.$row_member['mod_members_users_FirstNames']);
            $main->_td();
            $main->_tr();
            $main->_tbody();
            $main->_table();
            
            $main->hx(4,'Contact Details','','');$main->_hx(4);
            $main->table('', 'membersTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Address:');$main->_td();
            $main->td('', '', '', '');
            $main->add($row_member['mod_members_users_Address1'].'<br />');
            $main->add($row_member['mod_members_users_Address2'].'<br />');
            $main->add($row_member['mod_members_users_City'].'<br />');
            $main->add($row_member['mod_members_users_State'].'<br />');
            $main->add($row_member['mod_members_users_Zip'].'<br />');
            $main->add($row_member['mod_members_users_Country'].'<br />');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Telephone:');$main->_td();
            $main->td('', '', '', '');
            $main->add($row_member['mod_members_users_Telephone']);
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('E-mail Address:');$main->_td();
            $main->td('', '', '', '');
            $main->add($row_member['mod_members_users_Username'].'<br />');
            $main->_td();
            $main->_tr();
            $main->_tbody();
            $main->_table();
             
            // Data Protection
            $main->hx(4,'Data Protection','','');$main->_hx(4);
            $main->table('', 'membersTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', '', '', '');$main->add('<strong>I AGREE</strong> to consent for The Palaeontographical Society to hold the details provided in our card and database records so that we can keep our records accurate and up to date. Your details will not be passed to any third party without your consent and will be held for administrative purposes provided you remain a member.');$main->_td();
            $main->td('', '', '', '');
            $main->input('checkbox', 'terms', '1', '', '', 'required', '', '');
            $main->_td();
            $main->_tr();
            $main->_tbody();
            $main->_table();
            
            $main->div('shopTemplateCheckoutTerms','');   
              $main->hx(4,'Terms and Conditions of Membership','','');$main->_hx(4);
              $main->p('shopTemplateCheckoutTermsCenter','');
              $main->textarea ('TandC', membersConfig('T_AND_C', $db), '', '', '', '1');
              $main->_p();
              $main->p('','');
              $main->add('I fully accept the Palaeontographical Society\'s (PalseoSoc\'s) Terms and Conditions of Membership: YES ');
              $main->input('checkbox', 'checkoutTerms', '1', '', 'checkoutTerms', 'required', '', '');
              $main->_p();
            $main->_div();
            
            $main->div('','buttonWrapper');
            $main->input('hidden', 'submitApplication', '1', '', '', '', '', '');
            $main->input('Submit', '', 'Confirm Renewal and Transfer to PayPal', '', '', '', '', '');
            $main->input('Reset', '', 'Reset', '', '', '', '', '');
            $main->_div();
            // Form
            $main->_form();
          $main->_div();
        break;
        
        case "transfer":
          if (!isset($_POST['membershipType'])) {
            header ("Location: ".__SITEURL."members/renew/?membership=individual");
            die();
          }
          // Javascript automatic form submition
          $js->script('js','','
          $(document).ready(function(){
              setTimeout(function() {
               $("#membersPayPalTranfer").submit();
              }, 5000);
            });
          ');

          $main = new xhtml;
          $main->div ('membersRetrunLinksTop','');
          $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
          $main->_div();
          $main->hx(2,'Members\' - Renew as an Individual Member','','');$main->_hx(2);
          $main->div('shopTemplateCheckoutTransferForm','');
          $main->br(2);
          $main->add('Please wait while your application/order is transfered to PayPal...');
          $main->br(2);
          $main->add('<!-- PayPal Logo --><img  src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" alt="PayPal Logo"><!-- PayPal Logo -->');
          $main->br(1);
          $main->img(__SITEURL.'image/shop/paypalTransfer.gif', '', '', 'Image: PayPal Transfer', 'Image: PayPal Transfer');
          $main->br(2);
          $main->add('If you are not automatically transfered please press the button below:');
          $main->br(2);
          
          if ($BST) {
            // BST
            $orderNumber = date("mdy").'-'.(date("H")-1).date("is");
            $unquieKey = getUniqueKey().'-'.$orderNumber;
            $orderNumber = 'PS-'.$orderNumber;
          } else {
            // GMT
            $orderNumber = date("mdy").'-'.date("His");
            $unquieKey = getUniqueKey().'-'.$orderNumber;
            $orderNumber = 'PS-'.$orderNumber;
          }
          
          $membershipID = 'MEM-'.$_POST['membershipType'];
          
          // Get Membship Type
          $sql_selection = "SELECT * FROM mod_members_membership WHERE mod_members_membership_ID='{$_POST['membershipType']}'";
          $result_selection = $db->sql_query($sql_selection);
          $row_selection = $db->sql_fetchrow($result_selection);
          if ($row_selection['mod_members_membership_IsStudent'] === 1) {
            $membershipType = 'Student';
          } else {
            $membershipType = 'Individual';
          }
          // Get Details for Form from DB
          if (shopConfig('SANDBOX_MODE', $db) != '1') {
            // Live Paypal
            $main->form(shopConfig('URL', $db),'POST', '', 'membersPayPalTranfer','');
              $main->input('hidden', 'business', shopConfig('BUSSINESS', $db), '', '', '', '', '');
          } else {
            // Sandbox PayPal
            $main->form(shopConfig('SANDBOX_URL', $db),'POST','','membersPayPalTranfer','');
              $main->input('hidden', 'business', shopConfig('SANDBOX_BUSSINESS', $db), '', '', '', '', '');  
          }
            // cmd
            $main->input('hidden', 'cmd', '_cart', '', '', '', '', '');
            // invoice - Order Number
            $main->input('hidden', 'invoice', $orderNumber, '', '', '', '', '');
            // custom - 
            $main->input('hidden', 'custom', $unquieKey, '', '', '', '', '');
            // return url
            $main->input('hidden', 'return', __SITEURL.'members/renew/?membership=individual&stage=pdt', '', '', '', '', '');
            // notify url
            $main->input('hidden', 'notify_url', __SITEURL.'members/renew/?membership=individual&stage=ipn', '', '', '', '', '');
            // currency_code - Pounds Sterlin
            $main->input('hidden', 'currency_code', 'GBP', '', '', '', '', '');
            // Item
            $x = 1;
            // Item Name - max 128 char
            $main->input('hidden', 'item_name_'.$x, $row_selection['mod_members_membership_Name'], '', '', '', '', '');
            // Item ID
            $main->input('hidden', 'item_number_'.$x, $membershipID, '', '', '', '', '');
            // Item Amount
            $main->input('hidden', 'amount_'.$x, money_format('%!n', $num->round2DP($row_selection['mod_members_membership_Price'])), '', '', '', '', '');              
            // Item Quantity
            $main->input('hidden', 'quantity_'.$x, '1', '', '', '', '', '');        
            // upload - rewuired fro 3rd party shopping carts
            $main->input('hidden', 'upload', '1', '', '', '', '', '');
            // first name
            $main->input('hidden', 'first_name', preg_replace("/[^a-zA-Z0-9\s]/", "", $row_member['mod_members_users_FirstNames']), '', '', '', '', '');
            // email
            $main->input('hidden', 'email', $row_member['mod_members_users_Username'], '', '', '', '', '');
            // last name
            $main->input('hidden', 'last_name', preg_replace("/[^a-zA-Z0-9\s]/", "", $row_member['mod_members_users_LastName']), '', '', '', '', '');
            //address 1
            $main->input('hidden', 'address1', $row_member['mod_members_users_Address1'], '', '', '', '', '');
            //address 2
            $main->input('hidden', 'address2', $row_member['mod_members_users_Address2'], '', '', '', '', '');
            //city
            $main->input('hidden', 'city', $row_member['mod_members_users_City'], '', '', '', '', '');
            //state
            if ($row_member['mod_members_users_State'] != '') {
              $main->input('hidden', 'state', $row_member['mod_members_users_State'], '', '', '', '', '');
            }
            //zip
            $main->input('hidden', 'zip', $row_member['mod_members_users_Zip'], '', '', '', '', '');
            //country
            $main->input('hidden', 'country', $row_member['mod_members_users_Country'], '', '', '', '', '');
            // address overide
            //$main->input('hidden', 'address_override', '1', '', '', '', '', '');
            // buyers lang
            $main->input('hidden', 'lc', 'GB', '', '', '', '', '');
            
            
            $main->input('submit', '', 'Transfer to PayPal', '', '', '', '', '');
          $main->_form();
          $main->_div();
          
          // Individual Member
          $totalPrice = money_format('%!n', $num->round2DP($row_selection['mod_members_membership_Price']));
          
          // add order into database
          if (!get_magic_quotes_gpc()) {   
            $row_member['mod_members_users_Title'] = addslashes($row_member['mod_members_users_Title']);
            $row_member['mod_members_users_FirstNames'] = addslashes($row_member['mod_members_users_FirstNames']);
            $row_member['mod_members_users_LastName'] = addslashes($row_member['mod_members_users_LastName']);
            $row_member['mod_members_users_Username'] = addslashes($row_member['mod_members_users_Username']);
            $row_member['mod_members_users_Address1'] = addslashes($row_member['mod_members_users_Address1']);
            $row_member['mod_members_users_Address2'] = addslashes($row_member['mod_members_users_Address2']);
            $row_member['mod_members_users_City'] = addslashes($row_member['mod_members_users_City']);
            $row_member['mod_members_users_State'] = addslashes($row_member['mod_members_users_State']);
            $row_member['mod_members_users_Zip'] = addslashes($row_member['mod_members_users_Zip']);
            $row_member['mod_members_users_Country'] = addslashes($row_member['mod_members_users_Country']);
            $row_member['mod_members_users_Telephone'] = addslashes($row_member['mod_members_users_Telephone']);
            $row_member['mod_members_users_Newsletter'] = addslashes($row_member['mod_members_users_Newsletter']);
            $row_member['mod_members_users_PassOn'] = addslashes($row_member['mod_members_users_PassOn']);
            $_POST['terms'] = addslashes($_POST['terms']);
          }
          
          // save to db
          $db->sql_query("INSERT INTO mod_members_application (
          mod_members_application_Key, mod_members_application_InvoiceID, mod_members_application_OrderStatus, 
          mod_members_application_PaymentStatus, mod_members_application_PaymentStatusInfo, 
          mod_members_application_Total, mod_members_application_Type, mod_members_application_Username,
          mod_members_application_LastName, mod_members_application_FirstNames, mod_members_application_Title,
          mod_members_application_Address1, mod_members_application_Address2, mod_members_application_City, 
          mod_members_application_State, mod_members_application_Zip, mod_members_application_Country, 
          mod_members_application_Newsletter, mod_members_application_PassOn, mod_members_application_DataTerms,
          mod_members_application_ItemID, mod_members_application_ItemName, mod_members_application_ItemPrice,
          mod_members_application_Telephone, mod_members_application_MemberID, mod_members_application_IsRenewal
          ) VALUES (
          '$unquieKey', '$orderNumber', 'AwaitingPayment', 
          'NoPayment','Awaiting transfer to PayPal',
          '$totalPrice', '$membershipType','{$row_member['mod_members_users_Username']}', 
          '{$row_member['mod_members_users_LastName']}', '{$row_member['mod_members_users_FirstNames']}', '{$row_member['mod_members_users_Title']}',
          '{$row_member['mod_members_users_Address1']}','{$row_member['mod_members_users_Address2']}', '{$row_member['mod_members_users_City']}',
          '{$row_member['mod_members_users_State']}', '{$row_member['mod_members_users_Zip']}', '{$row_member['mod_members_users_Country']}', 
          '{$row_member['mod_members_users_Newsletter']}', '{$row_member['mod_members_users_PassOn']}', '{$_POST['terms']}', 
          '$membershipID', '{$row_selection['mod_members_membership_Name']}', '{$row_selection['mod_members_membership_Price']}',
          '{$row_member['mod_members_users_Telephone']}', '{$_SESSION['MEMBER_ID']}', '1'
          )");
          
          // Send email to customer / palaeosoc membership people
          // Email Customer
          $TO = $row_member['mod_members_users_Username'];
          $CC = '';
          $BCC = 'email-archive@palaeosoc.org';
          $FROM = '';
          $VAR_ARRAY = array();
          
          $VAR_ARRAY['INVOICE_ID'] = $orderNumber;
        
          emailMailer(27, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
          
          // Email staff - awaiting payment
          $TO = 'membership-orders@palaeosoc.org';
          $CC = '';
          $BCC = '';
          $FROM = '';
          $VAR_ARRAY = array();
          
          $VAR_ARRAY['INVOICE_ID'] = $orderNumber;
        
          emailMailer(28, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
          
        break;
      }
    }
  break;
  
  case "pdt":
        $main = new xhtml;
    $main->div ('membersRetrunLinksTop','');
    $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Account">Return to Members Account</a>');
    $main->_div();
    // Show invoice/recipt
    $main->div('shopTemplateCheckoutConfirmation',''); 
      $main->hx(2,'Members\' - Renew as an Individual Member - Confirmation','','');$main->_hx(2);
    $main->_div();
    
    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-synch';
    
    $tx_token = $_GET['tx'];
    
    // Get Details for Form from DB
    if (shopConfig('SANDBOX_MODE', $db) != '1') {
      // Live Paypal
      $auth_token = shopConfig('PDT', $db);
    } else {
      // Sandbox PayPal
      $auth_token = shopConfig('SANDBOX_PDT', $db);
    }
    $req .= "&tx=$tx_token&at=$auth_token";
    
    // post back to PayPal system to validate
    $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
    $header .="Content-Type: application/x-www-form-urlencoded\r\n";
    $header .="Host: www.paypal.com\r\n<http://www.paypal.com/r/n>";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    if (shopConfig('SANDBOX_MODE', $db) != '1') {
      // Live Paypal
      $fp = fsockopen ('ssl://'.shopConfig('PDT_URL', $db), 443, $errno, $errstr, 30);
    } else {
      // Sandbox PayPal
      $fp = fsockopen ('ssl://'.shopConfig('SANDBOX_PDT_URL', $db), 443, $errno, $errstr, 30);
    }
    
    if (!$fp) {
      // HTTP ERROR
    } else {
      fputs ($fp, $header . $req);
      // read the body data
      $res = '';
      $headerdone = false;
      while (!feof($fp)) {
        $line = fgets ($fp, 1024);
        if (strcmp($line, "\r\n") == 0) {
          // read the header
          $headerdone = true;
        } else if ($headerdone) {
          // header has been read. now read the contents
          $res .= $line;
        }
      }
      
      // parse the data
      $lines = explode("\n", $res);
      $keyarray = array();
      if (strcmp ($lines[0], "SUCCESS") == 0) {
        for ($i=1; $i<count($lines);$i++){
          @list($key,$val) = @explode("=", $lines[$i]);
          $keyarray[urldecode($key)] = urldecode($val);
        }
        $NO_ERROR = true;
        // check that txn_id has not been previously processed
        $txn_log_sql = "SELECT mod_members_txn_TxnID FROM mod_members_txn WHERE mod_members_txn_TxnID='".$keyarray['txn_id']."'";
        $txn_log_result = $db->sql_query($txn_log_sql);
        // then log txn_id in database
        if ($db->sql_numrows($txn_log_result) == 0) {
           $db->sql_query("INSERT INTO mod_members_txn (mod_members_txn_TxnID) VALUES ('".$keyarray['txn_id']."')");
        }

        // Get info from Database about order using invoice number
        $order_sql = "SELECT * FROM mod_members_application WHERE mod_members_application_InvoiceID='{$keyarray['invoice']}'";
        $order_result = $db->sql_query($order_sql);
        if ($db->sql_numrows($order_result) == 0) {
          $main->add("<h2>Transaction Error!</h2>");
          $main->add("This transaction has no valid order associated with it.<br>\n");
          $NO_ERROR = false;
        }
        
        if ($NO_ERROR) {
          $row_order = $db->sql_fetchrow($order_result);
          // Process payemtn info - if required
          if ($row_order['mod_members_application_PaymentStatus'] != 'Completed') {
            if ($keyarray['payment_status'] == 'Pending') {
              $db->sql_query("UPDATE mod_members_application SET 
              mod_members_application_PaymentStatus='{$keyarray['payment_status']}',
              mod_members_application_PaymentStatusInfo='{$keyarray['pending_reason']}'
               WHERE mod_members_application_InvoiceID='{$keyarray['invoice']}'");
            } elseif (($keyarray['payment_status'] == 'Reversed') OR ($keyarray['payment_status'] == 'Cancelled_Reversal') OR ($keyarray['payment_status'] == 'Refunded')) {
              $db->sql_query("UPDATE mod_members_application SET 
              mod_members_application_PaymentStatus='{$keyarray['payment_status']}',
              mod_members_application_PaymentStatusInfo='{$keyarray['reason_code']}'
               WHERE mod_members_application_InvoiceID='{$keyarray['invoice']}'");
            } elseif ($keyarray['payment_status'] == 'Completed') {
              $sql = "UPDATE mod_members_application SET 
              mod_members_application_PaymentStatus='{$keyarray['payment_status']}',
              mod_members_application_PaymentStatusInfo='Awaiting Account Activation',
              mod_members_application_OrderStatus='PaymentReceived'
               WHERE mod_members_application_InvoiceID='{$keyarray['invoice']}'";
              $db->sql_query($sql);
            } else {
              $db->sql_query("UPDATE mod_members_application SET 
              mod_members_application_PaymentStatus='{$keyarray['payment_status']}',
              mod_members_applicationPaymentStatusInfo=''
               WHERE mod_members_application_InvoiceID='{$keyarray['invoice']}'");
            }
          }
          
          
          
          $main->div('shopTemplateCheckoutConfirmationInvoice',''); 
          $main->add("<h3>Thank you for your renewal!</h3>");
          $main->div('shopTemplateCheckoutPayment','');
          $main->hx(4,'Payment Details','','');$main->_hx(4);
          $main->table('', 'checkoutTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Order/Invoice N&deg;:');$main->_td();
            $main->td('', '', '', '');
            $main->add($keyarray['invoice']);
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Transaction ID:');$main->_td();
            $main->td('', '', '', '');
            $main->add($keyarray['txn_id']);
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Payment Status:');$main->_td();
            $main->td('', '', '', '');
            $main->add($keyarray['payment_status']);
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Payment Date:');$main->_td();
            $main->td('', '', '', '');
            $main->add($keyarray['payment_date']);
            $main->_td();
            $main->_tr();
            $main->_tbody();
          $main->_table();
          $main->_div();      
          // Membership type
          $main->hx(4,'Membership Type','','');$main->_hx(4);
          $main->table('', 'membersTable');
          $main->tbody('', '');
          $main->tr('', '');
          $main->td('', 'title', '', '');$main->add('Type:');$main->_td();
          $main->td('', '', '', '');
          $main->add('<strong>'.$row_order['mod_members_application_ItemName'].' ('.money_format('%n', $num->round2DP(($row_order['mod_members_application_ItemPrice']))).')</strong>');
          $main->_td();
          $main->_tr();
          $main->_tbody();
          $main->_table();
          
          // Membership Details
          $main->hx(4,'Personal Details','','');$main->_hx(4);
          $main->table('', 'membersTable');
          $main->tbody('', '');
          $main->tr('', '');
          $main->td('', 'title', '', '');$main->add('Full Name:');$main->_td();
          $main->td('', '', '', '');
          $main->add($row_order['mod_members_application_Title'].' '.$row_order['mod_members_application_LastName'].', '.$row_order['mod_members_application_FirstNames']);
          $main->_td();
          $main->_tr();
          $main->_tbody();
          $main->_table();
          
          $main->hx(4,'Contact Details','','');$main->_hx(4);
          $main->table('', 'membersTable');
          $main->tbody('', '');
          $main->tr('', '');
          $main->td('', 'title', '', '');$main->add('Address:');$main->_td();
          $main->td('', '', '', '');
          $main->p('','indent');
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
          $main->_td();
          $main->_tr();
          $main->tr('', '');
          $main->td('', 'title', '', '');$main->add('Telephone:');$main->_td();
          $main->td('', '', '', '');
          $main->add($row_order['mod_members_application_Telephone']);
          $main->_td();
          $main->_tr();
          $main->tr('', '');
          $main->td('', 'title', '', '');$main->add('E-mail Address:');$main->_td();
          $main->td('', '', '', '');
          $main->add($row_order['mod_members_application_Username']);
          $main->_td();
          $main->_tr();
          $main->_tbody();
          $main->_table();
          
          // Options
          $main->hx(4,'Options','','');$main->_hx(4);
          $main->table('', 'membersTable');
          $main->tbody('', '');
          $main->tr('', '');
          $main->td('', '', '', '');$main->add('I DO NOT want to receive Newsletters, Council Reports, and other News by email from the society:');$main->_td();
          $main->td('', '', '', '');
          if ($row_order['mod_members_application_Newsletter']) {
            $main->input('checkbox', 'newsletter', '1', '1', '', '', '1', '1');      
          } else {
            $main->input('checkbox', 'newsletter', '1', '', '', '', '1', '1');
          }
          $main->_td();
          $main->_tr();
          $main->tr('', '');
          $main->td('', '', '', '');$main->add('I DO NOT consent for your details being passed on to other members of the Society for purposes of palaeontological research:');$main->_td();
          $main->td('', '', '', '');
          if ($row_order['mod_members_application_PassOn']) {
            $main->input('checkbox', 'passOn', '1', '1', '', '', '1', '1');      
          } else {
            $main->input('checkbox', 'passOn', '1', '', '', '', '1', '1');
          }
          $main->_td();
          $main->_tr();
          $main->_tbody();
          $main->_table();
          // Data Protection
          $main->hx(4,'Data Protection','','');$main->_hx(4);
          $main->table('', 'membersTable');
          $main->tbody('', '');
          $main->tr('', '');
          $main->td('', '', '', '');$main->add('<strong>I AGREE</strong> to consent for The Palaeontographical Society to hold the details provided in our card and database records so that we can keep our records accurate and up to date. Your details will not be passed to any third party without your consent and will be held for administrative purposes provided you remain a member.');$main->_td();
          $main->td('', '', '', '');
          $main->input('checkbox', 'terms', '1', '1', '', '', '1', '1');
          $main->_td();
          $main->_tr();
          $main->_tbody();
          $main->_table();
          $main->br(2);
          $main->add('<center>');
          $main->add("Your transaction has been completed, and a receipt for your membership renewal has been emailed to you.<br />You may log into your account at <a href='https://www.paypal.com'>www.paypal.com</a> to view details of this transaction.");
          $main->br(1);
          $main->add("<strong>We recommend PRINTING this for your records.</strong>");
          $main->add('</center>');
          $main->_div();
        }
      } else if (strcmp ($lines[0], "FAIL") == 0) {
        // log for manual investigation
        $main->add("<h2>Transaction Error!</h2>");
        $main->add("This transaction timed out.<br>\n");
      }
    }
    
    fclose ($fp);
  break;
  
  case "ipn":
    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    
    foreach ($_POST as $key => $value) {
      $value = urlencode(stripslashes($value));
      $req .= "&$key=$value";
    }

    // post back to PayPal system to validate
    $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
    $header .="Content-Type: application/x-www-form-urlencoded\r\n";
    $header .="Host: www.paypal.com\r\n<http://www.paypal.com/r/n>";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    if (shopConfig('SANDBOX_MODE', $db) != '1') {
      // Live Paypal
      $fp = fsockopen ('ssl://'.shopConfig('PDT_URL', $db), 443, $errno, $errstr, 30);
    } else {
      // Sandbox PayPal
      $fp = fsockopen ('ssl://'.shopConfig('SANDBOX_PDT_URL', $db), 443, $errno, $errstr, 30);
    }
    

    
    $date = date("Y-m-d H:i:s");
    
    if (!$fp) {
      // HTTP ERROR
      $db->sql_query("INSERT INTO mod_members_ipn (
      mod_members_ipn_Date, mod_members_ipn_Status, mod_members_ipn_Info
      ) VALUES (
      '$date','HTTP ERROR',''
      )");
    } else {
      fputs ($fp, $header . $req);
      while (!feof($fp)) {
        $res = fgets ($fp, 1024);
        if (strcmp ($res, "VERIFIED") == 0) {
          
          $NO_ERROR = true;
          // check that txn_id has not been previously processed
          $txn_log_sql = "SELECT mod_members_txn_TxnID FROM mod_members_txn WHERE mod_members_txn_TxnID='".$_POST['txn_id']."'";
          $txn_log_result = $db->sql_query($txn_log_sql);
          // then log txn_id in database
          if ($db->sql_numrows($txn_log_result) == 0) {
             $db->sql_query("INSERT INTO mod_members_txn (mod_members_txn_TxnID) VALUES ('".$_POST['txn_id']."')");
          }
          
          // assign posted variables to local variables
          $item_name = $_POST['item_name'];
          $item_number = $_POST['item_number'];
          $payment_status = $_POST['payment_status'];
          $payment_amount = $_POST['mc_gross'];
          $payment_currency = $_POST['mc_currency'];
          $txn_id = $_POST['txn_id'];
          $receiver_email = $_POST['receiver_email'];
          $payer_email = $_POST['payer_email'];
          
          // process payment
          if ($NO_ERROR) {
            // log success
            $db->sql_query("INSERT INTO mod_members_ipn (
            mod_members_ipn_Date, mod_members_ipn_Status, mod_members_ipn_Info
            ) VALUES (
            '$date','SUCCESS','$req'
            )");
            
            // Get email info on order from db           
            $order_sql = "SELECT * FROM mod_members_application WHERE mod_members_application_InvoiceID='{$_POST['invoice']}'";
            $order_result = $db->sql_query($order_sql);
            $row_order = $db->sql_fetchrow($result_order);
            
            // check the payment_status is Completed etc...
            switch ($_POST['payment_status']) {
              case 'Completed':
                // All ok send email to all - ready for dispatch
                $db->sql_query("UPDATE mod_members_application SET 
                mod_members_application_PaymentStatus='{$_POST['payment_status']}',
                mod_members_application_PaymentStatusInfo='Awaiting Account Activation',
                mod_members_application_OrderStatus='PaymentReceived'
                WHERE mod_members_application_InvoiceID='{$_POST['invoice']}'");
                
                // Email customer - payment cleared waiting for manual addition to members databse
                $TO = $row_order['mod_members_application_Username'];
                $CC = '';
                $BCC = 'email-archive@palaeosoc.org';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(33, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
                
                // Email staff - payment cleared waiting for manual addition to members databse
                $TO = 'membership-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(34, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
              break;
              
              case 'Denied':
              case 'Expired':
              case 'Voided':
              case 'Failed':
                // Order has had payment fail - cancel order send email to this effect
                $db->sql_query("UPDATE mod_members_application SET 
                mod_members_application_PaymentStatus='{$_POST['payment_status']}',
                mod_members_application_PaymentStatusInfo='Do Not Activate - Order Cancled',
                mod_members_application_OrderStatus='Canceled'
                WHERE mod_members_application_InvoiceID='{$_POST['invoice']}'");
                
                // Email customer - order canceled payment not taken
                $TO = $row_order['mod_members_application_Username'];
                $CC = '';
                $BCC = 'email-archive@palaeosoc.org';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(22, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
                
                // Email staff - order canceled payment not taken
                $TO = 'membership-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(23, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
              break;
              
              case 'Refunded':
              case 'Reversed':
                // Order has been refunded - cancel order and send email to this effect
                // Get reason form reason_code POST
                $db->sql_query("UPDATE mod_members_application SET 
                mod_members_application_PaymentStatus='{$_POST['payment_status']}',
                mod_members_application_PaymentStatusInfo='Do Not Activate - Membership Refunded. {$_POST['reason_code']}',
                mod_members_application_OrderStatus='Refunded'
                WHERE mod_shop_orders_InvoiceID='{$_POST['invoice']}'");
                
                // Email customer - payment refunded
                $TO = $row_order['mod_members_application_Username'];
                $CC = '';
                $BCC = 'email-archive@palaeosoc.org';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(25, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
                
                // Email staff - payment refunded
                $TO = 'membership-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(26, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
              break;
              
              case 'Processed':
                // Log but no email - payment accpeted waiting for it to clear
                $db->sql_query("UPDATE mod_members_application SET 
                mod_members_application_PaymentStatus='{$_POST['payment_status']}',
                mod_members_application_PaymentStatusInfo='Payment is Processing',
                mod_members_application_OrderStatus='AwaitingPayment'
                WHERE mod_members_application_InvoiceID='{$_POST['invoice']}'");
              break;
              
              case 'Canceled_Reversal':
                // Log but no email - payment refund cancled/returned (this would be a manaual problem to solve)
                // Get reason form reason_code POST
                $db->sql_query("UPDATE mod_members_application SET 
                mod_members_application_PaymentStatus='{$_POST['payment_status']}',
                mod_members_application_PaymentStatusInfo='Manual Processing Required. {$_POST['reason_code']}',
                mod_members_application_OrderStatus='PaymentReceived'
                WHERE mod_members_application_InvoiceID='{$_POST['invoice']}'");
                
              break;
              
              case 'Pending':
                // Log but no email - payment pending (might need to email palaeosoc staff for manual action)
                // get reason from pending_reason POST
                $db->sql_query("UPDATE mod_members_application SET 
                mod_members_application_PaymentStatus='{$_POST['payment_status']}',
                mod_members_application_PaymentStatusInfo='{$_POST['pending_reason']}',
                mod_members_application_OrderStatus='AwaitingPayment'
                WHERE mod_members_application_InvoiceID='{$_POST['invoice']}'");
                
                // Email staff - payment pending might need manual checking
                $TO = 'membership-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(24, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
              break;
            }
          }
        } else if (strcmp ($res, "INVALID") == 0) {
          // log for manual investigation
          $db->sql_query("INSERT INTO mod_members_ipn (
          mod_members_ipn_Date, mod_members_ipn_Status, mod_members_ipn_Info
          ) VALUES (
          '$date','INVALID','$res'
          )");
        }
      }
      fclose ($fp);
    }
    die();
  break;
  
}
?>
