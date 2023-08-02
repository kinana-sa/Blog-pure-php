<?php
require_once "trait_log.php";
require_once "connect.php";
abstract class Post
{
    use \Logger;

    protected $owner;
    protected $title;
    protected $content;

    public function __construct($owner, $title, $content)
    {
        $this->owner = $owner;
        $this->title = $title;
        $this->content = $content;
    }

    public function set_title($title)
    {
        $this->title = $title;
    }
    public function set_content($content)
    {
        $this->content = $content;
    }
    public function get_owner()
    {
        return $this->owner;
    }
    public function get_title()
    {
        return $this->title;
    }
    public function get_content()
    {
        return $this->content;
    }

    abstract public function add_post();

    abstract static public function update_post($post_id, $new_title, $new_content, $new_file);

    public static function delete_post($post_id, $table)
    {
        $db = \Database::getInstance();
        $connection = $db->getConnection();
        $query = "DELETE from {$table} WHERE id ='$post_id' ";
        $stmt = mysqli_query($connection, $query);

        Post::log("Post: " . $post_id . " deleted from '. $table .' table by user id :" . $_SESSION['user_id']);
    }

    // Get a post by its ID
    public static function get_post($post_id, $table)
    {
        $db = \Database::getInstance();
        $connection = $db->getConnection();
        $query = "SELECT * FROM {$table} WHERE id = '$post_id' ";
        $stmt = mysqli_query($connection, $query);
        $result = mysqli_fetch_assoc($stmt);
        return $result;
    }

    public static function show_posts($table)
    {
        $db = Database::getInstance();
        $connection = $db->getConnection();
        $query = "SELECT * FROM {$table}";
        $stmt = mysqli_query($connection, $query);
        $posts = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
        return $posts;
    }
}