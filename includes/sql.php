<?php
require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function to find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table)) {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}

/*--------------------------------------------------------------*/
/* Function to perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql) {
  global $db;
  $result = $db->query($sql);
  if (!$result) {
    // Log SQL error
    error_log("Error on this Query: " . $sql);
    error_log("SQL Error: " . $db->error);
    return []; // Return an empty array on error
  }
  $result_set = $db->while_loop($result);
  return $result_set;
}

/*--------------------------------------------------------------*/
/* Function to find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table, $id) {
  global $db;
  $id = (int)$id;
  if(tableExists($table)) {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
    if($result = $db->fetch_assoc($sql))
      return $result;
    else
      return null;
  }
}

/*--------------------------------------------------------------*/
/* Function to delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table, $id) {
  global $db;
  if(tableExists($table)) {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}

/*--------------------------------------------------------------*/
/* Function to count id by table name
/*--------------------------------------------------------------*/
function count_by_id($table) {
  global $db;
  if(tableExists($table)) {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
    return($db->fetch_assoc($result));
  }
}

/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table) {
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
  if($table_exit) {
    if($db->num_rows($table_exit) > 0)
      return true;
    else
      return false;
  }
}

/*--------------------------------------------------------------*/
/* Login with the data provided in $_POST,
/* coming from the login form.
/*--------------------------------------------------------------*/
function authenticate($username='', $password='') {
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id, username, password, user_level FROM users WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if($password_request === $user['password']) {
      return $user['id'];
    }
  }
  return false;
}

/*--------------------------------------------------------------*/
/* Function to find current logged-in user by session id
/*--------------------------------------------------------------*/
function current_user() {
  static $current_user;
  global $db;
  if(!$current_user) {
    if(isset($_SESSION['user_id'])) {
      $user_id = intval($_SESSION['user_id']);
      $current_user = find_by_id('users', $user_id);
    }
  }
  return $current_user;
}

/*--------------------------------------------------------------*/
/* Function to find all users
/* Joining users table and user groups table
/*--------------------------------------------------------------*/
function find_all_user() {
  global $db;
  $results = array();
  $sql = "SELECT u.id, u.name, u.username, u.user_level, u.status, u.last_login, g.group_name ";
  $sql .= "FROM users u ";
  $sql .= "LEFT JOIN user_groups g ON g.group_level = u.user_level ";
  $sql .= "ORDER BY u.name ASC";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for updating the last login of a user
/*--------------------------------------------------------------*/
function updateLastLogIn($user_id) {
  global $db;
  $date = make_date();
  $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
  $result = $db->query($sql);
  return ($result && $db->affected_rows() === 1 ? true : false);
}

/*--------------------------------------------------------------*/
/* Function to find all group names
/*--------------------------------------------------------------*/
function find_by_groupName($val) {
  global $db;
  $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return($db->num_rows($result) === 0 ? true : false);
}

/*--------------------------------------------------------------*/
/* Function to find group level
/*--------------------------------------------------------------*/
function find_by_groupLevel($level) {
  global $db;
  $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
  $result = $db->query($sql);
  return($db->num_rows($result) === 0 ? true : false);
}

/*--------------------------------------------------------------*/
/* Function for checking which user level has access to page
/*--------------------------------------------------------------*/
function page_require_level($require_level) {
  global $session;
  $current_user = current_user();
  $login_level = find_by_groupLevel($current_user['user_level']);
  // If user not logged in
  if (!$session->isUserLoggedIn(true)) {
    $session->msg('d','Please login...');
    redirect('index.php', false);
  }
  // If group status is Deactive
  elseif($login_level['group_status'] === '0') {
    $session->msg('d','This level user has been banned!');
    redirect('home.php',false);
  }
  // Checking if logged-in user level is less than or equal to required level
  elseif($current_user['user_level'] <= (int)$require_level) {
    return true;
  } else {
    $session->msg("d", "Sorry! You don't have permission to view the page.");
    redirect('home.php', false);
  }
}

/*--------------------------------------------------------------*/
/* Function for finding all product information
/* Joining stock_in table, suppliers table, and categories table
/*--------------------------------------------------------------*/
function join_product_table() {
  global $db;
  $sql  = "SELECT s.id, s.product_name AS name, s.quantity AS quantity,";
  $sql .= " s.buy_price AS buy_price, s.sale_price AS sale_price,";
  $sql .= " c.name AS categorie, sup.name AS supplier";
  $sql .= " FROM stock_in s";
  $sql .= " LEFT JOIN categories c ON c.id = s.category_id";
  $sql .= " LEFT JOIN suppliers sup ON sup.id = s.supplier_id";
  $sql .= " ORDER BY s.id DESC LIMIT 5";

  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for finding recent product added
/*--------------------------------------------------------------*/
function find_recent_product_added($limit) {
  global $db;
  $sql  = "SELECT s.id, s.product_name AS name, s.sale_price, c.name AS categorie, sup.name AS supplier";
  $sql .= " FROM stock_in s";
  $sql .= " LEFT JOIN categories c ON c.id = s.category_id";
  $sql .= " LEFT JOIN suppliers sup ON sup.id = s.supplier_id";
  $sql .= " ORDER BY s.id DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for updating product quantity
/*--------------------------------------------------------------*/
function update_stock_in_qty($qty, $product_id) {
  global $db;
  $qty = (int)$qty;
  $id  = (int)$product_id;
  $sql = "UPDATE stock_in SET quantity = quantity - '{$qty}' WHERE id = '{$id}'";
  $result = $db->query($sql);
  return ($db->affected_rows() === 1 ? true : false);
}

/*--------------------------------------------------------------*/
/* Function for checking product quantity
/*--------------------------------------------------------------*/
function find_product_by_title($title) {
  global $db;
  $sql = "SELECT product_name FROM stock_in WHERE product_name like '%$title%' LIMIT 5";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for checking if product exists by title
/*--------------------------------------------------------------*/
function validate_product($product) {
  global $db;
  $sql = "SELECT product_name FROM stock_in WHERE product_name = '{$db->escape($product)}' LIMIT 1";
  $result = $db->query($sql);
  return($db->num_rows($result) === 0 ? true : false);
}

/*--------------------------------------------------------------*/
/* Function for adding stock out
/*--------------------------------------------------------------*/
function add_stock_out($product_id, $qty, $user, $department) {
  global $db;
  $date = make_date();
  $sql  = "INSERT INTO stock_out (product_id, quantity, user, department, date)";
  $sql .= " VALUES ('{$db->escape($product_id)}', '{$db->escape($qty)}', '{$db->escape($user)}', '{$db->escape($department)}', '{$date}')";
  if($db->query($sql)) {
    update_stock_in_qty($qty, $product_id);
    return true;
  } else {
    return false;
  }
}

/*--------------------------------------------------------------*/
/* Function for returning product
/*--------------------------------------------------------------*/
function return_product($stock_out_id, $quantity, $returned_by, $reason) {
  global $db;
  $date_returned = make_date();
  $sql  = "INSERT INTO returned_products (stock_out_id, quantity, returned_by, date_returned, reason)";
  $sql .= " VALUES ('{$db->escape($stock_out_id)}', '{$db->escape($quantity)}', '{$db->escape($returned_by)}', '{$date_returned}', '{$db->escape($reason)}')";
  if($db->query($sql)) {
    $sql_update = "UPDATE stock_in s JOIN stock_out o ON s.id = o.product_id";
    $sql_update .= " SET s.quantity = s.quantity + '{$quantity}'";
    $sql_update .= " WHERE o.id = '{$db->escape($stock_out_id)}'";
    return $db->query($sql_update);
  } else {
    return false;
  }
}

?>
  