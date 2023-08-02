<?php

namespace Scientific {

    use Database;

    require_once "trait_log.php";
    require_once "connect.php";
    require_once "post.php";
    class ScientificPost extends \Post
    {
        use \Logger;
        protected $keywords = array();

        public function __construct($owner, $title, $content, $keywords)
        {
            parent::__construct($owner, $title, $content);
            $this->keywords = $keywords;
        }
        public function set_keywords($keywords)
        {
            $this->keywords = $keywords;
        }
        public function get_keywords()
        {
            return $this->keywords;
        }

        public function add_post()
        {
            $db = Database::getInstance();
            $connection = $db->getConnection();
            $query = "INSERT INTO scientific (title, content, owner_id, keywords) VALUES ('$this->title','$this->content','$this->owner','$this->keywords')";
            $stmt = mysqli_query($connection, $query);
            $last_id = mysqli_insert_id($connection);

            ScientificPost::log("New Scientific post id: " . $last_id . " added by user id :" . $this->owner);
        }

        public static function update_post($post_id, $new_title, $new_content, $new_keywords = null)
        {
            $db = Database::getInstance();
            $connection = $db->getConnection();
            $query = "UPDATE scientific SET title = '$new_title', content = '$new_content', keywords= '$new_keywords' WHERE id ='$post_id' ";
            $stmt = mysqli_query($connection, $query);

            ScientificPost::log("Scientific post: " . $post_id . " updated by user id :" . $_SESSION['user_id']);
        }
    }
}