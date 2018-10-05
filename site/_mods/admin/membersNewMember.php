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

$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['insertNewMember'])) {
  if (!get_magic_quotes_gpc()) {
    $_POST['title'] = addslashes($_POST['title']);
    $_POST['lastName'] = addslashes($_POST['lastName']);
    $_POST['firstNames'] = addslashes($_POST['firstNames']);
    $_POST['username'] = addslashes($_POST['username']);
    $_POST['password'] = addslashes($_POST['password']);
    $_POST['address1'] = addslashes($_POST['address1']);
    $_POST['address2'] = addslashes($_POST['address2']);
    $_POST['city'] = addslashes($_POST['city']);
    $_POST['state'] = addslashes($_POST['state']);
    $_POST['zip'] = addslashes($_POST['zip']);
    $_POST['country'] = addslashes($_POST['country']);
    $_POST['telephone'] = addslashes($_POST['telephone']);
  }
  
  $joinDate = date("d/m/Y");
  
  !isset($_POST['studentEndDate']) ? $_POST['studentEndDate'] = '' : null;
  
  !isset($_POST['newsletter']) ? $_POST['newsletter'] = 0 : null;
  !isset($_POST['passOn']) ? $_POST['passOn'] = 0 : null;
  // Add new member into members database
  $sql_insert = "INSERT INTO mod_members_users (
  mod_members_users_Username, mod_members_users_Password, mod_members_users_LastName,
  mod_members_users_FirstNames, mod_members_users_Title, mod_members_users_Address1,
  mod_members_users_Address2, mod_members_users_City, mod_members_users_State,
  mod_members_users_Zip, mod_members_users_Country, mod_members_users_Telephone,
  mod_members_users_Type, mod_members_users_StudentEnd, mod_members_users_JoinedOnline,
  mod_members_users_SubYear, mod_members_users_Newsletter, mod_members_users_PassOn
  ) VALUES (
  '{$_POST['username']}', '{$_POST['password']}', '{$_POST['lastName']}',
  '{$_POST['firstNames']}', '{$_POST['title']}', '{$_POST['address1']}',
  '{$_POST['address2']}', '{$_POST['city']}', '{$_POST['state']}',
  '{$_POST['zip']}', '{$_POST['country']}', '{$_POST['telephone']}',
  '{$_POST['membershipType']}', '{$_POST['studentEndDate']}', '$joinDate',
  '{$_POST['subYear']}', '{$_POST['newsletter']}', '{$_POST['passOn']}'
  )";
  $db->sql_query($sql_insert);
  $memberNumber = mysql_insert_id();
  
  if ($_POST['username'] != '') {
    // Send email to new member with username and password
    $TO = $_POST['username'];
    $CC = '';
    $BCC = 'email-archive@palaeosoc.org';
    $FROM = '';
    $VAR_ARRAY = array();
    
    $VAR_ARRAY['PASSWORD'] = $_POST['password'];
    $VAR_ARRAY['MEMBER_ID'] = $memberNumber;
    $VAR_ARRAY['USERNAME'] = $_POST['username'];
    emailMailer(30, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
  }
  $UPDATED = true;
}

// Add Javascript
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
$usernameArray = '';
$sql_selection = "SELECT mod_members_users_Username FROM mod_members_users WHERE mod_members_users_Username!=''";
$result_selection = $db->sql_query($sql_selection);
$x = 0;
while ($row_selection = $db->sql_fetchrow($result_selection)) {
  $usernameArray .= ' arrUsernames['.$x.'] = "'.$row_selection['mod_members_users_Username'].'";
  ';
  $x++;
}
$js->script('js','','
  var arrUsernames = new Array();
  '.$usernameArray.'
  function testUsername(value) {
    var test = true;
    $.each(
      arrUsernames,
      function( intIndex, objValue){
        if (value == objValue) {
          test = false;
        }
      }
    );
    if (test) {
      return true;
    } else {
      return false;
    }
  }
  jQuery.validator.addMethod("username", function(value, element) { 
    return this.optional(element) || testUsername(value); 
  }, "Please enter an unique Username. This username is already in use.");
  $(document).ready(function(){
    $("#adminNewMemberForm").validate({
      rules: {
        username: "username"
      }
    });
  });
');
$js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.core.min.js', '');
$js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.datepicker.min.js', '');
$js->script('js','','
  $(document).ready(function(){ 
    $("#membershipType").change(function(){
      message_index = $("#membershipType").val(); 
      $("#studentExtraField").empty(); 
      if (message_index == "Student") {
        $("#studentExtraField").append(\' Student Registration End Date: <input type="text" name="studentEndDate" id="studentEndDate" class="required" />\'); 
        $("#studentEndDate").datepicker();
      }
    });  
  });
');

$css->link('css', __SITEURL.'css/jquery/ui.core.css', '', 'screen');
$css->link('css', __SITEURL.'css/jquery/ui.datepicker.css', '', 'screen');
$css->link('css', __SITEURL.'css/jquery/ui.theme.css', '', 'screen');

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Members - New Member','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the addition of new Members to the PalaeoSoc members\' area.');
$main->_p();
$main->div ('adminMemberNewMember','');
  $main->hx(3,'Add New Member','','');$main->_hx(3);
  $main->p('','');
  $main->add('To add a new member to the PalaeoSoc members\' area the following form needs to be completed. Almost all fields are required. Once you have completed the form press the "Add New member" button. To return to the View Members page follow this link: <a href="?mode=members&amp;view=viewMembers" title="Link: View Members">View Members</a>.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('New Member Added! Member has been sent an email containing their username and password.<br /><a href="?mode=members&amp;view=viewMembers" title="Link: View Members">Retrun to View Members</a>');        
      $main->_div();
    $main->_div();
  }
  
  // Form
  $main->form('?mode=members&view=addMember','POST','','adminNewMemberForm','');
  
  
  // Personal Info
  $main->hx(4,'Membership Type','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Type:');$main->_td();
  $main->td('', '', '', '');
  !isset($_SESSION['application']['typeID']) ? null : $applicationMembershipType = $_SESSION['application']['typeID'];
  // Instutional or Institutional (Agency)
  $main->select('membershipType', 'membershipType', '', '', '');
    $main->option('Individual', 'Individual', '');
    $main->option('Student', 'Student', '');
  $main->_select();
  $main->span('studentExtraField','');$main->_span();
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="lastName">Membership Period:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->select('subYear', 'subYear', '', '', '');
    $main->option(date("Y"), date("Y"), '1');
    $main->option(date("Y")+1, date("Y")+1, '');
  $main->_select();
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  
  // Personal Info
  $main->hx(4,'Member\'s Name','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'title', '', '', '', 'inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="lastName">Last Name:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'lastName', '', '', '', 'required inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="firstNames">First Name(s):</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'firstNames', '', '', '', 'required inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  // Password & Username
  $main->hx(4,'Login Details','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>The username must be an e-mail address.</em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="username">Username/Email:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'username', '', '', 'username', 'email inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>The password must only contain alfa-numerical characters.</em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="password">Password:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'password',  generatePassword(), '', 'password', 'required inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  // Address
  $main->hx(4,'Address','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="address1">Address 2:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'address1', '', '', '', 'required inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="address2">Address 2:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'address2', '', '', '', 'inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="city">City:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'city', '', '', '', 'required inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="state">State/County:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'state', '', '', '', 'inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="zip">Zip/Post Code:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'zip', '', '', '', 'required inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="country">Country:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->select('country', '', '', '', '');
  $sql_country= "SELECT * FROM shop_country ORDER BY shop_country_Name ASC";
  $result_country = $db->sql_query($sql_country);
  while ($row_country = $db->sql_fetchrow($result_country)) {
    if ('GB'== $row_country['shop_country_ShortCode']) {
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
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="telephone">Telephone:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'telephone', '', '', '', 'inputFullWidth', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  // Options
  $main->hx(4,'Options','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', '', '', '');$main->add('Member DOES NOT want to receive Newsletters, Council Reports, and other News by email from the society:');$main->_td();
  $main->td('', '', '', '');
  $main->input('checkbox', 'newsletter', '1', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', '', '', '');$main->add('Member DOES NOT consent for their details being passed on to other members of the Society for purposes of palaeontological research:');$main->_td();
  $main->td('', '', '', '');
  $main->input('checkbox', 'passOn', '1', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'insertNewMember', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Add New Member', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();
?>
