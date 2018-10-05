<?php

// My Basket Page
// Return Link
$main->div ('pageRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a> > <a href="'.__SITEURL.'shop/home/" title="Return to Shop Home Page">Shop Home</a>');
$main->_div(); 

if (!isset($_GET['mode'])) { 
  $MODE = "customer";
} else {
  $MODE = $_GET['mode'];
}
switch ($MODE) {
  default:
  case "customer":
    
    if((!isset($_SESSION['shopMyBasket']['totalItems'])) OR ($_SESSION['shopMyBasket']['totalItems'] == '0')) {
      header ("Location: ".__SITEURL."shop/home/");
      die();
    } else {
      if (!isset($_GET['stage'])) { 
        $STAGE = "login";
      } else {
        $STAGE = $_GET['stage'];
      }
      switch ($STAGE) {
      
        default:
        case "login":
          if ($MEMBERVERIFIED) {
            // Skip this page and move on
            header("Location: ".__SITEURL."shop/home/?view=checkout&stage=details");
            die();
          } else {
            // Introduction
            $main->div('shopTemplateCheckoutIntro',''); 
              $main->hx(2,'Online Shop - Checkout','','');$main->_hx(2);
              $main->div('shopTemplateCheckoutProgress','');       
                $main->img(__SITEURL.'image/shop/checkoutProgress1.png', '', 'checkoutProgress', 'Image: Checkout Stage - Members\' Login', 'Image: Checkout Stage - Members\' Login');
              $main->_div();
            $main->_div();
             
            // two cols
            $main->div('shopTemplateCheckoutLogin','yui-g');
              $main->div('shopTemplateCheckoutLoginCol1','yui-u first');
                $main->hx(3,'I\'m a Member','','');$main->_hx(3);
                $main->add('If you are member of PalSoc please login below to receive your discount.');
                $main->br(1);
                // Error Message
                if ((isset($_GET['stage'])) AND ($_GET['stage'] == 'login')) {
                  $main->br(1);
                  $main->div('membersLoginError','');
                  $main->add('Your Username and/or Password combination could not be validated. Please try again.');
                  $main->_div();
                }
                // Add form
                $main->form('?view=checkout&amp;stage=login','POST','','membersLoginForm','');
                $main->br(1);
                $main->add('Username:&nbsp;');
                $main->input('text', 'MEMBER_UN', '', '', '', '', '', '');
                $main->br(2);
                $main->add('Password:&nbsp;');
                $main->input('password', 'MEMBER_PW', '', '', '', '', '', '');
                $main->br(2);
                $main->input('Submit', '', 'Login', '', '', '', '', '');
                $main->_form();
          	  $main->_div();
              $main->div('shopTemplateCheckoutLoginCol2','yui-u');
                $main->hx(3,'I\'m Not a Member','','');$main->_hx(3);
                $main->add('Not a member? To find out about the benfits of joining PalaeoSoc follow this link: <a href="'.__SITEURL.'page/membership/join-palsoc/" title="Join PalSoc">Join PalSoc</a>. Otherwise click the button below to skip this login page.');
                $main->br(2);
                $main->form('?view=checkout&amp;stage=details','POST','','skipMembersLoginForm','');
                $main->input('Submit', '', 'Skip login and move to next stage...', '', '', '', '', '');
                $main->_form();
              $main->_div();
            $main->_div();
            $main->add('<p><a href="'.__SITEURL.'shop/home/" title="Link: Continue Shopping"><img class="continueShopping" src="'.__SITEURL.'image/shop/continueShopping.png" alt="Link: Continue Shopping" /></a></p>');
          }
        break;
        
        case "details":
        
          if ($MEMBERVERIFIED) {
            $sql_members = "SELECT * FROM mod_members_users WHERE mod_members_users_ID='{$_SESSION['MEMBER_ID']}'";
            $result_members = $db->sql_query($sql_members);  
            $row_members = $db->sql_fetchrow($result_members);
          }
          
          $main->div('shopTemplateCheckoutDetails',''); 
            $main->hx(2,'Online Shop - Your Details','','');$main->_hx(2);
            $main->div('shopTemplateCheckoutProgress','');       
              $main->img(__SITEURL.'image/shop/checkoutProgress2.png', '', 'checkoutProgress', 'Image: Checkout Stage - Your Deatils', 'Image: Checkout Stage - Your Deatils');
            $main->_div();
          $main->_div();
          
          // Javascript
          
          $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  
          $js->script('js','','
            $(document).ready(function(){
              $("#checkoutSameAddress").click(function(){
                $("#customerDelAdd1").val($("#customerInvAdd1").val());
                $("#customerDelAdd2").val($("#customerInvAdd2").val());
                $("#customerDelCity").val($("#customerInvCity").val());
                $("#customerDelState").val($("#customerInvState").val());
                $("#customerDelZip").val($("#customerInvZip").val());
                $("#customerDelCountry").val($("#customerInvCountry").val());
              });
              $("#membersDetailsForm").validate({
                rules: {
                  customerLastName: {
                    required: true,
                    maxlength: 64
                  },
                  customerFirstNames: {
                    required: true,
                    maxlength: 32
                  },
                  customerEmail: {
                    required: true,
                    email: true,
                    maxlength: 128
                  },
                  customerInvAdd1: {
                    required: true,
                    maxlength: 100
                  },
                  customerInvAdd2: {
                    maxlength: 100
                  },
                  customerInvCity: {
                    required: true,
                    maxlength: 40
                  },
                  customerInvZip: {
                    required: true,
                    maxlength: 32
                  },
                  customerDelAdd1: {
                    required: true,
                    maxlength: 100
                  },
                  customerDelAdd2: {
                    maxlength: 100
                  },
                  customerDelCity: {
                    required: true,
                    maxlength: 40
                  },
                  customerDelZip: {
                    required: true,
                    maxlength: 32
                  }
                }
              });
            });
          ');
          
          
          $main->form('?view=checkout&amp;stage=confirm','POST','','membersDetailsForm','');
          
          // Contact Details
          $customerTitle = '';
          $customerLastName = '';
          $customerFirstNames = '';
          $customerEmail = '';
          
          if ($MEMBERVERIFIED) {
            // Get Members details
            !isset($_SESSION['customer']['title']) ? $customerTitle = $row_members['mod_members_users_Title'] : $customerTitle = $_SESSION['customer']['title'];
            !isset($_SESSION['customer']['lastName']) ? $customerLastName = $row_members['mod_members_users_LastName'] : $customerLastName = $_SESSION['customer']['lastName'];
            !isset($_SESSION['customer']['firstNames']) ? $customerFirstNames = $row_members['mod_members_users_FirstNames'] : $customerFirstNames = $_SESSION['customer']['firstNames'];
            !isset($_SESSION['customer']['email']) ? $customerEmail = $row_members['mod_members_users_Username'] : $customerEmail = $_SESSION['customer']['email'];
          } else {
            !isset($_SESSION['customer']['title']) ? null : $customerTitle = $_SESSION['customer']['title'];
            !isset($_SESSION['customer']['lastName']) ? null : $customerLastName = $_SESSION['customer']['lastName'];
            !isset($_SESSION['customer']['firstNames']) ? null : $customerFirstNames = $_SESSION['customer']['firstNames'];
            !isset($_SESSION['customer']['email']) ? null : $customerEmail = $_SESSION['customer']['email'];
          }
          
          $main->div('shopTemplateCheckoutDetailsContact','yui-g');
            $main->hx(3,'Contact Details','','');$main->_hx(3);
            $main->table('', 'checkoutTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Title:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerTitle', $customerTitle, '', '', '', '', '');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Last Name:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerLastName', $customerLastName, '', '', '', '', '');
            $main->add('<span class="info">(Required, Max 64 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('First Name(s):');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerFirstNames', $customerFirstNames, '', '', '', '', '');
            $main->add('<span class="info">(Required, Max 32 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Email Address:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerEmail', $customerEmail, '', '', '', '', '');
            $main->add('<span class="info">(Required, Max 128 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->_tbody();
            $main->_table();
          $main->_div();
          
          // Invoice Address
          $customerInvAdd1 = '';
          $customerInvAdd2 = '';
          $customerInvCity = '';
          $customerInvState = '';
          $customerInvZip = '';
          $customerInvCountry = 'GB';
          
          if ($MEMBERVERIFIED) {
            // Get Members details
            !isset($_SESSION['customer']['invAdd1']) ? $customerInvAdd1 = $row_members['mod_members_users_Address1'] : $customerInvAdd1 = $_SESSION['customer']['invAdd1'];
            !isset($_SESSION['customer']['invAdd2']) ? $customerInvAdd2 = $row_members['mod_members_users_Address2'] : $customerInvAdd2 = $_SESSION['customer']['invAdd2'];
            !isset($_SESSION['customer']['invCity']) ? $customerInvCity = $row_members['mod_members_users_City'] : $customerInvCity = $_SESSION['customer']['invCity'];
            !isset($_SESSION['customer']['invState']) ? $customerInvState = $row_members['mod_members_users_State'] : $customerInvState = $_SESSION['customer']['invState'];
            !isset($_SESSION['customer']['invZip']) ? $customerInvZip = $row_members['mod_members_users_Zip'] : $customerInvZip = $_SESSION['customer']['invZip'];
            !isset($_SESSION['customer']['invCountry']) ? $customerInvCountry = $row_members['mod_members_users_Country'] : $customerInvCountry = $_SESSION['customer']['invCountry'];
          } else {
            !isset($_SESSION['customer']['invAdd1']) ? null : $customerInvAdd1 = $_SESSION['customer']['invAdd1'];
            !isset($_SESSION['customer']['invAdd2']) ? null : $customerInvAdd2 = $_SESSION['customer']['invAdd2'];
            !isset($_SESSION['customer']['invCity']) ? null : $customerInvCity = $_SESSION['customer']['invCity'];
            !isset($_SESSION['customer']['invState']) ? null : $customerInvState = $_SESSION['customer']['invState'];
            !isset($_SESSION['customer']['invZip']) ? null : $customerInvZip = $_SESSION['customer']['invZip'];
            !isset($_SESSION['customer']['invCountry']) ? null : $customerInvCountry = $_SESSION['customer']['invCountry'];
          }
          
          $main->div('shopTemplateCheckoutDetailsInvoice','yui-g');
            $main->hx(3,'Invoice Address','','');$main->_hx(3);
            $main->table('', 'checkoutTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Address 1:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerInvAdd1', $customerInvAdd1, '', 'customerInvAdd1', '', '', '');
            $main->add('<span class="info">(Required, Max 100 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Address 2:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerInvAdd2', $customerInvAdd2, '', 'customerInvAdd2', '', '', '');
            $main->add('<span class="info">(Max 100 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('City:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerInvCity', $customerInvCity, '', 'customerInvCity', '', '', '');
            $main->add('<span class="info">(Required, Max 40 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('County/State:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerInvState', $customerInvState, '', 'customerInvState', '', '', '');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Post Code/Zip:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerInvZip', $customerInvZip, '', 'customerInvZip', '', '', '');
            $main->add('<span class="info">(Required, Max 32 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Country:');$main->_td();
            $main->td('', '', '', '');
            $main->select('customerInvCountry', 'customerInvCountry', '', '', '');
            $sql_country= "SELECT * FROM shop_country ORDER BY shop_country_Name ASC";
            $result_country = $db->sql_query($sql_country);
            while ($row_country = $db->sql_fetchrow($result_country)) {
              if ($customerInvCountry == $row_country['shop_country_ShortCode']) {
                $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '1');
              } else {
                $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '');
              }
            }
            $main->_select();
            $main->add('<span class="info">(Required)</span>');
            
            $main->_td();
            $main->_tr();
            $main->_tbody();
            $main->_table();
          $main->_div();
          
          // Delivery Address
          $customerDelAdd1 = '';
          $customerDelAdd2 = '';
          $customerDelCity = '';
          $customerDelState = '';
          $customerDelZip = '';
          $customerDelCountry = 'GB';
          
          if ($MEMBERVERIFIED) {
            // Get Members details
            !isset($_SESSION['customer']['delAdd1']) ? $customerDelAdd1 = $row_members['mod_members_users_Address1'] : $customerDelAdd1 = $_SESSION['customer']['delAdd1'];
            !isset($_SESSION['customer']['delAdd2']) ? $customerDelAdd2 = $row_members['mod_members_users_Address2'] : $customerDelAdd2 = $_SESSION['customer']['delAdd2'];
            !isset($_SESSION['customer']['delCity']) ? $customerDelCity = $row_members['mod_members_users_City'] : $customerDelCity = $_SESSION['customer']['delCity'];
            !isset($_SESSION['customer']['delState']) ? $customerDelState = $row_members['mod_members_users_State'] : $customerDelState = $_SESSION['customer']['delState'];
            !isset($_SESSION['customer']['delZip']) ? $customerDelZip = $row_members['mod_members_users_Zip'] : $customerDelZip = $_SESSION['customer']['delZip'];
            !isset($_SESSION['customer']['delCountry']) ? $customerDelCountry = $row_members['mod_members_users_Country'] : $customerDelCountry = $_SESSION['customer']['delCountry'];
          } else {
            !isset($_SESSION['customer']['delAdd1']) ? null : $customerDelAdd1 = $_SESSION['customer']['delAdd1'];
            !isset($_SESSION['customer']['delAdd2']) ? null : $customerDelAdd2 = $_SESSION['customer']['delAdd2'];
            !isset($_SESSION['customer']['delCity']) ? null : $customerDelCity = $_SESSION['customer']['delCity'];
            !isset($_SESSION['customer']['delState']) ? null : $customerDelState = $_SESSION['customer']['delState'];
            !isset($_SESSION['customer']['delZip']) ? null : $customerDelZip = $_SESSION['customer']['delZip'];
            !isset($_SESSION['customer']['delCountry']) ? null : $customerDelCountry = $_SESSION['customer']['delCountry'];
          }
          
          $main->div('shopTemplateCheckoutDetailsDelivery','yui-g');
            $main->hx(3,'Delivery Address','','');$main->_hx(3);
            $main->add(' Use same address as Invoice: <input type="button" value="Same Address" id="checkoutSameAddress">');
            $main->table('', 'checkoutTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Address 1:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerDelAdd1', $customerDelAdd1, '', 'customerDelAdd1', '', '', '');
            $main->add('<span class="info">(Required, Max 100 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Address 2:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerDelAdd2', $customerDelAdd2, '', 'customerDelAdd2', '', '', '');
            $main->add('<span class="info">(Max 100 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('City:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerDelCity', $customerDelCity, '', 'customerDelCity', '', '', '');
            $main->add('<span class="info">(Required, Max 40 Char)</span>');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('County/State:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerDelState', $customerDelState, '', 'customerDelState', '', '', '');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Post Code/Zip:');$main->_td();
            $main->td('', '', '', '');
            $main->input('text', 'customerDelZip', $customerDelZip, '', 'customerDelZip', '', '', '');
            $main->add('<span class="info">(Required, Max 32 Char)</span>');
            $main->_td();
            $main->_tr();      
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Country:');$main->_td();
            $main->td('', '', '', '');
            $main->select('customerDelCountry', 'customerDelCountry', '', '', '');
            $sql_country= "SELECT * FROM shop_country ORDER BY shop_country_Name ASC";
            $result_country = $db->sql_query($sql_country);
            while ($row_country = $db->sql_fetchrow($result_country)) {
              if ($customerDelCountry == $row_country['shop_country_ShortCode']) {
                $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '1');
              } else {
                $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '');
              }
            }
            $main->_select();
            $main->add('<span class="info">(Required)</span>');
            $main->_td();
            $main->_tr();
            $main->_tbody();
            $main->_table();
          $main->_div();
          
          $main->div('shopTemplateCheckoutLinks','');
            $main->input('Submit', '', 'Next...', '', '', '', '', '');
          $main->_div();
          if ($MEMBERVERIFIED) {
            $main->add('<p><a href="'.__SITEURL.'shop/home/" title="Link: Continue Shopping"><img class="continueShopping" src="'.__SITEURL.'image/shop/continueShopping.png" alt="Link: Continue Shopping" /></a></p>');
          } else {
            $main->add('<p><a href="'.__SITEURL.'shop/home/?view=checkout" title="Link: Checkout - Member\'s Login"><img class="checkoutBack" src="'.__SITEURL.'image/shop/checkoutBack.png" alt="Link: Checkout - Member\'s Login" /></a></p>');
          }
          $main->_form();
        break;
        
        case "confirm":
          // Add Post Values to Session
          isset($_POST['customerTitle']) ? $_SESSION['customer']['title'] = $_POST['customerTitle']: null;
          isset($_POST['customerLastName']) ? $_SESSION['customer']['lastName'] = $_POST['customerLastName']: null;
          isset($_POST['customerFirstNames']) ? $_SESSION['customer']['firstNames'] = $_POST['customerFirstNames']: null;
          isset($_POST['customerEmail']) ? $_SESSION['customer']['email'] = $_POST['customerEmail']: null;
          isset($_POST['customerInvAdd1']) ? $_SESSION['customer']['invAdd1'] = $_POST['customerInvAdd1']: null;
          isset($_POST['customerInvAdd2']) ? $_SESSION['customer']['invAdd2'] = $_POST['customerInvAdd2']: null;
          isset($_POST['customerInvCity']) ? $_SESSION['customer']['invCity'] = $_POST['customerInvCity']: null;
          isset($_POST['customerInvState']) ? $_SESSION['customer']['invState'] = $_POST['customerInvState']: null;
          isset($_POST['customerInvZip']) ? $_SESSION['customer']['invZip'] = $_POST['customerInvZip']: null;
          isset($_POST['customerInvCountry']) ? $_SESSION['customer']['invCountry'] = $_POST['customerInvCountry']: null;
          isset($_POST['customerDelAdd1']) ? $_SESSION['customer']['delAdd1'] = $_POST['customerDelAdd1']: null;
          isset($_POST['customerDelAdd2']) ? $_SESSION['customer']['delAdd2'] = $_POST['customerDelAdd2']: null;
          isset($_POST['customerDelCity']) ? $_SESSION['customer']['delCity'] = $_POST['customerDelCity']: null;
          isset($_POST['customerDelState']) ? $_SESSION['customer']['delState'] = $_POST['customerDelState']: null;
          isset($_POST['customerDelZip']) ? $_SESSION['customer']['delZip'] = $_POST['customerDelZip']: null;
          isset($_POST['customerDelCountry']) ? $_SESSION['customer']['delCountry'] = $_POST['customerDelCountry']: null;
          
          // Set Postage Costs/ Update
          $sql_postageRegion= "SELECT shop_postage.shop_postage_Zone FROM 
          shop_country JOIN (shop_region JOIN shop_postage ON shop_region.shop_region_PostageZone=shop_postage.shop_postage_Zone) ON shop_country.shop_country_Region=shop_region.shop_region_ID 
          WHERE 
          shop_country.shop_country_ShortCode='{$_SESSION['customer']['delCountry']}'";
          
          $result_postageRegion = $db->sql_query($sql_postageRegion);
          $row_postageRegion = $db->sql_fetchrow($result_postageRegion);
          $_SESSION['shopMyBasket']['currentPostage'] = $row_postageRegion['shop_postage_Zone'];
          
          $main->div('shopTemplateCheckoutReviewOrder',''); 
          
          $main->div('shopTemplateCheckoutReview',''); 
            $main->hx(2,'Online Shop - Review Your Order','','');$main->_hx(2);
            $main->div('shopTemplateCheckoutProgress','');       
              $main->img(__SITEURL.'image/shop/checkoutProgress3.png', '', 'checkoutProgress', 'Image: Checkout Stage - Review Your Order', 'Image: Checkout Stage - Review Your Order');
            $main->_div();
          $main->_div();
          
          // Display Contact/Address Info
          $main->div('shopTemplateCheckoutContact','');
          $main->hx(4,'Contact Details','','');$main->_hx(4);
          $main->table('', 'checkoutTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Full Name:');$main->_td();
            $main->td('', '', '', '');
            $main->add($_SESSION['customer']['title'].' '.$_SESSION['customer']['lastName'].', '.$_SESSION['customer']['firstNames'].' [<a href="?view=checkout&stage=details" title="Link: Edit">Edit</a>]');
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Email Address:');$main->_td();
            $main->td('', '', '', '');
            $main->add($_SESSION['customer']['email'].' [<a href="?view=checkout&stage=details" title="Link: Edit">Edit</a>]');
            $main->_td();
            $main->_tr();
            $main->_tbody();
          $main->_table();
          $main->_div();
          $main->div('shopTemplateCheckoutAddresses','yui-g');
            $main->div('shopTemplateCheckoutAddressesCol1','yui-u first');
              $main->hx(4,'Invoice Address','','');$main->_hx(4);
              $main->p('','indent');
              $main->add($_SESSION['customer']['title'].' '.$_SESSION['customer']['lastName'].', '.$_SESSION['customer']['firstNames']);
              $main->br(1);
              $main->add($_SESSION['customer']['invAdd1']);
              $main->br(1);
              if ($_SESSION['customer']['invAdd2'] != '') {
                $main->add($_SESSION['customer']['invAdd2']);
                $main->br(1);
              }
              $main->add($_SESSION['customer']['invCity']);
              $main->br(1);
              if ($_SESSION['customer']['invState'] != '') {
                $main->add($_SESSION['customer']['invState']);
                $main->br(1);
              }
              $main->add($_SESSION['customer']['invZip']);
              $main->br(1);
              $main->add($_SESSION['customer']['invCountry']);
              $main->_p();
              $main->p('','alignRight');
              $main->add('[<a href="?view=checkout&stage=details" title="Link: Edit">Edit</a>]');
              $main->_p();
        	  $main->_div();
            $main->div('shopTemplateCheckoutAddressesCol2','yui-u');
              $main->hx(4,'Delivery Address','','');$main->_hx(4);
              $main->p('','indent');
              $main->add($_SESSION['customer']['title'].' '.$_SESSION['customer']['lastName'].', '.$_SESSION['customer']['firstNames']);
              $main->br(1);
              $main->add($_SESSION['customer']['delAdd1']);
              $main->br(1);
              if ($_SESSION['customer']['invAdd2'] != '') {
                $main->add($_SESSION['customer']['delAdd2']);
                $main->br(1);
              }
              $main->add($_SESSION['customer']['delCity']);
              $main->br(1);
              if ($_SESSION['customer']['delState'] != '') {
                $main->add($_SESSION['customer']['delState']);
                $main->br(1);
              }
              $main->add($_SESSION['customer']['delZip']);
              $main->br(1);
              $main->add($_SESSION['customer']['delCountry']);
              $main->_p();
              $main->p('','alignRight');
              $main->add('[<a href="?view=checkout&stage=details" title="Link: Edit">Edit</a>]');
              $main->_p();
            $main->_div();
          $main->_div();
          // Basket
          $main->div('shopTemplateBasketContent','');   
          $main->hx(4,'Basket','','');$main->_hx(4);        
            $main->table('','basketTable');
            $main->thead('','');
            $main->tr('','');
            $main->th('','','','');
              $main->add('Item');
              $main->_th();
              $main->th('','','','');
              $main->add('Quantity');
              $main->_th();
              $main->th('','','','');
              $main->add('Each');
              $main->_th();
              $main->th('','','','');
              $main->add('Price');
              $main->_th();
            $main->_tr();
            $main->_thead();
            $main->tbody('','');
            foreach ($_SESSION['shopMyBasket']['items'] as $key => $itemArray) {
              $main->tr('','');
              $main->td('','','','');
              $main->add($itemArray['titleFull']);
              $main->_td();
              $main->td('','','','');
              $main->add($itemArray['quantity']);
              $main->_td();
              if ($MEMBERVERIFIED) {
                $main->td('','','','');
                $main->add(money_format('%n', $num->round2DP($itemArray['memPrice'])));
                $main->_td();
                $main->td('','','','');
                $main->add(money_format('%n', $num->round2DP(($itemArray['memPrice']*$itemArray['quantity']))));
                $main->_td();
                $main->_tr();
              } else {  
                $main->td('','','','');
                $main->add(money_format('%n', $num->round2DP($itemArray['price'])));
                $main->_td();
                $main->td('','','','');
                $main->add(money_format('%n', $num->round2DP(($itemArray['price']*$itemArray['quantity']))));
                $main->_td();
                $main->_tr();
              }
            }
            // Postage
            $main->tr('','');
            $main->td('','alignRight','','3');
            $main->add('Postage to ');
            $sql_post = "SELECT * FROM shop_postage WHERE shop_postage_Zone='{$_SESSION['shopMyBasket']['currentPostage']}'";
            $result_post = $db->sql_query($sql_post);
            $row_post = $db->sql_fetchrow($result_post);
            $main->add($row_post['shop_postage_ZoneName']);
            $_SESSION['customer']['postageCost'] = $row_post['shop_postage_Price'];
            $main->_td();
            $main->td('','','','');
            $main->add(money_format('%n', $num->round2DP($_SESSION['customer']['postageCost'])));
            $main->_td();
            $main->_tr();
            if ($MEMBERVERIFIED) {
              $main->tr('','');
              $main->td('','alignRight','','3');
              $main->add('Members\' Item(s) Total:');
              $main->_td();
              $main->td('','','','');
              $main->add('<strong>'.money_format('%n', $num->round2DP($_SESSION['shopMyBasket']['totalMemPrice']+$_SESSION['customer']['postageCost'])).'</strong>');
              $main->_td();
              $main->_tr();
            } else {
              // Total Price
              $main->tr('','');
              $main->td('','alignRight','','3');
              $main->add('Item(s) Total:');
              $main->_td();
              $main->td('','','','');
              $main->add('<strong>'.money_format('%n', $num->round2DP($_SESSION['shopMyBasket']['totalPrice']+$_SESSION['customer']['postageCost'])).'</strong>');
              $main->_td();
              $main->_tr();
            }
            $main->_tbody();
            $main->_table();
          $main->_div();
          
          // Javascript
          $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
          
          $js->script('js','','
            $(document).ready(function(){
              $("#checkoutReviewForm").validate({
                rules: {
                  checkoutTerms: {
                    required: true
                  }
                }
              });
            });
          ');
          
          $main->form('?view=checkout&amp;stage=transfer','POST','','checkoutReviewForm','');
          $main->div('shopTemplateCheckoutTerms','');   
            $main->hx(4,'Terms of Sale','','');$main->_hx(4);
            $main->p('shopTemplateCheckoutTermsCenter','');
            $main->textarea ('TandC', shopConfig('T_AND_C', $db), '', '', '', '1');
            $main->_p();
            $main->p('','');
            $main->add('I fully accept the Palaeontographical Society\'s (PalSoc\'s) Terms of Sale: YES ');
            $main->input('checkbox', 'checkoutTerms', '1', '', 'checkoutTerms', '', '', '');
            $main->_p();
          $main->_div();    
          
          $main->div('shopTemplateCheckoutLinks','');
            $main->input('submit', '', 'Confirm Order and Transfer to PayPal', '', '', '', '', '');
          $main->_div();
          $main->_form();
          $main->add('<p><a href="'.__SITEURL.'shop/home/?view=checkout&stage=details" title="Link: Your Details"><img class="checkoutBack" src="'.__SITEURL.'image/shop/checkoutBack.png" alt="Link: Your Details" /></a></p>');
          
          $main->_div();
        break;
        
        case "transfer":
          if ($BST) {
            // BST
            $orderNumber = 'PS-'.date("mdy").'-'.(date("H")-1).date("is");
          } else {
            // GMT
            $orderNumber = 'PS-'.date("mdy").'-'.date("His");
          }
          $main->div('shopTemplateCheckoutTransfer','');   
          $main->hx(2,'Online Shop - Transfering to PayPal...','','');$main->_hx(2);
          $main->_div();
          
          $main->div('shopTemplateCheckoutTransferForm','');
          $main->br(2);
          $main->add('Please wait while your order is transfered to PayPal...');
          $main->br(2);
          $main->add('<!-- PayPal Logo --><img  src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" alt="PayPal Logo"><!-- PayPal Logo -->');
          $main->br(1);
          $main->img(__SITEURL.'image/shop/paypalTransfer.gif', '', '', 'Image: PayPal Transfer', 'Image: PayPal Transfer');
          $main->br(2);
          $main->add('If you are not automatically transfered please press the button below:');
          $main->br(2);
          
          // Javascript automatic form submition
          $js->script('js','','
          $(document).ready(function(){
              setTimeout(function() {
               $("#checkoutPayPalTranfer").submit();
              }, 5000);
            });
          ');
          
          
          // Get Details for Form from DB
          if (shopConfig('SANDBOX_MODE', $db) != '1') {
            // Live Paypal
            $main->form(shopConfig('URL', $db),'POST','','checkoutPayPalTranfer','');
              $main->input('hidden', 'business', shopConfig('BUSSINESS', $db), '', '', '', '', '');
          } else {
            // Sandbox PayPal
            $main->form(shopConfig('SANDBOX_URL', $db),'POST','','checkoutPayPalTranfer','');
              $main->input('hidden', 'business', shopConfig('SANDBOX_BUSSINESS', $db), '', '', '', '', '');  
          }
          
            // cmd
            $main->input('hidden', 'cmd', '_cart', '', '', '', '', '');
            // invoice - Order Number
            $main->input('hidden', 'invoice', $orderNumber, '', '', '', '', '');
            // custom - 
            $main->input('hidden', 'custom', '', '', '', '', '', '');
            // return url
            $main->input('hidden', 'return', __SITEURL.'shop/home/?view=checkout&amp;mode=pdt', '', '', '', '', '');
            // notify url
            $main->input('hidden', 'notify_url', __SITEURL.'shop/home/?view=checkout&amp;mode=ipn', '', '', '', '', '');
            // currency_code - Pounds Sterlin
            $main->input('hidden', 'currency_code', 'GBP', '', '', '', '', '');
            // Items
            $x = 1;
            foreach ($_SESSION['shopMyBasket']['items'] as $key => $itemArray) {

              // Item Name - max 128 char
              $main->input('hidden', 'item_name_'.$x, $itemArray['titleShort'], '', '', '', '', '');
              // Item ID
              $main->input('hidden', 'item_number_'.$x, $key, '', '', '', '', '');
              // Item Amount
              if ($MEMBERVERIFIED) {
                $main->input('hidden', 'amount_'.$x, money_format('%!n', $num->round2DP($itemArray['memPrice'])), '', '', '', '', '');
              } else {
                $main->input('hidden', 'amount_'.$x, money_format('%!n', $num->round2DP($itemArray['price'])), '', '', '', '', '');              
              }
              // Item Quantity
              $main->input('hidden', 'quantity_'.$x, $itemArray['quantity'], '', '', '', '', '');        
              
              $x++;
            }
            // shipping/postage
            $main->input('hidden', 'handling_cart', money_format('%!n', $num->round2DP($_SESSION['customer']['postageCost'])), '', '', '', '', '');
            // upload - rewuired fro 3rd party shopping carts
            $main->input('hidden', 'upload', '1', '', '', '', '', '');
            // first name
            $main->input('hidden', 'first_name', preg_replace("/[^a-zA-Z0-9\s]/", "", $_SESSION['customer']['firstNames']), '', '', '', '', '');
            // email
            $main->input('hidden', 'email', $_SESSION['customer']['email'], '', '', '', '', '');
            // last name
            $main->input('hidden', 'last_name', preg_replace("/[^a-zA-Z0-9\s]/", "", $_SESSION['customer']['lastName']), '', '', '', '', '');
            //address 1
            $main->input('hidden', 'address1', $_SESSION['customer']['invAdd1'], '', '', '', '', '');
            //address 2
            $main->input('hidden', 'address2', $_SESSION['customer']['invAdd2'], '', '', '', '', '');
            //city
            $main->input('hidden', 'city', $_SESSION['customer']['invCity'], '', '', '', '', '');
            //state
            if ($_SESSION['customer']['invState'] != '') {
              $main->input('hidden', 'state', $_SESSION['customer']['invState'], '', '', '', '', '');
            }
            //zip
            $main->input('hidden', 'zip', $_SESSION['customer']['invZip'], '', '', '', '', '');
            //country
            $main->input('hidden', 'country', $_SESSION['customer']['invCountry'], '', '', '', '', '');
            // address overide
            //$main->input('hidden', 'address_override', '1', '', '', '', '', '');
            // buyers lang
            $main->input('hidden', 'lc', 'GB', '', '', '', '', '');
            
            
            $main->input('submit', '', 'Transfer to PayPal', '', '', '', '', '');
          $main->_form();
          $main->_div();
          
          if ($MEMBERVERIFIED) {
            $totalPrice = money_format('%!n', $num->round2DP($_SESSION['shopMyBasket']['totalMemPrice']));   
          } else {
            $totalPrice = money_format('%!n', $num->round2DP($_SESSION['shopMyBasket']['totalPrice']));
          }
          
          // add order into database
          if (!get_magic_quotes_gpc()) {
            $_SESSION['customer']['title'] = addslashes($_SESSION['customer']['title']);
            $_SESSION['customer']['firstNames'] = addslashes($_SESSION['customer']['firstNames']);
            $_SESSION['customer']['lastName'] = addslashes($_SESSION['customer']['lastName']);
            $_SESSION['customer']['email'] = addslashes($_SESSION['customer']['email']);
            $_SESSION['customer']['invAdd1'] = addslashes($_SESSION['customer']['invAdd1']);
            $_SESSION['customer']['invAdd2'] = addslashes($_SESSION['customer']['invAdd2']);
            $_SESSION['customer']['invCity'] = addslashes($_SESSION['customer']['invCity']);
            $_SESSION['customer']['invState'] = addslashes($_SESSION['customer']['invState']);
            $_SESSION['customer']['invZip'] = addslashes($_SESSION['customer']['invZip']);
            $_SESSION['customer']['invCountry'] = addslashes($_SESSION['customer']['invCountry']);
            $_SESSION['customer']['delAdd1'] = addslashes($_SESSION['customer']['delAdd1']);
            $_SESSION['customer']['delAdd2'] = addslashes($_SESSION['customer']['delAdd2']);
            $_SESSION['customer']['delCity'] = addslashes($_SESSION['customer']['delCity']);
            $_SESSION['customer']['delState'] = addslashes($_SESSION['customer']['delState']);
            $_SESSION['customer']['delZip'] = addslashes($_SESSION['customer']['delZip']);
            $_SESSION['customer']['delCountry'] = addslashes($_SESSION['customer']['delCountry']);
          }
          
          $db->sql_query("INSERT INTO mod_shop_orders (
          mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus, mod_shop_orders_PaymentStatus,
          mod_shop_orders_PaymentStatusInfo, mod_shop_orders_ItemTotal, mod_shop_orders_Postage,
          mod_shop_orders_MemberID, mod_shop_orders_CustomerTitle, mod_shop_orders_CustomerFirstNames,
          mod_shop_orders_CustomerLastName, mod_shop_orders_CustomerEmail, mod_shop_orders_InvAdd1,
          mod_shop_orders_InvAdd2, mod_shop_orders_InvCity, mod_shop_orders_InvState, 
          mod_shop_orders_InvZip, mod_shop_orders_InvCountry, mod_shop_orders_DelAdd1, 
          mod_shop_orders_DelAdd2, mod_shop_orders_DelCity, mod_shop_orders_DelState, 
          mod_shop_orders_DelZip, mod_shop_orders_DelCountry
          ) VALUES (
          '$orderNumber', 'AwaitingPayment', 'NoPayment',
          'Awaiting transfer to PayPal', '".money_format('%!n', $num->round2DP($totalPrice))."', '".money_format('%!n', $num->round2DP($_SESSION['customer']['postageCost']))."', 
          '{$_SESSION['MEMBER_ID']}', '{$_SESSION['customer']['title']}', '{$_SESSION['customer']['firstNames']}',
          '{$_SESSION['customer']['lastName']}', '{$_SESSION['customer']['email']}', '{$_SESSION['customer']['invAdd1']}',
          '{$_SESSION['customer']['invAdd2']}', '{$_SESSION['customer']['invCity']}', '{$_SESSION['customer']['invState']}', 
          '{$_SESSION['customer']['invZip']}', '{$_SESSION['customer']['invCountry']}', '{$_SESSION['customer']['delAdd1']}', 
          '{$_SESSION['customer']['delAdd2']}', '{$_SESSION['customer']['delCity']}', '{$_SESSION['customer']['delState']}', 
          '{$_SESSION['customer']['delZip']}', '{$_SESSION['customer']['delCountry']}'
          )");
          $orderID = mysql_insert_id();
          // add items to db
          foreach ($_SESSION['shopMyBasket']['items'] as $key => $itemArray) {
            if (!get_magic_quotes_gpc()) {
              $itemArray['type'] = addslashes($itemArray['type']);
              $itemArray['id'] = addslashes($itemArray['id']);
              $itemArray['titleFull'] = addslashes($itemArray['titleFull']);
              $itemArray['titleShort'] = addslashes($itemArray['titleShort']);
            }
            
            $db->sql_query("INSERT INTO mod_shop_orders_items (
            mod_shop_orders_items_OrderID, mod_shop_orders_items_InvoiceID, mod_shop_orders_items_Ref,
            mod_shop_orders_items_Type, mod_shop_orders_items_ItemID, mod_shop_orders_items_TitleFull,
            mod_shop_orders_items_TitleShort, mod_shop_orders_items_Quantity, mod_shop_orders_items_CoverPrice,
            mod_shop_orders_items_Price, mod_shop_orders_items_MemPrice
            ) VALUES (
            '$orderID','$orderNumber','$key',
            '{$itemArray['type']}','{$itemArray['id']}','{$itemArray['titleFull']}',
            '{$itemArray['titleShort']}','{$itemArray['quantity']}','".money_format('%!n', $num->round2DP($itemArray['coverPrice']))."',
            '".money_format('%!n', $num->round2DP($itemArray['price']))."','".money_format('%!n', $num->round2DP($itemArray['memPrice']))."'
            )");
          }
          
          // Send email to customer / palaeosoc shop people
          // Email Customer
          $TO = $_SESSION['customer']['email'];
          $CC = '';
          $BCC = 'email-archive@palaeosoc.org';
          $FROM = '';
          $VAR_ARRAY = array();
          
          $VAR_ARRAY['INVOICE_ID'] = $orderNumber;
        
          emailMailer(4, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
          
          // Email staff - awaiting payment
          $TO = 'shop-orders@palaeosoc.org';
          $CC = '';
          $BCC = '';
          $FROM = '';
          $VAR_ARRAY = array();
          
          $VAR_ARRAY['INVOICE_ID'] = $orderNumber;
        
          emailMailer(9, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
          
          //unset all session associated with this order
          
          if (!empty($_SESSION['shopMyBasket']['items'])) {
            foreach($_SESSION['shopMyBasket']['items'] as $item => $val) {
              foreach($_SESSION['shopMyBasket']['items'][$item] as $key => $val) {
                unset ($_SESSION['shopMyBasket']['items'][$item][$val]);
              }
              unset ($_SESSION['shopMyBasket']['items'][$item]);
            }
          }
          unset ($_SESSION['shopMyBasket'], $_SESSION['shopMyBasket']['totalItems'],$_SESSION['shopMyBasket']['totalMemPrice'],$_SESSION['shopMyBasket']['totalPrice']);
          
        break;
      }
    }
  break;
  
  case "pdt";
    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-synch';
    
    $tx_token = $_GET['tx'];
    
    // Get Details for Form from DB
    if (shopConfig('SANDBOX_MODE', $db) != '1') {
      // Live Paypal
      $auth_token = shopConfig('PDT', $db);
    } else {
      // Sandbox PayPal
      $auth_token = shopConfig('SANDBOX_PDT', $db);
    }
    $req .= "&tx=$tx_token&at=$auth_token";
    
    // post back to PayPal system to validate
    $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
    $header .="Content-Type: application/x-www-form-urlencoded\r\n";
    $header .="Host: www.paypal.com\r\n<http://www.paypal.com/r/n>";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    if (shopConfig('SANDBOX_MODE', $db) != '1') {
      // Live Paypal
      $fp = fsockopen ('ssl://'.shopConfig('PDT_URL', $db), 443, $errno, $errstr, 30);
    } else {
      // Sandbox PayPal
      $fp = fsockopen ('ssl://'.shopConfig('SANDBOX_PDT_URL', $db), 443, $errno, $errstr, 30);
    }
    
    if (!$fp) {
      // HTTP ERROR
    } else {
      fputs ($fp, $header . $req);
      // read the body data
      $res = '';
      $headerdone = false;
      while (!feof($fp)) {
        $line = fgets ($fp, 1024);
        if (strcmp($line, "\r\n") == 0) {
          // read the header
          $headerdone = true;
        } else if ($headerdone) {
          // header has been read. now read the contents
          $res .= $line;
        }
      }
      
      // parse the data
      $lines = explode("\n", $res);
      $keyarray = array();
      if (strcmp ($lines[0], "SUCCESS") == 0) {
        for ($i=1; $i<count($lines);$i++){
          @list($key,$val) = @explode("=", $lines[$i]);
          $keyarray[urldecode($key)] = urldecode($val);
        }
        $NO_ERROR = true;
        // check that txn_id has not been previously processed
        $txn_log_sql = "SELECT mod_shop_txn_TxnID FROM mod_shop_txn WHERE mod_shop_txn_TxnID='".$keyarray['txn_id']."'";
        $txn_log_result = $db->sql_query($txn_log_sql);
        // then log txn_id in database
        if ($db->sql_numrows($txn_log_result) == 0) {
           $db->sql_query("INSERT INTO mod_shop_txn (mod_shop_txn_TxnID) VALUES ('".$keyarray['txn_id']."')");
        }

        // Get info from Database about order using invoice number
        $order_sql = "SELECT * FROM mod_shop_orders WHERE mod_shop_orders_InvoiceID='{$keyarray['invoice']}'";
        $order_result = $db->sql_query($order_sql);
        if ($db->sql_numrows($order_result) == 0) {
          $main->add("<h2>Transaction Error!</h2>");
          $main->add("This transaction has no valid order associated with it.<br>\n");
          $NO_ERROR = false;
        }
        
        if ($NO_ERROR) {
          $row_order = $db->sql_fetchrow($order_result);
          // Process payemtn info - if required
          if ($row_order['mod_shop_orders_PaymentStatus'] != 'Completed') {
            if ($keyarray['payment_status'] == 'Pending') {
              $db->sql_query("UPDATE mod_shop_orders SET 
              mod_shop_orders_PaymentStatus='{$keyarray['payment_status']}',
              mod_shop_orders_PaymentStatusInfo='{$keyarray['pending_reason']}'
               WHERE mod_shop_orders_InvoiceID='{$keyarray['invoice']}'");
            } elseif (($keyarray['payment_status'] == 'Reversed') OR ($keyarray['payment_status'] == 'Cancelled_Reversal') OR ($keyarray['payment_status'] == 'Refunded')) {
              $db->sql_query("UPDATE mod_shop_orders SET 
              mod_shop_orders_PaymentStatus='{$keyarray['payment_status']}',
              mod_shop_orders_PaymentStatusInfo='{$keyarray['reason_code']}'
               WHERE mod_shop_orders_InvoiceID='{$keyarray['invoice']}'");
            } elseif ($keyarray['payment_status'] == 'Completed') {
              $db->sql_query("UPDATE mod_shop_orders SET 
              mod_shop_orders_PaymentStatus='{$keyarray['payment_status']}',
              mod_shop_orders_PaymentStatusInfo='Ready for Dispatch',
              mod_shop_orders_OrderStatus='PaymentReceived'
               WHERE mod_shop_orders_InvoiceID='{$keyarray['invoice']}'");
            } else {
              $db->sql_query("UPDATE mod_shop_orders SET 
              mod_shop_orders_PaymentStatus='{$keyarray['payment_status']}',
              mod_shop_orders_PaymentStatusInfo=''
               WHERE mod_shop_orders_InvoiceID='{$keyarray['invoice']}'");
            }
          }
          // Show invoice/recipt
          $main->div('shopTemplateCheckoutConfirmation',''); 
            $main->hx(2,'Online Shop - Confirmation','','');$main->_hx(2);
            $main->div('shopTemplateCheckoutProgress','');       
              $main->img(__SITEURL.'image/shop/checkoutProgress4.png', '', 'checkoutProgress', 'Image: Checkout Stage - Confirmation', 'Image: Checkout Stage - Confirmation');
            $main->_div();
          $main->_div();
          
          $main->div('shopTemplateCheckoutConfirmationInvoice',''); 
          $main->add("<h3>Thank you for your purchase!</h3>");
          $main->div('shopTemplateCheckoutPayment','');
          $main->hx(4,'Payment Details','','');$main->_hx(4);
          $main->table('', 'checkoutTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Order/Invoice N&deg;:');$main->_td();
            $main->td('', '', '', '');
            $main->add($keyarray['invoice']);
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Transaction ID:');$main->_td();
            $main->td('', '', '', '');
            $main->add($keyarray['txn_id']);
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Payment Status:');$main->_td();
            $main->td('', '', '', '');
            $main->add($keyarray['payment_status']);
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Payment Date:');$main->_td();
            $main->td('', '', '', '');
            $main->add($keyarray['payment_date']);
            $main->_td();
            $main->_tr();
            $main->_tbody();
          $main->_table();
          $main->_div();
          $main->div('shopTemplateCheckoutContactDetails','');
          $main->hx(4,'Contact Details','','');$main->_hx(4);
          $main->table('', 'checkoutTable');
            $main->tbody('', '');
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Full Name:');$main->_td();
            $main->td('', '', '', '');
            $main->add($row_order['mod_shop_orders_CustomerTitle'].' '.$row_order['mod_shop_orders_CustomerLastName'].', '.$row_order['mod_shop_orders_CustomerFirstNames']);
            $main->_td();
            $main->_tr();
            $main->tr('', '');
            $main->td('', 'title', '', '');$main->add('Email Address:');$main->_td();
            $main->td('', '', '', '');
            $main->add($row_order['mod_shop_orders_CustomerEmail']);
            $main->_td();
            $main->_tr();
            $main->_tbody();
          $main->_table();
          $main->_div();
          $main->div('shopTemplateCheckoutAddresses','yui-g');
            $main->div('shopTemplateCheckoutAddressesCol1','yui-u first');
              $main->hx(4,'Invoice Address','','');$main->_hx(4);
              $main->p('','indent');
              $main->add($row_order['mod_shop_orders_CustomerTitle'].' '.$row_order['mod_shop_orders_CustomerLastName'].', '.$row_order['mod_shop_orders_CustomerFirstNames']);
              $main->br(1);
              $main->add($row_order['mod_shop_orders_InvAdd1']);
              $main->br(1);
              if ($row_order['mod_shop_orders_InvAdd2'] != '') {
                $main->add($row_order['mod_shop_orders_InvAdd2']);
                $main->br(1);
              }
              $main->add($row_order['mod_shop_orders_InvCity']);
              $main->br(1);
              if ($row_order['mod_shop_orders_InvState'] != '') {
                $main->add($row_order['mod_shop_orders_InvState']);
                $main->br(1);
              }
              $main->add($row_order['mod_shop_orders_InvZip']);
              $main->br(1);
              $main->add($row_order['mod_shop_orders_InvCountry']);
              $main->_p();
        	  $main->_div();
            $main->div('shopTemplateCheckoutAddressesCol2','yui-u');
              $main->hx(4,'Delivery Address','','');$main->_hx(4);
              $main->p('','indent');
              $main->add($row_order['mod_shop_orders_CustomerTitle'].' '.$row_order['mod_shop_orders_CustomerLastName'].', '.$row_order['mod_shop_orders_CustomerFirstNames']);
              $main->br(1);
              $main->add($row_order['mod_shop_orders_DelAdd1']);
              $main->br(1);
              if ($row_order['mod_shop_orders_DelAdd2'] != '') {
                $main->add($row_order['mod_shop_orders_DelAdd2']);
                $main->br(1);
              }
              $main->add($row_order['mod_shop_orders_DelCity']);
              $main->br(1);
              if ($row_order['mod_shop_orders_DelState'] != '') {
                $main->add($row_order['mod_shop_orders_DelState']);
                $main->br(1);
              }
              $main->add($row_order['mod_shop_orders_DelZip']);
              $main->br(1);
              $main->add($row_order['mod_shop_orders_DelCountry']);
              $main->_p();
            $main->_div();
          $main->_div();
          // Basket
          $main->div('shopTemplateBasketContent','');   
          $main->hx(4,'Basket','','');$main->_hx(4);        
            $main->table('','basketTable');
            $main->thead('','');
            $main->tr('','');
            $main->th('','','','');
              $main->add('Item');
              $main->_th();
              $main->th('','','','');
              $main->add('Quantity');
              $main->_th();
              $main->th('','','','');
              $main->add('Each');
              $main->_th();
              $main->th('','','','');
              $main->add('Price');
              $main->_th();
            $main->_tr();
            $main->_thead();
            $main->tbody('','');
            
            
            $orderItem_sql = "SELECT * FROM mod_shop_orders_items WHERE mod_shop_orders_items_InvoiceID='{$keyarray['invoice']}'";
            $orderItem_result = $db->sql_query($orderItem_sql);
            while ($orderItem_row = $db->sql_fetchrow($orderItem_result)) {
              $main->tr('','');
              $main->td('','','','');
              $main->add($orderItem_row['mod_shop_orders_items_TitleFull']);
              $main->_td();
              $main->td('','','','');
              $main->add($orderItem_row['mod_shop_orders_items_Quantity']);
              $main->_td();
              if ($row_order['mod_shop_orders_MemberID'] != 0) {
                $main->td('','','','');
                $main->add(money_format('%n', $num->round2DP($orderItem_row['mod_shop_orders_items_MemPrice'])));
                $main->_td();
                $main->td('','','','');
                $main->add(money_format('%n', $num->round2DP(($orderItem_row['mod_shop_orders_items_MemPrice']*$orderItem_row['mod_shop_orders_items_Quantity']))));
                $main->_td();
                $main->_tr();
              } else {  
                $main->td('','','','');
                $main->add(money_format('%n', $num->round2DP($orderItem_row['mod_shop_orders_items_Price'])));
                $main->_td();
                $main->td('','','','');
                $main->add(money_format('%n', $num->round2DP(($orderItem_row['mod_shop_orders_items_Price']*$orderItem_row['mod_shop_orders_items_Quantity']))));
                $main->_td();
                $main->_tr();
              }
            }
            // Postage
            $main->tr('','');
            $main->td('','alignRight','','3');
            $main->add('Postage');
            $main->_td();
            $main->td('','','','');
            $main->add(money_format('%n', $row_order['mod_shop_orders_Postage']));
            $main->_td();
            $main->_tr();
            if ($row_order['mod_shop_orders_MemberID'] != 0) {
              $main->tr('','');
              $main->td('','alignRight','','3');
              $main->add('Members\' Item(s) Total:');
              $main->_td();
              $main->td('','','','');
              $main->add('<strong>'.money_format('%n', $num->round2DP($row_order['mod_shop_orders_ItemTotal']+$row_order['mod_shop_orders_Postage'])).'</strong>');
              $main->_td();
              $main->_tr();
            } else {
              // Total Price
              $main->tr('','');
              $main->td('','alignRight','','3');
              $main->add('Item(s) Total:');
              $main->_td();
              $main->td('','','','');
              $main->add('<strong>'.money_format('%n', $num->round2DP($row_order['mod_shop_orders_ItemTotal']+$row_order['mod_shop_orders_Postage'])).'</strong>');
              $main->_td();
              $main->_tr();
            }
            $main->_tbody();
            $main->_table();
          $main->_div();
          $main->br(2);
          $main->add('<center>');
          $main->add("Your transaction has been completed, and a receipt for your purchase has been emailed to you.<br />You may log into your account at <a href='https://www.paypal.com'>www.paypal.com</a> to view details of this transaction.");
          $main->br(1);
          $main->add("<strong>We recommend PRINTING this for your records.</strong>");
          $main->add('</center>');
          $main->_div();
        }
      } else if (strcmp ($lines[0], "FAIL") == 0) {
        // log for manual investigation
        $main->add("<h2>Transaction Error!</h2>");
        $main->add("This transaction timed out.<br>\n");
      }
    }
    
    fclose ($fp);

    
  break;
  
  case "ipn":
    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    
    foreach ($_POST as $key => $value) {
      $value = urlencode(stripslashes($value));
      $req .= "&$key=$value";
    }

    // post back to PayPal system to validate
    $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
    $header .="Content-Type: application/x-www-form-urlencoded\r\n";
    $header .="Host: www.paypal.com\r\n<http://www.paypal.com/r/n>";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    if (shopConfig('SANDBOX_MODE', $db) != '1') {
      // Live Paypal
      $fp = fsockopen ('ssl://'.shopConfig('PDT_URL', $db), 443, $errno, $errstr, 30);
    } else {
      // Sandbox PayPal
      $fp = fsockopen ('ssl://'.shopConfig('SANDBOX_PDT_URL', $db), 443, $errno, $errstr, 30);
    }
    

    
    $date = date("Y-m-d H:i:s");
    
    if (!$fp) {
      // HTTP ERROR
      $db->sql_query("INSERT INTO mod_shop_ipn (
      mod_shop_ipn_Date, mod_shop_ipn_Status, mod_shop_ipn_Info
      ) VALUES (
      '$date','HTTP ERROR',''
      )");
    } else {
      fputs ($fp, $header . $req);
      while (!feof($fp)) {
        $res = fgets ($fp, 1024);
        if (strcmp ($res, "VERIFIED") == 0) {
          
          $NO_ERROR = true;
          // check that txn_id has not been previously processed
          $txn_log_sql = "SELECT mod_shop_txn_TxnID FROM mod_shop_txn WHERE mod_shop_txn_TxnID='".$_POST['txn_id']."'";
          $txn_log_result = $db->sql_query($txn_log_sql);
          // then log txn_id in database
          if ($db->sql_numrows($txn_log_result) == 0) {
             $db->sql_query("INSERT INTO mod_shop_txn (mod_shop_txn_TxnID) VALUES ('".$_POST['txn_id']."')");
          }
          
          // assign posted variables to local variables
          $item_name = $_POST['item_name'];
          $item_number = $_POST['item_number'];
          $payment_status = $_POST['payment_status'];
          $payment_amount = $_POST['mc_gross'];
          $payment_currency = $_POST['mc_currency'];
          $txn_id = $_POST['txn_id'];
          $receiver_email = $_POST['receiver_email'];
          $payer_email = $_POST['payer_email'];
          
          // process payment
          if ($NO_ERROR) {
            // log success
            $db->sql_query("INSERT INTO mod_shop_ipn (
            mod_shop_ipn_Date, mod_shop_ipn_Status, mod_shop_ipn_Info
            ) VALUES (
            '$date','SUCCESS','$req'
            )");
            
            // Get email info on order from db
            $sql_order = "SELECT mod_shop_orders_CustomerEmail FROM mod_shop_orders WHERE mod_shop_orders_InvoiceID='{$_POST['invoice']}'";
            $result_order = $db->sql_query($sql_order);
            $row_order = $db->sql_fetchrow($result_order);
            
            // check the payment_status is Completed etc...
            switch ($_POST['payment_status']) {
              case 'Completed':
                // All ok send email to all - ready for dispatch
                $db->sql_query("UPDATE mod_shop_orders SET 
                mod_shop_orders_PaymentStatus='{$_POST['payment_status']}',
                mod_shop_orders_PaymentStatusInfo='Ready for Dispatch',
                mod_shop_orders_OrderStatus='PaymentReceived'
                WHERE mod_shop_orders_InvoiceID='{$_POST['invoice']}'");
                // Email customer - payment cleared waiting for dispatch
                $TO = $row_order['mod_shop_orders_CustomerEmail'];
                $CC = '';
                $BCC = 'email-archive@palaeosoc.org';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(5, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
                
                // Email staff - payment cleared waiting for dispatch
                $TO = 'shop-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(8, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
              break;
              
              case 'Denied':
              case 'Expired':
              case 'Voided':
              case 'Failed':
                // Order has had payment fail - cancel order send email to this effect
                $db->sql_query("UPDATE mod_shop_orders SET 
                mod_shop_orders_PaymentStatus='{$_POST['payment_status']}',
                mod_shop_orders_PaymentStatusInfo='Do Not Dispatch - Order Cancled',
                mod_shop_orders_OrderStatus='Canceled'
                WHERE mod_shop_orders_InvoiceID='{$_POST['invoice']}'");
                
                // Email customer - order canceled payment not taken
                $TO = $row_order['mod_shop_orders_CustomerEmail'];
                $CC = '';
                $BCC = 'email-archive@palaeosoc.org';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(7, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
                
                // Email staff - order canceled payment not taken
                $TO = 'shop-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(10, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
              break;
              
              case 'Refunded':
              case 'Reversed':
                // Order has been refunded - cancel order and send email to this effect
                // Get reason form reason_code POST
                $db->sql_query("UPDATE mod_shop_orders SET 
                mod_shop_orders_PaymentStatus='{$_POST['payment_status']}',
                mod_shop_orders_PaymentStatusInfo='Do Not Dispatch - Order Refunded. {$_POST['reason_code']}',
                mod_shop_orders_OrderStatus='Refunded'
                WHERE mod_shop_orders_InvoiceID='{$_POST['invoice']}'");
                
                // Email customer - payment refunded
                $TO = $row_order['mod_shop_orders_CustomerEmail'];
                $CC = '';
                $BCC = 'email-archive@palaeosoc.org';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(6, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
                
                // Email staff - payment refunded
                $TO = 'shop-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(11, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
              break;
              
              case 'Processed':
                // Log but no email - payment accpeted waiting for it to clear
                $db->sql_query("UPDATE mod_shop_orders SET 
                mod_shop_orders_PaymentStatus='{$_POST['payment_status']}',
                mod_shop_orders_PaymentStatusInfo='Payment is Processing',
                mod_shop_orders_OrderStatus='AwaitingPayment'
                WHERE mod_shop_orders_InvoiceID='{$_POST['invoice']}'");
              break;
              
              case 'Canceled_Reversal':
                // Log but no email - payment refund cancled/returned (this would be a manaual problem to solve)
                // Get reason form reason_code POST
                $db->sql_query("UPDATE mod_shop_orders SET 
                mod_shop_orders_PaymentStatus='{$_POST['payment_status']}',
                mod_shop_orders_PaymentStatusInfo='Manual Processing Required. {$_POST['reason_code']}',
                mod_shop_orders_OrderStatus='PaymentReceived'
                WHERE mod_shop_orders_InvoiceID='{$_POST['invoice']}'");
                
              break;
              
              case 'Pending':
                // Log but no email - payment pending (might need to email palaeosoc staff for manual action)
                // get reason from pending_reason POST
                $db->sql_query("UPDATE mod_shop_orders SET 
                mod_shop_orders_PaymentStatus='{$_POST['payment_status']}',
                mod_shop_orders_PaymentStatusInfo='{$_POST['pending_reason']}',
                mod_shop_orders_OrderStatus='AwaitingPayment'
                WHERE mod_shop_orders_InvoiceID='{$_POST['invoice']}'");
                
                // Email staff - payment pending might need manual checking
                $TO = 'shop-orders@palaeosoc.org';
                $CC = '';
                $BCC = '';
                $FROM = '';
                $VAR_ARRAY = array();
                
                $VAR_ARRAY['INVOICE_ID'] = $_POST['invoice'];
              
                emailMailer(12, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
              break;
            }
          }
        } else if (strcmp ($res, "INVALID") == 0) {
          // log for manual investigation
          $db->sql_query("INSERT INTO mod_shop_ipn (
          mod_shop_ipn_Date, mod_shop_ipn_Status, mod_shop_ipn_Info
          ) VALUES (
          '$date','INVALID','$res'
          )");
        }
      }
      fclose ($fp);
    }
    die();
  break;
}
?>
