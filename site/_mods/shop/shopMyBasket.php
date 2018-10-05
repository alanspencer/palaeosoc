<?php
$removeItem = false;
$clearBasket = false;
// Add to Basket
if ((isset($_POST['addToBasket'])) AND (($_POST['quantityToAdd'] != '') OR ($_POST['quantityToAdd'] != '0')) AND ($_POST['shopItemRef'] != '')) {
  // Check to see if there is already an item with same ref, if so add to it.
  if (isset($_SESSION['shopMyBasket']['items']) AND (array_key_exists($_POST['shopItemRef'], $_SESSION['shopMyBasket']['items']))) {
    $_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'] = $_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'] + $_POST['quantityToAdd'];
    $_SESSION['shopMyBasket']['totalPrice'] = $_SESSION['shopMyBasket']['totalPrice'] + ($_POST['shopItemPrice']*$_POST['quantityToAdd']);
    $_SESSION['shopMyBasket']['totalMemPrice'] = $_SESSION['shopMyBasket']['totalMemPrice'] + ($_POST['shopItemMemPrice']*$_POST['quantityToAdd']);
    $_SESSION['shopMyBasket']['totalItems'] = $_SESSION['shopMyBasket']['totalItems']+$_POST['quantityToAdd'];
  } else {
    // Add new entry
    if (isset($_POST['shopItemPreference'])) {
      $_POST['shopItemPreference'] == 'original' ? $printType='ORIG' : $printType='RP';
      $preference = ' [Pref: '.$_POST['shopItemPreference'].']';
      $preferenceShort = '['.$printType.'] ';
    } else {
      $preference ='';
      $preferenceShort ='';
    } 
    // array (id, type, quantity ,titleFull, titleShort, CoverPrice, Price, MemPrice, PostageReq);
    $_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']] = array(
    'id'=>$_POST['shopItemID'],
    'type'=>$_POST['shopItemType'],
    'quantity'=>$_POST['quantityToAdd'],
    'titleFull'=>$_POST['shopItemTitleFull'].$preference,
    'titleShort'=>$preferenceShort.$_POST['shopItemTitleShort'],
    'coverPrice'=>$_POST['shopItemCoverPrice'],
    'price'=>$_POST['shopItemPrice'],
    'memPrice'=>$_POST['shopItemMemPrice'],
    'postageReq'=>$_POST['shopItemPostageReq']);
    $_SESSION['shopMyBasket']['totalPrice'] = @$_SESSION['shopMyBasket']['totalPrice'] + ($_POST['shopItemPrice']*$_POST['quantityToAdd']);
    $_SESSION['shopMyBasket']['totalMemPrice'] = @$_SESSION['shopMyBasket']['totalMemPrice'] + ($_POST['shopItemMemPrice']*$_POST['quantityToAdd']);
    $_SESSION['shopMyBasket']['totalItems'] = @$_SESSION['shopMyBasket']['totalItems']+$_POST['quantityToAdd'];
  }
}
// Update Item Value
if ((isset($_POST['newItemQuantity'])) AND ($_POST['newItemQuantity'] != '')) {
  if ($_POST['newItemQuantity'] == 0) {
    // remove Item
    $removeItem = true;
  } else {
    // Update Item Quantity
    if($_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'] > $_POST['newItemQuantity']) {
      // Reduce Quantity
      $multiplyBy =  $_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'] - $_POST['newItemQuantity'];
      $_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'] = $_POST['newItemQuantity'];
      $_SESSION['shopMyBasket']['totalPrice'] = $_SESSION['shopMyBasket']['totalPrice'] - ($_POST['shopItemPrice']*$multiplyBy);
      $_SESSION['shopMyBasket']['totalMemPrice'] = $_SESSION['shopMyBasket']['totalMemPrice'] - ($_POST['shopItemMemPrice']*$multiplyBy);
      $_SESSION['shopMyBasket']['totalItems'] = $_SESSION['shopMyBasket']['totalItems']-$multiplyBy;
    } 
    if($_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'] < $_POST['newItemQuantity']) {
      // Increase Quantity
      $multiplyBy = $_POST['newItemQuantity'] - $_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'];
      $_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'] = $_POST['newItemQuantity'];
      $_SESSION['shopMyBasket']['totalPrice'] = $_SESSION['shopMyBasket']['totalPrice'] + ($_POST['shopItemPrice']*$multiplyBy);
      $_SESSION['shopMyBasket']['totalMemPrice'] = $_SESSION['shopMyBasket']['totalMemPrice'] + ($_POST['shopItemMemPrice']*$multiplyBy);
      $_SESSION['shopMyBasket']['totalItems'] = $_SESSION['shopMyBasket']['totalItems']+$multiplyBy;
    }
  }
}
// Remove Item
if (($removeItem) OR (isset($_POST['removeItem']))) {
  // Find how many are being removed and update overall prices/item nubers
  $multiplyBy =  $_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]['quantity'];
  $_SESSION['shopMyBasket']['totalPrice'] = $_SESSION['shopMyBasket']['totalPrice'] - ($_POST['shopItemPrice']*$multiplyBy);
  $_SESSION['shopMyBasket']['totalMemPrice'] = $_SESSION['shopMyBasket']['totalMemPrice'] - ($_POST['shopItemMemPrice']*$multiplyBy);
  $_SESSION['shopMyBasket']['totalItems'] = $_SESSION['shopMyBasket']['totalItems']-$multiplyBy;
  // Now remove item from array
  foreach($_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']] as $key => $val) {
    unset ($_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']][$key]);
  }
  unset ($_SESSION['shopMyBasket']['items'][$_POST['shopItemRef']]);
  // Now check to see if the basket is now empty if so wipe whole basket
  if ((empty($_SESSION['shopMyBasket']['items'])) OR ($_SESSION['shopMyBasket']['totalItems'] == 0)) {
    $clearBasket = true;
  }
}
// Clear Basket
if ((isset($_POST['clearBasket'])) OR ($clearBasket)) {
  if (!empty($_SESSION['shopMyBasket']['items'])) {
    foreach($_SESSION['shopMyBasket']['items'] as $item => $val) {
      foreach($_SESSION['shopMyBasket']['items'][$item] as $key => $val) {
        unset ($_SESSION['shopMyBasket']['items'][$item][$val]);
      }
      unset ($_SESSION['shopMyBasket']['items'][$item]);
    }
  }
  unset ($_SESSION['shopMyBasket'], $_SESSION['shopMyBasket']['totalItems'],$_SESSION['shopMyBasket']['totalMemPrice'],$_SESSION['shopMyBasket']['totalPrice']);
}
// Postage
if (!isset($_SESSION['shopMyBasket']['currentPostage'])) {
  $_SESSION['shopMyBasket']['currentPostage'] = 'uk';
}
if (isset($_POST['shopPostageChange'])) {
  $_SESSION['shopMyBasket']['currentPostage'] = $_POST['shopPostageChange'];
}

// My Basket Page
// Return Link
$main->div ('pageRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a> > <a href="'.__SITEURL.'shop/home/" title="Return to Shop Home Page">Shop Home</a>');
$main->_div(); 

// Introduction
$main->div('shopTemplateBasketIntro',''); 
  $main->hx(2,'Online Shop - My Basket','','');$main->_hx(2);           
  $main->add('The contents of your shopping basket are shown below:');
$main->_div();

//print_r($_SESSION['shopMyBasket']);

if((!isset($_SESSION['shopMyBasket']['totalItems'])) OR ($_SESSION['shopMyBasket']['totalItems'] == '0')) {
  $main->div('shopTemplateBasketContent','');
  $main->div('shopTemplateBasketClear','');
    $main->input ('submit', 'clearBasket', 'Empty Basket', '', 'clearBasket', '', '1', '1');
  $main->_div();
  $main->hx(3,'Basket','','');$main->_hx(3);   
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
  $main->tr('','');
    $main->td('','','','4');
    $main->add('<center><p><strong>Your shopping basket is empty.</strong><br /><a href="'.__SITEURL.'shop/home/" title="Link: Online Shop">Go to Online Shop...</a></p></center>');
    $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  $main->_div();
} else {
  $main->form('','POST','','shopClearBasket', '');
    
  $js->script('js','','
    $(document).ready(function(){
      $("#clearBasket").click(function() {
        if (confirm("Are you sure you want to REMOVE ALL items?")) {
          this.form.submit();
        } else {
          return false;
        }
      });
      $(".newItemQuantity").each(function(intIndex){
        $(this).bind("change",function() {
          this.form.submit();
        });
      });
      $(".removeItem").each(function(intIndex){
        $(this).bind("click",function() {
          if (confirm("Are you sure you want to REMOVE this item?")) {
            this.form.submit();
          } else {
            return false;
          }
        });
      });
      $("#shopPostageChange").change(function() {
        this.form.submit();
      });
    });
  ');
  $main->div('shopTemplateBasketContent','');   
  $main->div('shopTemplateBasketClear','');
    $main->input ('submit', 'clearBasket', 'Empty Basket', '', 'clearBasket', '', '', '');
  $main->_div();
  $main->hx(3,'Basket','','');$main->_hx(3);        
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
      $main->form('','POST','','shopUpdateItems', '');
      $main->input ('text', 'newItemQuantity', $itemArray['quantity'], '', '', 'newItemQuantity', '', '');
      $main->input ('hidden', 'shopItemRef', $itemArray['type'].$itemArray['id'], '', '', '', '', '');
      $main->input ('hidden', 'shopItemPrice', $itemArray['price'], '', '', '', '', '');
      $main->input ('hidden', 'shopItemMemPrice', $itemArray['memPrice'], '', '', '', '', '');
      $main->input ('submit', 'updateQuantity', 'Update', '', '', 'hidden', '', '');
      $main->add('<input type="image" name="removeItem" class="removeItem" src="'.__SITEURL.'image/shop/removeButton.png" value="Remove" title="Remove Item"/>');
      $main->_form();
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
    $main->form('','POST','','shopUpdatePostage', '');
    $main->add('Postage to ');
    $main->select('shopPostageChange', 'shopPostageChange', '', '', '');
    $postagePrice = 0; 
    $sql_post = "SELECT * FROM shop_postage";
    $result_post = $db->sql_query($sql_post);
    while ($row_post = $db->sql_fetchrow($result_post)) {
      if ($_SESSION['shopMyBasket']['currentPostage'] == $row_post['shop_postage_Zone']) {
        $main->option($row_post['shop_postage_Zone'], $row_post['shop_postage_ZoneName'], '1');
        $postagePrice = $row_post['shop_postage_Price'];
      } else {
        $main->option($row_post['shop_postage_Zone'], $row_post['shop_postage_ZoneName'], '');
      }
    }
    $main->_select();
    $main->input ('submit', 'updatePostage', 'Update', '', '', 'hidden', '', '');
    $main->_form();
    $main->_td();
    $main->td('','','','');
    $main->add(money_format('%n', $num->round2DP($postagePrice)));
    $main->_td();
    $main->_tr();
    if ($MEMBERVERIFIED) {
      // Members' Price
      //$main->tr('','');
      //$main->td('','alignRight','','3');
      //$main->add('Total Saving:');
      //$main->_td();
      //$main->td('','','','');
      //$main->add('-'.money_format('%n', $num->round2DP($_SESSION['shopMyBasket']['totalPrice']-$_SESSION['shopMyBasket']['totalMemPrice'])));
      //$main->_td();
      //$main->_tr();
      $main->tr('','');
      $main->td('','alignRight','','3');
      $main->add('Members\' Item(s) Total:');
      $main->_td();
      $main->td('','','','');
      $main->add('<strong>'.money_format('%n', $num->round2DP($_SESSION['shopMyBasket']['totalMemPrice']+$postagePrice)).'</strong>');
      $main->_td();
      $main->_tr();
    } else {
      // Total Price
      $main->tr('','');
      $main->td('','alignRight','','3');
      $main->add('Item(s) Total:');
      $main->_td();
      $main->td('','','','');
      $main->add('<strong>'.money_format('%n', $num->round2DP($_SESSION['shopMyBasket']['totalPrice']+$postagePrice)).'</strong>*');
      $main->_td();
      $main->_tr();
    }
    $main->_tbody();
    $main->_table();
    $main->add('<span class="basketTerms">*Member\'s who are not yet logged in will be able to recieve their discount during Checkout</span>');
  $main->_div();
  
  $main->div('shopTemplateBasketLinks','');
    $main->add('<p><a href="'.__SITEURL.'shop/home/?view=checkout" title="Link: Checkout"><img class="goToCheckout" src="'.__SITEURL.'image/shop/goToCheckout.png" alt="Link: Checkout" /></a></p>');
  $main->_div();
  if (isset($_POST['returnPage'])) {
    $main->add('<p><a href="'.$_POST['returnPage'].'" title="Link: Continue Shopping"><img class="continueShopping" src="'.__SITEURL.'image/shop/continueShopping.png" alt="Link: Continue Shopping" /></a></p>');
  } else {
    $main->add('<p><a href="'.__SITEURL.'shop/home/" title="Link: Continue Shopping"><img class="continueShopping" src="'.__SITEURL.'image/shop/continueShopping.png" alt="Link: Continue Shopping" /></a></p>');
  }
}

// Add Recomendations
// 3 cols with random 3 monographs (those with images)

$sql_monographs = "SELECT 
shop_monographs.shop_monographs_ID, shop_monographs.shop_monographs_Volume, shop_monographs.shop_monographs_Part,
shop_monographs.shop_monographs_IssueNumber, shop_monographs.shop_monographs_Title, shop_monographs.shop_monographs_Pagination,
shop_monographs.shop_monographs_Year, shop_monographs.shop_monographs_SalePrice, shop_monographs.shop_monographs_CoverPrice, 
shop_monographs.shop_monographs_MembersPrice, shop_monographs.shop_monographs_StockOriginal, shop_monographs.shop_monographs_StockReprint,
shop_monographs.shop_monographs_Thumbnail
FROM 
shop_monographs 
WHERE 
shop_monographs.shop_monographs_Thumbnail!='' AND shop_monographs.shop_monographs_StockOriginal+shop_monographs.shop_monographs_StockReprint!=0 
ORDER BY RAND() 
LIMIT 3";
$result_monographs = $db->sql_query($sql_monographs);

for ($x=1;$x!=4;$x++) {
  $row_shop_mono = $db->sql_fetchrow($result_monographs);
  // Get Authors
  $sql_shop_mono_author = "SELECT 
  shop_monographs_authors.shop_monographs_authors_LastName, shop_monographs_authors.shop_monographs_authors_FirstNames
  FROM shop_monographs_to_authors JOIN shop_monographs_authors ON 
  shop_monographs_to_authors.shop_monographs_to_authors_Author=shop_monographs_authors.shop_monographs_authors_ID 
  WHERE shop_monographs_to_authors.shop_monographs_to_authors_Journal='{$row_shop_mono['shop_monographs_ID']}' 
  ORDER BY shop_monographs_authors.shop_monographs_authors_LastName ASC";
  $results_shop_mono_author = $db->sql_query($sql_shop_mono_author);
  $authors = '';
  $authorsSimple = '';
  $authorCount = $db->sql_numrows($results_shop_mono_author);
  if ($authorCount !=0 ) {
    while ($row_shop_mono_author = $db->sql_fetchrow($results_shop_mono_author)) {
      $authors .= '<a href="'. __SITEURL.'search/?view=results&amp;type=advShop&amp;author='.strtolower($row_shop_mono_author['shop_monographs_authors_LastName']).'" title="Search: '.$row_shop_mono_author['shop_monographs_authors_LastName'].'">'.$row_shop_mono_author['shop_monographs_authors_LastName'].', '.$row_shop_mono_author['shop_monographs_authors_FirstNames'].'</a>';
      $authorsSimple .= strtolower($row_shop_mono_author['shop_monographs_authors_LastName']);
      $authorCount--;
      if ($authorCount == 1) {
        $authors .= ' &amp; ';
        $authorsSimple .= ' &amp; ';
      }
      if ($authorCount > 1) {
        $authors .= ', ';
        $authorsSimple .=', ';
      }
      $authors .= ' ';
      $authorsSimple .= ' ';
    }
  } else {
    $authors = '{Undefined Author} ';
    $authorsSimple = 'undefined';
  }
  $searchString = str_replace(' ', '+', $row_shop_mono['shop_monographs_Title']);
  $output = "$authors{$row_shop_mono['shop_monographs_Year']}. <a href=\"". __SITEURL."search/?view=results&type=advShop&title=$searchString\" title=\"Search: {$row_shop_mono['shop_monographs_Title']}\">{$row_shop_mono['shop_monographs_Title']}</a> ";
  if ($row_shop_mono['shop_monographs_Part'] != '') {
    $output .= "Part {$row_shop_mono['shop_monographs_Part']}. ";
  }
  $output .= "<em>Monograph of the Palaeontographical Society</em> London: {$row_shop_mono['shop_monographs_Pagination']} (Issue {$row_shop_mono['shop_monographs_IssueNumber']}";
  if ($row_shop_mono['shop_monographs_Volume'] != '') {
    $output .= ", part of Volume {$row_shop_mono['shop_monographs_Volume']}";
  }
  $output .= ")";
  
  $z ='recommendationCol'.$x;
  $$z = '<a href="'.__SITEURL.'shop/monographs/issue:'.$row_shop_mono['shop_monographs_IssueNumber'].'/">
  <img class="monographThumb" src="'.__SITEURL.'image/shopMonographs/thumb/'.$row_shop_mono['shop_monographs_Thumbnail'].'" alt="Image: '
  .$row_shop_mono['shop_monographs_Title'].'" title="Image: '.$row_shop_mono['shop_monographs_Title'].'" /></a><br />'
  .$output
  .'<br />Price: <span class="priceSalePrice">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])).'</span>'
  .'<br /><br /><a href="'.__SITEURL.'shop/monographs/issue:'.$row_shop_mono['shop_monographs_IssueNumber'].'/">
  <img class="moreDetails" src="'.__SITEURL.'image/shop/moreDetails.png" alt="Link: '
  .$row_shop_mono['shop_monographs_Title'].'" title="Link: '.$row_shop_mono['shop_monographs_Title'].'" /></a>';
}

$main->div('shopTemplateBasketRecommendations','');
  $main->hx(3,'Recommendations','','');$main->_hx(3);
  $main->div('shopTemplateCols','yui-gb');
    $main->div('shopTemplateRecommendationsCol1','yui-u first');
      $main->hx(3,'1','','hidden');$main->_hx(3);
      $main->add($recommendationCol1);
    $main->_div();
    $main->div('shopTemplateRecommendationsCol2','yui-u second');
      $main->hx(3,'2','','hidden');$main->_hx(3);
      $main->add($recommendationCol2);
    $main->_div();
    $main->div('shopTemplateRecommendationsCol3','yui-u');
      $main->hx(3,'3','','hidden');$main->_hx(3);
      $main->add($recommendationCol3);
    $main->_div();
  $main->_div();
$main->_div();
?>
