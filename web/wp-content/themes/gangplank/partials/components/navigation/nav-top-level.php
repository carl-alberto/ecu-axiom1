<?php if(is_second_level()):
$primaryMenu = get_menu_from_tools(2); $audienceMenu = get_menu_from_tools(1); ?>
<div id="top-level">
    <ul>
        <li class="audience-item"><a class="top-level-link audience-link ecu-event-tracking" data-ga-category="Section:NavBar" data-ga-action="AudienceDropDown" id="btnAudienceDropdown" href="#"><?php echo $audienceMenu[0]->title; ?><span class="fa fa-chevron-down" aria-hidden="true"></span></a>
            <ul id="audience-dropdown">
                <?php foreach ($audienceMenu as $audienceItem):
                    if($audienceItem->is_external == true): ?>
                        <li><a class="audience-link ecu-event-tracking" data-menu="audience" data-ga-category="Section:NavBar" data-ga-action="<?php echo $audienceItem->link_text; ?>" data-ga-label="desktop" href="<?php echo $audienceItem->link; ?>" rel="noopener noreferrer"><?php echo $audienceItem->link_text; ?></a></li>
                    <?php else: ?>
                        <li><a class="audience-link ecu-event-tracking" data-menu="audience" data-ga-category="Section:NavBar" data-ga-action="<?php echo $audienceItem->link_text; ?>" data-ga-label="desktop" href="<?php echo $audienceItem->link; ?>"><?php echo $audienceItem->link_text; ?></a></li>
                    <?php endif;
                endforeach; ?>
            </ul>
        </li>
        <?php foreach ($primaryMenu as $menuItem):
            if($menuItem->link != 'audience'):?>
                <li><a class="top-level-link ecu-event-tracking" data-ga-category="Section:NavBar" data-ga-action="<?php echo $menuItem->link_text; ?>" data-ga-label="desktop" href="<?php echo $menuItem->link; ?>"><?php echo $menuItem->link_text; ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
