function printAttendance()
{
    var divToPrint=document.getElementById("print_attendance");
    var htmlToPrint = '' +
        '<style type="text/css">' +
        '.table-bordered {' +
        'border: 1px solid #ddd;' +
        '}' +
        '.table {' +
        'width: 100%;' +
        'max-width: 100%;' +
        'margin-bottom: 20px;' +
        '}' +
        'table {' +
        'border-spacing: 0;' +
        'border-collapse: collapse;' +
        '}' +
        '.table-bordered>tbody>tr>th, .table-bordered>tbody>tr>td {' +
        'border: 1px solid #ddd;' +
        '}' +
        '.table>tbody>tr>td, .table>tbody>tr>th, {' +
        'padding: 8px;' +
        'line-height: 1.42857143;' +
        'vertical-align: top;' +
        '}' +
        'td {' +
        'text-align: left;' +
        'display: table-cell;' +
        '}' +
        'th {' +
        'text-align: center;' +
        'font-weight: bold;' +
        'display: table-cell;' +
        '}' +
        'body {' +
        'color: #73879C;' +
        'font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;' +
        'font-size: 8px;' +
        '}' +
        '</style>';


    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.document.getElementById('print').style.display='block';
    newWin.print();
    newWin.close();
}