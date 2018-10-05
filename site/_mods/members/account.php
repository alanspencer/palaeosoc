<?php

function date_membership_remaining_days($date)
{
    $date = '31 December ' . $date;
    $days = ((int)((strtotime($date) - strtotime(date("Y-m-d"))) / (60 * 60 * 24)));
    if ($days >= 365) {
        $years = ((int)($days / 365));
        if ($years > 1) {
            $years_text = 'years';
        } else {
            $years_text = 'year';
        }
        $days = $days % 365;
        if ($days > 1) {
            $days_text = 'days';
        } else {
            $days_text = 'day';
        }
        return "<span style=\"color:green;\"><strong>$years</strong> $years_text <strong>$days</strong> $days_text remaining</span>";

    } elseif (($days <= 364) AND ($days >= 30)) {
        return "<span style=\"color:green;\"><strong>0</strong> years <strong>$days</strong> days remaining</span>";

    } elseif (($days <= 30) AND ($days >= 1)) {
        if ($days > 1) {
            $days_text = days;
        } else {
            $days_text = day;
        }
        return "<span style=\"color:red;\"><strong>0</strong> years <strong>$days</strong> $days_text remaining</span>\n";
    } elseif ($days <= 0) {
        return "<span style=\"color:red;\">Expired!</span>";
    }
}

$sql_members = "SELECT * FROM mod_members_users WHERE mod_members_users_ID='{$_SESSION['MEMBER_ID']}'";
$result_members = $db->sql_query($sql_members);
$row_members = $db->sql_fetchrow($result_members);

$main = new xhtml;
$main->hx(2, 'Member\'s Account', '', '');
$main->_hx(2);
$main->p('', '');
$main->add('Hello ' . $row_members['mod_members_users_Title'] . ' ' . $row_members['mod_members_users_LastName'] . ', ' . $row_members['mod_members_users_FirstNames']);
$main->add(' | <a href="?mode=logout" title="Link: Logout">LOGOUT</a>');
$main->_p();
$main->p('', '');
$main->add('Your "Member\'s Account"');

$main->_p();
$main->div('membersAccount', '');
$main->hx(3, 'My Subscription', '', '');
$main->_hx(3);

$main->p('', '');
$main->add('Membership Type: <strong>' . $row_members['mod_members_users_Type'] . '</strong>');
if ($row_members['mod_members_users_Type'] == 'Student') {
    $main->add(' | Student Registration Ends: <strong>' . $row_members['mod_members_users_StudentEnd'] . '</strong>');
}
$main->_p();

$main->p('', '');
$endTime = strtotime('31 December ' . $row_members['mod_members_users_SubYear']);
$now = time();
$timeleft = $endTime - $now;
$daysleft = round((($timeleft / 24) / 60) / 60);
$main->add('Your current subscription is valid untill: <strong>31<sup>st</sup> December ' . $row_members['mod_members_users_SubYear'] . ' | ' . date_membership_remaining_days($row_members['mod_members_users_SubYear']) . '</strong>');
$main->_p();

$main->hx(3, 'Options', '', '');
$main->_hx(3);
// Three Columns
$main->div('membersAccountCols', 'yui-gb');
$main->div('membersAccountCol1', 'yui-u first');
$main->hx(4, 'My Details', '', '');
$main->_hx(4);
$main->ul('', '');
$main->li('<a href="?mode=myDetails&amp;view=username" title="Link: Change Username">Change Username</a>', '', '');
$main->_li();
$main->li('<a href="?mode=myDetails&amp;view=password" title="Link: Change Password">Change Password</a>', '', '');
$main->_li();
$main->li('<a href="?mode=myDetails&amp;view=personal" title="Link: Edit Personal Details">Edit Personal Details</a>', '', '');
$main->_li();
//$main->add('No Links');
$main->_ul();
$main->_div();
$main->div('membersAccountCol2', 'yui-u');
$main->hx(4, 'My Subscription', '', '');
$main->_hx(4);
$main->ul('', '');
if (date("Y") >= (int)($row_members['mod_members_users_SubYear'])) {
    $main->li('<a href="' . __SITEURL . 'members/renew/?membership=individual" title="Link: Renew Subscription">Renew Subscription</a>', '', '');
    $main->_li();
} else {
    $main->li('<span class="notActive">Renew Subscription</span>', '', '');
    $main->_li();
}
//$main->li('<span class="notActive">Cancel Subscription</span>','','');$main->_li();
$main->_ul();
$main->_div();
$main->div('membersAccountCol3', 'yui-u');
/*
$main->hx(4,'Online Shop','','');$main->_hx(4);
$main->ul('','');
$sql_orders = "SELECT mod_shop_orders_ID FROM mod_shop_orders WHERE mod_shop_orders_MemberID='{$_SESSION['MEMBER_ID']}'";
$result_orders = $db->sql_query($sql_orders);
if ($db->sql_numrows($result_orders) != 0) {
  $sql_orders_uncompleted = "SELECT mod_shop_orders_ID
  FROM mod_shop_orders
  WHERE
  mod_shop_orders_MemberID='{$_SESSION['MEMBER_ID']}'
  AND ( mod_shop_orders_OrderStatus!='Refunded'
  AND mod_shop_orders_OrderStatus!='Canceled'
  AND mod_shop_orders_OrderStatus!='Dispatched')";
  $result_orders_uncompleted = $db->sql_query($sql_orders_uncompleted);
  if ($db->sql_numrows($result_orders_uncompleted) != 0) {
    $main->li('<a href="?mode=shop&amp;view=currentOrders" title="Link: View Current Orders">View Current Orders</a>','','');$main->_li();
  } else {
    $main->li('<span class="notActive">View Current Orders</span>','','');$main->_li();
  }

  $sql_orders_completed = "SELECT mod_shop_orders_ID
  FROM mod_shop_orders
  WHERE
  mod_shop_orders_MemberID='{$_SESSION['MEMBER_ID']}'
  AND ( mod_shop_orders_OrderStatus='Refunded'
  OR mod_shop_orders_OrderStatus='Canceled'
  OR mod_shop_orders_OrderStatus='Dispatched')";
  $result_orders_completed = $db->sql_query($sql_orders_completed);
  if ($db->sql_numrows($result_orders_completed) != 0) {
    $main->li('<a href="?mode=shop&amp;view=completedOrders" title="Link: View Completed Orders">View Completed Orders</a>','','');$main->_li();
  } else {
    $main->li('<span class="notActive">View Completed Orders</span>','','');$main->_li();
  }
  $main->li('<a href="?mode=shop&amp;view=allOrders" title="Link: View All Orders">View All Orders</a>','','');$main->_li();
} else {
  $main->li('<span class="notActive">View Current Orders</span>','','');$main->_li();
  $main->li('<span class="notActive">View Completed Orders</span>','','');$main->_li();
  $main->li('<span class="notActive">View All Orders</span>','','');$main->_li();
}
$main->_ul();
*/
$main->_div();
$main->_div();
$main->_div();

?>
