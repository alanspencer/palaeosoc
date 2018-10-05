<?php

// Add Javascript
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');

$js->script('js','','
  $(document).ready(function(){
    
  });
');

// Produce Page
$main = new xhtml;
$main->div ('membersRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'members/" title="Link: Return to Members Home">Return to Members Home</a>');
$main->_div();
$main->hx(2,'Members\'s Account - Shop Orders','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the viewing of your online shop orders.');
$main->_p();
$main->div ('membersShopOrders','');
  $main->hx(3,'Shop Orders','','');$main->_hx(3);
  $main->p('','');
  $main->add('To view a specific order follow the "View Order" link on the right of the table.');
  $main->_p();
  
  $main->table('', 'membersTable');
  $main->thead('', '');
  $main->tr('', '');
  $main->th('', '', '', '');$main->add('Order ID');$main->_th();
  $main->th('', '', '', '');$main->add('Date/Time');$main->_th();
  $main->th('', '', '', '');$main->add('Status');$main->_th();
  $main->th('', '', '', '');$main->add('N&deg; Items');$main->_th();
  $main->th('', '', '', '');$main->add('Total');$main->_th();
  $main->th('', '', '', '');$main->add('Options');$main->_th();
  $main->_tr();
  $main->_thead();
  $main->tbody('', '');
  
  switch ($_GET['view']) {
    case 'currentOrders':
      // Uncompleted Orders
      $sql_orders = "SELECT 
      mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
      mod_shop_orders_Postage, mod_shop_orders_ItemTotal
      FROM mod_shop_orders 
      WHERE 
      mod_shop_orders_MemberID='{$_SESSION['MEMBER_ID']}' 
      AND ( mod_shop_orders_OrderStatus!='Refunded'
      AND mod_shop_orders_OrderStatus!='Canceled'
      AND mod_shop_orders_OrderStatus!='Dispatched')
      ORDER BY mod_shop_orders_InvoiceID DESC";
    break;
    
    case 'allOrders':
      // Uncompleted Orders
      $sql_orders = "SELECT 
      mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
      mod_shop_orders_Postage, mod_shop_orders_ItemTotal
      FROM mod_shop_orders 
      WHERE 
      mod_shop_orders_MemberID='{$_SESSION['MEMBER_ID']}' 
      ORDER BY mod_shop_orders_InvoiceID DESC";
    break;
    
    case 'completedOrders':
      // Uncompleted Orders
      $sql_orders = "SELECT 
      mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
      mod_shop_orders_Postage, mod_shop_orders_ItemTotal
      FROM mod_shop_orders 
      WHERE 
      mod_shop_orders_MemberID='{$_SESSION['MEMBER_ID']}' 
      AND ( mod_shop_orders_OrderStatus='Refunded'
      OR mod_shop_orders_OrderStatus='Canceled'
      OR mod_shop_orders_OrderStatus='Dispatched')
      ORDER BY mod_shop_orders_InvoiceID DESC";
    break;
  }
  $result_orders = $db->sql_query($sql_orders);
  
  while ($row_orders = $db->sql_fetchrow($result_orders)) {
    list($prefix, $orderDate, $time) = split('-', $row_orders['mod_shop_orders_InvoiceID']);
    list($month, $day, $year) = str_split ($orderDate, 2);
    list($hour, $minute, $second) = str_split ($time, 2);
    $numberItems = 0;
    $orderItem_sql = "SELECT * FROM mod_shop_orders_items WHERE mod_shop_orders_items_InvoiceID='{$row_orders['mod_shop_orders_InvoiceID']}'";
    $orderItem_result = $db->sql_query($orderItem_sql);
    while ($orderItem_row = $db->sql_fetchrow($orderItem_result)) {
      $numberItems += $orderItem_row['mod_shop_orders_items_Quantity'];
    }
    $main->tr('', '');
    $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_InvoiceID']);$main->_td();
    $main->td('', '', '', '');$main->add($day.'/'.$month.'/20'.$year.' '.$hour.':'.$minute.':'.$second);$main->_td();
    $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_OrderStatus']);$main->_td();
    $main->td('', '', '', '');$main->add($numberItems);$main->_td();
    $main->td('', '', '', '');$main->add(money_format('%n', $num->round2DP($row_orders['mod_shop_orders_ItemTotal']+$row_orders['mod_shop_orders_Postage'])));$main->_td();
    $main->td('', '', '', '');$main->add('<a href="?mode=shop&amp;view=order&amp;id='.$row_orders['mod_shop_orders_InvoiceID'].'&amp;returnURL='.$_GET['view'].'" title="View Order: '.$row_orders['mod_shop_orders_InvoiceID'].'">View Order</a>');$main->_td();
    $main->_tr();
  }
  $main->_tbody();
  $main->_table();
  
$main->_div();
