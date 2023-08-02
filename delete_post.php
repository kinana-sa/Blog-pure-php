<?php

require_once 'post.php';
require_once 'scientific_post.php';
require_once 'social_post.php';

session_start();

$id = $_GET['id'];
if (isset($_GET['file'])) {
  $post = Post::get_post($id, 'social');

  if (!$post) {
    echo "Invalid post ID.";
    exit;
  }
  if ($_SESSION['user_id'] !== $post['owner_id']) {
    echo "You don't have permission to delete this post.";
    exit;
  }

  Post::delete_post($id, 'social');

  echo "Post deleted successfully!";
} else {
  $post = Post::get_post($id, 'scientific');

  if (!$post) {
    echo "Invalid post ID.";
    exit;
  }

  if ($_SESSION['user_id'] !== $post['owner_id']) {
    echo "You don't have permission to delete this post.";
    exit;
  }

  Post::delete_post($id, 'scientific');

  echo "Post deleted successfully!";
}
header('Location: index.php');