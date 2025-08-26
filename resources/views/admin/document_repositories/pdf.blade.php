<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <style>
        table {
            margin: 0 auto;
        }

        /* Default Table Style */
        table {
            color: #333;
            background: white;
            border: 1px solid grey;
            font-size: 10pt;
            width: 100%;
            border-collapse: collapse;
        }

        table thead th,
        table tfoot th {
            /* color: #777;
            background: rgba(0, 0, 0, .1); */
            color: #000;
            background: rgb(254 153 1);
        }

        table caption {
            padding: .5em;
        }

        table th,
        table td {
            padding: 0.3em;
            height: 10px;
            border: 1px solid lightgrey;
        }

        /* Zebra Table Style */
        [data-table-theme*=zebra] tbody tr:nth-of-type(odd) {
            background: rgba(0, 0, 0, .05);
        }

        [data-table-theme*=zebra][data-table-theme*=dark] tbody tr:nth-of-type(odd) {
            background: rgba(255, 255, 255, .05);
        }

        /* Dark Style */
        [data-table-theme*=dark] {
            color: #ddd;
            background: #333;
            font-size: 10px;
            border-collapse: collapse;
        }

        [data-table-theme*=dark] thead th,
        [data-table-theme*=dark] tfoot th {
            color: #aaa;
            background: rgba(0255, 255, 255, .15);
        }

        [data-table-theme*=dark] caption {
            padding: .5em;
        }

        [data-table-theme*=dark] th,
        [data-table-theme*=dark] td {
            padding: .5em;
            border: 1px solid grey;
        }

        .main,
        .sec-head,
        .third-head,
        .fourth-head,
        .fifth-head,
        .sixth-head,
        .seventh-head,
        .eight-head,
        .nineth-head,
        .tenth-head,
        .eleventh-head,
        .twelve-head,
        .thirteen-head,
        .fourteen-head {
            text-transform: uppercase;
            margin: 5px 0px;
            font-size: 16px;
            font-weight: 700;
            color: #031d5a;
            text-shadow: 1px 1px 2px black;
        }

        /* .fifth-head{
        position: relative;
    } */
        .back-image {
            padding: 5px 0;
            width: 30%;
            display: block !important;
            margin: 0 auto !important;
        }

        .billing,
        .shipping {
            width: 48%;
            /* Adjust to 48% to add some spacing */
            display: inline-block;
            vertical-align: top;
            margin-right: 2%;
            /* Add a small margin for spacing */
        }

        .shipping {
            margin-right: 0;
            /* Remove the margin from the last element */
        }

        .invoice-dates {
            margin-bottom: 20px;
            /* Add space below the date row */
        }

        .invoice-dates .col-md-4 {
            padding: 10px;
            /* Adjust padding if needed */
        }

        .invoice-dates p {
            margin: 0;
            /* Remove default margin to align text better */
        }

        .complete {
            position: absolute;
            top: 810px;
            left: 360px;
            width: 50%;
        }

        .complete2 {
            position: absolute;
            top: 585px;
            left: 360px;
            width: 50%;
        }

        .current-address {
            height: 46px !important;
            position: fixed;
        }

        .permanent-address {
            height: 46px !important;
            position: fixed;
        }

        .complete-table1 {
            margin-bottom: 5px;
        }

        .children-detail {
            height: 123px !important;
        }

        .first-page {
            margin-bottom: 20px;
        }

        .first-page-2 {
            margin-bottom: 150px;
        }

        .second-page {
            margin-bottom: 100px;
        }

        .reference1 {
            margin-bottom: 30px;
        }

        .third-page {
            margin-bottom: 520px;
        }

        .documents {
            width: 75%;
            margin-left: 0px;
            margin-top: 20px;
            margin-bottom: 70px;
        }

        .documents2 {
            width: 75%;
            margin-left: 0px;
        }

        /* .fourth-page{
        margin-bottom:610px;
    } */
        .information-first {
            margin-bottom: 20px;
        }

        .information-signature {
            width: 35%;
            margin-left: 0px;
            margin-bottom: 30px;
        }

        .invoice-dates {
            font-size: 16px;
        }

        .content-fourth {
            height: 700px !important;
            margin-bottom: 300px;
        }
    </style>
</head>

<body>
    <img class="back-image" src="{{ asset('public/admin/assets/images/page') }}/{{ $home_page_data['header_logo'] }}" alt="Logo">
    <h4 class="main" style="text-align: center; font-size: 24px; margin-top: 20px;">DOCUMENT DETAILS</h4>

    <hr>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="row box-header">
                            <div class="col-md-6">
                                <h4 class="box-title">Project Category: <h3>{{ ucfirst($document->parent_id) }}</h3></h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="box-title">Project Name: <h3>{{ ucfirst($document->name) }}</h3></h4>
                            </div>
                        </div>

                        <div class="row invoice-dates">
                            <div class="col-md-6">
                                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($document->start_date)->format('d/M/Y') ?? 'N/A' }}</p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($document->end_date)->format('d/M/Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>