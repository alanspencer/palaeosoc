<?php
// Get Config for module

function shopConfig ($VAR, $db) {
	$sql_file_config = "SELECT * FROM mod_shop_config WHERE mod_shop_config_Var='$VAR'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config_num = $db->sql_numrows($result_file_config); 
  if ($row_file_config_num != 0) {
    $row_file_config = $db->sql_fetchrow($result_file_config);
    return $row_file_config['mod_shop_config_Val']; 
  } else {
    return false;
  }
}


$STYLE_COLOR = shopConfig('STYLE_COLOR', $db);
if ($STYLE_COLOR == 'Random') {
  // Ramdom Color
  $style_sql = "SELECT * FROM style_color WHERE style_color_IncludeInRandom='1' ";
  $style_sql .="ORDER BY Rand() LIMIT 1";
  $style_result = $db->sql_query($style_sql);
  $style_row = $db->sql_fetchrow($style_result);
  $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
 } elseif (($STYLE_COLOR != 'Default') AND ($STYLE_COLOR != '')) {
  // Set Home Page Color
  $style_sql = "SELECT * FROM style_color WHERE style_color_Name='$STYLE_COLOR'";
  $style_result = $db->sql_query($style_sql);
  $style_row = $db->sql_fetchrow($style_result);
  $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
} else {
  // Default Site Color
  $css->link('css', __SITEURL.'css/main.css.php', '', 'screen');
}

$main = new xhtml;
/*
if (isset($_GET['view'])) {
  $VIEW = $_GET['view'];
  switch ($VIEW) {
    default:
      include(dirname(__FILE__).'/shop/shopHome.php');
    break;
    
    case 'myBasket':
      include(dirname(__FILE__).'/shop/shopMyBasket.php');
    break;
    
    case 'postage':
      include(dirname(__FILE__).'/shop/shopPostage.php');
    break;
    
    case 'checkout':
      include(dirname(__FILE__).'/shop/shopCheckout.php');
    break;
  }
} else {
  if (!isset($_GET['shopSection'])) { 
    $SHOPSECTION = "home";
  } else {
    $SHOPSECTION = $_GET['shopSection'];
  }

  switch ($SHOPSECTION) {
    default:
    case 'home':
      include(dirname(__FILE__).'/shop/shopHome.php');
    break;
    
    case 'monographs':
      include(dirname(__FILE__).'/shop/shopMonographs.php');
    break;  
  }
}
*/

$pageTitle = ' - '.shopConfig('PAGE_TITLE', $db);

$main->div ('pageRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a>');
$main->_div();

$main->div('shopTemplateStyle1','');

$main->div('homeTemplateImportant','');
$main->div('','title');
$main->hx(2,'Important - Shop Closed','','');$main->_hx(2);
$main->_div();
$main->add('The monographs of the Palaeontographical Society can now be found and bought from <a href="https://www.tandfonline.com/tmps20" target="_blank">Taylor and Francis</a>.');
$main->_div();

$main->_div();
$main->_div();