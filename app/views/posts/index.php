<?php require APPROOT. '/views/inc/header.php'; ?>
<?php flash('post_added'); ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT ;?>/posts/add" class="btn btn-primary pull-right">
            <i class="fa fa-pencil"></i> Add post
        </a>
    </div>
</div>

<?php foreach ($data['posts'] as $post): ?>
    <div class="card card-body mb-3">
        <h4 class="card-title"><?php echo $post->title; ?></h4>
        <div class="bg-light p-2 mb-3">
            Written By <a href="<?php echo URLROOT;?>/users/showProfile/<?php echo $post->id ?>"> <?php echo $post->name; ?></a> on <?php echo $post->postCreated; ?>
        </div>
        <p class="card-text"><?php echo $post->body;  ?>...</p>
        <a href="<?php echo URLROOT?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark">More</a>
    </div>
<?php endforeach; ?>

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-end">
        <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <?php for($i = 1; $i <= ceil($data['total_post']/5); $i++): ?>
                <li class="page-item"><a class="page-link" href="<?php echo URLROOT; ?>/posts/index/<?php echo "5/" ?>"><?php echo $i; ?></a></li>
        <?php endfor; ?>

        <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>


<?php require APPROOT. '/views/inc/footer.php'; ?>
