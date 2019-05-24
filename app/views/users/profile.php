<?php require APPROOT. '/views/inc/header.php'; ?>
    <div class="container">
        <div class="row">
            <!-- code start -->
            <div class="twPc-div">
                <a class="twPc-bg twPc-block"></a>

                <div class="pt-2 pb-5">
                    <a title="Mert S. Kaplan" href="https://twitter.com/mertskaplan" class="twPc-avatarLink">
                        <img alt="Mert S. Kaplan" src="https://mertskaplan.com/wp-content/plugins/msk-twprofilecard/img/mertskaplan.jpg" class="twPc-avatarImg">
                    </a>

                    <div class="twPc-divUser">
                        <div class="twPc-divName">
                            <a href="https://twitter.com/mertskaplan"><?php echo $data['user']->name;?></a>
                            <?php if($_SESSION['user_id'] !== $data['user']->id): ?>
                                <?php if($data['isFollowed']):?>
                                    <button class="btn btn-primary pull-right" disabled id="followed_btn" onclick="followUser(<?php echo $data['user']->id; ?>)">Followed</button>
                                <?php elseif($data['isFollowsMe']): ?>
                                    <button class="btn btn-primary pull-right"  id="follow_btn" onclick="followUser(<?php echo $data['user']->id; ?>)">Follow Back</button>
                                <?php else: ?>
                                    <button class="btn btn-primary pull-right"  id="follow_btn" onclick="followUser(<?php echo $data['user']->id; ?>)">Follow</button>
                                <?php endif; ?>

                            <?php endif; ?>
                        </div>
                        <span>
				            <a href="https://twitter.com/mertskaplan"><span><?php echo $data['user']->email;?></span></a>
			            </span>
                    </div>

                    <div class="twPc-divStats">
                        <ul class="twPc-Arrange">
                            <li class="twPc-ArrangeSizeFit">
                                    <span class="twPc-StatLabel twPc-block">Following</span>
                                    <span class="twPc-StatValue text-primary" id="total_following"><?php echo $data['following']->following;?></span>
                                </a>
                            </li>
                            <li class="twPc-ArrangeSizeFit">
                                    <span class="twPc-StatLabel twPc-block">Followers</span>
                                    <span class="twPc-StatValue text-primary" id="total_followers"><?php echo $data['followers']->follower;?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- code end -->
        </div>
    </div>

<script type="text/javascript">
    function followUser(followe_id){

       var followe_id = followe_id;

       $.ajax({
           url:'<?php echo URLROOT; ?>/users/follow/'+followe_id,
           method: 'POST',
           success: function (result) {
             var data = JSON.parse(result);
             if(result != 'failure'){
                $("#total_followers").text(data.followers.follower);
                $("#total_following").text(data.following.following);
                $("#follow_btn").text("Followed").attr('disabled', 'true');
             }
            }
       })

    }
</script>

<?php require APPROOT. '/views/inc/footer.php'; ?>