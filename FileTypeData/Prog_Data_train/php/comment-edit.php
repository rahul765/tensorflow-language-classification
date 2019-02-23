<?php
  if (!isset($_COOKIE['blog_id'])) {
    header("Location: http://localhost/projects/mina/blogs/");
    exit;
  }
  
  if (!$_POST['post_id']) {
    header ("Location: http://localhost/projects/mina/blogs/error.php");
    exit;
  }
  
  require "myphpfiles/blogConnect.php";
  require "myphpfiles/blogDBAccess.php";
  require "myphpfiles/printHtml.php";

  $blog = getBlogByID($_COOKIE['blog_id']);
  if (!$blog) {
    header("Location: http://localhost/projects/mina/blogs/error.php");
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?=$blog['blogname']?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="/projects/mina/assets/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding: 40px;
      }
    </style>
    <link href="/projects/mina/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
   </head>

  <body>
    <div class="container">
      <?php
      include "../assets/html/top-navbar.php";
      ?>
      <div class="row-fluid">
        <br><br>
        <div class="page-header">
          <h1>
            Manage comments on your blog
            <small>Delete the ones you don't like.</small>
          </h1>
        </div>
      </div>
      <div class="row-fluid">
        <!--this is actual content-->
        <div class="span2">
          
        </div>
        <div class='row-fluid'>
        <div class='span8'>
          <button id='go-to-blog-edit' class='btn'>Go back</button>
          <br>
          <br>
        <?php
          $query = "select * from `comments` where `post_id`={$_POST['post_id']}";
          $result = mysql_query($query);
          
          if ($result) {
              printEditCommentsHtml($result);
          } else {
              print "<h1>There are no comments on this post for you to manage.</h1>";
          }
        ?>
      </div>
         </div>
    </div>
    </div>
    <!-- /container -->
    <?php include ("../assets/html/scripts.html"); ?>
  </body>
</html>