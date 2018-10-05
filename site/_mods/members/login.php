<?php
// Login - Member
$main = new xhtml;

$main->div('memberLoginTemplate','');
  // Login Box
  $main->div('memberLoginBox','');
    $main->hx(2,'Members\' Login','','');$main->_hx(2);
    $main->br(1);
    if ((isset($_GET['status'])) AND ($_GET['status'] == 'login')) {
      $main->div('memberLoginError','');
      $main->add('Your Username and/or Password combination could not be validated. Please try again.');
      $main->_div();
    }
    if ((isset($_GET['status'])) AND ($_GET['status'] == 'loggedOut')) {
      $main->div('memberLoggedOut','');
      $main->add('You have logged out.');
      $main->_div();
    }          
    $main->form('?status=login','POST','','membersLoginForm','');
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
    $main->add('Have you forgotten your password? Follow this link to <a href="?mode=forgot" title="Recover your Password">Recover your Password</a>.');
    $main->_div();
  $main->_div();
  
  $main->br(2);
  
  $main->div('memberJoinBox','');
  $main->hx(3,'Join as a Member','','');$main->_hx(3);
  $main->br(1);
  // two cols
  $main->div('','yui-g');
    $main->div('','yui-u first center');
      $main->div('membersJoinIndividual','');
      $main->span('','');
      $main->add('<a href="'.__SITEURL.'members/join/?membership=individual" title="Link: Join as an Individual Member">');
      $main->add('<span class="hidden">Join as an Individual Member</span>');
      $main->add('</a>');
      $main->_span();
      $main->_div();
    $main->_div();
    $main->div('','yui-u center');
      $main->div('membersJoinInstitution','');
      $main->span('','');
      $main->add('<a href="'.__SITEURL.'members/join/?membership=institution" title="Link: Join as an Institutional Member">');
      $main->add('<span class="hidden">Join as an Institutional Member</span>');
      $main->add('</a>');
      $main->_span();
      $main->_div();
    $main->_div();
  $main->_div();
  $main->_div();
$main->_div();

?>
