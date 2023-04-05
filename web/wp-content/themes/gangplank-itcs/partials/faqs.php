<?php
global $post;
$args = array(
    'posts_per_page' => 100,
    'post_type' => 'faq',
    'meta_key' => 'service',
    'meta_value' => $post->ID
);
$faqs = get_posts($args);
if(count($faqs) > 0): ?>
    <a href="#faqs"></a>
    <h2>FAQs</h2>
    <div class="accordion" id="faqs">
        <?php foreach($faqs as $faq): ?>
            <div class="faq">
                <div class="card">
                    <div class="card-header" id="faq-<?php echo $faq->ID; ?>">
                      <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#q-<?php echo $faq->ID; ?>" aria-expanded="false" aria-controls="q-<?php echo $faq->ID; ?>">
                          <?php echo $faq->post_title; ?>
                        </button>
                      </h5>
                    </div>

                    <div id="q-<?php echo $faq->ID; ?>" class="collapse" aria-labelledby="faq-<?php echo $faq->ID; ?>" data-parent="#faqs">
                      <div class="card-body">
                        <?php echo $faq->post_content; ?>
                      </div>
                    </div>
                 </div>
            </div>
        <?php endforeach;?>
    </div>
<?php endif;
