<?php

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Members - View Pricing','','');$main->_hx(2);
$main->p('','');
$main->add('View and select Prices to edit.');
$main->_p();
$main->div ('adminMembersViewPicing','');
  $main->hx(3,'View Members','','');$main->_hx(3);
  $main->p('','');
  $main->add('Below is the list of current membership price options availiable to members.');
  $main->_p();
  
  // View Admins
  $main->hx(4,'Accounts Types','','');$main->_hx(4);
  $main->table('', 'adminTable');
  
  $main->thead('', '');
  $main->tr('', '');
  $main->th('', '', '', '');$main->add('Name');$main->_th();
  $main->th('', '', '', '');$main->add('Type');$main->_th();
  $main->th('', '', '', '');$main->add('Student?');$main->_th();
  $main->th('', '', '', '');$main->add('Price');$main->_th();
  $main->th('', '', '', '');$main->add('Options');$main->_th();
  $main->_tr();
  $main->_thead();
  
  $main->tbody('', '');
  
  $sql_membership = "SELECT * FROM mod_members_membership ORDER BY mod_members_membership_Price ASC";
  $result_membership = $db->sql_query($sql_membership);
  while ($row_membership = $db->sql_fetchrow($result_membership)) {
    $main->tr('', '');
    $main->td('', '', '', '');$main->add($row_membership['mod_members_membership_Name']);$main->_td();
    $main->td('', '', '', '');$main->add($row_membership['mod_members_membership_Type']);$main->_td();
    $main->td('', '', '', '');$main->add($row_membership['mod_members_membership_IsStudent']);$main->_td();
    $main->td('', '', '', '');$main->add($row_membership['mod_members_membership_Price']);$main->_td();
    $main->td('', '', '', '');
    $main->add('<a href="?mode=members&amp;view=editPricing&amp;id='.$row_membership['mod_members_membership_ID'].'" title="Link: Edit Pricing">Edit</a>');
    $main->_td();
    $main->_tr();
  }
  $main->_tbody();
  $main->_table();
  
$main->_div();  
?>
