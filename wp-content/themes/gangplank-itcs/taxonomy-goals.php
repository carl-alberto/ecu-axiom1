<?php get_header(); ?>
    <main id="main">
        <div class="container">
            <?php breadcrumbs(); ?>
            <?php h1_title(); ?>
            <?php if (have_posts()) :?>
                <table class="d-none d-sm-none d-md-table table table-striped table-bordered table-hover table-responsive">
                    <thead>
                        <th>Project</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Sponsor</th>
                    </thead>
                    <tbody>
                    <?php while (have_posts()) : the_post(); ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php the_title(); ?>
                                </strong>
                            </td>
                            <td>
                                <?php the_field('description'); ?>
                                <?php if(get_field('tdx_link')): ?>
                                    <a href="<?php the_field('tdx_link'); ?>" class="btn btn-sm btn-primary text-nowrap" target="_blank">View Project</a>
                                <?php endif; ?>
                            </td>
                            <td><?php the_field('start_date'); ?></td>
                            <td><?php the_field('end_date'); ?></td>
                            <td><?php the_field('sponsor'); ?>
                                <?php if(get_field('department')): ?>
                                    <br /><?php the_field('department'); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="goal d-sm-block d-md-none">
                        <strong>
                            <?php the_title(); ?>
                        </strong>
                        <?php if(get_field('description')): ?><?php the_field('description'); ?><?php endif; ?>
                        <?php if(get_field('start_date')): ?><p>Start: <?php the_field('start_date'); ?></p><?php endif; ?>
                        <?php if(get_field('start_date')): ?><p>Finish: <?php the_field('end_date'); ?></p><?php endif; ?>
                        <?php if(get_field('start_date')): ?><p>Contact: <?php the_field('sponsor'); ?></p><?php endif; ?>
                        <?php if(get_field('tdx_link')): ?>
                            <a href="<?php the_field('tdx_link'); ?>" class="btn btn-sm btn-primary text-nowrap" target="_blank">View Project</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </main>
<?php get_footer(); ?>
