<?php

require("myphpfiles/blogConnect.php");
require("myphpfiles/blogDBAccess.php");

$blog_id = $_COOKIE['blog_id'];

$post_id = $_POST['post_id'];

$response['result'] = false;
    
if ($post_id == "newpost") {
    if (addNewPostToDB($blog_id, $_POST['title'], $_POST['subtitle'], $_POST['body'])) {
        $response['result'] = true;
        $response['message'] = "Your post has been added.";
        print json_encode($response);
    } else {
        $response['result'] = false;
        $response['message'] = "There was an error. Your post was not added :(";
    }
} else {
    $query = "select * from `posts` where `id`={$_POST['post_id']}";
    $resource = mysql_query($query);
    if (!$resource) {
        $response['message'] = "We have encoutered an error. It seems that you are trying to edit a post that doesn't exist.";
        print json_encode($response);
        exit;
    } else {
        $post = mysql_fetch_array($resource);
        if ($post['blog_id'] != $_COOKIE['blog_id']) {
            $response['message'] = "We have encountered an error. It seems that you are trying to edit a post that does not blong to your blog. Please trying logging out and back in";
            print json_encode($response);
            exit;
        } else {
            if (!updatePostInDB($_POST['post_id'], $_POST['title'], $_POST['subtitle'], $_POST['body'])) {
                $response['message'] = "We have encountered an error updating this post. The post was not updated";
                print json_encode($response);
                exit;
            } else {
                $response['result'] = true;
                $response['message'] = "The post has been updated.";
                print json_encode($response);
                exit;
            }
        }
    }
}