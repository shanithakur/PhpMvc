<?php require APPROOT. '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Add a new navigation page</h2>
                <p>Please fill out this form to add a page</p>
                <form action="<?php echo URLROOT; ?>/admins/addMenu" method="post">
                    <div class="form-group">
                        <label for="label">label: <sup>*</sup></label>
                        <input type="text" name="label" class="form-control form-control-lg <?php echo
                        (!empty($data['label_err']))? 'is-invalid':''?>" value="<?php echo $data['label']; ?>" required >
                        <span class="invalid-feedback"><?php echo $data['label_err'] ?></span>
                    </div>

                    <div class="form-group">
                        <label for="link">link: <sup>*</sup></label>
                        <input type="text" name="link" class="form-control form-control-lg <?php echo
                        (!empty($data['link_err']))? 'is-invalid':''?>" value="<?php echo $data['link']; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['link_err'] ?></span>
                    </div>

                    <div class="form-group">
                        <label for="target">Select Target:</label>
                        <select class="form-control" id="target" name="target">
                            <option value="">select</option>
                            <option value="_blank">blank</option>
                            <option value="_self">self</option>
                            <option value="_parent">parent</option>
                            <option value="_top">top</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['target_err'] ?></span>
                    </div>
                    <div class="form-group">
                        <label for="page_body">Body:</label>
                        <textarea id="page_body" rows="5" class="form-control form-control-lg" name="page_body"></textarea>
                    </div>

                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Add menu" class="btn btn-success btn-block">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require APPROOT. '/views/inc/footer.php'; ?>