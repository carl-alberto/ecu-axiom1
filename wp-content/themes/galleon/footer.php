                    </div>
                </div>
            </main> <!-- /main -->
        </div> <!-- /.content-wrap -->
        <footer>
            <!-- TODO: Delete me -->
            <?php // legacy_footer() ?>
            <?php include('template-parts/footer/footer-branding.php'); ?>
        </footer>
        <?php back_to_top(); ?>
        <?php wp_footer(); ?>
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
    </body>
</html>