<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Print - #{{ $billing->invoice_no }} - @yield('title', app_name())</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css'>

    <style type="text/css">
        @page {
            bleed: 1cm;
            size: A4 portrait;
            size: auto;
            /* auto is the initial value */
            margin-bottom: 50pt;
            margin-top: 0cm;
            font-size: 12pt;
            #content,
            #page {
                width: 100%;
                margin: 0;
                float: none;
            }
        }
        
        @media print {
            .page {
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
            table {
                page-break-inside: auto;
            }
            tr.last-row {
                background-color: #555!important;
            }
            tr.last-row > th,
            tr.last-row > td {
                background-color: unset!important;
            }
            div.page-break {
                page-break-before: auto;
            }
        }
        
        .gray {
            color: #333;
        }
        
        .gray-ish {
            color: #666;
        }
        
        .almost-gray {
            color: #999;
        }
        
        body {
            background-color: #fff;
            padding-top: 25px;
            -webkit-print-color-adjust: exact !important;
            height: 100%;
        }
        
        div.container {
            background-color: white;
            border-radius: 10px;
            height: 100%;
            position: relative;
            margin-bottom: 50px;
        }
        
        div.invoice-header {
            background-color: #444;
            color: white;
            border-bottom: 3px solid rgb(255, 77, 77);
        }
        
        div.invoice-header > div > p {
            font-size: 1.2rem;
            font-weight: 350;
        }
        
        div.invoice-header > div > h1 {
            font-size: 4rem;
        }
        
        div.invoice-table {
            border-top: 3px solid rgb(255, 77, 77);
        }
        
        div.invoice-table > table.table > thead,
        div.invoice-table > table.table > thead.thead > tr,
        div.invoice-table > table.table > thead.thead > tr > th {
            border-top: none;
        }
        
        div.total-field {
            position: relative;
        }
        
        h5.due-date {
            position: absolute;
            bottom: 10px;
            right: 15px;
        }
        
        div.sub-table {
            border-left: 3px solid rgb(255, 77, 77);
            padding-left: 0;
        }
        
        div.sub-table > table {
            padding-bottom: 0;
            margin-bottom: 0;
        }
        
        tr.last-row {
            margin-top: 25px;
            background-color: #555;
            color: white;
            border-top: 3px solid rgb(255, 77, 77);
        }
        
        p.footer {
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: white;
            padding-top: 15px;
            border-top: 3px solid red;
        }
    </style>
    <div class="container">
        <div class="row px-5 pt-5">
            <div class="col-3 contact-details">
                <h5>Pasien: {{ $billing->patient->patient_name }}</h5>
                <h6><em>ID: {{ $billing->patient->patient_unique_id }}</em></h6>
                <p><em>Alamat: {{ isset($billing->patient->address) ? ucwords(strtolower($billing->patient->address)) : '-' }}, {{ isset($billing->patient->city->name) ? ucwords(strtolower($billing->patient->city->name)) : '-' }} {{ $billing->patient->zip_code }}</em></p>
            </div>
            <div class="col-1 offset-2 logo">
                <img width="125px" height="125px" src="{{ asset(setting()->get('logo_square')) }}">
            </div>
            <div class="invoice-details col-3 offset-3 text-right">
                <h6>Invoice No. #{{ $billing->invoice_no }}</h6>
                <h6>Dibuat Pada: {{ $billing->created_at }}</h6>
                <h6>Jatuh Tempo: {{ $billing->date }}</h6>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-10 offset-1 invoice-table pt-1">
                <table class="table table-hover">
                    <thead class="thead splitForPrint">
                        <tr>
                            <th scope="col gray-ish">NO.</th>
                            <th scope="col gray-ish">Produk/Servis</th>
                            <th scope="col gray-ish">Qty.</th>
                            <th scope="col gray-ish">Harga</th>
                            <th class="text-right" scope="col gray-ish">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($billing->invDetail as $key => $item)
                        @php
                            $amount = $item->qty * $item->price;
                        @endphp
                        <tr>
                            <th scope="row">{{ ($key+1) }}</th>
                            <td class="item">{{ $item->product }}</td>
                            <td>{{ $item->qty }}</td>
                            <td class="text-right">{{ currency()->rupiah($item->price, setting()->get('currency_symbol')) }}</td>
                            <td class="text-right">{{ currency()->rupiah($amount, setting()->get('currency_symbol')) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row invoice_details">
            <!-- invoiced to details -->
            <div class="col-4 offset-1 pt-3">
                <h4 class="gray-ish">Ringkasan dan Catatan Faktur</h4>
                <p class="pt-3 almost-gray">{{ $billing->notes }}</p>
            </div>
            <!-- Invoice assets and total -->
            <div class="offset-1 col-5 mb-3 pr-3 sub-table">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th scope="row gray-ish">Subtotal</th>
                            <td class="text-right">{{ currency()->rupiah($billing->subtotal, setting()->get('currency_symbol')) }}</td>
                        </tr>
                        <tr class="d-none">
                            <th scope="row gray-ish">Total Tax</th>
                            <td class="text-right">{{ currency()->rupiah($billing->total_tax, setting()->get('currency_symbol')) }}</td>
                        </tr>
                        <tr>
                            <th scope="row gray-ish">Diskon {{ $billing->discount_percent }}</th>
                            <td class="text-right">{{ currency()->rupiah($billing->discountprice, setting()->get('currency_symbol')) }}</td>
                        </tr>
                        <tr class="last-row">
                            <th scope="row">
                                <h4>Total</h4></th>
                            <td class="text-right">
                                <h4>{{ currency()->rupiah($billing->totalprice, setting()->get('currency_symbol')) }}</h4></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="text-center pb-3"><em> Faktur dibuat dan valid tanpa tanda tangan dan meterai.</em></p>
    </div>

    <!-- Laravel Scaffolding JS -->
    <script src="//code.jquery.com/jquery-3.4.1.min.js}"></script>
    <script type="text/javascript">
    jQuery(function () {
        /**
         * PDF page settings.
         * Must have the correct values for the script to work.
         * All numbers must be in inches (as floats)!
         * '/25.4' part is a converstiom from mm to in.
         *
         * @type {Object}
         */
        var pdfPage = {
            width: 11.7, // inches
            height: 8.3, // inches
        };

        /**
         * The distance to bottom of which if the element is closer, it should moved on
         * the next page. Should be at least the element (TR)'s height.
         *
         * @type {Number}
         */
        var splitThreshold = 42;

        /**
         * Class name of the tables to automatically split.
         * Should not contain any CSS definitions because it is automatically removed
         * after the split.
         *
         * @type {String}
         */
        var splitClassName = 'splitForPrint';

        var debug = false;
        var profile = false;
        var startTime = 0;
        var endTime = 0;
        var tableProcessStart = 0;
        var tableProcessEnd = 0;
        var profileDivsList = [];
        var profileDiv = $('<div>');
        var dpi = 120;

        var pageWidth = (pdfPage.width - pdfPage.margins.left - pdfPage.margins.right) * dpi;
        // page height in pixels
        var pageHeight = (pdfPage.height - pdfPage.margins.top - pdfPage.margins.bottom) * dpi;
        // temporary set body's width and padding to match pdf's size
        var $body = $('body .report_data'); //a single div should wrap whole pdf content
        $body.css('width', pageWidth);
        $body.css('margin-left', pdfPage.margins.left + 'in');
        $body.css('margin-right', pdfPage.margins.right + 'in');
        $body.css('margin-top', pdfPage.margins.top + 'in');
        $body.css('margin-bottom', pdfPage.margins.bottom + 'in');

        var currentPageZero = 0; //the offset for the currentPage. Will go with pageHeight increments page by page
        var tablesModified = true;
        /** div with page-break logic
         * css should include
         * page-break-before: always;
         */
        var breaker = $('<div class="page-break"></div>');
        var pageOffset = 0;
        /**
         * Table splitting logic lives in this method
         * table - table to split, should be a jQuery object
         * tableIndex - and index from original page where this table is sitting, allows to properly insert table back to where it belonged
         * onTableSplittedCallback - a function that will be caleed when table will be splitted out - allows to do some post-processing if required
         */
        function splitTable(table, tableIndex, onTableSplittedCallback) {

            /**
             * Logic of appending a new table
             * container - jQuery object where we will append our table (NOTE: a collectable 'div' is used for each table in the script for memory optimization, it should be passed here)
             * trs - array of <tr> objects (non jQuery) for our new table
             * isLastBatch - passed as 'true' if we have tr's left over from processing and we don't want a page break because there still can be some place left on the page
             */

            //The idea here is that we grab our header from original table, clone it, add some modifications and use it for all our future tables
            //find our header
            var originalHeader = table.find('tr.heading1');
            var tableHeader;
            var tableHeaderHeight;
            if (originalHeader.length > 0) {
                tableHeader = originalHeader.clone();
                tableHeader.addClass('page-split'); //mark header so we could display that this one is splitted
                /*
                 * In my project we had to add ellipsis before header on each new page
                 * Feel free to modify this part.
                 * Idea here is that first row of the table determines all column widths, and if it will not be exactly as our header row - table will be messed up significantly
                 * So, we have to make a copy of our header, clean it from the text and add some ellipsis
                 */
                var ellipsis = tableHeader.clone();
                ellipsis.removeClass('heading1');
                ellipsis.find('th,td').each(function(i, e) {
                    $(e).text("");
                });
                var ellipsisDiv = $('<div>').addClass('ellipsis').text("...");
                ellipsis.find('th:eq(0),td:eq(0)').append(ellipsisDiv);
                //!careful here. first row of table determines column widths!
                tableHeader = tableHeader.before(ellipsis);
            }

            templateTable.append("<tr class='noborder'><td></td></tr>");

            var tmpTables = []; //this array will store temporarry tables - we will append them after splitting logic is finished
            var tmpTrs = []; //this array will store rows for each temporarry table
            var collectableDiv = $('<div>'); //this div will collect our splitted table

            $('tbody tr', table).each(function() {
                var tr = $(this);
                //get offset for current page, taking custom pageOffset into consideration
                var trTop = tr.offset().top - currentPageZero - pageOffset;

                if (debug) {
                    aa.text(aa.text() + "(o:" + tr.offset().top + " : t:" + trTop + " || ");
                }

                //if we fit the page with threshold - go ahead and push tr into tmpTrs array
                if (trTop >= pageHeight - splitThreshold) { //else go to the next page
                    if (tmpTrs.length == 1 && $(tmpTrs[0]).hasClass('heading1')) {
                        tmpTables.push([]); //if the only row we have fit the page is a header - add a page split and move on - we don't need a single header left in the end of the page
                    } else {
                        //another special case. if we hit the page end and  prev.row is 'heading2' - don't leave it last on the page
                        if ($(tmpTrs[tmpTrs.length - 1]).hasClass('heading2')) {
                            var heading2Row = tmpTrs.pop();
                            var heading1Row = null;
                            if ($(tmpTrs[tmpTrs.length - 1]).hasClass('heading1')) { //if it turnes out that heading1 is before - pop it also
                                heading1Row = tmpTrs.pop();
                            }
                            tmpTables.push(tmpTrs);
                            tmpTrs = [];
                            if (heading1Row) {
                                tmpTrs.push(heading1Row);
                            }
                            tmpTrs.push(heading2Row);
                        } else {
                            //save table and start new
                            tmpTables.push(tmpTrs);
                            tmpTrs = [];
                        }
                    }

                    currentPageZero += pageHeight;
                }
                tmpTrs.push(tr[0]);
            });

            //save leftower for the page and remove the original table away
            tmpTables.push(tmpTrs);
            tmpTrs = [];

            var originalTableHeight = table.outerHeight();
            collectableDiv.css('width', table.width());
            table.remove();

            //append each splitted table to a collectable div
            $.each(tmpTables, function(i, trs) {
                appendTable(collectableDiv, trs, i === tmpTables.length - 1);
            })

            //add that div to the page and particular index - this is where rendering will take place
            var elementInParent = parent.children().eq(tableIndex);
            if (elementInParent.length > 0) {
                elementInParent.before(collectableDiv);
            } else {
                parent.children().eq(tableIndex - 1).after(collectableDiv);
            }
            pageOffset += (collectableDiv.outerHeight() - originalTableHeight); //new table can be greater then the original because we added some new headers

            /*Feel free to strip out debuggin and profiling to minimize script size*/
            if (profile) {
                resultsApppendEnd = new Date().getTime();
                profileDivsList.push("<div>== append results took:" + (resultsApppendEnd - resultsApppendStart) + "</div>")
            }
            if (profile) {
                tableProcessEnd = new Date().getTime();
                profileDivsList.push("<div>== process table at " + tableIndex + " took:" + (tableProcessEnd - tableProcessStart) + "</div>")
            }
        }

        while (tablesModified) {
            tablesModified = false;
            while ($('table.' + splitClassName).length > 0) {
                var table = $('table.' + splitClassName + ':eq(0)');
                splitTable(table, table.index(), function(splittedTable) {
                    //some custom post-processing for each new table. This aslo was project-specific, feel free to modify
                    var headers = splittedTable.find('.heading1');
                    if (headers.length > 1) {
                        splittedTable.find('.ellipsis').addClass('hidden');
                        splittedTable.find('.heading1.page-split').addClass('hidden');
                    }
                    if (splittedTable.find('.page-split').length > 0) {
                        splittedTable.removeClass('new_section');
                    }
                    if (splittedTable.find('.page-split.hidden').length > 0) {
                        splittedTable.addClass('new_section');
                    }
                    return splittedTable;
                });
            }
        }

        // restore body's padding
        $body.css('padding-left', 0);
        $body.css('padding-right', 0);
    });
    </script>
    </body>

</html>