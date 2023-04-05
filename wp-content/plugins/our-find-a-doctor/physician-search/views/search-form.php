
<div class="row">
    <div class="col-md-6">
        <form method="GET" action="<?php echo get_the_permalink(); ?>">
            <div class="form-group">
                <label for="q">Search by doctor's name:</label>
                <div class="input-group">
                    <?php $q = sanitize_text_field($_GET['q']); ?>
                    <input type="text" name="q" placeholder="Search" value="<?php echo $q; ?>" aria-label="Search" class="form-control">
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <form id="specialty-form" method="GET" action="<?php echo get_the_permalink(); ?>">
            <div class="form-group">
                <label for="specialty">Search by specialty:</label>
                <select class="form-control" id="specialty" name="term">
                    <?php $terms = get_terms( [
                        'taxonomy'  => 'specialty',
                        'count'     =>  true
                    ] );
                    $qterm = sanitize_text_field( $_GET['term'] );?>
                        <?php if(!$qterm): ?><option value="" selected="selected">Select Specialization</option><?php endif; ?>
                    <?php foreach($terms as $term): ?>
                        <option value="<?php echo $term->slug; ?>"
                            <?php if($qterm === $term->slug) echo 'selected="selected"'; ?>>
                            <?php echo "{$term->name} ($term->count)"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>
<a href="<?php echo get_the_permalink( get_the_id() ); ?>?all">or view a list of all ECU Physicians doctors</a>