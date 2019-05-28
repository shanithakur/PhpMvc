<?php

/**
 *@function showFollowBtn
 *@param takes user following status
 *@return a button HTML
 */
function showFollowBtn($data){
    if($data['isFollowed']){
        return "<button class='btn btn-primary pull-right'   id='unfollow_btn' onclick=unFollowUser('".$data['user']->id."')>Unfollow</button>";
    } elseif ($data['isFollowsMe']){
        return "<button class='btn btn-primary pull-right'  id='follow_back_btn' onclick=followUser('".$data['user']->id."')>Follow Back</button>";
    }else {
        return  "<button class='btn btn-primary pull-right'  id='follow_btn' onclick=followUser('".$data['user']->id."')>Follow</button>";
    }
}

?>