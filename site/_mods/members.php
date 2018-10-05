<?php

// Set Members Wide CSS Style

function membersConfig ($VAR, $db) { 
	$sql_file_config = "SELECT * FROM mod_members_config WHERE mod_members_config_Var='$VAR'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config_num = $db->sql_numrows($result_file_config); 
  if ($row_file_config_num != 0) {
    $row_file_config = $db->sql_fetchrow($result_file_config);
    return $row_file_config['mod_members_config_Val']; 
  } else {
    return false;
  }
}

$STYLE_COLOR = membersConfig('DEFAULT_STYLE_COLOR', $db);
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

// Page Title
$pageTitle = ' - Members';

// If Admin Logged In
if ($MEMBERVERIFIED) {
  if (!isset($_GET['mode'])) { 
    $MODE = "dashboard";
  } else {
    $MODE = $_GET['mode'];
  }
  
  switch ($MODE) {
    default:
    case 'account':
      include(dirname(__FILE__).'/members/account.php');
    break;
    
    case 'myDetails':
      switch ($_GET['view']) {
        case 'username':
          include(dirname(__FILE__).'/members/membersChangeUsername.php');
        break;
        
        case 'password':
          include(dirname(__FILE__).'/members/membersChangePassword.php');
        break;
        
        case 'personal':
          include(dirname(__FILE__).'/members/membersEditPersonal.php');
        break;
      }
    break;
    
    case 'subscription':
      switch ($_GET['view']) {
        case 'renewal':
          include(dirname(__FILE__).'/members/membersSubRenewal.php');
        break;
        
        case 'cancel':
          include(dirname(__FILE__).'/members/membersSubCancel.php');
        break;
      }
    break;
    
    case 'shop':
      switch ($_GET['view']) {
        case 'completedOrders':
        case 'currentOrders':
        case 'allOrders':
          include(dirname(__FILE__).'/members/membersShopOrders.php');
        break;
        
        case 'order':
          include(dirname(__FILE__).'/members/membersShopOrderView.php');
        break;
      }
    break;
    
    case 'logout':
      unset($_SESSION['MEMBER_UN'], $_SESSION['MEMBER_PW'], $_SESSION['MEMBER_ID']);
    	$MEMBERVERIFIED = 0;
    	header ("Location: ".__SITEURL."members/account/?status=loggedOut");
    	die();
    break;
    
    case "renew":
      if (!isset($_GET['membership'])) { 
        $MEMBERSHIP = "individual";
      } else {
        $MEMBERSHIP = $_GET['membership'];
      }
      switch ($MEMBERSHIP) {
        default:
        case "individual":
          include(dirname(__FILE__).'/members/renewIndividual.php');
        break;
        
        case "institution":
          include(dirname(__FILE__).'/members/renewInstitution.php');
        break;
      }
    break;
  }
  
// If Member Not Logged In
} else {
  if (!isset($_GET['mode'])) { 
    $MODE = "login";
  } else {
    $MODE = $_GET['mode'];
  }
  switch ($MODE) {
    default:
    case"login":
      // Get Login Page
      include(dirname(__FILE__).'/members/login.php');
    break;
    
    case 'forgot':
      include(dirname(__FILE__).'/members/forgot.php');
    break;
    
    case "join":
      if (!isset($_GET['membership'])) { 
        $MEMBERSHIP = "individual";
      } else {
        $MEMBERSHIP = $_GET['membership'];
      }
      switch ($MEMBERSHIP) {
        default:
        case "individual":
          include(dirname(__FILE__).'/members/joinIndividual.php');
        break;
        
        case "institution":
          include(dirname(__FILE__).'/members/joinInstitution.php');
        break;
        
        case "studentApplicationForm":
          include(dirname(__FILE__).'/members/studentApplicationForm.php');
        break;
      }
    break;
    
    case "renew":
      if (!isset($_GET['membership'])) { 
        $MEMBERSHIP = "individual";
      } else {
        $MEMBERSHIP = $_GET['membership'];
      }
      switch ($MEMBERSHIP) {
        default:
        case "individual":
          include(dirname(__FILE__).'/members/renewIndividual.php');
        break;
        
        case "institution":
          include(dirname(__FILE__).'/members/renewInstitution.php');
        break;
      }
    break;
  }
}