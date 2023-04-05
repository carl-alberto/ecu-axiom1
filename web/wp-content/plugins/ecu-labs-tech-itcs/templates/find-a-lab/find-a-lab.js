$(document).ready(function() {

    $('#ecuLabsDataTables')
        .on('search.dt', function () {
            console.log('search');
             $(".ecu-spinner-overlay").removeClass('ecu-spinner-hide');
        })
        .on('draw.dt', function () {
            console.log('draw');
             $(".ecu-spinner-overlay").addClass('ecu-spinner-hide');
        });

    $('.ecu-tool-tip').tooltip();

    $('#software').selectWoo({});
    $(".select2-search__field").attr("aria-label", "Software");
    $(".select2-search__field").removeAttr("role");
        
    $.fn.DataTable.ext.pager.numbers_length = 5;

    var table = $('#ecuLabsDataTables').DataTable({
        responsive: true,
        order: [],
        searchDelay: 350,
        lengthMenu: [ [ 25, 50, 75, 100, -1 ], [ 25, 50, 75, 100, "All" ] ],
        columns: [
            { width: "20%" },
            { width: "25%" },
            { width: "10%" },
            { width: "20%" },            
            { width: "5%", orderable: false },
            { width: "5%", orderable: false }
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
        fnInitComplete : function() {
            $('#ecuLabs_controls .datatables_controls').append($('#ecuLabs_wrapper .dataTables_length'));
            $('#ecuLabs_controls .datatables_controls').append($('#ecuLabs_wrapper .dataTables_info'));
            $('#ecuLabs_controls .datatables_controls').append($('#ecuLabs_wrapper .dataTables_paginate'));
    
            $("#ecuLabsDataTables_info").removeAttr("aria-live");

            // clear filters
            $('#ecuLabsclearFilters').click(function(e){
                var table = $('#ecuLabsDataTables').DataTable();
                table
                    .search('')
                    .columns().search('')
                    .draw();
                $('select#software').val('').trigger('change');
                $('#ecuLabsDataTables tfoot select').val('');
                $('#ecuLabsDataTables tfoot input').val('');
                e.preventDefault();
            });

            // add filters to individual columns
            this.api().columns().every(function () {
                var column = this;
                var dropdowns = [0,1,3,4];
                if(dropdowns.indexOf(column.selector.cols) != -1) {
                    // handle filtering images
                    if(column.selector.cols == 4) {
                        var select = $('<select title="Peripherals"><option value="">' + $(column.header()).data('option') + '</option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            column
                                .search( $(this).val() )
                                .draw();
                        });
                        select.append( '<option value="B/W Printer">B/W Printer</option>' );
                        select.append( '<option value="Color Printer">Color Printer</option>' );
                        select.append( '<option value="Scanner">Scanner</option>' );
                    } 
                    // normal text filtering
                    else {
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
                    if(column.selector.cols == 2) {
                        var column = this;
                        $('<div class="dataTables_filter"><input title="Search for Room Number" type="search"  /></div>')
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

    // this does an OR using regex
    $('select#software').on('change', function(){
        var values = $(this).val();
        var str = '';
        if(values) {
            for(i=0;i<values.length;i++) {
                str += values[i];
                if( i != (values.length-1) ) {
                    str += '|';
                }
            }
        }
        table.search( str, true, false ).draw();
    });

});
