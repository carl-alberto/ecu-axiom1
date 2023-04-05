<?php get_header();?>
    <main id="main">
        <div class="container">
            <?php h1_title(); ?>
            <?php if (have_posts()) :?>
                <table class="d-none d-sm-none d-md-block table table-striped table-hover table-responsive">
                    <thead>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Sponsor</th>
                    </thead>
                    <tbody>
                    <?php while (have_posts()) : the_post(); ?>
                        <tr>
                            <td>
                                <?php the_field('description'); ?>
                                <?php if(get_field('tdx_link')): ?>
                                    <a href="<?php the_field('tdx_link'); ?>" class="btn btn-primary" target="_blank">View Project</a>
                                <?php endif; ?>
                            </td>
                            <td><?php the_field('start_date'); ?></td>
                            <td><?php the_field('end_date'); ?></td>
                            <td><?php the_field('sponsor'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="goal d-sm-block d-md-none">
                    <?php the_field('description'); ?>
                    <p>Start: <?php the_field('start_date'); ?></p>
                    <p>Finish: <?php the_field('end_date'); ?></p>
                    <p>Contact: <?php the_field('sponsor'); ?></p>
                    <?php if(get_field('tdx_link')): ?>
                        <a href="<?php the_field('tdx_link'); ?>" class="btn btn-primary" target="_blank">View Project</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
<?php get_footer(); ?>
