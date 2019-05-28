<?php require APPROOT. '/views/inc/header.php'; ?>

    <h1><?php echo $data['title']; ?></h1>
    <p><?php echo $data['body'];?></p>
    <form action="<?php echo URLROOT; ?>/comments/addComment/<?php echo $data['page_id'];?>/contactModel" method="post">
        <div class="form-group">
            <label for="contact_comment">Comment:</label>
            <textarea id="contact_comment" class="form-control form-control-lg" name="contactComment"></textarea>
        </div>
        <input type="submit" value="Submit" class="btn btn-success">
    </form>


    <!--Display user comments -->
    <div class="pt-3">
        <?php foreach ($data['comments'] as $comment): ?>
            <p><b><a href="<?php echo URLROOT;?>/users/showProfile/<?php echo $comment->id ?>"><?php echo $comment->name; ?></a></b> Commented on : <?php echo $comment->commented_on; ?></p>
            <p style="padding-left: 10px"><?php echo $comment->comment; ?></p>
            <div class="container">
                <a class="like"  id="disable-like-btn<?php echo $comment->comment_id; ?>"  onclick="likeComment(<?php echo $comment->comment_id;?>,<?php echo$comment->model_id;?>);">
                    <i  class="fa fa-thumbs-o-up fa-lg"></i><span id="likes<?php echo $comment->comment_id; ?>">
                        <?php echo $comment->totallikes; ?>
                    </span>
                </a>
                <a class="dislike" id="disable-dislike-btn<?php echo $comment->comment_id; ?>"   onclick="dislikeComment(<?php echo $comment->comment_id;?>,<?php echo$comment->model_id?>);">
                    <i class="fa fa-thumbs-o-down fa-lg"></i><span id="dislikes<?php echo $comment->comment_id; ?>">
                        <?php echo $comment->totaldislikes; ?>
                    </span>
                </a>
            </div>

            <?php if($_SESSION['user_id'] == $comment->user_id): ?>
                <form  action="<?php echo URLROOT?>/comments/deleteComment/<?php echo $comment->comment_id ?>/contactmodel" method="post">
                    <input type="hidden" value="<?php echo $data['post']->id; ?>" name="post_id">
                    <input type="submit" value="Delete" class="btn btn-link">
                </form>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <!-- Display comments ends here -->

<?php require APPROOT. '/views/inc/footer.php'; ?>