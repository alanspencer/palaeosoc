<?php
$UPDATED = false;
$ERROR = false;

//If there is a POST then process it
if (isset($_POST['submitApplication'])) {
  // email application to treasurer
  // Email Member
  
  $VAR_ARRAY = array();
  $sql_selection = "SELECT * FROM mod_members_membership WHERE mod_members_membership_ID='{$_POST['membershipType']}'";
  $result_selection = $db->sql_query($sql_selection);
  $row_selection = $db->sql_fetchrow($result_selection);
  
  $VAR_ARRAY['membershipName'] = $row_selection['mod_members_membership_Name'];
  $VAR_ARRAY['membershipPrice'] = $row_selection['mod_members_membership_Price'];
  $VAR_ARRAY['institutionName'] = $_POST['institutionName'];
  $VAR_ARRAY['contactName'] = $_POST['contactName'];
  $VAR_ARRAY['address1'] = $_POST['address1'];
  $VAR_ARRAY['address2'] = $_POST['address2'];
  $VAR_ARRAY['city'] = $_POST['city'];
  $VAR_ARRAY['state'] = $_POST['state'];
  $VAR_ARRAY['zip'] = $_POST['zip'];
  $VAR_ARRAY['country'] = $_POST['country'];
  $VAR_ARRAY['telephone'] = $_POST['telephone'];
  $VAR_ARRAY['email'] = $_POST['email'];
  $VAR_ARRAY['newsletter'] = $_POST['newsletter'];
  $VAR_ARRAY['passOn'] = $_POST['passOn'];
  $VAR_ARRAY['terms'] = $_POST['terms'];
  
  $TO = 'membership-orders@palaeosoc.org';
  $CC = '';
  $BCC = '';
  $FROM = '';
  emailMailer(16, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
  
  // email receipt to customer
  $TO = $_POST['email'];
  $CC = '';
  $BCC = '';
  $FROM = '';
  emailMailer(17, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
  
  $UPDATED = true;
}

// Add Javascript
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');

$js->script('js','','
  $(document).ready(function(){
    $("#membersJoinInstitiutionForm").validate();
  });
');


// Produce Page
$main = new xhtml;
$main->div ('membersRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
$main->_div();
$main->hx(2,'Members\' - Join as an Institutional Member','','');$main->_hx(2);
$main->div ('membersJoinInstitutionPage','');
  $main->add('<p>To submit an application to the society to become an instituational member please fill in the form below.</p>
  <p>Once your application has been processed you will then receive an email request from PayPal to remit the appropriate sum using their secure payment site.</p>
  <p>This version of the subscription renewal form must only be used by those who wish to pay by <strong>Credit or Debit Card</strong>. The payment service is provided by PayPal, but does not require a PayPal account.</p>');
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Thank You. Your application has been sent to the Palaeontographical Society Treasurer. A copy of the information submitted has been sent to you via email.');        
      $main->_div();
    $main->_div();
    $main->br(1);
  } else {
    // Form
    $main->form('?membership=institution','post','','membersJoinInstitiutionForm','');
    
    // Membership type
    $main->hx(4,'Membership Type','','');$main->_hx(4);
    $main->table('', 'membersTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="username">Type:</label>');$main->_td();
    $main->td('', '', '', '');
    // Instutional or Institutional (Agency)
    $main->select('membershipType', 'membershipType', '', '', '');
    $sql_selection = "SELECT * FROM mod_members_membership WHERE mod_members_membership_Type='institutional' ORDER BY mod_members_membership_Name ASC";
    $result_selection = $db->sql_query($sql_selection);
    $isDesSet = false;
    $descriptionArray = '';
    while ($row_selection = $db->sql_fetchrow($result_selection)) {
      $main->option($row_selection['mod_members_membership_ID'], $row_selection['mod_members_membership_Name'].' ('.money_format('%n', $num->round2DP(($row_selection['mod_members_membership_Price']))).')', '');
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
    $main->hx(4,'Membership Details','','');$main->_hx(4);
    $main->table('', 'membersTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Institution Name:');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'institutionName', '', '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Name of Contact:');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'contactName', '', '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Address 1:');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'address1', '', '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Address 2:');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'address2', '', '', '', '', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('City:');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'city', '', '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('County/State:');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'state', '', '', '', '', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Post Code/Zip:');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'zip', '', '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Country:');$main->_td();
    $main->td('', '', '', '');
    $main->select('country', '', '', '', '');
    $sql_country= "SELECT * FROM shop_country ORDER BY shop_country_Name ASC";
    $result_country = $db->sql_query($sql_country);
    while ($row_country = $db->sql_fetchrow($result_country)) {
      if ('GB' == $row_country['shop_country_ShortCode']) {
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
    $main->input('text', 'telephone', '', '', '', '', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('E-mail Address:');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'email', '', '', '', 'required email', '', '');
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
    $main->input('checkbox', 'newsletter', '1', '', '', '', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', '', '', '');$main->add('Tick the box if you DO NOT consent for your details being passed on to other members of the Society for purposes of palaeontological research:');$main->_td();
    $main->td('', '', '', '');
    $main->input('checkbox', 'passOn', '1', '', '', '', '', '');
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
    
    $main->div('','buttonWrapper');
    $main->input('hidden', 'submitApplication', '1', '', '', '', '', '');
    $main->input('Submit', '', 'Submit Your Application', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();
  }
$main->_div();
?>
