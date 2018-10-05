<?php
$order_sql = "SELECT mod_shop_orders_MemberID FROM mod_shop_orders WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'";
$order_result = $db->sql_query($order_sql);
$num_order = $db->sql_numrows($order_result);
$row_order = $db->sql_fetchrow($order_result);

if (($num_order==0) OR ($row_order['mod_shop_orders_MemberID']!=$_SESSION['MEMBER_ID'])) {
  header("Location: ".__SITEURL."members/account/?mode=shop&view={$_GET['returnURL']}");
  die();
} else {
  $order_sql = "SELECT * FROM mod_shop_orders WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'";
  $order_result = $db->sql_query($order_sql);
  $row_order = $db->sql_fetchrow($order_result);
  
  // Produce Page
  $main = new xhtml;
  $main->div ('membersRetrunLinksTop','');
  $main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Home">Return to Members Home</a>');
  $main->_div();
  $main->hx(2,'Members\'s Account - Shop Order View','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the viewing of your online shop orders.');
  $main->_p();
  $main->div ('membersShopOrderView','');
    $main->hx(3,'Order: '.$_GET['id'],'','');$main->_hx(3);
    $main->p('','');
    $main->add('To return to the Orders List page follow this link: <a href="?mode=shop&amp;view='.$_GET['returnURL'].'" title="Link: Orders List Page">Orders List Page</a>');
    $main->_p();
    
    $main->div('shopTemplateCheckoutConfirmationInvoice',''); 
  
    $main->div('shopTemplateCheckoutPayment','');
    $main->hx(4,'Payment Details','','');$main->_hx(4);
    $main->table('', 'checkoutTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order/Invoice N&deg;:');$main->_td();
      $main->td('', '', '', '');
      $main->add($_GET['id']);
      $main->_td();
      $main->_tr();
      
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order Status:');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_order['mod_shop_orders_OrderStatus']);
      $main->_td();
      $main->_tr();
      
      if ($row_order['mod_shop_orders_OrderStatus'] == 'Dispatched') {
        $main->tr('', '');
        $main->td('', 'title', '', '');$main->add('Dispatch Date:');$main->_td();
        $main->td('', '', '', '');
        $main->add($row_order['mod_shop_orders_DispatchedDate']);
        $main->_td();
        $main->_tr();
      }
      
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order Date:');$main->_td();
      $main->td('', '', '', '');
      list($prefix, $orderDate, $time) = split('-', $row_order['mod_shop_orders_InvoiceID']);
      list($month, $day, $year) = str_split ($orderDate, 2);
      list($hour, $minute, $second) = str_split ($time, 2);
      $main->add($day.'/'.$month.'/20'.$year.' '.$hour.':'.$minute.':'.$second);
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
      
      
      $orderItem_sql = "SELECT * FROM mod_shop_orders_items WHERE mod_shop_orders_items_InvoiceID='{$row_order['mod_shop_orders_InvoiceID']}'";
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
    $main->add("You may log into your account at <a href='https://www.paypal.com'>www.paypal.com</a> to view details of this transaction.");
    $main->add('</center>');
    $main->_div();
    
  $main->_div();
}
?>
