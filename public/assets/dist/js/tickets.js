$(document).ready(function () {
    $('#ticketsPrincipal').DataTable({
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }],
        responsive  : true,
        lengthChange: false,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : true,
        rowReorder  : false,
        order: [[ 0, "desc" ]],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros.",
            info: "Mostrando tickets del _START_ al _END_ de un total de _TOTAL_ tickets",
            infoEmpty: "Mostrando tickets del 0 al 0 de 0 tickets",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            infoPostFix: "",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "Ningún dato disponible en esta tabla",
            row: "Registro",
            export: "Exportar",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": Activar para ordenar la columna de manera ascendente",
                sortDescending: ": Activar para ordenar la columna de manera descendente"
            },
            select: {
                row: "registro",
                selected: "seleccionado"
            }
        },
        // dom: 'Bfrtip',
        //                 buttons: [
        //         {
        //             extend: 'collection',
        //             text: 'Exportar',
        //             buttons: [
        //                 'copy',
        //                 'excel',
        //                 'csv',
        //                 {extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL'},
        //                 {
        //                     extend: 'print',
        //                     customize: function ( win ) {
        //                         $(win.document.body)
        //                             .css( 'font-size', '10pt' );

        //                         $(win.document.body).find( 'table' )
        //                             .addClass( 'compact' )
        //                             .css( 'font-size', 'inherit' );
        //                     }
        //                 }
        //             ]
        //         }]


    });

    // setInterval( function () {
    //     $('#ticketsPrincipal').DataTable().draw();
    // }, 300 );
    $('#ticketsUsuario').DataTable({
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }],
        responsive: true,
        lengthChange: false,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : true,
        rowReorder  : false,
        order: [[ 0, "desc" ]],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros.",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            infoPostFix: "",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "Ningún dato disponible en esta tabla",
            row: "Registro",
            export: "Exportar",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": Activar para ordenar la columna de manera ascendente",
                sortDescending: ": Activar para ordenar la columna de manera descendente"
            },
            select: {
                row: "registro",
                selected: "seleccionado"
            }
        }


    });

    $('#reporte').DataTable({
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }],
        responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal( {
                        header: function ( row ) {
                            var data = row.data();
                            return 'Detalle Ticket '+data[0];
                        }
                    } ),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            },
        lengthChange: false,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : true,
        rowReorder  : false,
        order: [[ 0, "desc" ]],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros.",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            infoPostFix: "",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "Ningún dato disponible en esta tabla",
            row: "Registro",
            export: "Exportar",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": Activar para ordenar la columna de manera ascendente",
                sortDescending: ": Activar para ordenar la columna de manera descendente"
            },
            select: {
                row: "registro",
                selected: "seleccionado"
            }
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                text: 'Exportar',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    {extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL'},
                    {
                        extend: 'print',
                        customize: function ( win ) {
                            $(win.document.body)
                                .css( 'font-size', '10pt' );

                            $(win.document.body).find( 'table' )
                                .addClass( 'compact' )
                                .css( 'font-size', 'inherit' );
                        }
                    }
                ]
            }],

    });

    $('#btnFormularioConsulta').click(function(){

        var Tipo        = $("#id_tipo").val();
        var Prioridad   = $("#id_prioridad").val();
        var Estado      = $("#id_estado").val();
        var Categoria   = $("#id_categoriarepo").val();
        var Creador     = $("#id_creado").val();
        var Asignado    = $("#id_asignado").val();
        var Sede        = $("#id_sede").val();
        var FechaInicio = $("#fechaInicio").val();
        var FechaFin    = $("#fechaFin").val();
        var tipo        = 'post';

        $.ajax({
            type: "post",
            url : "consultarTicket",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {_method: tipo,id_tipo: Tipo,id_prioridad: Prioridad,id_estado: Estado,
                    id_categoria: Categoria,id_creado: Creador,id_asignado: Asignado,id_sede: Sede,
                    fechaInicio: FechaInicio,fechaFin: FechaFin},
            success : function(data){

                var valido = data['valido'];
                var errores = data['errors'];
                if(valido === 'true'){
                    var Resultado = jQuery.parseJSON(data['results']);
                    $('#panelResultado').show();
                    $('#reporte').DataTable().destroy();
                    $('#reporte').empty();
                    $('#reporte').DataTable({
                        data: Resultado,
                        columnDefs: [
                            { responsivePriority: 1, targets: 0 },
                            { responsivePriority: 2, targets: -10 }],
                            responsive: {
                                details: {
                                    display: $.fn.dataTable.Responsive.display.modal( {
                                        header: function ( row ) {
                                            var data = row.data();
                                            return 'Detalle Ticket ';
                                        }
                                    } ),
                                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                                        tableClass: 'table'
                                    })
                                }
                            },
                        lengthChange: false,
                        searching   : true,
                        ordering    : true,
                        info        : true,
                        autoWidth   : true,
                        processing  : true,
                        rowReorder  : false,
                        order: [[ 0, "desc" ]],
                        columns: [
                                    { "data": "id" },
                                    { "data": "created_at" },
                                    { "data": "updated_at" },
                                    { "data": "kind_id" },
                                    { "data": "priority_id" },
                                    { "data": "status_id" },
                                    { "data": "user_id" },
                                    { "data": "asigned_id" },
                                    { "data": "title" },
                                    { "data": "name_user" },
                                    { "data": "tel_user" },
                                    { "data": "user_email" },
                                    { "data": "description" },
                                    { "data": "project_id" },
                                    { "data": "asigned_id" },
                                    { "data": "historial" }
                                ],
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'collection',
                                text: 'Exportar',
                                buttons: [
                                    'copy',
                                    'excel',
                                    'csv',
                                    {extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL'},
                                    {
                                        extend: 'print',
                                        customize: function ( win ) {
                                            $(win.document.body)
                                                .css( 'font-size', '10pt' );

                                            $(win.document.body).find( 'table' )
                                                .addClass( 'compact' )
                                                .css( 'font-size', 'inherit' );
                                        }
                                    }
                                ]
                            }],
                        language: {
                            processing: "Procesando...",
                            search: "Buscar:",
                            lengthMenu: "Mostrar _MENU_ registros.",
                            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            infoEmpty: "Mostrando registros del 0 al 0 de 0 registros",
                            infoFiltered: "(filtrado de un total de _MAX_ registros)",
                            infoPostFix: "",
                            loadingRecords: "Cargando...",
                            zeroRecords: "No se encontraron resultados",
                            emptyTable: "Ningún dato disponible en esta tabla",
                            row: "Registro",
                            export: "Exportar",
                            paginate: {
                                first: "Primero",
                                previous: "Anterior",
                                next: "Siguiente",
                                last: "Ultimo"
                            },
                            aria: {
                                sortAscending: ": Activar para ordenar la columna de manera ascendente",
                                sortDescending: ": Activar para ordenar la columna de manera descendente"
                            },
                            select: {
                                row: "registro",
                                selected: "seleccionado"
                            }
                        }
                    });
                }else{
                    $.each(errores,function(key, value){
                        if(value){
                            toastr.error(value);
                        }
                    });
                    $('#panelResultado').hide();
                    $('#progreso').hide();
                }
            },

            });
        });
        $('#btnFormularioConsultaT').click(function(){

            var Ticket  = $("#ticket").val();
            var tipo    = 'post';

            $.ajax({
                type: "post",
                url : "consultarxTicket",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {_method: tipo,ticket: Ticket},
                success : function(data){

                    var valido = data['valido'];
                    var errores = data['errors'];
                    if(valido === 'true'){
                        var Resultado = jQuery.parseJSON(data['results']);
                        $('#panelResultado').show();
                        $('#reporte').DataTable().destroy();
                        $('#reporte').empty();
                        $('#reporte').DataTable({
                            data: Resultado,
                            columnDefs: [
                                { responsivePriority: 1, targets: 0 },
                                { responsivePriority: 2, targets: -10 }],
                                responsive: {
                                    details: {
                                        display: $.fn.dataTable.Responsive.display.modal( {
                                            header: function ( row ) {
                                                var data = row.data();
                                                return 'Detalle Ticket ';
                                            }
                                        } ),
                                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                                            tableClass: 'table'
                                        })
                                    }
                                },
                            lengthChange: false,
                            searching   : true,
                            ordering    : true,
                            info        : true,
                            autoWidth   : true,
                            processing  : true,
                            rowReorder  : false,
                            order: [[ 0, "desc" ]],
                            columns: [
                                        { "data": "id" },
                                        { "data": "created_at" },
                                        { "data": "updated_at" },
                                        { "data": "kind_id" },
                                        { "data": "priority_id" },
                                        { "data": "status_id" },
                                        { "data": "user_id" },
                                        { "data": "asigned_id" },
                                        { "data": "title" },
                                        { "data": "name_user" },
                                        { "data": "tel_user" },
                                        { "data": "user_email" },
                                        { "data": "description" },
                                        { "data": "project_id" },
                                        { "data": "asigned_id" },
                                        { "data": "historial" }
                                    ],
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'collection',
                                    text: 'Exportar',
                                    buttons: [
                                        'copy',
                                        'excel',
                                        'csv',
                                        {extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL'},
                                        {
                                            extend: 'print',
                                            customize: function ( win ) {
                                                $(win.document.body)
                                                    .css( 'font-size', '10pt' );

                                                $(win.document.body).find( 'table' )
                                                    .addClass( 'compact' )
                                                    .css( 'font-size', 'inherit' );
                                            }
                                        }
                                    ]
                                }],
                            language: {
                                processing: "Procesando...",
                                search: "Buscar:",
                                lengthMenu: "Mostrar _MENU_ registros.",
                                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                infoEmpty: "Mostrando registros del 0 al 0 de 0 registros",
                                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                                infoPostFix: "",
                                loadingRecords: "Cargando...",
                                zeroRecords: "No se encontraron resultados",
                                emptyTable: "Ningún dato disponible en esta tabla",
                                row: "Registro",
                                export: "Exportar",
                                paginate: {
                                    first: "Primero",
                                    previous: "Anterior",
                                    next: "Siguiente",
                                    last: "Ultimo"
                                },
                                aria: {
                                    sortAscending: ": Activar para ordenar la columna de manera ascendente",
                                    sortDescending: ": Activar para ordenar la columna de manera descendente"
                                },
                                select: {
                                    row: "registro",
                                    selected: "seleccionado"
                                }
                            }
                        });
                    }else{
                        $.each(errores,function(key, value){
                            if(value){
                                toastr.error(value);
                            }
                        });
                        $('#panelResultado').hide();
                        $('#progreso').hide();
                    }
                },

                });
            });
        $(document).ajaxSend(function(event, request, settings) {
            $('#loading-indicator').show();
          });

          $(document).ajaxComplete(function(event, request, settings) {
            $('#loading-indicator').hide();
          });
    function changeFunc() {
        var selectBox = document.getElementById("id_categoria");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        var tipo = 'post';
        var select = document.getElementById("id_usuario");

        $.ajax({
            url: "{{route('buscarCategoria')}}",
            type: "get",
            data: {_method:tipo,id_categoria: selectedValue},
            success: function(data){
                var vValido = data['valido'];

                if (vValido === 'true') {
                    var ListUsuario = data['Usuario'];
                    select.options.length = 0;
                    for(index in ListUsuario) {
                        select.options[select.options.length] = new Option(ListUsuario[index], index);
                    }

                }

            }
          });
    }

    function changeFuncRepo() {
        var selectBox = document.getElementById("id_categoria");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        var tipo = 'post';
        var select = document.getElementById("id_usuario");

        $.ajax({
            url: "{{route('buscarCategoria')}}",
            type: "get",
            data: {_method:tipo,id_categoria: selectedValue},
            success: function(data){
                var vValido = data['valido'];

                if (vValido === 'true') {
                    var ListUsuario = data['Usuario'];
                    select.options.length = 0;
                    for(index in ListUsuario) {
                        select.options[select.options.length] = new Option(ListUsuario[index], index);
                    }

                }

            }
          });
    }

    $('#recurrentes').DataTable({
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }],
        responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal( {
                        header: function ( row ) {
                            var data = row.data();
                            return 'Detalle Ticket '+data[0];
                        }
                    } ),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            },
        lengthChange: false,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : true,
        rowReorder  : false,
        order: [[ 0, "asc" ]],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros.",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            infoPostFix: "",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "Ningún dato disponible en esta tabla",
            row: "Registro",
            export: "Exportar",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": Activar para ordenar la columna de manera ascendente",
                sortDescending: ": Activar para ordenar la columna de manera descendente"
            },
            select: {
                row: "registro",
                selected: "seleccionado"
            }
        },
        dom: 'Bfrtip',
        buttons: [
                {
                    extend: 'collection',
                    text: 'Exportar',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        {extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL'},
                        {
                            extend: 'print',
                            customize: function ( win ) {
                                $(win.document.body)
                                    .css( 'font-size', '10pt' );

                                $(win.document.body).find( 'table' )
                                    .addClass( 'compact' )
                                    .css( 'font-size', 'inherit' );
                            }
                        }
                    ]
                }],

    });

    $('#recurrentesf').DataTable({
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }],
        responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal( {
                        header: function ( row ) {
                            var data = row.data();
                            return 'Detalle Ticket '+data[0];
                        }
                    } ),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            },
        lengthChange: false,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : true,
        rowReorder  : false,
        order: [[ 0, "asc" ]],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros.",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            infoPostFix: "",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "Ningún dato disponible en esta tabla",
            row: "Registro",
            export: "Exportar",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Ultimo"
            },
            aria: {
                sortAscending: ": Activar para ordenar la columna de manera ascendente",
                sortDescending: ": Activar para ordenar la columna de manera descendente"
            },
            select: {
                row: "registro",
                selected: "seleccionado"
            }
        },
        dom: 'Bfrtip',
        buttons: [
                {
                    extend: 'collection',
                    text: 'Exportar',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        {extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL'},
                        {
                            extend: 'print',
                            customize: function ( win ) {
                                $(win.document.body)
                                    .css( 'font-size', '10pt' );

                                $(win.document.body).find( 'table' )
                                    .addClass( 'compact' )
                                    .css( 'font-size', 'inherit' );
                            }
                        }
                    ]
                }],

    });

});

