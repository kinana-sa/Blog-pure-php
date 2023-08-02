<?php

namespace Social {

    use Database;

    require_once "trait_log.php";
    require_once "connect.php";
    require_once "post.php";

    class SocialPost extends \Post
    {
        use \Logger;
        protected $image;

        public function __construct($owner, $title, $content, $img)
        {
            parent::__construct($owner, $title, $content);
            $this->image = $img;
        }
        public function set_image($img)
        {
            $this->image = $img;
        }
        public function get_image()
        {
            return $this->image;
        }

        public function add_post()
        {
            $db = Database::getInstance();
            $connection = $db->getConnection();
            $query = "INSERT INTO social (title, image,content,owner_id) VALUES ('$this->title', '$this->image','$this->content','$this->owner')";
            mysqli_query($connection, $query);
            $last_id = mysqli_insert_id($connection);

            SocialPost::log("New Social post id: " . $last_id . " added by user id :" . $this->owner);
        }

        public static function update_post($post_id, $new_title, $new_content, $new_image)
        {
            $db = Database::getInstance();
            $connection = $db->getConnection();
            $query = "UPDATE social SET title = '$new_title', content = '$new_content', image = '$new_image' WHERE id ='$post_id' ";
            $stmt = mysqli_query($connection, $query);

            SocialPost::log("Social post: " . $post_id . " updated by user id :" . $_SESSION['user_id']);
        }
    }
}