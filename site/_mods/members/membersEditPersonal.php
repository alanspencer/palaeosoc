<?php
$UPDATED = false;

//If there is a POST then process it
if (isset($_POST['editPersonal'])) {
  if (!get_magic_quotes_gpc()) {
    $_POST['lastName'] = addslashes($_POST['lastName']);
    $_POST['firstNames'] = addslashes($_POST['firstNames']);
    $_POST['title'] = addslashes($_POST['title']);
    $_POST['address1'] = addslashes($_POST['address1']);
    $_POST['address2'] = addslashes($_POST['address2']);
    $_POST['city'] = addslashes($_POST['city']);
    $_POST['state'] = addslashes($_POST['state']);
    $_POST['zip'] = addslashes($_POST['zip']);
    $_POST['country'] = addslashes($_POST['country']);
    
    $_POST['telephone'] = addslashes($_POST['telephone']);
  }

  // Update Password
  $db->sql_query("UPDATE mod_members_users SET 
  mod_members_users_LastName='{$_POST['lastName']}',
  mod_members_users_FirstNames='{$_POST['firstNames']}',
  mod_members_users_Title='{$_POST['title']}',
  mod_members_users_Address1='{$_POST['address1']}',
  mod_members_users_Address2='{$_POST['address2']}',
  mod_members_users_City='{$_POST['city']}',
  mod_members_users_State='{$_POST['state']}',
  mod_members_users_Zip='{$_POST['zip']}',
  mod_members_users_Country='{$_POST['country']}',
  mod_members_users_Telephone='{$_POST['telephone']}',
  mod_members_users_Newsletter='{$_POST['newsletter']}',
  mod_members_users_PassOn='{$_POST['passOn']}'
  WHERE mod_members_users_ID='{$_SESSION['MEMBER_ID']}'");
  $UPDATED = true;
}

// Add Javascript
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');

$js->script('js','','
  $(document).ready(function(){
    $("#membersEditPersonalForm").validate();
  });
');


// Produce Page
$main = new xhtml;
$main->div ('membersRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Home">Return to Members Home</a>');
$main->_div();
$main->hx(2,'Members\'s Account - Edit Personal','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the alteration of your personal details (Name, Address, etc...).');
$main->_p();
$main->div ('membersEditPersonal','');
  $main->hx(3,'Edit Personal','','');$main->_hx(3);
  $main->p('','');
  $main->add('To update your personal details please use the form below. When ready press the "Save Changes" button.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Password Changed!<br /><a href="'.__SITEURL.'members/" title="Return to Members Home">Return to Members Home</a>');        
      $main->_div();
    $main->_div();
    $main->br(1);
  }
  
  // Form
  $main->form('?mode=myDetails&amp;view=personal','post','','membersEditPersonalForm','');
  
  $sql_member = "SELECT * FROM mod_members_users WHERE mod_members_users_ID='{$_SESSION['MEMBER_ID']}'";
  $result_member = $db->sql_query($sql_member);  
  $row_member = $db->sql_fetchrow($result_member); 
  // Username
  $main->hx(4,'Edit Name','','');$main->_hx(4);
  $main->table('', 'membersTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'title', $row_member['mod_members_users_Title'], '', 'title', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="lastName">Last Name:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'lastName', $row_member['mod_members_users_LastName'], '', 'lastName', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="firstNames">First Names:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'firstNames', $row_member['mod_members_users_FirstNames'], '', 'firstNames', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->hx(4,'Edit Address','','');$main->_hx(4);
  $main->table('', 'membersTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="address1">Address 1:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'address1', $row_member['mod_members_users_Address1'], '', 'address1', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="address2">Address 2:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'address2', $row_member['mod_members_users_Address2'], '', 'address2', '', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="city">City:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'city', $row_member['mod_members_users_City'], '', 'city', '', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="state">County/State:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'state', $row_member['mod_members_users_State'], '', 'state', '', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="address2">Post Code/Zip:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'zip', $row_member['mod_members_users_Zip'], '', 'zip', '', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="country">Country:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->select('country', 'country', '', '', '');
  $sql_country= "SELECT * FROM shop_country ORDER BY shop_country_Name ASC";
  $result_country = $db->sql_query($sql_country);
  while ($row_country = $db->sql_fetchrow($result_country)) {
    if ($row_member['mod_members_users_Country'] == $row_country['shop_country_ShortCode']) {
      $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '1');
    } else {
      $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '');
    }
  }
  $main->_select();
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  // Telephone
  $main->hx(4,'Telephone','','');$main->_hx(4);
  $main->table('', 'membersTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="title">Telphone:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'telephone', $row_member['mod_members_users_Telephone'], '', '', '', '', '');
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
  if ($row_member['mod_members_users_Newsletter']) {
    $main->input('checkbox', 'newsletter', '1', '1', '', '', '', '');      
  } else {
    $main->input('checkbox', 'newsletter', '1', '', '', '', '', '');
  }
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', '', '', '');$main->add('I DO NOT consent for my details being passed on to other members of the Society for purposes of palaeontological research:');$main->_td();
  $main->td('', '', '', '');
  if ($row_member['mod_members_users_PassOn']) {
    $main->input('checkbox', 'passOn', '1', '1', '', '', '', '');      
  } else {
    $main->input('checkbox', 'passOn', '1', '', '', '', '', '');
  }
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'editPersonal', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Save Changes', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();
?>
