<div id="widget-crud">
    <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
        <h2>Create New Sidebar</h2>
        <div class="input-group w-25">
            <input type="text" class="form-control" name="title" placeholder="Sidebar Name">
            <div class="input-group-append">
                <input type="hidden" name="action" value="widget_crud">
                <input type="hidden" name="method" value="create">
                <button class="btn btn-primary" type="submit"><span class="fas fa-plus"></span></button>
            </div>
        </div>
    </form>
</div>