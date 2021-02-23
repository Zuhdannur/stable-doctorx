<!doctype html>
<html lang="en">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css'>
<head>
    <meta charset="UTF-8">
    <title>Print - #{{ $purchase->financeTrx->transaction_code }} - @yield('title', app_name())</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        
        body {
            margin: 0px;
        }
        
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        
        a {
            color: #fff;
            text-decoration: none;
        }
        
        table {
            font-size: x-small;
        }
        
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        
        .invoice table {
            margin: 15px;
        }
        
        .invoice h3 {
            margin-left: 15px;
        }
        
        .information {
            background-color: #FFF;
            color: #000;
        }
        
        .information .logo {
            margin: 5px;
        }
        
        .information table {
            padding: 10px;
        }
    </style>

</head>

<body>

    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" style="width: 40%;">
                    @switch($purchase->status)
                    @case(1)
                        <h2 class='text-success'>Lunas</h2>
                        @break
                    @default
                        @php
                            $due_date = date_create(date('Y-m-d' ,strtotime($purchase->due_date)));
                            $now = date_create(date('Y-m-d'));
                            $diff = date_diff($now, $due_date);
                            $diff = $diff->format('%R%a');
                        @endphp
                        @if ($diff > 0)
                            <h2 class='text-warning'>Belum Lunas</h2>
                        @else
                            <h2 class='text-danger'>Jatuh Tempo</h2>
                        @endif
                @endswitch
                    <h5>Penerima: {{ $purchase->financeTrx->person }}</h5>
                    <h6>Dibuat Pada: {{ $purchase->created_at }}</h6>
                    <h6>Jatuh Tempo: {{ strtotime($purchase->due_date) > 0 ? date('d/m/y', strtotime($purchase->due_date)) : ' - ' }}</h6>
                </td>
                <td align="center">
                    <img src="{{ public_path().'/'.setting()->get('logo_square') }}" alt="Logo" width="128" class="logo" />
                </td>
                <td align="right" style="width: 40%;">
                    <h3>{{ setting()->get('app_name') }}</h3>
                    <pre>
                        Telepon: {{ setting()->get('phone') }}
                        Email: {{ setting()->get('email') }}
                        Website: {{ url('/') }}
                        <br /><br /><br /><br />
                    </pre>
                </td>
            </tr>

        </table>
    </div>

    <br/>

    <div class="invoice">
        <h3>Pembelian No.{{ $purchase->financeTrx->transaction_code  }}</h3>
        <table class="table table-hover" width="100%">
            <thead class="thead splitForPrint">
                <tr>
                    <th scope="col gray-ish">#</th>
                    <th scope="col gray-ish">Items</th>
                    <th scope="col gray-ish">Deskripsi</th>
                    <th class="text-right" scope="col gray-ish">Qty</th>
                    <th class="text-right" scope="col gray-ish">Harga Satuan</th>
                    <th class="text-right" scope="col gray-ish">Pajak</th>
                    <th class="text-right" scope="col gray-ish">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sub_total = 0;
                    $totalTax = 0;
                    $potonganValue = '0';
                    $potongan_account = '';
                    $no = 1;
                @endphp

                @foreach($purchase->detail as $key => $item)
                        <tr>                                    
                            <th scope="row">{{ ($no++) }}</th>
                            @php
                                $product = explode('#',$item->items, 2);
                                $product_detail = explode('-', $product[1]);
                            @endphp
                            <td class="item">{{ (isset($product_detail[0]) ? $product_detail[0] : '' ).( isset($product_detail[1]) ? '-'.$product_detail[1] : '')}}</td>
                            <td class="text-center">{{ $item->desc }}</td>
                            <td class="text-right">{{ $item->qty }}</td>
                            <td class="text-right">{{ currency()->rupiah($item->price, setting()->get('currency_symbol')) }}</td>
                            <td class="text-right">{{ $item->tax_label }}</td>
                            <td class="text-right">{{ currency()->rupiah($item->price_total, setting()->get('currency_symbol')) }}</td>
                            @php
                                $sub_total = $sub_total + $item->price_total;
                                $sum_tax = $item->price_total * $item->tax_value / 100;
                                $totalTax = $totalTax + $sum_tax;
                            @endphp
                        </tr>
                @endforeach

                <tr>
                    <td colspan="6" class="font-w600 text-right">Subtotal</td>
                    <td class="text-right">{{ currency()->rupiah($sub_total, setting()->get('currency_symbol')) }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="font-w600 text-right">Total Tax</td>
                    <td class="text-right">{{ currency()->rupiah($totalTax, setting()->get('currency_symbol')) }}</td>
                </tr>
                <tr>
                    @if ($purchase->financeTrx->potongan)
                        {{-- get potongan --}}
                         @php
                            $potongan = $purchase->getPotongan();
                            if($potongan){
                                $potonganValue = $potongan->value;
                                $potongan_account = $potongan->account->account_name ? $potongan->account->account_name : '';
                            }else{
                                $potonganValue = 0;
                            }
                        @endphp 

                        <td colspan="6" class="font-w600 text-right">Potongan ( {{ $potongan_account}}) : {{ $purchase->financeTrx->potongan." %" }}</td>
                        <td class="text-right">{{ currency()->rupiah($potonganValue, setting()->get('currency_symbol')) }}</td>
                    @else
                        <td colspan="6" class="font-w600 text-right">Potongan</td>
                        <td class="text-right">{{ currency()->rupiah(0, setting()->get('currency_symbol')) }}</td>
                    @endif
                </tr>
                <tr>
                    @if ($purchase->getBiaya())
                        {{-- get potongan --}}
                        @php
                            $biaya = $purchase->getBiaya();
                            if($biaya){
                                $biayaValue = $biaya->value;
                                $biaya_account = $biaya->account->account_name ? $biaya->account->account_name : '';
                            }else{
                                $biayaValue = 0;
                            }
                        @endphp 
                        <td colspan="6" class="font-w600 text-right">Biaya Tambahan ( {{ $biaya_account}}) : {{ $purchase->financeTrx->biaya }}</td>
                        <td class="text-right">{{ currency()->rupiah($biayaValue, setting()->get('currency_symbol')) }}</td>
                    @else
                        <td colspan="6" class="font-w600 text-right">Biaya Tambahan</td>
                        <td class="text-right">{{ currency()->rupiah(0, setting()->get('currency_symbol')) }}</td>
                    @endif
                </tr>
                <tr class="table-warning">
                    <td colspan="6" class="font-w700 text-uppercase text-right">Total</td>
                    <td class="font-w700 text-right">{{ currency()->rupiah($purchase->total, setting()->get('currency_symbol')) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="text-center text-muted pb-3"><em> Faktur dibuat dan valid tanpa tanda tangan dan meterai.</em></p>
    <div class="information" style="position: absolute; bottom: 0;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
                </td>
                <td align="right" style="width: 50%;">
                    {{ setting()->get('address') }}
                </td>
            </tr>

        </table>
    </div>
    
    <script src="//code.jquery.com/jquery-3.4.1.min.js}"></script>
    <script type="text/javascript">
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
    </script>
</body>

</html>