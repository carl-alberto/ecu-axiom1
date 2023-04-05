<?php
if($actions = get_field('actions')): if(is_array($actions)):?>
<div class="link-group">
    <ul>
        <?php foreach($actions as $link): $link = $link['link'];
            if(!empty($link['url'])):?>
                <li>
                    <a href="<?php echo $link['url']; ?>" target="<?php echo empty($link['target']) ? '' : $link['target']; ?>" class="btn-ribbon">
                        <?php echo empty($link['title']) ? 'Resource Link' : $link['title']; ?>
                    </a>
                </li>
            <?php endif;
        endforeach; ?>
    </ul>
</div>
<?php endif; endif;
$args = array(
    'posts_per_page' => 1,
    'post_type' => 'faq',
    'meta_key' => 'service',
    'meta_value' => $post->ID
);
$faqs = get_posts($args); $has_faqs = count($faqs) ? true : false;

$args = array(
    'posts_per_page' => 1,
    'post_type' => 'tutorial',
    'meta_key' => 'service',
    'meta_value' => $post->ID
);
$tuts = get_posts($args); $has_tuts = count($tuts) ? true : false;

if($has_faqs || $has_tuts): ?>
    <div class="link-group">
        <ul>
            <?php if($has_faqs): ?>
                <li><a href="<?php echo get_the_permalink() . 'faqs/';?>" class="btn-ribbon">Frequently Asked Questions</a></li>
            <?php endif;
            if($has_tuts): ?>
                <li><a href="<?php echo get_the_permalink() . 'tutorials/';?>" class="btn-ribbon">Tutorials</a></li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>
<div id="custom-sidebar">
    <?php
    if($sidebar = get_field('sidebar_selector')){
        dynamic_sidebar("custom-sidebar-{$sidebar}");
    } ?>
</div>
<?php $id = get_the_id();

$taxs = array(
'category' => get_the_terms($id, 'service-category'),
'audience' => get_the_terms($id, 'audience'),
);

foreach($taxs as $key => $value):
    if(!empty($value)): ?>
        <div class="link-group tags">
            <strong><?php echo ucfirst($key); ?></strong>
            <ul>
            <?php foreach($value as $cat):
                if(!empty($cat)):?>
                    <li><a href="<?php echo tax_link($cat->slug, $key); ?>" class="tag <?php echo $key;?>" ><?php echo $cat->name; ?></a></li>
                <?php endif;
            endforeach; ?>
            </ul>
        </div>
    <?php endif;
endforeach; ?>
