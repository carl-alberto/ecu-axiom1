        </div> <!-- main-content -->
    </div>
     <?php if((get_page_template_slug() == 'page-blank.php' && get_field('toggle_footer')) || get_page_template_slug() != 'page-blank.php'): ?>
        <footer aria-label="footer">
            <?php get_template_part('partials/components/navigation/footer-second-level'); ?>
            <?php get_template_part('partials/components/navigation/footer-wordpress'); ?>
        </footer>
    <?php endif; ?>
    <?php if(get_field('back_to_top') || (is_tax() && get_field('back_to_top', get_queried_object()))): ?>
      <a href='javascript:' id='back-to-top'><span class='fa fa-chevron-up' aria-label=”back to top” aria-hidden='true'></span></a>
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
