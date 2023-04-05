<script>
    var ecu_available_technology = {};
</script>
<?php foreach ($available_technology as $app): ?>
    <script>
        ecu_available_technology['<?php echo esc_attr($app->id); ?>'] = '<?php echo esc_html($app->name); ?>';
    </script>
<?php endforeach ?>

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

    <a href="#" id="ecuClassroomTechclearFilters" class="btn btn-primary mx-2 float-right">Clear filters</a>

    <div class="ecu_datatables_wrapper">

        <div id="ecuClassroomTech_controls" class="container" style="clear:both;">
            <div class="dataTables_wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-default">
                            <span class="ecu-h5">Find a classroom with <strong>all</strong> of the following technologies:</span>
                            <select id="equipment" multiple="multiple" name="equipment[]" class="wc-enhanced-select float-left" data-placeholder="Choose technologies&hellip;" aria-label="Software">
                                <?php foreach ($available_technology as $app): ?>
                                    <option value="equipment_<?php echo esc_attr($app->id); ?>"><?php echo esc_html($app->name); ?></option>
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
        <table id="ecuClassroomTechDataTable" class="table responsive" style="width:100%;">
            <thead>
                <tr>
                    <th data-option="All Buildings">Building</th>
                    <th data-option="All Rooms">Room</th>
                    <th data-option="All">Capacity</th>
                    <th data-option="All Types">Type</th>
                    <th>Equipment</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td>Building</td>
                    <td>Room</td>
                    <td>Capacity</td>
                    <td>Type</td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
            <tbody>
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $item): ?>
                        <tr>
                            <td><i class="fa fa-plus"></i><?php echo esc_html($item->building_name); ?></td>
                            <td><?php echo esc_html($item->room); ?></td>
                            <td>
                                <?php if(!empty($item->capacity)): ?>
                                    <span class="d-none">
                                        <?php echo $item->capacity < 25 ? 'under_25' : ''; ?>
                                        <?php echo (24 < $item->capacity && $item->capacity < 76) ? '25_75' : ''; ?>
                                        <?php echo $item->capacity > 75 ? 'over_75' : ''; ?>
                                    </span>
                                    <?php echo esc_html($item->capacity); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($item->room_type); ?></td>
                            <td>
                                <?php if(!empty($item->equipment)): ?>
                                    <?php foreach ($item->equipment as $equip): ?>
                                        <?php if(!empty($equip->equipment_icon)): ?>
                                            <span class="d-none">equipment_<?php echo esc_attr($equip->equipment_id); ?></span>
                                            <a
                                                class="equipment_tooltip"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                target="_blank"
                                                data-original-title="<?php echo esc_attr($equip->equipment_tooltip); ?>"
                                                href="<?php echo esc_attr($equip->equipment_url); ?>">
                                                <img
                                                    alt="<?php echo esc_attr($equip->equipment_tooltip); ?>" src="<?php echo CDN_IMAGE_URL . 'classroomtech-equipment/' . esc_attr($equip->equipment_icon); ?>"
                                                    width="25px" height="25px" />
                                            </a>
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

</div>