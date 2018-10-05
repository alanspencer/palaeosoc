<?php
// Get Default Config for page
function pageConfig ($VAR, $db) { 
	$sql_file_config = "SELECT * FROM mod_page_config WHERE mod_page_config_Var='$VAR'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config_num = $db->sql_numrows($result_file_config); 
  if ($row_file_config_num != 0) {
    $row_file_config = $db->sql_fetchrow($result_file_config);
    return $row_file_config['mod_page_config_Val']; 
  } else {
    return false;
  }
}

/* What we get sent
  $_GET['pageSection']
  $_GET['pageName']
*/

// Work out what kind of page we should be showing Section or Page
$SECTIONONLY = false;
if ((isset($_GET['pageSection'])) AND ($_GET['pageSection'] != '')) {
  $_GET['pageSection'] = strtolower($_GET['pageSection']);
  // We have a Section need to check if section exsists
  $sec_sql = "SELECT * FROM mod_page_section WHERE mod_page_section_Name='{$_GET['pageSection']}'";
  $sec_result = $db->sql_query($sec_sql);
  if ($db->sql_numrows($sec_result) == 0) {
    // Return 404
    header ("Location: ".__SITEURL."404/");
    die();
  } else {
    $sec_row = $db->sql_fetchrow($sec_result);
    if ((isset($_GET['pageName'])) AND ($_GET['pageName'] != '')) {
      $_GET['pageName'] = strtolower($_GET['pageName']);
      // We have a Page need to check if page exsists and is part of the section
      $page_sql = "SELECT * FROM mod_page_content WHERE mod_page_content_PageName='{$_GET['pageName']}' AND mod_page_content_Section='{$_GET['pageSection']}'";
      $page_result = $db->sql_query($page_sql);
      if ($db->sql_numrows($page_result) == 0) {
        // Return 404
        header ("Location: ".__SITEURL."404/");
        die();
      } else {
        // We have a valid page
        $page_row = $db->sql_fetchrow($page_result);
      }
    } else {
      $SECTIONONLY = true;
    }
  }
}

if ($SECTIONONLY) {
  // SECTION ONLY

  // Page Colour
  if ($sec_row['mod_page_section_PageColor'] == 'Random') {
    // Ramdom Color
    $style_sql = "SELECT * FROM style_color WHERE style_color_IncludeInRandom='1' ";
    $style_sql .="ORDER BY Rand() LIMIT 1";
    $style_result = $db->sql_query($style_sql);
    $style_row = $db->sql_fetchrow($style_result);
    $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'&ampstyle=a', '', 'screen');
  } elseif (($sec_row['mod_page_section_PageColor'] != 'Default') AND ($sec_row['mod_page_section_PageColor'] != '')) {
    // Set Page Mod Page Color
    $style_sql = "SELECT * FROM style_color WHERE style_color_Name='{$sec_row['mod_page_section_PageColor']}'";
    $style_result = $db->sql_query($style_sql);
    $style_row = $db->sql_fetchrow($style_result);
    $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'&ampstyle=b', '', 'screen');
  } else {
    $DEFAULT_STYLE_COLOR = pageConfig('DEFAULT_STYLE_COLOR', $db);
    if ($DEFAULT_STYLE_COLOR == 'Random') {
      // Ramdom Color
      $style_sql = "SELECT * FROM style_color WHERE style_color_IncludeInRandom='1' ";
      $style_sql .="ORDER BY Rand() LIMIT 1";
      $style_result = $db->sql_query($style_sql);
      $style_row = $db->sql_fetchrow($style_result);
      $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'&ampstyle=c', '', 'screen');
    } elseif (($DEFAULT_STYLE_COLOR != 'Default') AND ($DEFAULT_STYLE_COLOR != '')) {
      // Set Page Mod Page Color
      $style_sql = "SELECT * FROM style_color WHERE style_color_Name='$DEFAULT_STYLE_COLOR'";
      $style_result = $db->sql_query($style_sql);
      $style_row = $db->sql_fetchrow($style_result);
      $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'&ampstyle=d', '', 'screen');
    } else {
      // Default Site Color
      $css->link('css', __SITEURL.'css/main.css.php?style=e', '', 'screen');
    }
  }
  
  $main = new xhtml;
  
  // Page Title
  $sec_row['mod_page_section_Title'] = html_entity_decode($sec_row['mod_page_section_Title']);
  $sec_row['mod_page_section_Title'] == '' ? $pageTitle = 'Undefined Page' : $pageTitle = $sec_row['mod_page_section_Title'];
  $pageTitle = ' - '.$pageTitle.' (Overview)';
  
  $main->div('pageOverviewTemplate','');
  
  // Get all pages under this section an list alfabetically
  $page_sql = "SELECT * FROM mod_page_content WHERE mod_page_content_Section='{$_GET['pageSection']}' ORDER BY mod_page_content_TopTitle ASC";
  $page_result = $db->sql_query($page_sql);
  $page_num = $db->sql_numrows($page_result);
  
  // Top Text
  $main->div('pageOverviewTemplateTop','');
    // right return link
    $main->div ('pageRetrunLinksTop','');
    $main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a>');
    $main->_div(); 
    $sec_row['mod_page_section_Description'] = html_entity_decode($sec_row['mod_page_section_Description']);
    $main->hx(2,$sec_row['mod_page_section_Title'].' (Overview)','','');$main->_hx(2);              
    $main->add('<p>'.$sec_row['mod_page_section_Description'].'</p><p>Listed below are all '.$page_num.' pages included in the "'.$sec_row['mod_page_section_Title'].'" section.</p>');
  $main->_div();
  
  $main->hx(3,'Page List','','');$main->_hx(3); 
  
  if ($page_num  == 0) {
    $main->add('<p>Sorry. No pages could be found in this section. <a href="'.__SITEURL.'home/" title="Link: Return to Home Page">Return to Home Page</a></p>');
  } else {
    
    $qry = "SELECT mod_page_content_PageName, mod_page_content_TopTitle, LEFT(mod_page_content_TopTitle, 1) AS first_char FROM mod_page_content
    WHERE (UPPER(mod_page_content_TopTitle) BETWEEN 'A' AND 'Z'
    OR mod_page_content_TopTitle BETWEEN '0' AND '9') AND mod_page_content_Section='{$_GET['pageSection']}' ORDER BY mod_page_content_TopTitle";

    $result = $db->sql_query($qry);
    $int_set = false;
    $AFLAPAGES = array();
    while ($row = $db->sql_fetchrow($result)) {
      $AFLAPAGES[strtoupper($row['first_char'])][] = '<a href="'.__SITEURL.'page/'.$_GET['pageSection'].'/'.$row['mod_page_content_PageName'].'/" title="Link: '.$row['mod_page_content_TopTitle'].'">'.$row['mod_page_content_TopTitle'].'</a>';
    } 
    
    // 0-9 to J
    $col1 = '';
    $col1list = '';
    foreach (range(0, 9) as $number) {
      if (!empty($AFLAPAGES[$number])) {
        foreach ($AFLAPAGES[$number] as $key => $val) {
          $col1list .= '<li>'.$val.'</li>';
        }
      }
    }
    if ($col1list == '') {
      $col1 .= "<h4 class=\"empty\">0-9</h4>\n";
    } else {
      $col1 .= "<h4 class=\"full\">0-9</h4>\n";
    }
    $col1 .= "<ul>$col1list</ul>\n";
    foreach (range('A', 'H') as $letter) {
      $col1list = '';  
      if (!empty($AFLAPAGES[$letter])) {
        foreach ($AFLAPAGES[$letter] as $key => $val) {
          $col1list .= '<li>'.$val.'</li>';
        }
        $col1 .= "<h4 class=\"full\">$letter</h4>\n";
      } else {
        $col1 .= "<h4 class=\"empty\">$letter</h4>\n";
      }
      $col1 .= "<ul>$col1list</ul>\n";
    }
    
    $col2 = '';
    foreach (range('I', 'Q') as $letter) {
      $col2list = '';  
      if (!empty($AFLAPAGES[$letter])) {
        foreach ($AFLAPAGES[$letter] as $key => $val) {
          $col2list .= '<li>'.$val.'</li>';
        }
        $col2 .= "<h4 class=\"full\">$letter</h4>\n";
      } else {
        $col2 .= "<h4 class=\"empty\">$letter</h4>\n";
      }
      $col2 .= "<ul>$col2list</ul>\n";
    }
    
    $col3 = '';
    foreach (range('R', 'Z') as $letter) {
      $col3list = '';  
      if (!empty($AFLAPAGES[$letter])) {
        foreach ($AFLAPAGES[$letter] as $key => $val) {
          $col3list .= '<li>'.$val.'</li>';
        }
        $col3 .= "<h4 class=\"full\">$letter</h4>\n";
      } else {
        $col3 .= "<h4 class=\"empty\">$letter</h4>\n";
      }
      $col3 .= "<ul>$col3list</ul>\n";
    }
    
    // Three Cols
    $main->div('pageOverviewTemplateCols','yui-gb');
      $main->div('pageOverviewTemplateCol1','yui-u first');
      $main->add($col1);
  	  $main->_div();
      $main->div('pageOverviewTemplateCol2','yui-u');
      $main->add($col2);
      $main->_div();
      $main->div('pageOverviewTemplateCol3','yui-u');
      $main->add($col3);
      $main->_div();
    $main->_div();
  }
  $main->_div();
  
} else {
  // PAGE

  // Page Colour
  if ($page_row['mod_page_content_PageColor'] == 'Random') {
    // Ramdom Color
    $style_sql = "SELECT * FROM style_color WHERE style_color_IncludeInRandom='1' ";
    $style_sql .="ORDER BY Rand() LIMIT 1";
    $style_result = $db->sql_query($style_sql);
    $style_row = $db->sql_fetchrow($style_result);
    $_SESSION['style']['page'] = $style_row['style_color_ID'];
    $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
  } elseif (($page_row['mod_page_content_PageColor'] != 'Default') AND ($page_row['mod_page_content_PageColor'] != '')) {
    // Set Page Mod Page Color
    $style_sql = "SELECT * FROM style_color WHERE style_color_Name='{$page_row['mod_page_content_PageColor']}'";
    $style_result = $db->sql_query($style_sql);
    $style_row = $db->sql_fetchrow($style_result);
    $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
  } else {
    if ($sec_row['mod_page_section_PageColor'] == 'Random') {
      // Ramdom Color
      $style_sql = "SELECT * FROM style_color WHERE style_color_IncludeInRandom='1' ";
      $style_sql .="ORDER BY Rand() LIMIT 1";
      $style_result = $db->sql_query($style_sql);
      $style_row = $db->sql_fetchrow($style_result);
      $_SESSION['style']['page'] = $style_row['style_color_ID'];
      $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
    } elseif (($sec_row['mod_page_section_PageColor'] != 'Default') AND ($sec_row['mod_page_section_PageColor'] != '')) {
      // Set Page Mod Page Color
      $style_sql = "SELECT * FROM style_color WHERE style_color_Name='{$sec_row['mod_page_section_PageColor']}'";
      $style_result = $db->sql_query($style_sql);
      $style_row = $db->sql_fetchrow($style_result);
      $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
    } else {
      $DEFAULT_STYLE_COLOR = pageConfig('DEFAULT_STYLE_COLOR', $db);
      if ($DEFAULT_STYLE_COLOR == 'Random') {
        // Ramdom Color
        $style_sql = "SELECT * FROM style_color WHERE style_color_IncludeInRandom='1' ";
        $style_sql .="ORDER BY Rand() LIMIT 1";
        $style_result = $db->sql_query($style_sql);
        $style_row = $db->sql_fetchrow($style_result);
        $_SESSION['style']['page'] = $style_row['style_color_ID'];
        $css->link('css', __SITEURL.'_style/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
      } elseif (($DEFAULT_STYLE_COLOR != 'Default') AND ($DEFAULT_STYLE_COLOR != '')) {
        // Set Page Mod Page Color
        $style_sql = "SELECT * FROM style_color WHERE style_color_Name='$DEFAULT_STYLE_COLOR'";
        $style_result = $db->sql_query($style_sql);
        $style_row = $db->sql_fetchrow($style_result);
        $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
      } else {
        // Default Site Color
        $css->link('css', __SITEURL.'css/main.css.php', '', 'screen');
      }
    }
  }
  
  
  // PalSoc page Page Module
  $main = new xhtml;
  
  // Page Title
  $page_row['mod_page_content_TopTitle'] = html_entity_decode($page_row['mod_page_content_TopTitle']);
  $page_row['mod_page_content_TopTitle'] == '' ? $pageTitle = 'Undefined Page' : $pageTitle = $page_row['mod_page_content_TopTitle'];
  $pageTitle = ' - '.$pageTitle;
  

  
  // Add info box if needed
  $page_row['mod_page_content_NoteStyle'] != '' ? $NOTETEMPLATE = $page_row['mod_page_content_NoteStyle'] : $NOTETEMPLATE = pageConfig('DEFAULT_INFO_BOX_STYLE', $db);
  $page_row['mod_page_content_NoteText'] = html_entity_decode($page_row['mod_page_content_NoteText']);
  
  if ($page_row['mod_page_content_NoteToggle']) {
    switch ($NOTETEMPLATE) {
      default:
      case 'normal':
        $main->div('pageTemplateNormal','');         
          $main->add($page_row['mod_page_content_NoteText']);
        $main->_div();
      break;
      
      case 'notice':
        $main->div('pageTemplateNormal','');
          $main->div('','title');
            $main->hx(2,'Notice','','');$main->_hx(2); 
          $main->_div();       
          $main->add($page_row['mod_page_content_NoteText']);
        $main->_div();
      break;
      
      case 'important':
        $main->div('pageTemplateImportant','');
          $main->div('','title');
            $main->hx(2,'Important','','');$main->_hx(2); 
          $main->_div();          
          $main->add($page_row['mod_page_content_NoteText']);
        $main->_div();
      break;
      
      case 'warning':
        $main->div('pageTemplateWarning','');
          $main->div('','title');
            $main->hx(2,'Warning!','','');$main->_hx(2);
          $main->_div();           
          $main->add($page_row['mod_page_content_NoteText']);
        $main->_div();
      break;
    }
  }
  
  // Get Template Style
  $page_row['mod_page_content_TopText'] = html_entity_decode($page_row['mod_page_content_TopText']);
  $page_row['mod_page_content_Col1Title'] = html_entity_decode($page_row['mod_page_content_Col1Title']);
  $page_row['mod_page_content_Col1Text'] = html_entity_decode($page_row['mod_page_content_Col1Text']);
  $page_row['mod_page_content_Col2Title'] = html_entity_decode($page_row['mod_page_content_Col2Title']);
  $page_row['mod_page_content_Col2Text'] = html_entity_decode($page_row['mod_page_content_Col2Text']);
  $page_row['mod_page_content_Col3Title'] = html_entity_decode($page_row['mod_page_content_Col3Title']);
  $page_row['mod_page_content_Col3Text'] = html_entity_decode($page_row['mod_page_content_Col3Text']);
  $page_row['mod_page_content_BottomTitle'] = html_entity_decode($page_row['mod_page_content_BottomTitle']);
  $page_row['mod_page_content_BottomText'] = html_entity_decode($page_row['mod_page_content_BottomText']);
  
  $page_row['mod_page_content_Template'] != '' ? $PAGETEMPLATE = $page_row['mod_page_content_Template'] : $PAGETEMPLATE = pageConfig('DEFAULT_TEMPLATE_STYLE', $db);
  
  switch ($PAGETEMPLATE) {
    default:
    case 'style1':
      /*  
    
      --------------------------
      | Top Text                |
      |                         |   
      |                         |
      |                         |
      ---------------------------
      
      */
      
      $main->div('pageTemplateStyle1','');
      
      // Top Text
      $main->div('pageTemplateTop',''); 
        // right return link
        $main->div ('pageRetrunLinksTop','');
        $main->add('<a href="'.__SITEURL.'home/" title="Link: Home Page">Home Page</a> > <a href="'.__SITEURL.'page/'.$_GET['pageSection'].'/" title="Link: '.$sec_row['mod_page_section_Title'].' (Overview)">'.$sec_row['mod_page_section_Title'].' (Overview)</a>');
        $main->_div();
        if ($page_row['mod_page_content_TopTitle'] != '') {
          $main->hx(2,$page_row['mod_page_content_TopTitle'],'','');$main->_hx(2);
        }          
        $main->add($page_row['mod_page_content_TopText']);
      $main->_div();
  
      $main->_div();
    break;
    
    case 'style2':
      /*  
    
      --------------------------
      | Top Text                |
      |                         |   
      ---------------------------
      |    Col 1   |   Col 2    |
      ---------------------------
      
      */
      
      $main->div('pageTemplateStyle2','');
      
      // Top Text
      $main->div('pageTemplateTop','');
        // right return link
        $main->div ('pageRetrunLinksTop','');
        $main->add('<a href="'.__SITEURL.'home/" title="Link: Home Page">Home Page</a> > <a href="'.__SITEURL.'page/'.$_GET['pageSection'].'/" title="Link: '.$sec_row['mod_page_section_Title'].' (Overview)">'.$sec_row['mod_page_section_Title'].' (Overview)</a>');
        $main->_div();
        if ($page_row['mod_page_content_TopTitle'] != '') {
          $main->hx(2,$page_row['mod_page_content_TopTitle'],'','');$main->_hx(2);
        }          
             
        $main->add($page_row['mod_page_content_TopText']);
      $main->_div();
      
      // Three Cols
      $main->div('pageTemplateCols','yui-g');
        $main->div('pageTemplateCol1','yui-u first');
          if ($page_row['mod_page_content_Col1Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col1Title'],'','');$main->_hx(3);
          }                 
          $main->add($page_row['mod_page_content_Col1Text']);
    	  $main->_div();
        $main->div('pageTemplateCol2','yui-u');
          if ($page_row['mod_page_content_Col2Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col2Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col2Text']);
        $main->_div();
      $main->_div();
  
      $main->_div();
    break;
    
    case 'style3':
      /*  
    
      --------------------------
      | Top Text                |
      |                         |   
      ---------------------------
      |    Col 1   |   Col 2    |
      ---------------------------
      | Bottom Text             |
      |                         |
      ---------------------------
      
      */
      
      $main->div('pageTemplateStyle3','');
      
      // Top Text
      $main->div('pageTemplateTop',''); 
        // right return link
        $main->div ('pageRetrunLinksTop','');
        $main->add('<a href="'.__SITEURL.'home/" title="Link: Home Page">Home Page</a> > <a href="'.__SITEURL.'page/'.$_GET['pageSection'].'/" title="Link: '.$sec_row['mod_page_section_Title'].' (Overview)">'.$sec_row['mod_page_section_Title'].' (Overview)</a>');
        $main->_div();
        if ($page_row['mod_page_content_TopTitle'] != '') {
          $main->hx(2,$page_row['mod_page_content_TopTitle'],'','');$main->_hx(2);
        }          
             
        $main->add($page_row['mod_page_content_TopText']);
      $main->_div();
      
      // Three Cols
      $main->div('pageTemplateCols','yui-g');
        $main->div('pageTemplateCol1','yui-u first');
          if ($page_row['mod_page_content_Col1Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col1Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col1Text']);
    	  $main->_div();
        $main->div('pageTemplateCol2','yui-u');
          if ($page_row['mod_page_content_Col2Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col2Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col2Text']);
        $main->_div();
      $main->_div();
      
      //Bottom Text
      $main->div('pageTemplateBottom','');
        if ($page_row['mod_page_content_BottomTitle'] != '') {
          $main->hx(3,$page_row['mod_page_content_BottomTitle'],'','');$main->_hx(3);
        } 
        $main->add($page_row['mod_page_content_BottomText']);     
      $main->_div();
  
      $main->_div();
    break;
    
    case 'style4':
      /*  
    
      --------------------------
      | Top                     |
      |                         |
      ---------------------------
      | Col 1 |  Col 2 |  Col3  |
      |       |        |        |
      ---------------------------
      
      */
      
      $main->div('pageTemplateStyle4','');
      
      // Top Text
      $main->div('pageTemplateTop','');
        // right return link
        $main->div ('pageRetrunLinksTop','');
        $main->add('<a href="'.__SITEURL.'home/" title="Link: Home Page">Home Page</a> > <a href="'.__SITEURL.'page/'.$_GET['pageSection'].'/" title="Link: '.$sec_row['mod_page_section_Title'].' (Overview)">'.$sec_row['mod_page_section_Title'].' (Overview)</a>');
        $main->_div();
        if ($page_row['mod_page_content_TopTitle'] != '') {
          $main->hx(2,$page_row['mod_page_content_TopTitle'],'','');$main->_hx(2);
        }          
             
        $main->add($page_row['mod_page_content_TopText']);
      $main->_div();
      
      // Three Cols
      $main->div('pageTemplateCols','yui-gb');
        $main->div('pageTemplateCol1','yui-u first');
          if ($page_row['mod_page_content_Col1Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col1Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col1Text']);
    	  $main->_div();
        $main->div('pageTemplateCol2','yui-u');
          if ($page_row['mod_page_content_Col2Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col2Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col2Text']);
        $main->_div();
        $main->div('pageTemplateCol3','yui-u');
          if ($page_row['mod_page_content_Col3Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col3Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col3Text']);
        $main->_div();
      $main->_div();
      
      $main->_div();
    break;
    
    case 'style5':
      /*  
    
      --------------------------
      | Top Text                |
      |                         |   
      ---------------------------
      | Col 1 |  Col 2 |  Col3  |
      |       |        |        |
      ---------------------------
      | Bottom Text             |
      |                         |
      ---------------------------
      
      */
      
      $main->div('pageTemplateStyle5','');
      
      // Top Text
      $main->div('pageTemplateTop','');
        // right return link
        $main->div ('pageRetrunLinksTop','');
        $main->add('<a href="'.__SITEURL.'home/" title="Link: Home Page">Home Page</a> > <a href="'.__SITEURL.'page/'.$_GET['pageSection'].'/" title="Link: '.$sec_row['mod_page_section_Title'].' (Overview)">'.$sec_row['mod_page_section_Title'].' (Overview)</a>');
        $main->_div(); 
        if ($page_row['mod_page_content_TopTitle'] != '') {
          $main->hx(2,$page_row['mod_page_content_TopTitle'],'','');$main->_hx(2);
        }          
             
        $main->add($page_row['mod_page_content_TopText']);
      $main->_div();
      
      // Three Cols
      $main->div('pageTemplateCols','yui-gb');
        $main->div('pageTemplateCol1','yui-u first');
          if ($page_row['mod_page_content_Col1Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col1Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col1Text']);
    	  $main->_div();
        $main->div('pageTemplateCol2','yui-u second');
          if ($page_row['mod_page_content_Col2Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col2Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col2Text']);
        $main->_div();
        $main->div('pageTemplateCol3','yui-u');
          if ($page_row['mod_page_content_Col3Title'] != '') {
            $main->hx(3,$page_row['mod_page_content_Col3Title'],'','');$main->_hx(3);
          } 
          $main->add($page_row['mod_page_content_Col3Text']);
        $main->_div();
      $main->_div();
      
      //Bottom Text
      $main->div('pageTemplateBottom','');
        if ($page_row['mod_page_content_BottomTitle'] != '') {
            $main->hx(3,$page_row['mod_page_content_BottomTitle'],'','');$main->_hx(3);
          } 
        $main->add($page_row['mod_page_content_BottomText']);     
      $main->_div();
  
      $main->_div();
    break;
  }
}
?>
