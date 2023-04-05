<?php if(is_second_level()): ?>
<div id="resources-primary-menu" class="resource-links">
    <?php $menu = get_menu_from_tools(2);
    $columns = count($menu) > 0 ? array_chunk($menu, ceil(count($menu) / 2)) : false;?>
    <div class="row">
        <?php if($columns): ?>
            <?php foreach($columns as $column):?>
                <div class="col-6">
                    <ul>
                        <?php foreach($column as $link): if($link->link != 'audience'): ?>
                            <?php if ($link->is_external): ?>
                                <li><a class="ecu-event-tracking" data-menu='resources' data-ga-category="Section:NavBar" data-ga-action="<?php echo $link->link_text; ?>" data-ga-label="mobile" href="<?php echo $link->link; ?>" data-ga-label="mobile" target="_blank" rel="noopener noreferrer"><?php echo $link->link_text; ?></a></li>
                            <?php else: ?>
                                <li><a class="ecu-event-tracking" data-menu='resources' data-ga-category="Section:NavBar" data-ga-action="<?php echo $link->link_text; ?>" data-ga-label="mobile" href="<?php echo $link->link; ?>"><?php echo $link->link_text; ?></a></li>
                            <?php endif; ?>
                        <?php endif; endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<div id="resources-audiences" class="resource-links">
    <h2>I am...</h2>
    <?php $menu = get_menu_from_tools(1);
    $columns = count($menu) > 0 ? array_chunk($menu, ceil(count($menu) / 2)) : false;?>
    <div class="row">
        <?php if($columns): ?>
            <?php foreach($columns as $column):?>
                <div class="col-6">
                    <ul>
                        <?php foreach($column as $link):?>
                            <?php if ($link->is_external): ?>
                                <li><a class="ecu-event-tracking" data-menu='resources' data-ga-category="Section:NavBar" data-ga-action="<?php echo $link->link_text; ?>" data-ga-label="mobile" href="<?php echo $link->link; ?>" data-ga-label="mobile" target="_blank" rel="noopener noreferrer"><?php echo $link->link_text; ?></a></li>
                            <?php else: ?>
                                <li><a class="ecu-event-tracking" data-menu='resources' data-ga-category="Section:NavBar" data-ga-action="<?php echo $link->link_text; ?>" data-ga-label="mobile" href="<?php echo $link->link; ?>"><?php echo $link->link_text; ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
