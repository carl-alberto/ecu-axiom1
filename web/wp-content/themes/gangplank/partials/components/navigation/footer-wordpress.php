<?php if(!is_second_level()): ?>
<section id="theme-footer">
  <div class="container">
    <?php $footer_type = get_field('footer_type', 'option');
    switch($footer_type){
      case 'link_farm':
        if($link_farm = get_field('footer_link_farm', 'option')){
          echo do_shortcode("[link_farm link_farm_id='{$link_farm}' /]");
        }
      break;
      case 'menu':
        get_template_part('partials/components/footer-menu');
        break;
      default:
        break;
    }?>
    <div id="footer-meta">
      <div class="row">
        <div class="col-md-8">
          <div id="footer-location">
            <?php $contact = get_field('contact', 'option'); ?>
            <span class="title">East Carolina University
              <?php echo $contact['location_name'] && $contact['location_name'] != 'East Carolina University' ? '<br />' . $contact['location_name'] : ''; ?>
            </span>
            <address>
                <?php echo $contact['address'] ? $contact['address'] : 'East 5th Street'; ?> <?php echo $contact['locality'] ? $contact['locality'] : 'Greenville, NC 27858-4353'; ?>
            </address>
            <a class="phone" href="tel:+1<?php echo $contact['phone'] ? $contact['phone'] : '2523286131'; ?>">
              <?php echo $contact['phone'] ? $contact['phone'] : '(252) 328-6131'; ?>
            </a> |
            <a href="<?php echo $contact['link'] ? $contact['link']['url'] : get_bloginfo('wpurl') . '/contact-us/'; ?>">Contact Us</a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="footer-resources">
            <ul>
              <li>&copy; <?php echo date('Y'); ?></li>
              <li><a class="ecu-event-tracking" data-ga-category="Footer" data-ga-action="Terms" href="https://<?php echo getenv('TOPSITE_ENV'); ?>/terms">Terms of Use</a></li>
            </ul>
          </div>
          <div class="footer-resources">
            <ul>
              <li><a class="ecu-event-tracking" data-ga-category="Footer" data-ga-action="Accessibility" href="https://accessibility.ecu.edu/">Accessibility</a></li>
              <li><a class="ecu-event-tracking" data-ga-category="Footer" data-ga-action="Report a Barrier" href="https://accessibility.ecu.edu/report-an-accessibility-barrier/?referrer=<?php bloginfo('wpurl'); ?>">Report a Barrier</a></li>
            </ul>
          </div>
          <div class="footer-resources">
            <br />
            <div id="google_translate_element"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
      <!-- Google Translate -->
  <script type="text/javascript">

      function googleTranslateElementInit() {
        new google.translate.TranslateElement({
          pageLanguage: 'en'
        }, 'google_translate_element');
        //Fix accessibility issues with google translate.
        $('img.goog-te-gadget-icon').attr('alt','Google Translate');
        $('div#goog-gt-tt div.logo img').attr('alt','Google Translate');
        $('div#goog-gt-tt .original-text').css('text-align','left');
        $('.goog-te-gadget-simple .goog-te-menu-value span').css('color','#000000');
        $('.goog-te-combo').attr('aria-label', 'Google Translate');
        $('.goog-te-combo').change(function(){
        $('#nav').css('marginTop', '40px');
      });
    }
  </script>
  <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php endif; ?>
