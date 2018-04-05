$(document).ready(function() {
    var from = document.getElementById("from_date").value;
    var to = document.getElementById("to_date").value;

    $('#initiative_report_table').removeAttr('width').DataTable( {
        dom: 'Bfrtip',
        scrollX: true,
        autoWith: false,
        columnDefs: [
            { "className": "dt-center", "width": 100, "targets": 0},
            { "className": "dt-center", "width": 100, "targets": 1},
            { "className": "dt-center", "width": 100, "targets": 2},
            { "className": "dt-center", "width": 100, "targets": 3},
            { "className": "dt-center", "width": 100, "targets": 4},
            { "className": "dt-center", "width": 100, "targets": 5},
            { "className": "dt-center", "width": 100, "targets": 6},
            { "className": "dt-center", "width": 100, "targets": 7},
            { "className": "dt-center", "width": 100, "targets": 8},
            { "className": "dt-center", "width": 100, "targets": 9},
            { "className": "dt-center", "width": 100, "targets": 10},
            { "className": "dt-center", "width": 100, "targets": 11},
            { "className": "dt-center", "width": 100, "targets": 12},
            { "className": "dt-center", "width": 100, "targets": 13},
            { "className": "dt-center", "width": 100, "targets": 14},
            { "className": "dt-center", "width": 100, "targets": 15},
            { "className": "dt-center", "width": 100, "targets": 16},
            { "className": "dt-center", "width": 100, "targets": 17},
            { "className": "dt-center", "width": 100, "targets": 18},
            { "className": "dt-center", "width": 100, "targets": 19},
            { "className": "dt-center", "width": 100, "targets": 20},
            { "className": "dt-center", "width": 100, "targets": 21},
            { "className": "dt-center", "width": 100, "targets": 22},
            { "className": "dt-center", "width": 100, "targets": 23},
            { "className": "dt-center", "width": 100, "targets": 24},
            { "className": "dt-center", "width": 100, "targets": 25},
            { "className": "dt-center", "width": 100, "targets": 26},
            { "className": "dt-center", "width": 100, "targets": 27},
            { "className": "dt-center", "width": 100, "targets": 28},
            { "className": "dt-center", "width": 100, "targets": 29},
            { "className": "dt-center", "width": 100, "targets": 30},
            { "className": "dt-center", "width": 100, "targets": 31},
            { "className": "dt-center", "width": 100, "targets": 32},
            { "className": "dt-center", "width": 100, "targets": 33},
            { "className": "dt-center", "width": 100, "targets": 34},
            { "className": "dt-center", "width": 100, "targets": 35},
            { "className": "dt-center", "width": 100, "targets": 36},
            { "className": "dt-center", "width": 100, "targets": 37},
            { "className": "dt-center", "width": 100, "targets": 38},
            { "className": "dt-center", "width": 100, "targets": 39},
            { "className": "dt-center", "width": 100, "targets": 40},
            { "className": "dt-center", "width": 100, "targets": 41},
            { "className": "dt-center", "width": 100, "targets": 42},
            { "className": "dt-center", "width": 100, "targets": 43},
            { "className": "dt-center", "width": 100, "targets": 44},
            { "className": "dt-center", "width": 100, "targets": 45},
            { "className": "dt-center", "width": 100, "targets": 46},
            { "className": "dt-center", "width": 100, "targets": 47},
            { "className": "dt-center", "width": 100, "targets": 48},
            { "className": "dt-center", "width": 100, "targets": 49},
            { "className": "dt-center", "width": 100, "targets": 50},
            { "className": "dt-center", "width": 100, "targets": 51},
            { "className": "dt-center", "width": 100, "targets": 52},
            { "className": "dt-center", "width": 100, "targets": 53},
            { "className": "dt-center", "width": 100, "targets": 54},
            { "className": "dt-center", "width": 100, "targets": 55},
            { "className": "dt-center", "width": 100, "targets": 56},
            { "className": "dt-center", "width": 100, "targets": 57},
            { "className": "dt-center", "width": 100, "targets": 58},
            { "className": "dt-center", "width": 100, "targets": 59},
            { "className": "dt-center", "width": 100, "targets": 60},
            { "className": "dt-center", "width": 100, "targets": 61},
            { "className": "dt-center", "width": 100, "targets": 62},
            { "className": "dt-center", "width": 100, "targets": 63},
            { "className": "dt-center", "width": 100, "targets": 64},
            { "className": "dt-center", "width": 100, "targets": 65},
            { "className": "dt-center", "width": 100, "targets": 66},
            { "className": "dt-center", "width": 100, "targets": 67}
        ],
        buttons: {
            buttons: [
                {
                    extend: 'colvis',
                    text: 'Hide/Unhide Columns'
                },
                {
                    extend: 'print',
                    text: 'Print Table', 
                    title: 'Initiative Report',
                    autoPrint: true,
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '10pt');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                },
                {
                    extend: 'excel',
                    text: 'Save to Excel',
                    title: 'Initiative Report'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Save to PDF',
                    action: function(e, dt, node, config) 
                    {
                        var req = new XMLHttpRequest();
                        var fd = new FormData();

                        fd.append("report_from", from);
                        fd.append("report_to", to);

                        req.open("POST", "php/reports/initiative_report/create_initiative_report_pdf.php", true);
                        req.responseType = "blob";

                        req.onreadystatechange = function () 
                        {
                            if (req.readyState === 4 && req.status === 200) 
                            {
                                var filename = "Initiative Report.pdf";

                                if (typeof window.chrome !== 'undefined') 
                                {
                                    // Chrome version
                                    var link = document.createElement('a');

                                    link.href = window.URL.createObjectURL(req.response);
                                    link.download = filename;
                                    link.click();
                                } 
                                else if (typeof window.navigator.msSaveBlob !== 'undefined') 
                                {
                                    // IE version
                                    var blob = new Blob([req.response], { type: 'application/pdf' });
                                    window.navigator.msSaveBlob(blob, filename);
                                }
                                else
                                {
                                    // Firefox version
                                    var file = new File([req.response], filename, { type: 'application/force-download' });
                                    window.open(URL.createObjectURL(file));
                                }
                            }
                        };
                        req.send(fd);
                    }
                }
            ],
            columnDefs: [ {
                visible: false
            }]
        }
    });
});