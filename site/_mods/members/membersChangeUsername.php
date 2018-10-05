<?php
$UPDATED = false;
$ERROR = false;

//If there is a POST then process it
if (isset($_POST['changeUsername'])) {
  if (($_POST['username1'] != '') AND ($_POST['username2'] != '')) {
    // Email Member
    $TO = $_POST['username2'];
    $CC = $_SESSION['MEMBER_UN'];
    $BCC = 'email-archive@palaeosoc.org';
    $FROM = '';
    $VAR_ARRAY = array();
    
    $VAR_ARRAY['MEMBER_ID'] = $_SESSION['MEMBER_ID'];
    $VAR_ARRAY['old_username'] = $_SESSION['MEMBER_UN'];
    $VAR_ARRAY['new_username'] = $_POST['username2'];
  
    emailMailer(2, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
    
    if (!get_magic_quotes_gpc()) {
      $_POST['username1'] = addslashes($_POST['username1']);
    }
    // Check that username has not already been taken
    $sql_member = "SELECT mod_members_users_ID FROM mod_members_users WHERE mod_members_users_Username='{$_POST['username1']}'";
    $result_member = $db->sql_query($sql_member);  
    // If no admins by that username or password unset session
    if ($db->sql_numrows($result_member) == 0) { 
      $_SESSION['MEMBER_UN'] = $_POST['username2'];
      // Update Username
      $db->sql_query("UPDATE mod_members_users SET mod_members_users_Username='{$_POST['username1']}' WHERE mod_members_users_ID='{$_SESSION['MEMBER_ID']}'");
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
    $("#membersChangeUsernameForm").validate({
    rules: {
      username2: {
        equalTo: "#username1"
      }
    },
    messages: {
      username2: {
        equalTo: "Both usernames must match."
      }
    },
    });
  });
');


// Produce Page
$main = new xhtml;
$main->div ('membersRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Home">Return to Members Home</a>');
$main->_div();
$main->hx(2,'Members\'s Account - Change Username','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the alteration of your username.');
$main->_p();
$main->div ('membersChangeUsername','');
  $main->hx(3,'Change Username','','');$main->_hx(3);
  $main->p('','');
  $main->add('To update your username please enter a new username in the form below. When ready press the "Change Username" button.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Username Changed! Email Sent.<br /><a href="'.__SITEURL.'members/" title="Return to Members Home">Return to Members Home</a>');        
      $main->_div();
    $main->_div();
    $main->br(1);
  }
  if ($ERROR) {
    $main->div('','errorWrapper');
      $main->div('','errorbox');
        $main->add('Username NOT Changed!<br />This username is already taken. Please use another email address.');        
      $main->_div();
    $main->_div();
    $main->br(1);
  }
  
  // Form
  $main->form('?mode=myDetails&amp;view=username','post','','membersChangeUsernameForm','');
  
  // Username
  $main->hx(4,'Change Username','','');$main->_hx(4);
  $main->table('', 'membersTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>The username must be an e-mail address. This is where all e-mails will be sent.</em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Current Username:');$main->_td();
  $main->td('', '', '', '');
  $main->add($_SESSION['MEMBER_UN']);
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="username1">New Username:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'username1', '', '', 'username1', 'required email', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="username2">Re-type New Username:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'username2', '', '', 'username2', 'required email', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'changeUsername', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Change Username', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();
?>
