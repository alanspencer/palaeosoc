<?php
// Login - Administration
$main = new xhtml;

$main->div('adminLoginTemplate','');
  // Login Box
  $main->div('adminLoginBox','');
    $main->hx(2,'Administration Login','','');$main->_hx(2);
    $main->br(1);
    if ((isset($_GET['status'])) AND ($_GET['status'] == 'login')) {
      $main->div('adminLoginError','');
      $main->add('Your Username and/or Password combination could not be validated. Please try again.');
      $main->_div();
    }         
    $main->form('?status=login','POST','','adminLoginForm','');
    $main->br(1);
    $main->add('Username:&nbsp;');
    $main->input('text', 'ADMIN_UN', '', '', '', '', '', '');
    $main->br(2);
    $main->add('Password:&nbsp;');
    $main->input('password', 'ADMIN_PW', '', '', '', '', '', '');
    $main->br(2);
    $main->input('Submit', '', 'Login', '', '', '', '', '');
    $main->_form();
  $main->_div();
$main->_div();

?>
