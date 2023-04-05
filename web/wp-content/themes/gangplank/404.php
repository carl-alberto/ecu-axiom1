<?php get_header(); ?>
  <div class="container">

      <main id="main">
        <h1>404 Page Not Found</h1>
        <p>The requested page cannot be found</p>
        <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <p>Return to the <a href="<?php bloginfo('wpurl'); ?>">homepage</a> or try one of the following:</p>
        <ul>
          <li>If you came here directly check for typos in the URL</li>
          <li>If a link brought you here, contact the web site administrator to alert them that the link is broken</li>
          <li>Use the search below to locate the content you are looking for</li>
        </ul>
        <div class="row">
            <div class="col-md-6">
        <form action="https://www.ecu.edu/search/" method="get">
                <label for="search-input" class="sr-only accessible">Search</label>
                <input type="hidden" value="default_collection" name="site">
                <input type="hidden" value="ecu_frontend" name="client">
                <input type="hidden" value="xml_no_dtd" name="output">
                <input type="hidden" value="ecu_frontend" name="proxystylesheet">
                <input type="hidden" value="25" name="num">
                <div class="input-group">
                  <input type="text" name="q" aria-label="Search" class="form-control" id="search-input" placeholder="Search">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><span class="fa fa-search"></span></button>
                  </span>
                </div>
              </form>
          </div>
      </div>
      </main>

  </div>
<?php get_footer(); ?>
