<?php
// Set Admin Wide CSS Style
$css->link('css', __SITEURL.'css/main.css.php', '', 'screen');

// Page Title
$pageTitle = ' - Administration';

// If Admin Logged In
if ($ADMINVERIFIED) {
  if (!isset($_GET['mode'])) { 
    $MODE = "dashboard";
  } else {
    $MODE = $_GET['mode'];
  }
  
  switch ($MODE) {
    default:
    case 'dashboard':
      include(dirname(__FILE__).'/admin/dashboard.php');
    break; 
    
    case 'logout':
      unset($_SESSION['ADMIN_UN'], $_SESSION['ADMIN_PW'], $_SESSION['ADMIN_ID']);
    	$ADMINVERIFIED = 0;
    	header ("Location: ".__SITEURL);
    	die();
    break;
    
    case 'siteWide':
      switch ($_GET['view']) {
        case 'colorSwatches':
          include(dirname(__FILE__).'/admin/colorSwatches.php');
        break;
        
        case 'viewNavigation':
          include(dirname(__FILE__).'/admin/viewNavigation.php');
        break;
        
        case 'editNavigation':
          include(dirname(__FILE__).'/admin/editNavigation.php');
        break;
        
        case 'deleteNavigation':
          include(dirname(__FILE__).'/admin/deleteNavigation.php');
        break;
        
        case 'addNavigation':
          include(dirname(__FILE__).'/admin/addNavigation.php');
        break;
      }
    break;
    
    case 'admin':
      switch ($_GET['view']) {
        case 'newAdmin':
          include(dirname(__FILE__).'/admin/adminNewAdmin.php');
        break;
        
        case 'viewAdmins':
          include(dirname(__FILE__).'/admin/adminViewAdmins.php');
        break;
        
        case 'editAdmin':
          include(dirname(__FILE__).'/admin/adminEditAdmin.php');
        break;
        
        case 'deleteAdmin':
          include(dirname(__FILE__).'/admin/adminDeleteAdmin.php');
        break;
      }
    break;
    
    case 'home':
      switch ($_GET['view']) {
        case 'alterTemplate':
          include(dirname(__FILE__).'/admin/homeAlterTemplate.php');
        break;
        
        case 'alterPageColor':
          include(dirname(__FILE__).'/admin/homeAlterPageColor.php');
        break;
        
        case 'alterExploreSite':
          include(dirname(__FILE__).'/admin/homeAlterExploreSite.php');
        break;
        
        case 'editAnnouncementBox':
          include(dirname(__FILE__).'/admin/homeEditAnnoucementBox.php');
        break;
        
        case 'editContents':
          include(dirname(__FILE__).'/admin/homeEditContents.php');
        break;
      }
    break;
    
    case 'page':
      switch ($_GET['view']) {
        case 'pageConfig':
          include(dirname(__FILE__).'/admin/pageConfig.php');
        break;
        
        case 'newPage':
          include(dirname(__FILE__).'/admin/pageNewPage.php');
        break;
        
        case 'newPageSimple':
          include(dirname(__FILE__).'/admin/pageNewPageSimple.php');
        break;
        
        case 'viewPages':
          include(dirname(__FILE__).'/admin/pageViewPages.php');
        break;
        
        case 'editPage':
          include(dirname(__FILE__).'/admin/pageEditPage.php');
        break;
        
        case 'deletePage':
          include(dirname(__FILE__).'/admin/pageDeletePage.php');
        break;
        
        case 'newSection':
          include(dirname(__FILE__).'/admin/pageNewSection.php');
        break;
        
        case 'viewSections':
          include(dirname(__FILE__).'/admin/pageViewSections.php');
        break;
        
        case 'editSection':
          include(dirname(__FILE__).'/admin/pageEditSection.php');
        break;
        
        case 'deleteSection':
          include(dirname(__FILE__).'/admin/pageDeleteSection.php');
        break;
      }
    break;

      /*
    case "shop":
      switch ($_GET['view']) {
        case 'uploadMonographs':
          include(dirname(__FILE__).'/admin/uploadMonographs.php');
        break;
        
        case 'orderManagement':
          include(dirname(__FILE__).'/admin/shopOrderManagement.php');
        break;
        
        case 'viewOrder':
          include(dirname(__FILE__).'/admin/shopViewOrder.php');
        break;
        
        case 'monographManagement':
          include(dirname(__FILE__).'/admin/shopMonographManagement.php');
        break;
        
        case 'addMonograph':
          include(dirname(__FILE__).'/admin/shopNewMonograph.php');
        break;
        
        case 'editMonograph':
          include(dirname(__FILE__).'/admin/shopEditMonograph.php');
        break;
        
        case 'shopConfig':
          include(dirname(__FILE__).'/admin/shopConfig.php');
        break;
        
        case 'shopTemplate':
          include(dirname(__FILE__).'/admin/shopTemplate.php');
        break;
        
        case 'editAnouncementBox':
          include(dirname(__FILE__).'/admin/shopEditAnnoucementBox.php');
        break;
        
        case 'editContent':
          include(dirname(__FILE__).'/admin/shopEditContents.php');
        break;
      }
    break;
    */

    case "members":
      switch ($_GET['view']) {
        case 'membersConfig':
          include(dirname(__FILE__).'/admin/membersConfig.php');
        break;
        
        case 'applicationManagement':
          include (dirname(__FILE__).'/admin/membersApplicationManagement.php');
        break;
        
        case 'viewApplication':
          include (dirname(__FILE__).'/admin/membersViewApplication.php');
        break;
        
        case 'downloadMembers':
          include (dirname(__FILE__).'/admin/membersDownload.php');
        break;
        
        case 'addMember':
          include (dirname(__FILE__).'/admin/membersNewMember.php');
        break;
        
        case 'viewMembers':
          include (dirname(__FILE__).'/admin/membersViewMembers.php');
        break;
        
        case 'editMember':
          include (dirname(__FILE__).'/admin/membersEditMember.php');
        break;
        
        case 'deleteMember':
          include (dirname(__FILE__).'/admin/membersDeleteMember.php');
        break;
        
        case 'resetAllPasswords':
          include (dirname(__FILE__).'/admin/membersResetPasswordsAll.php');
        break;
        
        case 'pricing':
          include (dirname(__FILE__).'/admin/membersViewPricing.php');
        break;
        
        case 'editPricing':
          include (dirname(__FILE__).'/admin/membersEditPricing.php');
        break;
      }
    break;
    
    case 'scripts':
      switch ($_GET['view']) {
        case 'testEmail':
          include(dirname(__FILE__).'/admin/testEmail.php');
        break;
      }
    break;
  
  }
  
// If Admin Not Logged In
} else {
  // Get Login Page
  include(dirname(__FILE__).'/admin/login.php');
}