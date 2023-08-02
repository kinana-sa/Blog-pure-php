<?php
require_once "connect.php";
require_once "trait_log.php";
class User
{
  use \Logger;

  protected $name;
  protected $email;
  protected $password;
  private $type;

  public function __construct($name, $email, $password, $type = "visitor")
  {

    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
    $this->type = $type;
  }

  public function register()
  {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    $pass = password_hash($this->password, PASSWORD_DEFAULT); // Creates a password hash
    $query = "INSERT INTO users (name,email, password,type) VALUES (
                  '$this->name','$this->email','$pass','$this->type')";
    $stmt = mysqli_query($connection, $query);
    if ($stmt) {
      $last_id = mysqli_insert_id($connection);
      User::log("User : " .  $last_id  . " registerd");
      return true;
    } else return false;
  }

  public static function login($email, $password)
  {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    $user = "";
    $query = "SELECT * FROM users WHERE email = '$email'"; // email is unique

    $stmt = mysqli_query($connection, $query);
    if (mysqli_num_rows($stmt) === 1) {
      $row = mysqli_fetch_assoc($stmt);
      if (password_verify($password, $row['password']))
        $user = $row;
    }
    if (!empty($user)) {
      User::log("User : " .  $user['id']  . " Loged In.");
      if (session_status() === PHP_SESSION_NONE) {
        session_start();
      }
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['name'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['type'] = $user['type'];
      return $user;
    } else {
      return false;
    }
  }
  public static function logout()
  {
    User::log("User : " . $_SESSION['user_id'] . " Loged out");
    session_start();
    session_destroy();
  }

  public static function show_users()
  {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    $query = "SELECT * FROM users";
    $stmt = mysqli_query($connection, $query);
    $users = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    return $users;
  }

  // Get a user by their ID
  public static function get_user($user_id)
  {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    $query = "SELECT * FROM users WHERE id = '$user_id' ";
    $stmt = mysqli_query($connection, $query);
    $result = mysqli_fetch_assoc($stmt);
    return $result;
  }
}