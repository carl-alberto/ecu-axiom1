$(document).ready(function() {

    $('#ecuFindAProgramDataTables')
        .on('search.dt', function () {
            console.log('search');
             $(".ecu-spinner-overlay").removeClass('ecu-spinner-hide');

              // Hide spinner after 5 seconds.
            setTimeout(function() {
                jQuery(".ecu-spinner-overlay").addClass('ecu-spinner-hide');
            }, 5000);
        })
        .on('draw.dt', function () {
            console.log('draw');
             $(".ecu-spinner-overlay").addClass('ecu-spinner-hide');
        });

    $.fn.DataTable.ext.pager.numbers_length = 5;
    
    $('#ecuFindAProgramDataTables').DataTable({
        responsive: true,
        order: [],
        searchDelay: 350,
        lengthMenu: [ [ 25, 50, 75, 100, -1 ], [ 25, 50, 75, 100, "All" ] ],
        columns: [
            { width: "30%" },
            { width: "15%" },
            { width: "20%" },
            { width: "20%" },
            { width: "15%" }
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
            $('#ecuFindAProgram_controls .datatables_controls').append($('#ecuFindAProgramDataTables_length'));
            $('#ecuFindAProgram_controls .datatables_controls').append($('#ecuFindAProgramDataTables_paginate'));
            $('#ecuFindAProgram_controls .datatables_controls').append($('#ecuFindAProgramDataTables_info'));      
            
            // clear filters
            $('#clearFilters').click(function(e){
                var table = $('#ecuFindAProgramDataTables').DataTable();
                table
                    .search('')
                    .columns().search('')
                    .draw();
                $('#ecuFindAProgramDataTables tfoot select').val('');
                $('#ecuFindAProgramDataTables tfoot input').val('');
                e.preventDefault();
            });

            // add filters to individual columns
            this.api().columns().every(function () {
                var column = this;
                if(column.selector.cols != 0 && column.selector.cols != 4) {
                    var select = $('<select title="Search ' + $(column.header()).text() + '"><option value="">' + $(column.header()).data('option') + '</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
                    column.data().unique().sort().each( function ( d, j ) {
                        d = d.replace('<i class="fa fa-plus" aria-hidden="true"></i>','');
                        if(d) {
                            select.append( '<option value="' + d + '">' + d + '</option>');
                        }
                } );
                }
                else {
                    if(column.selector.cols == 0) {
                        var column = this;
                        $('<div class="dataTables_filter"><input type="search" title="Search Programs" placeholder="Search Programs" /></div>')
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
});
