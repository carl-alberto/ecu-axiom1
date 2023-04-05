<div class="ecu-spinner-overlay-wrapper">
    <div class="ecu-spinner-overlay">
        <div class="ecu-spinner">
            <div class="ecu-rect1"></div>
            <div class="ecu-rect2"></div>
            <div class="ecu-rect3"></div>
            <div class="ecu-rect4"></div>
            <div class="ecu-rect5"></div>
            <p class="ecu-spinner-message">Loading</p>
        </div>
    </div>

<a href="#" id="ecuLabsclearFilters" class="btn btn-primary mx-2 float-right">Clear filters</a>
<div id="ecuLabs_wrapper" class="ecu_datatables_wrapper ">

    <div id="ecuLabs_controls" class="container" style="clear:both;">
        <div class="dataTables_wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-default">
                        <span class="ecu-h5">Find a Lab With Software:</span>
                        <select id="software" multiple="multiple" name="software[]" class="wc-enhanced-select float-left" data-placeholder="Choose software&hellip;" aria-label="Software">
                            <?php foreach ($available_software as $app): ?>
                                <option value="software_<?php echo esc_attr($app->software_id); ?>_<?php echo esc_attr($app->os_id); ?>"><?php echo esc_html($app->software); ?> ( <?php echo esc_html($app->os); ?>)</option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 datatables_controls form-group text-center">
                    <!-- JS layer places datables-provided HTML here -->
                </div>
            </div>
        </div>
    </div>
    <table id="ecuLabsDataTables" class="table responsive" style="width:100%;">
        <thead>
            <th data-option="All Types">Type</th>
            <th data-option="All Buildings">Building Name</th>
            <th data-option="All Rooms">Room</th>
          <th data-option="All Names">Name</th>
            <th data-option="Any">Peripherals</th>

            <th>Details</th>
        </thead>
        <tfoot>
            <tr>
                <td>Type</td>
                <td>Building Name</td>
                <td>Room</td>
                <td>Name</td>
                <td>Peripherals</td>

                <td></td>
            </tr>
        </tfoot>
        <tbody>
            <?php if (!empty($results)): ?>
                <?php foreach ($results as $item): ?>
                    <tr>
                        <td><i class="fa fa-plus"></i><?php echo esc_html($item->lab_type); ?></td>
                        <td><?php echo esc_html($item->building_name); ?></td>
                        <td><?php echo esc_html($item->room); ?></td>
                        <td><?php echo esc_html($item->lab_name); ?></td>
                        <td>
                            <?php if(!empty($item->peripherals)): ?>
                                <?php foreach ($item->peripherals as $icon => $peripheral): ?>
                                    <?php if(!empty($icon)): ?>
                                        <span class="d-none"><?php echo esc_attr($peripheral); ?></span>
                                        <img class="ecu-tool-tip" src="<?php echo CDN_IMAGE_URL . 'labs-peripherals/' . esc_attr($icon); ?>" title="<?php echo esc_attr($peripheral); ?>" alt="<?php echo esc_attr($peripheral); ?>" width="25px" height="25px">
                                    <?php endif; ?>
                                <?php endforeach ?>
                            <?php endif; ?>
                            <?php if(!empty($item->software_ids)): ?>
                                <?php foreach ($item->software_ids as $id): ?>
                                    <?php if(!empty($id)): ?>
                                        <span class="d-none">software_<?php echo esc_attr($id); ?></span>
                                    <?php endif; ?>
                                <?php endforeach ?>
                            <?php endif; ?>
                        </td>                        
                        <td>
                            <a target="_blank" href="http://<?php echo getenv('TOPSITE_ENV'); ?>/buildings/<?php echo esc_attr($item->building_code); ?>/<?php echo esc_attr($item->room); ?>">
                                <i style="font-size:2em;" class="fa fa-info-circle" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</div>

<div>