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
  $STAGE = "application";
} else {
  $STAGE = $_GET['stage'];
}
switch ($STAGE) {
  default:
  case "application":
    // Add Javascript
    $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
    
    $js->script('js','','
      $(document).ready(function(){
        $("#membersJoinIndividualForm").validate();
      });
    ');
    
    // Defaults
    $applicationMembershipType = '';
    $applicationTitle = '';
    $applicationFirstNames = '';
    $applicationLastName = '';
    $applicationAddress1 = '';
    $applicationAddress2 = '';
    $applicationCity = '';
    $applicationState = '';
    $applicationZip = '';
    $applicationTelephone = '';
    $applicationEmail = '';
    $applicationNewletter = '';
    $applicationPassOn = '';
    $applicationTerms = '';
    
    // Produce Page
    $main = new xhtml;
    $main->div ('membersRetrunLinksTop','');
    $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
    $main->_div();
    $main->hx(2,'Members\' - Join as an Individual Member','','');$main->_hx(2);
    $main->div ('membersJoinIndividualPage','');
      $main->add('<p>To submit an application to the society to become an individual member please fill in the form below.</p>
      <p>Once you have completed the form press the "Submit Your Application" button. For membership applications, other then "Individual (Student)", you will then be directed to the PayPal Secure payment site, once the payment has cleared your membership will become active. Student applications will need to follow the instructions contained within the email received after submitting the form.</p>
      <p>This version of the subscription renewal form must only be used by those who wish to pay by <strong>Credit or Debit Card</strong>. The payment service is provided by PayPal, but does not require a PayPal account.</p>');
      // If Updated
      // Form
      $main->form('?membership=individual&amp;stage=process','post','','membersJoinIndividualForm','');
      
      // Membership type
      $main->hx(4,'Membership Type','','');$main->_hx(4);
      $main->table('', 'membersTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Type:');$main->_td();
      $main->td('', '', '', '');
      !isset($_SESSION['application']['typeID']) ? null : $applicationMembershipType = $_SESSION['application']['typeID'];
      // Instutional or Institutional (Agency)
      $main->select('membershipType', 'membershipType', '', '', '');
      $sql_selection = "SELECT * FROM mod_members_membership WHERE mod_members_membership_Type!='institutional' ORDER BY mod_members_membership_Name ASC";
      $result_selection = $db->sql_query($sql_selection);
      $isDesSet = false;
      $descriptionArray = '';
      while ($row_selection = $db->sql_fetchrow($result_selection)) {
        if ($applicationMembershipType == $row_selection['mod_members_membership_ID']) {
          $main->option($row_selection['mod_members_membership_ID'], $row_selection['mod_members_membership_Name'].' ('.money_format('%n', $num->round2DP(($row_selection['mod_members_membership_Price']))).')', '1');
          $isDesSet = true;
          $membershipDescription = $row_selection['mod_members_membership_Description'];
        } else {
          $main->option($row_selection['mod_members_membership_ID'], $row_selection['mod_members_membership_Name'].' ('.money_format('%n', $num->round2DP(($row_selection['mod_members_membership_Price']))).')', '');
        }
        $descriptionArray .= ' description['.$row_selection['mod_members_membership_ID'].'] = "'.$row_selection['mod_members_membership_Description'].'";
        ';
        if (!$isDesSet) {
          $membershipDescription = $row_selection['mod_members_membership_Description'];
          $isDesSet = true;
        }
      }
      $main->_select();
      $main->br(1);
      $js->script('js','','
        var description = new Array(); 
        '.$descriptionArray.'
        $(document).ready(function(){ 
          $("#membershipType").change(function(){
            message_index = $("#membershipType").val(); 
            $("#membershipTypeDescription").empty(); 
            if (message_index > 0) {
              $("#membershipTypeDescription").append(description[message_index]); 
            }
          });
        });
      ');
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
      $main->td('', 'title', '', '');$main->add('Title:');$main->_td();
      $main->td('', '', '', '');
      !isset($_SESSION['application']['title']) ? null : $applicationTitle = $_SESSION['application']['title'];
      $main->input('text', 'title', $applicationTitle, '', '', '', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('First Name(s):');$main->_td();
      $main->td('', '', '', '');
      !isset($_SESSION['application']['firstNames']) ? null : $applicationFirstNames = $_SESSION['application']['firstNames'];
      $main->input('text', 'firstNames', $applicationFirstNames, '', '', 'required', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Last Name:');$main->_td();
      $main->td('', '', '', '');
      !isset($_SESSION['application']['lastName']) ? null : $applicationLastName = $_SESSION['application']['lastName'];
      $main->input('text', 'lastName', $applicationLastName, '', '', 'required', '', '');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      $main->hx(4,'Contact Details','','');$main->_hx(4);
      $main->table('', 'membersTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Address 1:');$main->_td();
      $main->td('', '', '', '');
      !isset($_SESSION['application']['address1']) ? null : $applicationAddress1 = $_SESSION['application']['address1'];
      $main->input('text', 'address1', $applicationAddress1, '', '', 'required', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      !isset($_SESSION['application']['address2']) ? null : $applicationAddress2 = $_SESSION['application']['address2'];
      $main->td('', 'title', '', '');$main->add('Address 2:');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'address2', $applicationAddress2, '', '', '', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      !isset($_SESSION['application']['city']) ? null : $applicationCity = $_SESSION['application']['city'];
      $main->td('', 'title', '', '');$main->add('City:');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'city', $applicationCity, '', '', 'required', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      !isset($_SESSION['application']['state']) ? null : $applicationState = $_SESSION['application']['state'];
      $main->td('', 'title', '', '');$main->add('County/State:');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'state', $applicationState, '', '', '', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      !isset($_SESSION['application']['zip']) ? null : $applicationZip = $_SESSION['application']['zip'];
      $main->td('', 'title', '', '');$main->add('Post Code/Zip:');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'zip', $applicationZip, '', '', 'required', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Country:');$main->_td();
      $main->td('', '', '', '');
      $main->select('country', '', '', '', '');
      $sql_country= "SELECT * FROM shop_country ORDER BY shop_country_Name ASC";
      $result_country = $db->sql_query($sql_country);
      !isset($_SESSION['application']['country']) ? $applicationCountry = 'GB' : $applicationCountry = $_SESSION['application']['country'];
      while ($row_country = $db->sql_fetchrow($result_country)) {
        if ($applicationCountry == $row_country['shop_country_ShortCode']) {
          $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '1');
        } else {
          $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '');
        }
      }
      $main->_select();
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Telephone:');$main->_td();
      $main->td('', '', '', '');
      !isset($_SESSION['application']['telephone']) ? null : $applicationTelephone = $_SESSION['application']['telephone'];
      $main->input('text', 'telephone', $applicationTelephone, '', '', '', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      !isset($_SESSION['application']['email']) ? null : $applicationEmail = $_SESSION['application']['email'];
      $main->td('', 'title', '', '');$main->add('E-mail Address:');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'email', $applicationEmail, '', '', 'required email', '', '');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      // Options
      $main->hx(4,'Options','','');$main->_hx(4);
      $main->table('', 'membersTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', '', '', '');$main->add('Tick the box if you DO NOT want to receive Newsletters, Council Reports, and other News by email from the society:');$main->_td();
      $main->td('', '', '', '');
      !isset($_SESSION['application']['newsletter']) ? null : $applicationNewletter = $_SESSION['application']['newsletter'];
      if ($applicationNewletter) {
        $main->input('checkbox', 'newsletter', '1', '1', '', '', '', '');      
      } else {
        $main->input('checkbox', 'newsletter', '1', '', '', '', '', '');
      }
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', '', '', '');$main->add('Tick the box if you DO NOT consent for your details being passed on to other members of the Society for purposes of palaeontological research:');$main->_td();
      $main->td('', '', '', '');
      !isset($_SESSION['application']['passOn']) ? null : $applicationPassOn = $_SESSION['application']['passOn'];
      if ($applicationPassOn) {
        $main->input('checkbox', 'passOn', '1', '1', '', '', '', ''); 
      } else {
        $main->input('checkbox', 'passOn', '1', '', '', '', '', '');
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
      !isset($_SESSION['application']['terms']) ? null : $applicationTerms = $_SESSION['application']['terms'];
      if ($applicationTerms) {
        $main->input('checkbox', 'terms', '1', '1', '', 'required', '', '');
      } else {
        $main->input('checkbox', 'terms', '1', '', '', 'required', '', '');
      }
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      $main->div('','buttonWrapper');
      $main->input('hidden', 'submitApplication', '1', '', '', '', '', '');
      $main->input('Submit', '', 'Submit Your Application', '', '', '', '', '');
      $main->input('Reset', '', 'Reset', '', '', '', '', '');
      $main->_div();
      // Form
      $main->_form();
    $main->_div();
  break;
  
  case "process":
    if (!isset($_POST['membershipType'])) {
      header ("Location: ".__SITEURL."members/join/?membership=individual");
      die();
    }
    // First check that the username is unique
    if (!get_magic_quotes_gpc()) {
      $_POST['email'] = addslashes($_POST['email']);
    }
    $sql_member = "SELECT mod_members_users_ID FROM mod_members_users WHERE mod_members_users_Username='{$_POST['email']}'";
    $result_member = $db->sql_query($sql_member); 
    if ($db->sql_numrows($result_member) != 0) { 
      // Username Failed
      // Add Javascript
      $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
      
      $js->script('js','','
        $(document).ready(function(){
          $("#membersJoinIndividualEmailForm").validate();
        });
      ');
      
      
      // Produce Page
      $main = new xhtml;
      $main->div ('membersRetrunLinksTop','');
      $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
      $main->_div();
      $main->hx(2,'Members\' - Join as an Individual Member','','');$main->_hx(2);
      $main->div ('membersJoinIndividualPage','');
        $main->div('','errorWrapper');
          $main->div('','errorbox');
            $main->add('Sorry, the email address you have used in your application already holds membership. If you wanted to renew your membership please login to the member\'s area. Otherwise select a different email address.');        
          $main->_div();
        $main->_div();
        $main->br(1);
        // If Updated
        // Form
        $main->form('?membership=individual&amp;stage=process','post','','membersJoinIndividualEmailForm','');
        
        $main->hx(4,'Email Address','','');$main->_hx(4);
        $main->table('', 'membersTable');
        $main->tbody('', '');
        $main->tr('', '');
        $main->td('', 'title', '', '');$main->add('E-mail Address:');$main->_td();
        $main->td('', '', '', '');
        $main->input('text', 'email', $_POST['email'], '', '', 'required email', '', '');
        $main->_td();
        $main->_tr();
        $main->_tbody();
        $main->_table();
        
        // Put Post into Session
        
        $main->input('hidden', 'membershipType', $_POST['membershipType'], '', '', '', '', '');
        $main->input('hidden', 'title', $_POST['title'], '', '', '', '', '');
        $main->input('hidden', 'firstNames', $_POST['firstNames'], '', '', '', '', '');
        $main->input('hidden', 'lastName', $_POST['lastName'], '', '', '', '', '');
        $main->input('hidden', 'address1', $_POST['address1'], '', '', '', '', '');
        $main->input('hidden', 'address2', $_POST['address2'], '', '', '', '', '');
        $main->input('hidden', 'city', $_POST['city'], '', '', '', '', '');
        $main->input('hidden', 'state', $_POST['state'], '', '', '', '', '');
        $main->input('hidden', 'zip', $_POST['zip'], '', '', '', '', '');
        $main->input('hidden', 'country', $_POST['country'], '', '', '', '', '');
        $main->input('hidden', 'telephone', $_POST['telephone'], '', '', '', '', '');
        isset($_POST['newsletter']) ? null : $_POST['newsletter'] = 0;
        $main->input('hidden', 'newsletter', $_POST['newsletter'], '', '', '', '', '');
        isset($_POST['passOn']) ? null : $_POST['passOn'] = 0;
        $main->input('hidden', 'passOn', $_POST['passOn'], '', '', '', '', '');
        isset($_POST['terms']) ? null : $_POST['terms'] = 0;
        $main->input('hidden', 'terms', $_POST['terms'], '', '', '', '', '');
        
        
        
        $main->div('','buttonWrapper');
        $main->input('hidden', 'resubmitApplication', '1', '', '', '', '', '');
        $main->input('Submit', '', 'Re-Submit Your Application', '', '', '', '', '');
        $main->input('Reset', '', 'Reset', '', '', '', '', '');
        $main->_div();
        // Form
        $main->_form();
      $main->_div();
    } else {
      // Produce Page
      $main = new xhtml;
      $main->div ('membersRetrunLinksTop','');
      $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
      $main->_div();
      $main->hx(2,'Members\' - Join as an Individual Member - Review','','');$main->_hx(2);
      $main->div ('membersJoinIndividualReview','');
      $main->add('<p>Please review the information below before proceeding to the next stage.</p>');

      // Put post into session if they have been sent
      isset($_POST['title']) ? $_SESSION['application']['title'] = $_POST['title']: null;
      isset($_POST['lastName']) ? $_SESSION['application']['lastName'] = $_POST['lastName']: null;
      isset($_POST['firstNames']) ? $_SESSION['application']['firstNames'] = $_POST['firstNames']: null;
      isset($_POST['email']) ? $_SESSION['application']['email'] = $_POST['email']: null;
      isset($_POST['address1']) ? $_SESSION['application']['address1'] = $_POST['address1']: null;
      isset($_POST['address2']) ? $_SESSION['application']['address2'] = $_POST['address2']: null;
      isset($_POST['city']) ? $_SESSION['application']['city'] = $_POST['city']: null;
      isset($_POST['state']) ? $_SESSION['application']['state'] = $_POST['state']: null;
      isset($_POST['zip']) ? $_SESSION['application']['zip'] = $_POST['zip']: null;
      isset($_POST['country']) ? $_SESSION['application']['country'] = $_POST['country']: null;
      isset($_POST['membershipType']) ? $_SESSION['application']['typeID'] = $_POST['membershipType']: null;
      isset($_POST['telephone']) ? $_SESSION['application']['telephone'] = $_POST['telephone']: null;
      isset($_POST['passOn']) ? $_SESSION['application']['passOn'] = $_POST['passOn']: $_SESSION['application']['passOn'] = 0;
      isset($_POST['newsletter']) ? $_SESSION['application']['newsletter'] = $_POST['newsletter']: $_SESSION['application']['newsletter'] = 0;
      isset($_POST['terms']) ? $_SESSION['application']['terms'] = $_POST['terms']: $_SESSION['application']['terms'] = 1;
      isset($_POST['customerDelCountry']) ? $_SESSION['application']['delCountry'] = $_POST['customerDelCountry']: null;
      
      // Username OK
      $sql_selection = "SELECT * FROM mod_members_membership WHERE mod_members_membership_ID='{$_SESSION['application']['typeID']}'";
      $result_selection = $db->sql_query($sql_selection);
      $row_selection = $db->sql_fetchrow($result_selection);
      if ($row_selection['mod_members_membership_IsStudent'] == 1) {
        $_SESSION['application']['type'] = 'Student';
      } else {
        $_SESSION['application']['type'] = 'Individual';
      }
      $_SESSION['application']['typeName'] = $row_selection['mod_members_membership_Name'];
      $_SESSION['application']['price'] = $row_selection['mod_members_membership_Price'];
    
      // CONFIRMATION
      
      // Membership type
      $main->hx(4,'Membership Type','','');$main->_hx(4);
      $main->table('', 'membersTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Type:');$main->_td();
      $main->td('', '', '', '');
      $sql_selection = "SELECT * FROM mod_members_membership WHERE mod_members_membership_Type!='institutional' AND mod_members_membership_ID='{$_SESSION['application']['typeID']}'";
      $result_selection = $db->sql_query($sql_selection);
      $row_selection = $db->sql_fetchrow($result_selection);
      $main->add('<strong>'.$row_selection['mod_members_membership_Name'].' ('.money_format('%n', $num->round2DP(($row_selection['mod_members_membership_Price']))).')</strong>');
      $main->add('<br /> - ');$main->span('membershipTypeDescription','');$main->add($row_selection['mod_members_membership_Description']);$main->_span();
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
      $main->add($_SESSION['application']['title'].' '.$_SESSION['application']['lastName'].', '.$_SESSION['application']['firstNames']);
      $main->p('','alignRight');
      $main->add('[<a href="?membership=individual&amp;stage=application" title="Link: Edit">Edit</a>]');
      $main->_p();
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
      $main->add($_SESSION['application']['address1']);
      $main->br(1);
      if ($_SESSION['application']['address2'] != '') {
        $main->add($_SESSION['application']['address2']);
        $main->br(1);
      }
      $main->add($_SESSION['application']['city']);
      $main->br(1);
      if ($_SESSION['application']['state'] != '') {
        $main->add($_SESSION['application']['state']);
        $main->br(1);
      }
      $main->add($_SESSION['application']['zip']);
      $main->br(1);
      $main->add($_SESSION['application']['country']);
      $main->_p();
      $main->p('','alignRight');
      $main->add('[<a href="?membership=individual&amp;stage=application" title="Link: Edit">Edit</a>]');
      $main->_p();
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Telephone:');$main->_td();
      $main->td('', '', '', '');
      $main->add($_SESSION['application']['telephone']);
      $main->p('','alignRight');
      $main->add('[<a href="?membership=individual&amp;stage=application" title="Link: Edit">Edit</a>]');
      $main->_p();
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('E-mail Address:');$main->_td();
      $main->td('', '', '', '');
      $main->add($_SESSION['application']['email']);
      $main->p('','alignRight');
      $main->add('[<a href="?membership=individual&amp;stage=application" title="Link: Edit">Edit</a>]');
      $main->_p();
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      // Options
      $main->hx(4,'Options','','');$main->_hx(4);
      $main->table('', 'membersTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', '', '', '');$main->add('Tick the box if you DO NOT want to receive Newsletters, Council Reports, and other News by email from the society:');$main->_td();
      $main->td('', '', '', '');
      if ($_SESSION['application']['newsletter']) {
        $main->input('checkbox', 'newsletter', '1', '1', '', '', '1', '1');      
      } else {
        $main->input('checkbox', 'newsletter', '1', '', '', '', '1', '1');
      }
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', '', '', '');$main->add('Tick the box if you DO NOT consent for your details being passed on to other members of the Society for purposes of palaeontological research:');$main->_td();
      $main->td('', '', '', '');
      if ($_SESSION['application']['passOn']) {
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
       
      if ($_SESSION['application']['type'] != 'Student') {
        // Javascript
        $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
        
        $js->script('js','','
          $(document).ready(function(){
            $("#checkoutReviewForm").validate({
              rules: {
                checkoutTerms: {
                  required: true
                }
              }
            });
          });
        ');
        $main->form('?membership=individual&amp;stage=transfer','POST','','checkoutReviewForm','');
        $main->div('shopTemplateCheckoutTerms','');   
          $main->hx(4,'Terms and Conditions of Membership','','');$main->_hx(4);
          $main->p('shopTemplateCheckoutTermsCenter','');
          $main->textarea ('TandC', membersConfig('T_AND_C', $db), '', '', '', '1');
          $main->_p();
          $main->p('','');
          $main->add('I fully accept the Palaeontographical Society\'s (PalseoSoc\'s) Terms and Conditions of Membership: YES ');
          $main->input('checkbox', 'checkoutTerms', '1', '', 'checkoutTerms', '', '', '');
          $main->_p();
        $main->_div();    
        
        $main->div('shopTemplateCheckoutLinks','');
          $main->input('submit', '', 'Confirm Order and Transfer to PayPal', '', '', '', '', '');
        $main->_div();
        $main->_form();
        $main->add('<p><a href="'.__SITEURL.'members/join/?membership=individual&amp;stage=application" title="Link: Your Details"><img class="checkoutBack" src="'.__SITEURL.'image/shop/checkoutBack.png" alt="Link: Your Details" /></a></p>');
      } else {
        $main->form('?membership=individual&amp;stage=interlude','POST','','','');   
        $main->div('shopTemplateCheckoutLinks','');
          $main->input('submit', '', 'Confirm Application Details', '', '', '', '', '');
        $main->_div();
        $main->_form();
        $main->add('<p><a href="'.__SITEURL.'members/join/?membership=individual&amp;stage=application" title="Link: Your Details"><img class="checkoutBack" src="'.__SITEURL.'image/shop/checkoutBack.png" alt="Link: Your Details" /></a></p>');

      }
      $main->_div();
    }
  break;
  
  case "interlude":
    if ((!isset($_SESSION['application']['price'])) OR (!isset($_SESSION['application']['typeID'])) OR (!isset($_SESSION['application']['typeName']))) {
      header ("Location: ".__SITEURL."members/join/?membership=individual");
      die();
    }
    // Save Student data and send emails with intructions for next step
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
    
    $membershipID = 'MEM-'.$_SESSION['application']['typeID'];
    $totalPrice = money_format('%!n', $num->round2DP($_SESSION['application']['price']));
    
    // add order into database
    if (!get_magic_quotes_gpc()) {   
      $_SESSION['application']['title'] = addslashes($_SESSION['application']['title']);
      $_SESSION['application']['firstNames'] = addslashes($_SESSION['application']['firstNames']);
      $_SESSION['application']['lastName'] = addslashes($_SESSION['application']['lastName']);
      $_SESSION['application']['email'] = addslashes($_SESSION['application']['email']);
      $_SESSION['application']['address1'] = addslashes($_SESSION['application']['address1']);
      $_SESSION['application']['address2'] = addslashes($_SESSION['application']['address2']);
      $_SESSION['application']['city'] = addslashes($_SESSION['application']['city']);
      $_SESSION['application']['state'] = addslashes($_SESSION['application']['state']);
      $_SESSION['application']['zip'] = addslashes($_SESSION['application']['zip']);
      $_SESSION['application']['country'] = addslashes($_SESSION['application']['country']);
      $_SESSION['application']['telephone'] = addslashes($_SESSION['application']['telephone']);
      $_SESSION['application']['newsletter'] = addslashes($_SESSION['application']['newsletter']);
      $_SESSION['application']['passOn'] = addslashes($_SESSION['application']['passOn']);
      $_SESSION['application']['terms'] = addslashes($_SESSION['application']['terms']);
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
    mod_members_application_Telephone
    ) VALUES (
    '$unquieKey', '$orderNumber', 'AwaitingStudentAuth', 
    'NoPayment','Awaiting transfer to PayPal',
    '$totalPrice', '{$_SESSION['application']['type']}','{$_SESSION['application']['email']}', 
    '{$_SESSION['application']['lastName']}', '{$_SESSION['application']['firstNames']}', '{$_SESSION['application']['title']}',
    '{$_SESSION['application']['address1']}','{$_SESSION['application']['address2']}', '{$_SESSION['application']['city']}',
    '{$_SESSION['application']['state']}', '{$_SESSION['application']['zip']}', '{$_SESSION['application']['country']}', 
    '{$_SESSION['application']['newsletter']}', '{$_SESSION['application']['passOn']}', '{$_SESSION['application']['terms']}', 
    '$membershipID', '{$_SESSION['application']['typeName']}', '{$_SESSION['application']['price']}',
    '{$_SESSION['application']['telephone']}'
    )");
    
    // Send Student Instruction email
    // Email Customer
    $TO = $_SESSION['application']['email'];
    $CC = '';
    $BCC = 'email-archive@palaeosoc.org';
    $FROM = '';
    $VAR_ARRAY = array();
    
    $VAR_ARRAY['INVOICE_ID'] = $orderNumber;
    $VAR_ARRAY['KEY'] = $unquieKey;
    $VAR_ARRAY['NAME'] = $_SESSION['application']['title'].' '.$_SESSION['application']['lastName'].', '.$_SESSION['application']['firstNames'];
    emailMailer(18, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
    
    
    // Produce Page
    $main = new xhtml;
    $main->div ('membersRetrunLinksTop','');
    $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
    $main->_div();
    $main->hx(2,'Members\' - Join as an Individual Member - Interlude','','');$main->_hx(2);
    $main->div ('membersJoinIndividualInterlude','');
    $main->add('<p>Thank you for starting the student membership application process.</p><p>You will shortly recieve an email explaining the next steps to take.</p>');
    $main->_div();
    
    // clear session
    unset ($_SESSION['application']['price'], $_SESSION['application']['typeID'], $_SESSION['application']['typeName']);    unset ($_SESSION['application']['price'], $_SESSION['application']['typeID'], $_SESSION['application']['typeName']);
  break;
  
  case "continue":
    // For students to carry on with their application
    // Basically the same as process review
    if (!isset($_GET['key'])) {
      header ("Location: ".__SITEURL."members/join/?membership=individual");
      die();
    }
    // Need to check that key is valid and that is is a student
    $order_sql = "SELECT * FROM mod_members_application WHERE mod_members_application_Key='{$_GET['key']}'";
    $order_result = $db->sql_query($order_sql);
    $row_order = $db->sql_fetchrow($order_result);
    if (($db->sql_numrows($order_result) == 0) OR ($row_order['mod_members_application_OrderStatus'] != 'AwaitingPayment')) {
      header ("Location: ".__SITEURL."members/join/?membership=individual");
      die();
    } else {
      
      // Need to check that authorisation has been given
      
      // Need to set session to db values
      $main = new xhtml;
      $main->div ('membersRetrunLinksTop','');
      $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
      $main->_div();
      $main->hx(2,'Members\' - Join as an Individual Member - Continue','','');$main->_hx(2);
      $main->div ('membersJoinIndividualReview','');
      $main->add('<p>Thank you for contiuning the student membership application process. Please check the infomation below before proceeding to the PayPal payment site.</p>');
      
      
      // CONFIRMATION
      
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
      
      
      
      // Javascript
      $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
      
      $js->script('js','','
        $(document).ready(function(){
          $("#checkoutReviewForm").validate({
            rules: {
              checkoutTerms: {
                required: true
              }
            }
          });
        });
      ');
      
      $main->form('?membership=individual&amp;stage=student-transfer&amp;key='.$_GET['key'],'POST','','checkoutReviewForm','');
      $main->div('shopTemplateCheckoutTerms','');   
        $main->hx(4,'Terms and Conditions of Membership','','');$main->_hx(4);
        $main->p('shopTemplateCheckoutTermsCenter','');
        $main->textarea ('TandC', membersConfig('T_AND_C', $db), '', '', '', '1');
        $main->_p();
        $main->p('','');
        $main->add('I fully accept the Palaeontographical Society\'s (PalSoc\'s) Terms of Sale: YES ');
        $main->input('checkbox', 'checkoutTerms', '1', '', 'checkoutTerms', '', '', '');
        $main->_p();
      $main->_div();    
      
      $main->div('shopTemplateCheckoutLinks','');
        $main->input('submit', '', 'Confirm Order and Transfer to PayPal', '', '', '', '', '');
      $main->_div();
      $main->_form();
  
      $main->_div();
    }
  break;
  
  case "transfer":
    if ((!isset($_SESSION['application']['price'])) OR (!isset($_SESSION['application']['typeID'])) OR (!isset($_SESSION['application']['typeName']))) {
      header ("Location: ".__SITEURL."members/join/?membership=individual");
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
    $main->hx(2,'Members\' - Join as an Individual Member','','');$main->_hx(2);
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
    
    $membershipID = 'MEM-'.$_SESSION['application']['typeID'];
    
    // Get Details for Form from DB
    if (shopConfig('SANDBOX_MODE', $db) != '1') {
      // Live Paypal
      $main->form(shopConfig('URL', $db),'POST','','membersPayPalTranfer','');
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
      $main->input('hidden', 'return', __SITEURL.'members/join/?membership=individual&stage=pdt', '', '', '', '', '');
      // notify url
      $main->input('hidden', 'notify_url', __SITEURL.'members/join/?membership=individual&stage=ipn', '', '', '', '', '');
      // currency_code - Pounds Sterlin
      $main->input('hidden', 'currency_code', 'GBP', '', '', '', '', '');
      // Item
      $x = 1;
      // Item Name - max 128 char
      $main->input('hidden', 'item_name_'.$x, $_SESSION['application']['typeName'], '', '', '', '', '');
      // Item ID
      $main->input('hidden', 'item_number_'.$x, $membershipID, '', '', '', '', '');
      // Item Amount
      $main->input('hidden', 'amount_'.$x, money_format('%!n', $num->round2DP($_SESSION['application']['price'])), '', '', '', '', '');              
      // Item Quantity
      $main->input('hidden', 'quantity_'.$x, '1', '', '', '', '', '');        
      // upload - rewuired fro 3rd party shopping carts
      $main->input('hidden', 'upload', '1', '', '', '', '', '');
      // first name
      $main->input('hidden', 'first_name', preg_replace("/[^a-zA-Z0-9\s]/", "", $_SESSION['application']['firstNames']), '', '', '', '', '');
      // email
      $main->input('hidden', 'email', $_SESSION['application']['email'], '', '', '', '', '');
      // last name
      $main->input('hidden', 'last_name', preg_replace("/[^a-zA-Z0-9\s]/", "", $_SESSION['application']['lastName']), '', '', '', '', '');
      //address 1
      $main->input('hidden', 'address1', $_SESSION['application']['address1'], '', '', '', '', '');
      //address 2
      $main->input('hidden', 'address2', $_SESSION['application']['address2'], '', '', '', '', '');
      //city
      $main->input('hidden', 'city', $_SESSION['application']['city'], '', '', '', '', '');
      //state
      if ($_SESSION['application']['state'] != '') {
        $main->input('hidden', 'state', $_SESSION['application']['state'], '', '', '', '', '');
      }
      //zip
      $main->input('hidden', 'zip', $_SESSION['application']['zip'], '', '', '', '', '');
      //country
      $main->input('hidden', 'country', $_SESSION['application']['country'], '', '', '', '', '');
      // address overide
      //$main->input('hidden', 'address_override', '1', '', '', '', '', '');
      // buyers lang
      $main->input('hidden', 'lc', 'GB', '', '', '', '', '');
      
      
      $main->input('submit', '', 'Transfer to PayPal', '', '', '', '', '');
    $main->_form();
    $main->_div();
    
    // Individual Member
    $totalPrice = money_format('%!n', $num->round2DP($_SESSION['application']['price']));
    
    // add order into database
    if (!get_magic_quotes_gpc()) {   
      $_SESSION['application']['title'] = addslashes($_SESSION['application']['title']);
      $_SESSION['application']['firstNames'] = addslashes($_SESSION['application']['firstNames']);
      $_SESSION['application']['lastName'] = addslashes($_SESSION['application']['lastName']);
      $_SESSION['application']['email'] = addslashes($_SESSION['application']['email']);
      $_SESSION['application']['address1'] = addslashes($_SESSION['application']['address1']);
      $_SESSION['application']['address2'] = addslashes($_SESSION['application']['address2']);
      $_SESSION['application']['city'] = addslashes($_SESSION['application']['city']);
      $_SESSION['application']['state'] = addslashes($_SESSION['application']['state']);
      $_SESSION['application']['zip'] = addslashes($_SESSION['application']['zip']);
      $_SESSION['application']['country'] = addslashes($_SESSION['application']['country']);
      $_SESSION['application']['telephone'] = addslashes($_SESSION['application']['telephone']);
      $_SESSION['application']['newsletter'] = addslashes($_SESSION['application']['newsletter']);
      $_SESSION['application']['passOn'] = addslashes($_SESSION['application']['passOn']);
      $_SESSION['application']['terms'] = addslashes($_SESSION['application']['terms']);
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
    mod_members_application_Telephone
    ) VALUES (
    '$unquieKey', '$orderNumber', 'AwaitingPayment', 
    'NoPayment','Awaiting transfer to PayPal',
    '$totalPrice', '{$_SESSION['application']['type']}','{$_SESSION['application']['email']}', 
    '{$_SESSION['application']['lastName']}', '{$_SESSION['application']['firstNames']}', '{$_SESSION['application']['title']}',
    '{$_SESSION['application']['address1']}','{$_SESSION['application']['address2']}', '{$_SESSION['application']['city']}',
    '{$_SESSION['application']['state']}', '{$_SESSION['application']['zip']}', '{$_SESSION['application']['country']}', 
    '{$_SESSION['application']['newsletter']}', '{$_SESSION['application']['passOn']}', '{$_SESSION['application']['terms']}', 
    '$membershipID', '{$_SESSION['application']['typeName']}', '{$_SESSION['application']['price']}',
    '{$_SESSION['application']['telephone']}'
    )");
    
    // Send email to customer / palaeosoc membership people
    // Email Customer
    $TO = $_SESSION['application']['email'];
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
    
    unset ($_SESSION['application']['price'], $_SESSION['application']['typeID'], $_SESSION['application']['typeName']);
  break;
  
  case "student-transfer":
    if (!isset($_GET['key'])) {
      header ("Location: ".__SITEURL."members/join/?membership=individual");
      die();
    }
    // Need to check that key is valid and that is is a student
    $order_sql = "SELECT * FROM mod_members_application WHERE mod_members_application_Key='{$_GET['key']}'";
    $order_result = $db->sql_query($order_sql);
    $row_order = $db->sql_fetchrow($order_result);
    if (($db->sql_numrows($order_result) == 0) OR ($row_order['mod_members_application_OrderStatus'] != 'AwaitingPayment')) {
      header ("Location: ".__SITEURL."members/join/?membership=individual");
      die();
    } else {
    
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
      $main->hx(2,'Members\' - Join as an Individual Member','','');$main->_hx(2);
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
      
      // Get Details for Form from DB
      if (shopConfig('SANDBOX_MODE', $db) != '1') {
        // Live Paypal
        $main->form(shopConfig('URL', $db),'','checkoutPayPalTranfer','');
          $main->input('hidden', 'business', shopConfig('BUSSINESS', $db), '', '', '', '', '');
      } else {
        // Sandbox PayPal
        $main->form(shopConfig('SANDBOX_URL', $db),'POST','','checkoutPayPalTranfer','');
          $main->input('hidden', 'business', shopConfig('SANDBOX_BUSSINESS', $db), '', '', '', '', '');  
      }
        // cmd
        $main->input('hidden', 'cmd', '_cart', '', '', '', '', '');
        // invoice - Order Number
        $main->input('hidden', 'invoice', $row_order['mod_members_application_InvoiceID'], '', '', '', '', '');
        // custom - 
        $main->input('hidden', 'custom', $row_order['mod_members_application_Key'], '', '', '', '', '');
        // return url
        $main->input('hidden', 'return', __SITEURL.'members/join/?membership=individual&stage=pdt', '', '', '', '', '');
        // notify url
        $main->input('hidden', 'notify_url', __SITEURL.'members/join/?membership=individual&stage=ipn', '', '', '', '', '');
        // currency_code - Pounds Sterlin
        $main->input('hidden', 'currency_code', 'GBP', '', '', '', '', '');
        // Item
        $x = 1;
        // Item Name - max 128 char
        $main->input('hidden', 'item_name_'.$x, $row_order['mod_members_application_ItemName'], '', '', '', '', '');
        // Item ID
        $main->input('hidden', 'item_number_'.$x, $row_order['mod_members_application_ItemID'], '', '', '', '', '');
        // Item Amount
        $main->input('hidden', 'amount_'.$x, money_format('%!n', $num->round2DP($row_order['mod_members_application_ItemPrice'])), '', '', '', '', '');              
        // Item Quantity
        $main->input('hidden', 'quantity_'.$x, '1', '', '', '', '', '');        
        // upload - rewuired fro 3rd party shopping carts
        $main->input('hidden', 'upload', '1', '', '', '', '', '');
        // first name
        $main->input('hidden', 'first_name', preg_replace("/[^a-zA-Z0-9\s]/", "", $row_order['mod_members_application_FirstNames']), '', '', '', '', '');
        // email
        $main->input('hidden', 'email', $row_order['mod_members_application_Username'], '', '', '', '', '');
        // last name
        $main->input('hidden', 'last_name', preg_replace("/[^a-zA-Z0-9\s]/", "", $row_order['mod_members_application_LastName']), '', '', '', '', '');
        //address 1
        $main->input('hidden', 'address1', $row_order['mod_members_application_Address1'], '', '', '', '', '');
        //address 2
        $main->input('hidden', 'address2', $row_order['mod_members_application_Address2'], '', '', '', '', '');
        //city
        $main->input('hidden', 'city', $row_order['mod_members_application_City'], '', '', '', '', '');
        //state
        if ($row_order['mod_members_application_State'] != '') {
          $main->input('hidden', 'state', $row_order['mod_members_application_State'], '', '', '', '', '');
        }
        //zip
        $main->input('hidden', 'zip', $row_order['mod_members_application_Zip'], '', '', '', '', '');
        //country
        $main->input('hidden', 'country', $row_order['mod_members_application_Country'], '', '', '', '', '');
        // address overide
        //$main->input('hidden', 'address_override', '1', '', '', '', '', '');
        // buyers lang
        $main->input('hidden', 'lc', 'GB', '', '', '', '', '');
        
        
        $main->input('submit', '', 'Transfer to PayPal', '', '', '', '', '');
      $main->_form();
      $main->_div();
      
      unset ($_SESSION['application']['price'], $_SESSION['application']['typeID'], $_SESSION['application']['typeName']);
    }
  break;
  
  
  case "pdt":
    
    $main = new xhtml;
    $main->div ('membersRetrunLinksTop','');
    $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
    $main->_div();
    // Show invoice/recipt
    $main->div('shopTemplateCheckoutConfirmation',''); 
      $main->hx(2,'Members\' - Join as an Individual Member - Confirmation','','');$main->_hx(2);
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
          $main->add("<h3>Thank you for your purchase!</h3>");
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
          $main->add("Your transaction has been completed, and a receipt for your membership has been emailed to you.<br />You may log into your account at <a href='https://www.paypal.com'>www.paypal.com</a> to view details of this transaction.");
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
              
                emailMailer(20, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
                
                // Email staff - payment cleared waiting for manual addition to members databse
                $TO = 'membership-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(21, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
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
