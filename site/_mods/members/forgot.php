<?php
$UPDATED = false;
$ERROR = false;

//If there is a POST then process it
if (isset($_POST['recoverPassword'])) {
  if ($_POST['username'] != '') {
    
    // look up password if username is recognised
    if (!get_magic_quotes_gpc()) {
      $_POST['username'] = addslashes($_POST['username']);
    }
    
    $sql_member = "SELECT mod_members_users_ID, mod_members_users_Username, mod_members_users_Password FROM mod_members_users WHERE mod_members_users_Username='{$_POST['username']}'";
    $result_member = $db->sql_query($sql_member);  
    // If no members by that username
    if ($db->sql_numrows($result_member) != 0) { 
      $row_member = $db->sql_fetchrow($result_member);
      // Email Member
      $TO = $row_member['mod_members_users_Username'];
      $CC = '';
      $BCC = 'email-archive@palaeosoc.org';
      $FROM = '';
      $VAR_ARRAY = array();
      
      $VAR_ARRAY['MEMBER_ID'] = $row_member['mod_members_users_ID'];
      $VAR_ARRAY['password'] = $row_member['mod_members_users_Password'];
    
      emailMailer(1, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
      
      $UPDATED = true;
    } else {
      $ERROR = true;
    }
  } 
}

// Add Javascript
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');

$js->script('js','','
  $(document).ready(function(){
    $("#membersRecoverPasswordForm").validate();
  });
');


// Produce Page
$main = new xhtml;
$main->div ('membersRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Login">Return to Members Login</a>');
$main->_div();
$main->hx(2,'Members\'s - Recover Password','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the recovery of your password.');
$main->_p();
$main->div ('membersRecoverPassword','');
  $main->hx(3,'Recover Password','','');$main->_hx(3);
  $main->p('','');
  $main->add('To recover your password please enter your current username in the form below. When ready press the "Recover Password" button.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Your Password has been sent to you via Email.');        
      $main->_div();
    $main->_div();
    $main->br(1);
  }
  if ($ERROR) {
    $main->div('','errorWrapper');
      $main->div('','errorbox');
        $main->add('Username NOT Known!<br />Please try again.');        
      $main->_div();
    $main->_div();
    $main->br(1);
  }
  // Form
  $main->form('?mode=forgot','post','','membersRecoverPasswordForm','');
  
  // Username
  $main->hx(4,'Recover Password','','');$main->_hx(4);
  $main->table('', 'membersTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('Please enter your PalaeoSoc Members Username (this is in the form of an email address).');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="username">Username:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'username', '', '', '', 'required email', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'recoverPassword', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Recover Your Password', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();
?>
