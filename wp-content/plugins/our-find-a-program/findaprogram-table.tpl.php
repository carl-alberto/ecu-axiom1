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

<a href="#" id="clearFilters" class="btn btn-primary mx-2 float-right" >Clear filters</a>
<div id="ecuFindAProgram_wrapper" class="ecu_datatables_wrapper ">
    <div id="ecuFindAProgram_controls" class="container">
    <div class="dataTables_wrapper">
        <div class="row">
            <div class="col-md-12 datatables_controls form-group text-center">
                <!-- JS layer places datables-provided HTML here -->
            </div>
        </div>
    </div>
    <table id="ecuFindAProgramDataTables" class="table responsive" style="width:100%;">
        <thead>
            <tr>
                <th data-priority="1">Program</th>
                <th data-priority="3" data-option="All Colleges &amp; Schools">College</th>
                <th data-priority="2" data-option="All Levels">Program Level</th>
                <th data-priority="4" data-option="All Delivery Method">Delivery Method</th>                
                <th data-priority="5"><span class="badge badge-success">New</span></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td></td>
                <td>College</td>
                <td>Program Level</td>
                <td>Delivery Method</td>
                <td></td>               
            </tr>
        </tfoot>
        <tbody>
            <?php if (!empty($programs)): ?>
                <?php foreach ($programs as $program): ?>
                    <tr>
                        <td><a href="?id=<?php echo esc_attr($program->id) ?>"><?php echo esc_html($program->name) ?></a></td>
                        <td><?php echo esc_html($program->college); ?></td>
                        <td><?php echo esc_html($program->degree_lvl); ?></td>
                        <td><?php echo esc_html($program->delivery_method); ?></td>                       
                        <td><?php echo $program->is_new ? '<span class="badge badge-success">New</span>' : ''; ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</div>

</div>