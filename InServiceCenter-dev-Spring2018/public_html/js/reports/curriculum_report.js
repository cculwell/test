$(document).ready(function() {
    var from = document.getElementById("from_date").value;
    var to = document.getElementById("to_date").value;    

    $('#curriculum_report_table').removeAttr('width').DataTable( {
        dom: 'Bfrtip',
        scrollX: true,
        autoWith: false,
        columnDefs: [
            { "className": "dt-center", "width": 150, "targets": 0},
            { "className": "dt-center", "width": 150, "targets": 1},
            { "className": "dt-center", "width": 150, "targets": 2},
            { "className": "dt-center", "width": 150, "targets": 3},
            { "className": "dt-center", "width": 150, "targets": 4},
            { "className": "dt-center", "width": 150, "targets": 5},
            { "className": "dt-center", "width": 150, "targets": 6}
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
                    title: 'Curriculum Report',
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
                    title: 'Curriculum Report'
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

                        req.open("POST", "php/reports/curriculum_report/create_curriculum_report_pdf.php", true);
                        req.responseType = "blob";

                        req.onreadystatechange = function () 
                        {
                            if (req.readyState === 4 && req.status === 200) 
                            {
                                var filename = "Curriculum Report.pdf";
                                
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
            }],
        }
    });
});