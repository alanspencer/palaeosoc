<?php

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Members - View Members','','');$main->_hx(2);
$main->p('','');
$main->add('View and select Members to edit/delete.');
$main->_p();
$main->div ('adminMembersViewMembers','');
  $main->hx(3,'View Members','','');$main->_hx(3);
  $main->p('','');
  $main->add('Using the list of current members below you may either choose to Edit a member 
  - by clicking on the "Edit" link in the "Options" column - OR Delete a member 
  - by clicking on the "Delete" link in the "Options" column. 
  To add a new member follow this link: <a href="?mode=members&amp;view=addMember" title="Link: New Member">New Member</a>.');
  $main->_p();
  
  // View Admins
  $main->hx(4,'Accounts','','');$main->_hx(4);
  $main->table('', 'adminTable');
  
  $main->thead('', '');
  $main->tr('', '');
  $main->th('', '', '', '');$main->add('Full Name');$main->_th();
  $main->th('', '', '', '');$main->add('Username');$main->_th();
  $main->th('', '', '', '');$main->add('Sub Year');$main->_th();
  $main->th('', '', '', '');$main->add('Type');$main->_th();
  $main->th('', '', '', '');$main->add('Options');$main->_th();
  $main->_tr();
  $main->_thead();
  
  $main->tbody('', '');
  
  $sql_member = "SELECT * FROM mod_members_users ORDER BY mod_members_users_LastName ASC";
  $result_member = $db->sql_query($sql_member);
  while ($row_member = $db->sql_fetchrow($result_member)) {
    if ($row_member['mod_members_users_Username'] == '') {
      $main->tr('', 'membersNoUsername');
    } else {
      $main->tr('', '');
    }
    $main->td('', '', '', '');$main->add($row_member['mod_members_users_Title'].' '.$row_member['mod_members_users_LastName'].', '.$row_member['mod_members_users_FirstNames']);$main->_td();
    if ($row_member['mod_members_users_Username'] == '') {
      $main->td('', 'membersNoUsernameText', '', '');$main->add('No Username/Email Address!');$main->_td();
    } else {
      $main->td('', '', '', '');$main->add($row_member['mod_members_users_Username']);$main->_td();
    }
    if ($row_member['mod_members_users_SubYear'] < date("Y")) {
      $main->td('', 'expired', '', '');$main->add($row_member['mod_members_users_SubYear']);$main->_td();
    } else {
      $main->td('', '', '', '');$main->add($row_member['mod_members_users_SubYear']);$main->_td();
    }
    $main->td('', '', '', '');$main->add($row_member['mod_members_users_Type']);$main->_td();
    $main->td('', '', '', '');
    $main->add('<a href="mailto:'.$row_member['mod_members_users_Username'].'" title="Email: '.$row_member['mod_members_users_Username'].'">Email</a> | ');
    $main->add('<a href="?mode=members&amp;view=editMember&amp;id='.$row_member['mod_members_users_ID'].'" title="Link: Edit Member">Edit</a>');
    $main->add(' | <a href="?mode=members&amp;view=deleteMember&amp;id='.$row_member['mod_members_users_ID'].'" title="Link: Delete Member">Delete</a>');
    $main->_td();
    $main->_tr();
  }
  $main->_tbody();
  $main->_table();
  
$main->_div();  
?>
