<?php
$errors = array();

/*--------------------------------------------------------------*/
/* Function for Remove escapes special
/* characters in a string for use in an SQL statement
/*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}

/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}

/*--------------------------------------------------------------*/
/* Function for Uppercase first character
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}

/*--------------------------------------------------------------*/
/* Function for Checking input fields not empty
/*--------------------------------------------------------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." can't be blank.";
      return $errors;
    }
  }
}

/*--------------------------------------------------------------*/
/* Function for Display Session Message
/* Ex echo display_msg($message);
/*--------------------------------------------------------------*/
function display_msg($msg =''){
  $output = array();
  if(!empty($msg)) {
    foreach ($msg as $key => $value) {
      $output  = "<div class=\"alert alert-{$key}\">";
      $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
      $output .= remove_junk(first_character($value));
      $output .= "</div>";
    }
    return $output;
  } else {
    return "" ;
  }
}

/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
  if (headers_sent() === false) {
    header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
  }
  exit();
}

/*--------------------------------------------------------------*/
/* Function for find out total selling price, buying price and profit
/*--------------------------------------------------------------*/
function total_price($totals){
  $sum = 0;
  $sub = 0;
  foreach($totals as $total ){
    $sum += $total['total_selling_price'];
    $sub += $total['total_buying_price'];
    $profit = $sum - $sub;
  }
  return array($sum,$profit);
}

/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function read_date($str){
  if($str) {
    return date('F j, Y, g:i:s a', strtotime($str));
  } else {
    return null;
  }
}

/*--------------------------------------------------------------*/
/* Function for Readable Make date time
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}

/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}

/*--------------------------------------------------------------*/
/* Function for Creating random string
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str = '';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++) {
    $str .= $cha[mt_rand(0,strlen($cha))];
  }
  return $str;
}

/*--------------------------------------------------------------*/
/* Function for finding all items taken
/*--------------------------------------------------------------*/
function find_all_items_taken() {
  global $db;
  $sql  = "SELECT p.name AS product_name, s.quantity, s.issued_to, c.name AS category, s.date_issued ";
  $sql .= "FROM stock_out s ";
  $sql .= "JOIN products p ON p.id = s.product_id ";
  $sql .= "JOIN categories c ON c.id = s.category_id";
  $result = $db->query($sql);
  return $result ? $result->fetch_all(MYSQLI_ASSOC) : false;
}

/*--------------------------------------------------------------*/
/* Function for finding all returned products
/*--------------------------------------------------------------*/
function find_all_returned_products() {
  global $db;
  $sql  = "SELECT p.name AS product_name, r.quantity, r.returned_by, r.reason, r.date_returned ";
  $sql .= "FROM returned_products r ";
  $sql .= "JOIN stock_in p ON p.id = r.stock_in_id";
  $result = $db->query($sql);
  return $result ? $result->fetch_all(MYSQLI_ASSOC) : false;
}

/*--------------------------------------------------------------*/
/* Function for generating reports
/*--------------------------------------------------------------*/
function generate_report($type) {
  global $db;
  $sql = "";
  switch($type) {
    case 'daily':
      $sql = "SELECT * FROM stock_in WHERE DATE(date) = CURDATE()";
      break;
    case 'weekly':
      $sql = "SELECT * FROM stock_in WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
      break;
    case 'monthly':
      $sql = "SELECT * FROM stock_in WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
      break;
    case 'yearly':
      $sql = "SELECT * FROM stock_in WHERE YEAR(date) = YEAR(CURDATE())";
      break;
  }
  return find_by_sql($sql);
}

?>
