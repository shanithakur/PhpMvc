<?php require APPROOT. '/views/inc/header.php'; ?>
<!-- Back Button -->
<a href="<?php echo URLROOT;?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>

<h1><?php echo $data['post']->title; ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
    Written By <a class="text-white" href="<?php echo URLROOT;?>/users/showProfile/<?php echo $data['user']->id; ?>"><?php echo $data['user']->name; ?></a> on <?php echo $data['user']->created_At;?>
    <!-- Edit and delete buttons -->
    <?php if($data['post']->user_id == $_SESSION['user_id']): ?>
        <hr>
        <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id;?>" class="btn btn-dark" >Edit</a>

        <form class="pull-right" action="<?php echo URLROOT?>/posts/delete/<?php echo $data['post']->id ?>" method="post">
            <input type="submit" value="Delete" class="btn btn-danger">
        </form>
    <?php endif; ?>
    <!-- Edit and delete buttons code ends here -->
</div>
<p><?php echo $data['post']->body;?></p>


<!-- Below code Post comment of user-->
<form action="<?php echo URLROOT;?>/comments/addComment/<?php echo $data['post']->id ?>" method="post">
    <div class="form-group">
        <label for="comment" class="font-weight-bold">Comment: </label>
        <textarea name="comment" class="form-control form-control-lg <?php echo
        (!empty($data['comment_err']))? 'is-invalid':''?>"><?php echo $data['comment'] ?></textarea>
        <span class="invalid-feedback"><?php echo $data['comment_err'] ?></span>
    </div>
    <input type="submit" value="Comment" class="btn btn-success">
</form>
<!-- Post comment code ends here -->

<!--Display user comments -->
    <div class="pt-3">
        <?php foreach ($data['comments'] as $comment): ?>
            <p><b><a href="<?php echo URLROOT;?>/users/showProfile/<?php echo $comment->id ?>"><?php echo $comment->name; ?></a></b> Commented on : <?php echo $comment->commented_on; ?></p>
            <p style="padding-left: 10px"><?php echo $comment->comment; ?></p>

            <?php if($_SESSION['user_id'] == $comment->user_id): ?>
                <form  action="<?php echo URLROOT?>/comments/deleteComment/<?php echo $comment->comment_id ?>" method="post">
                    <input type="hidden" value="<?php echo $data['post']->id; ?>" name="post_id">
                    <input type="submit" value="Delete" class="btn btn-link">
                </form>
             <?php endif; ?>
        <?php endforeach; ?>
    </div>
<!-- Display comments ends here -->



<?php require APPROOT. '/views/inc/footer.php'; ?>

