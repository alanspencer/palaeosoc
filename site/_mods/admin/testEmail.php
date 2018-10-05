<?php

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Administration - Scripts','','');$main->_hx(2);
$main->p('','');
$main->add('Testing Email.');
$main->_p();
$main->div ('adminTestingEmail','');
  $main->hx(3,'View Admins','','');$main->_hx(3);
  $main->p('','');
  $main->add('An Email has been sent to the test account.');
  $main->_p();  
$main->_div(); 

// Send Email
$TO = 'a.spencer09@imperial.ac.uk';
$CC = '';
$BCC = '';
$FROM = '';
$VAR_ARRAY = array();

emailMailer(37, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db); 
?>
