<?php

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Administration - View Admins','','');$main->_hx(2);
$main->p('','');
$main->add('View and select Admins to edit.');
$main->_p();
$main->div ('adminAdminViewAdmins','');
  $main->hx(3,'View Admins','','');$main->_hx(3);
  $main->p('','');
  $main->add('Using the list of current admins below you may either choose to Edit an admin 
  - by clicking on the "Edit" link in the "Options" column - OR Delete an admin 
  - by clicking on the "Delete" link in the "Options" column. It should be noted that you can not delete your own administration account. 
  To add a new admin follow this link: <a href="?mode=admin&amp;view=newAdmin" title="Link: New Admin">New Admin</a>.');
  $main->_p();
  
  // View Admins
  $main->hx(4,'Accounts','','');$main->_hx(4);
  $main->table('', 'adminTable');
  
  $main->thead('', '');
  $main->tr('', '');
  $main->th('', '', '', '');$main->add('Full Name');$main->_th();
  $main->th('', '', '', '');$main->add('Username');$main->_th();
  $main->th('', '', '', '');$main->add('Options');$main->_th();
  $main->_tr();
  $main->_thead();
  
  $main->tbody('', '');
  
  $sql_admin = "SELECT * FROM mod_admin_users ORDER BY mod_admin_users_LastName ASC";
  $result_admin = $db->sql_query($sql_admin);
  while ($row_admin = $db->sql_fetchrow($result_admin)) {
    $main->tr('', '');
    $main->td('', '', '', '');$main->add($row_admin['mod_admin_users_Title'].' '.$row_admin['mod_admin_users_LastName'].', '.$row_admin['mod_admin_users_FirstName']);$main->_td();
    $main->td('', '', '', '');$main->add($row_admin['mod_admin_users_Username']);$main->_td();
    $main->td('', '', '', '');
    $main->add('<a href="mailto:'.$row_admin['mod_admin_users_Username'].'" title="Email: '.$row_admin['mod_admin_users_Username'].'">Email</a> | ');
    $main->add('<a href="?mode=admin&amp;view=editAdmin&amp;id='.$row_admin['mod_admin_users_ID'].'" title="Link: Edit Admin">Edit</a>');
    if ($_SESSION['ADMIN_ID'] != $row_admin['mod_admin_users_ID']) {
      $main->add(' | <a href="?mode=admin&amp;view=deleteAdmin&amp;id='.$row_admin['mod_admin_users_ID'].'" title="Link: Delete Admin">Delete</a>');
    }
    $main->_td();
    $main->_tr();
  }
  $main->_tbody();
  $main->_table();
  
$main->_div();  
?>
