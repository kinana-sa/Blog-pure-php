<?php
require_once 'user.php';
User::logout();
print_r('LogedOut Successfully.');
header('Location: login.php');