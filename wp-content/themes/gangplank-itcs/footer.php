        </div> <!-- main-content -->
    </div>
    <footer aria-label="footer">
    	<?php
    	global $wp;
        if (!is_front_page()) {
            if($help = get_field('itcs_help_links', 'option')) { ?>
                <section aria-label="Get Help" class="ribbon style-1 get-help-ribbon">
                <div class="container">
                <?php echo $help; ?>
                </div>
                </section>
        <?php
            }
        }
    	?>
    	<?php if($dashboard = get_field('itcs_footer_dashboard', 'option')): ?>
            <section aria-label="Footer links" class="ribbon style-3 footer-ribbon">
                <div class="container">
                	<?php echo do_shortcode('[dashboard_2 dashboard_id="'.$dashboard.'" /]'); ?>
                </div>
            </section>
        <?php endif; ?>
        <?php get_template_part('partials/components/navigation/footer-wordpress'); ?>
    </footer>
    <?php if(get_field('back_to_top') || (is_tax() && get_field('back_to_top', get_queried_object()))): ?>
      <a href='javascript:' id='back-to-top'><span class='fa fa-chevron-up' aria-hidden='true'></span></a>
    <?php endif; ?>
  <?php wp_footer(); ?>

        <?php
            if(is_prod()) {
                echo "
                <!-- SiteImprove Analytics -->
                <script type='text/javascript'>
                /*<![CDATA[*/
                (function() {
                 var sz = document.createElement('script'); sz.type = 'text/javascript'; sz.async = true;
                 sz.src = '//siteimproveanalytics.com/js/siteanalyze_66356777.js';
                 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sz, s);
                })();
                /*]]>*/
                </script>";
            }
        ?>
  <!-- You Visit Virtual Tour Links-->
  <script async="async" defer="defer" src="https://www.youvisit.com/tour/Embed/js2"></script>
  </body>
</html>
