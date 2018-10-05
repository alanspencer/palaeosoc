<?php
// Reset all Passwords
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

$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Members - Reset All Passwords','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the reseting of all members passwords.');
$main->_p();

if (!isset($_GET['process'])) { 
  $PROCESS = "sendEmail";
} else {
  $PROCESS = $_GET['process'];
}
switch ($PROCESS) {
  default:
  case "sendEmail":
  $main->div ('adminShopUploadMonographs','');
    $main->hx(3,'Send Emails to Members Affected?','','');$main->_hx(3);
    $main->form('?mode=members&amp;view=resetAllPasswords&amp;process=execute','POST','','adminMembersPasswordResetForm','');
    // Two Columns
    $main->div('','buttonWrapper');
    $main->add('Email Members: ');
    $main->input('checkbox', 'emailMembers', '1', '', '', '', '', '');
    $main->_div();
    $main->div('','buttonWrapper');
    $main->input('Submit', '', 'Reset All Passwords', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();
  $main->_div();
  break;
  
  case "execute":
      $main->div ('adminMembersResetAllPasswords','');
        $main->hx(3,'Processing DataBase','','');$main->_hx(3);
        
        $sql_members = "SELECT * FROM mod_members_users WHERE mod_members_users_Username!=''";
        $result_members = $db->sql_query($sql_members);  
        // Update
        $main->add('<ol>');
        while ($row_members = $db->sql_fetchrow($result_members)) {
          $password = generatePassword();
          $db->sql_query("UPDATE mod_members_users SET mod_members_users_Password='$password' WHERE mod_members_users_ID='{$row_members['mod_members_users_ID']}'");
          
          $main->add('<li>Member '.$row_members['mod_members_users_Username'].' has been updated.</li>');
          
          if ((isset($_POST['emailMembers'])) AND ($_POST['emailMembers'] == 1)) {
            // Email Member
            $TO = $row_members['mod_members_users_Username'];
            $CC = '';
            $BCC = 'email-archive@palaeosoc.org';
            $FROM = '';
            $VAR_ARRAY = array();
            
            $VAR_ARRAY['MEMBER_ID'] = $row_members['mod_members_users_ID'];
            $VAR_ARRAY['password'] = $password;
          
            emailMailer(3, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
            
          }
        }
        $main->add('</ol>');
      $main->_div();
  break;
}


?>
