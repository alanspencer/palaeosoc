<?php

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Pages - View Sections','','');$main->_hx(2);
$main->p('','');
$main->add('View and select Sections to edit.');
$main->_p();
$main->div ('adminPageViewSection','');
  $main->hx(3,'View Sections','','');$main->_hx(3);
  $main->p('','');
  $main->add('To add a new section follow this link: <a href="?mode=page&amp;view=newSection" title="Link: New Section">New Section</a>.');
  $main->_p();
  
  $main->hx(4,'Sections','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->thead('', '');
  $main->tr('', '');
  $main->th('', '', '', '');$main->add('Section Title');$main->_th();
  $main->th('', '', '', '');$main->add('Section Reference');$main->_th();
  $main->th('', '', '', '');$main->add('N&deg; Pages');$main->_th();
  $main->th('', '', '', '');$main->add('Options');$main->_th();
  $main->_tr();
  $main->_thead();  
  $main->tbody('', '');
  
  $sql_sec = "SELECT * FROM mod_page_section ORDER BY mod_page_section_Title ASC";
  $result_sec = $db->sql_query($sql_sec);
  $num_sec = $db->sql_numrows($result_sec);
  if ($num_sec != 0) {
    while ($row_sec = $db->sql_fetchrow($result_sec)) {
      // View Sections
      $main->tr('', '');
      $main->td('', 'sectionTitle', '', '');$main->add($row_sec['mod_page_section_Title']);$main->_td();
      $main->td('', 'sectionRef', '', '');$main->add($row_sec['mod_page_section_Name']);$main->_td();
      $main->td('', 'sectionNumPages', '', '');$main->add($row_sec['mod_page_section_NumPages']);$main->_td();
      $main->td('', '', '', '');
      $main->add('<a href="'.__SITEURL.'page/'.$row_sec['mod_page_section_Name'].'/" title="Link: View Section">View</a> | ');
      $main->add('<a href="?mode=page&amp;view=editSection&amp;id='.$row_sec['mod_page_section_ID'].'" title="Link: Edit Section">Edit</a>');
      $main->add(' | <a href="?mode=page&amp;view=deleteSection&amp;id='.$row_sec['mod_page_section_ID'].'" title="Link: Delete Section">Delete</a>');
      $main->_td();
      $main->_tr();
    }
    $main->_tbody();
    $main->_table();
  } else {
    $main->add('No sections found. <a href="?mode=page&view=newSection" title="Link: New Section">Add a new section</a>');
  }
$main->_div();  
?>
