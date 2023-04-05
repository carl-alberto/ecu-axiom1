<div id="main-nav" class="sticky-top <?php echo $class; ?>" <?php if(isset($style)) echo $style; ?>>
    <nav id="ecu-nav" aria-label="university navigation">
        <div class="container">
            <div class="nav-wrap">
                <div id="wordmark">
                    <a class="ecu-event-tracking" data-ga-category="Section:NavBar" data-ga-action="Wordmark" href="http://<?php echo getenv('TOPSITE_ENV'); ?>" title="East Carolina University">
                        <img src="<?php echo get_site_url('', 'wp-content/themes/gangplank/images/ECU_lockup_primary_White.svg'); ?>" alt="East Carolina University Homepage">
                    </a>
                </div>
                <div id="ecu-nav-menu">
                    <?php get_template_part('partials/components/navigation/nav-top-level'); ?>
                    <div id="resources">
                        <ul>
                            <?php if(is_second_level()): ?>
                                <li><a href="https://give.ecu.edu/s/722/17/advancement/home.aspx">Give</a></li>
                                <li><a href="http://<?php echo getenv('TOPSITE_ENV'); ?>/apply">Apply</a></li>
                            <?php endif; ?>
                            <li>
                                <button id="resource-toggle" type="button" data-toggle="collapse" data-target="#resources-menu" aria-expanded="false" aria-controls="resources-menu">
                                    <span class="fa fa-search" aria-hidden="true"></span> |
                                    <span class="fa fa-navicon" id="resource-icon" aria-hidden="true"></span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <nav id="resources-menu" aria-label="mobile and supplementary navigation and search" class="collapse">
        <div id="resource-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div id="search">
                            <form action="//search.ecu.edu" method="get">
    		                    <label for="search-input" class="sr-only accessible">Search</label>
    						  	<div class="input-group">
    						    	<input data-menu="resources"  data-menu="resources" type="text" name="q" aria-label="Search" class="form-control" id="search-input" placeholder="Search">
    						    	<span class="input-group-btn">
    		        					<button data-menu="resources"  data-menu="resources" class="btn btn-default ecu-event-tracking" type="submit" data-ga-category="Section:Search" data-ga-action="Search Submit"><span class="fa fa-search"></span></button>
    		      					</span>
    						  	</div>
    						</form>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <?php get_template_part('partials/components/navigation/nav-top-resources'); ?>
                        <div class="resource-links" data-menu="resources">
                            <h2>Resources</h2>
                            <div class="row">
                                <?php $columns = get_menu_from_tools(3, true);
                                if(is_array($columns)):
                                    $i = 0; $count = count($columns);
                                    foreach($columns as $column): ?>
                                        <div class="col-6 col-sm-3">
                                            <ul>
                                                <?php foreach($column as $resource):?>
                                                    <li><a href="<?php echo $resource->link; ?>"  data-menu="resources" class="ecu-event-tracking" data-ga-category="Section:NavBar" data-ga-action="<?php echo $resource->link_text; ?>" data-ga-label="resources"><?php echo $resource->link_text; ?></a></li>
                                                <?php endforeach;
                                                if($i + 1 == $count && $social_menu = get_menu_from_tools(8)): ?>
                                                    <li>
                                                        <?php foreach($social_menu as $menuItem): $target = $menuItem->is_external == true ? '_blank' : '';?>
                                                            <a data-ga-category="Section:NavBar"  data-menu="resources" data-ga-label="resources" data-ga-action="social" href="<?php echo $menuItem->link; ?>" class="social-link ecu-event-tracking" target="<?php echo $target; ?>" rel="noopener noreferrer"><?php echo $menuItem->link_text; ?></a>
                                                        <?php endforeach; ?>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    <?php $i++; endforeach;
                                endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <?php get_template_part('partials/components/navigation/nav-second-level'); ?>
    <?php get_template_part('partials/components/navigation/nav-wordpress'); ?>
</div>
