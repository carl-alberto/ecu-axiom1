$(document).ready(function() {

    $('#ecuClassroomTechDataTable')
        .on('search.dt', function () {
            console.log('search');
             $(".ecu-spinner-overlay").removeClass('ecu-spinner-hide');
        })
        .on('draw.dt', function () {
            console.log('draw');
             $(".ecu-spinner-overlay").addClass('ecu-spinner-hide');
        });

    $('.equipment_tooltip').tooltip();
    
    $('#equipment').selectWoo({});
    $(".select2-search__field").attr("aria-label", "Software");
    $(".select2-search__field").removeAttr("role");
        
    $.fn.DataTable.ext.pager.numbers_length = 5;
   
    var table = $('#ecuClassroomTechDataTable').DataTable({
        responsive: true,
        order: [],
        searchDelay: 350,
        lengthMenu: [ [ 25, 50, 75, 100, -1 ], [ 25, 50, 75, 100, "All" ] ],
        columns: [
            { width: "25%" },
            { width: "10%" },
            { width: "10%" },
            { width: "15%" },
            { width: "30%", "orderable": false },
            { width: "10%", "orderable": false }
        ],
        processing: true,
        language: {
            infoFiltered: "",
            paginate: {
                previous: "&lt;&lt;", 
                next: "&gt;&gt;"
            },
            aria: {
                paginate: {
                    previous: 'Previous',
                    next:     'Next'
                }
            } 
        },
        dom: 'BT<"clear">lfrtip',
        buttons: [
            {
                extend: 'csv',
                filename: 'Classroom_Tech_Network_ECU',
                text: 'Export',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    modifier: {
                        search: 'none'
                    }
                },

                //Function which customize the CSV (input : csv is the object that you can preprocesss)
                customize: function (csv) {

                    // Split the csv to get the rows
                    var split_csv = csv.split("\n");

                    // Personnalize the headers
                    var header_row = '"Building","Room","Capacity","Type",';

                    // append to header row from array
                    for(key in ecu_available_technology){
                        header_row += '"' + ecu_available_technology[key] + '",'
                    }
                    // remove the last comma
                    header_row = header_row.substring(0,(header_row.length-1));
                    split_csv[0] = header_row;

                    // build a lookup table for the header. used in row processing
                    var header_lookup = header_row.split('","');
                    header_lookup[0] = header_lookup[0].replace(/"/g, '');
                    header_lookup[header_lookup.length-1] = header_lookup[header_lookup.length-1].replace(/"/g, '');

                    //For each row except the first one (header)
                    $.each(split_csv.slice(1), function (index, csv_row) {

                        // Split on quotes and comma to get each cell
                        var csv_cell_array = csv_row.split('","');

                        // //Remove replace the two quotes which are left at the beginning and the end (first and last cell)
                        if( csv_cell_array[0] !== undefined ) {
                            csv_cell_array[0] = csv_cell_array[0].replace(/"/g, '');
                        }
                        if( csv_cell_array[4] !== undefined ) {
                            csv_cell_array[4] = csv_cell_array[4].replace(/"/g, '');
                        }

                        // normalize the data in capacity to remove white space, and an key which facilitates filtering
                        if( csv_cell_array[2] !== undefined ) {
                            var capacity = csv_cell_array[2].replace(" ", "");
                            capacity = capacity.replace('under_25','');
                            capacity = capacity.replace('25_75','');
                            capacity = capacity.replace('over_75','');
                            csv_cell_array[2] = capacity;
                        }
                        
                        // normalize the data in equipment
                        if( csv_cell_array[4] !== undefined ) {

                            // parse equipment
                            var equipment = csv_cell_array[4].replace(" ", "");

                            // set a default blank value for column for, which was equipment, but is now the first piece of equipment itself
                            csv_cell_array[4] = "";

                            // create an array of equipment for this row
                            var equipment = equipment.split('equipment_');
                            // first item is always blank, remove it
                            equipment.shift();
                            // loop over the array and do some processing to determine which cells get an "X" mark
                            for(key in equipment) {
                                var equipment_id = equipment[key];
                                var equipment_name = ecu_available_technology[parseInt(equipment_id)];
                                var header_index = header_lookup.indexOf(equipment_name);
                                csv_cell_array[header_index] = 'X';
                            }
                        }

                        //Join the table on the quotes and comma; add back the quotes at the beginning and end
                        csv_cell_array_quotes = '"' + csv_cell_array.join('","') + '"';

                        //Insert the new row into the rows array at the previous index (index +1 because the header was sliced)
                        split_csv[index + 1] = csv_cell_array_quotes;
                    });

                    //Join the rows with line breck and return the final csv (datatables will take the returned csv and process it)
                    csv = split_csv.join("\n");
                    return csv;
                }
            }
        ],
        fnInitComplete : function() {

            // move elements around for styline
            var controls = $('#ecuClassroomTech_controls .datatables_controls');
            controls.append($('#ecuClassroomTechDataTable_length'));
            controls.append($('#ecuClassroomTechDataTable_info'));
            controls.append($('#ecuClassroomTechDataTable_paginate'));
            
            var export_button = $('.buttons-csv');
            export_button.addClass('btn').addClass('btn-primary');
            $('#ecuClassroomTechclearFilters').after(export_button);

            $("#ecuClassroomTechDataTable_info").removeAttr("aria-live");

            // clear filters
            $('#ecuClassroomTechclearFilters').click(function(e){
                var table = $('#ecuClassroomTechDataTable').DataTable();
                table
                    .search('')
                    .columns().search('')
                    .draw();
                $('select#equipment').val('').trigger('change');
                $('#ecuClassroomTechDataTable tfoot select').val('');
                $('#ecuClassroomTechDataTable tfoot input').val('');
                e.preventDefault();
            });

            // add filters to individual columns
            this.api().columns().every(function () {
                var column = this;
                if(column.selector.cols != 1 && column.selector.cols != 5 ) {

                    // handle filtering ranges
                    if(column.selector.cols == 2) {
                        var select = $('<select title="Capacity"><option value="">' + $(column.header()).data('option') + '</option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            column
                                .search( $(this).val() )
                                .draw();
     
                        });
                        select.append( '<option value="under_25">≤ 24</option>' );
                        select.append( '<option value="25_75">25 - 75</option>' );
                        select.append( '<option value="over_75">≥ 76</option>' );
                    } 
                    // normal text filtering
                    else if(column.selector.cols != 4) {
                        var select = $('<select title="Search ' + $(column.header()).text() + '"><option value="">' + $(column.header()).data('option') + '</option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        });
                        column.data().unique().sort().each( function ( d, j ) {
                            d = d.replace('<i class="fa fa-plus"></i>','');
                            if(d) {
                                select.append( '<option value="' + d + '">' + d + '</option>');
                            }
                        });
                    }
                }
                // a text search for rooms
                else {
                    if(column.selector.cols == 1) {
                        var column = this;
                        $('<div class="dataTables_filter"><input title="Search for Room Number" type="search" /></div>')
                            .appendTo( $(column.footer()).empty() );
                        $('input', this.footer() ).on( 'keyup change', function () {
                            if ( column.search() !== this.value ) {
                                    column
                                        .search( this.value )
                                        .draw();
                            }
                        } );
                    }
                }
            });
        },
    });

    // this does an AND using regex
    $('select#equipment').on('change', function(){
        var values = $(this).val();
        var str = '(?=.*';
        if(values) {
            for(i=0;i<values.length;i++) {
                str += values[i];
                if( i != (values.length-1) ) {
                    str += ')(?=.*';
                }
            }
        }
        str += ')';
        table.columns(4).search( str, true, false ).draw();
    });

});
