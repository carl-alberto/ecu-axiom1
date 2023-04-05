<?php /* Template Name: Services */ ?>
<?php get_header();?>
    <main id="main">
        <div class="container">
            <?php get_template_part('partials/components/header-meta'); ?>
            <?php the_content(); ?>
            <?php $audiences = get_terms(
                array(
                    'taxonomy' => 'audience',
                    'hide_empty' => true
                )
            );
            $categories = get_terms(
                array(
                    'taxonomy' => 'service-category',
                    'hide_empty' => true
                )
            ); ?>
            <div class="row">
                <aside class="col-lg-3 order-lg-2 ">
                    <div class="counter">
                        <label>Services Displayed:</label>
                        <div class="counter-wrap">
                            <span class="counter-displayed">&infin;</span>/
                            <span class="counter-total">&infin;</span>
                        </div>
                    </div>
                    <input type="text" id="quicksearch" aria-label="Quick Search" class="form-control" placeholder="Search" value="<?php echo sanitize_text_field($_GET['tags']); ?>" name="quicksearch" />

                    <div class="button-toggle">
                        <button class="switch-field active" data-action="view" data-value="detailed">Detailed</button>
                        <button class="switch-field" data-action="view" data-value="simple">Simple</button>
                    </div>

                    <div class="button-toggle">
                        <button class="switch-field active" data-action="sort" data-value="term">Category</button>
                        <button class="switch-field" data-action="sort" data-value="alpha">Alphabetical</button>
                    </div>

                    <h2 class="filter-label" data-toggle="collapse" href="#audiences" role="button" aria-expanded="false" aria-controls="audiences">Audiences</h2>
                    <div id="audiences" class="collapse show">
                        <div class="filter-group" data-filter-group="audience">
                            <?php foreach($audiences as $a): ?>
                                <button class="filter-services" data-filter=".<?php echo $a->slug; ?>" data-label="<?php echo $a->name; ?>"><?php echo $a->name; ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <h2 class="filter-label" data-toggle="collapse" href="#categories" role="button" aria-expanded="false" aria-controls="categories">Categories</h2>
                    <div id="categories" class="collapse show">
                        <div class="filter-group" data-filter-group="category">
                            <?php foreach($categories as $c): ?>
                                <button class="filter-services" data-filter=".<?php echo $c->slug; ?>" data-label="<?php echo $c->name; ?>"><?php echo $c->name; ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </aside>
                <div class="col-lg-9 order-lg-1 ">
                    <a name="top" name="top" aria-hidden="true"></a>
                    <ul id="alpha-nav" class="d-none">
                        <li class="heading">Jump To</li>
                        <?php foreach(range('A', 'Z') as $letter): ?>
                            <li><a href="" class="alpha-link alpha-nav alpha-<?php echo $letter; ?>"><?php echo $letter; ?></a>
                        <?php endforeach; ?>
                    </ul>
                    <h2 id="search-results" class="d-none"><span class="cat-icon fa fa-search"></span>Search Results for: <span id="search-query"></span></h2>
                    <div id="services">
                        <?php // Category Terms
                        $all = array();
                        $allTerms = array_merge($audiences, $categories);
                        foreach($allTerms as $term):
                            $all[] = $term->slug;
                        endforeach;
                        $arrays = array_merge($all, range('A', 'Z'));
                        $filters = implode(' ', $arrays);
                        $terms = get_terms(array('taxonomy' => 'service-category','hide_empty' => true));
                        $order = 0; foreach($terms as $term): ?>
                            <div class="service-item service-header sort-term <?php echo $filters; ?>" data-type="<?php echo $term->slug; ?>" data-alpha-sort="<?php echo trim($term->name); ?>">
                                <h2>
                                    <?php echo get_field('icon', $term) ? '<span class="cat-icon fa '.get_field('icon', $term).'"></span>' . $term->name : $term->name; ?>
                                </h2>
                            </div>
                            <?php $args = array(
                                'posts_per_page' => -1,
                                'post_type' => 'service',
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => $term->taxonomy,
                                        'field' => 'term_id',
                                        'terms' => $term->term_id,
                                    )
                                )
                            );
                            $posts = get_posts($args);
                            $order++; foreach($posts as $post):
                                $slugs = array($term->slug);
                                $tags = array(array($term->term_id, $term->name, 'category', $term->slug));
                                $audience = get_the_terms($post->post_id, 'audience');
                                if(is_array($audience)):
                                    foreach($audience as $cat):
                                        $slugs[] = $cat->slug;
                                        $tags[] = array($cat->term_id, $cat->name, 'audience', $cat->slug);
                                    endforeach;
                                endif;
                                $slugs = implode(" ", $slugs); ?>

                                <div class="service-item service <?php echo $slugs; ?>" data-term="<?php echo $term->slug; ?>" data-alpha="<?php echo substr(ucfirst($post->post_title), 0, 1); ?>" data-alpha-sort="<?php echo trim(ucfirst($post->post_title)); ?>">
                                    <a href="<?php echo get_permalink($post->post_id); ?>" class="link searchable ecu-h5"><?php echo $post->post_title; ?></a>
                                    <div class="service-details">
                                        <?php echo get_the_excerpt($post->post_id) ? '<p class="service-description">' . get_the_excerpt($post->post_id) . '</p>' : ''; ?>
                                        <?php if($tags = get_the_terms($post->post_id, 'service-tags')): ?>
                                                <ul class="d-none" aria-hidden="true">
                                                    <?php foreach($tags as $tag): ?>
                                                        <li class="searchable" aria-hidden="true"><?php echo $tag->name; ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                    </div>
                               </div>
                            <?php $order++; endforeach;
                        endforeach; ?>
                        <?php // Alpha Terms
                       $order = 0; foreach(range('A', 'Z') as $letter):?>
                        <div class="service-item service-header sort-alpha <?php echo $filters; ?>" data-type="<?php echo trim($letter); ?>" data-alpha-sort="<?php echo trim($letter); ?>">
                            <div class="alpha-header">
                                <a name="alpha-<?php echo $letter; ?>" class="link"><?php echo $letter; ?></a>
                                <a href="#top" class="btt alpha-link">Back to Top</a>
                            </div>
                        </div>
                        <?php $order++; endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </main>
<?php wp_reset_postdata(); get_footer(); ?>
