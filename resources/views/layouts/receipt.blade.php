<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        body {
            color: #666;
            font-size: 12px;
            font-weight: 400;
            line-height: 1.4em;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f5f6fa;
        }

        .tm_pos_invoice_wrap {
            max-width: 340px;
            margin: auto;
            margin-top: 0px;
            padding: 30px 20px;
            background-color: #fff;
        }

        .tm_pos_company_logo {
            display: flex;
            justify-content: center;
            margin-bottom: 7px;
        }

        .tm_pos_company_logo img {
            vertical-align: middle;
            border: 0;
            max-width: 100%;
            height: auto;
            max-height: 45px;
        }

        .tm_pos_invoice_top {
            text-align: center;
            margin-bottom: 18px;
        }

        .tm_pos_invoice_heading {
            display: flex;
            justify-content: center;
            position: relative;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold !important;
            margin: 10px 0;
            color:#0018A8 !important;

        }

        .tm_pos_invoice_heading:before {
            content: '';
            position: absolute;
            height: 0;
            width: 100%;
            left: 0;
            top: 46%;
            border-top: 1px dashed #666;
        }

        .tm_pos_invoice_heading span {
            display: inline-flex;
            padding: 0 5px;
            background-color: #fff;
            z-index: 1;
            font-weight: 500;
            position: relative;
        }

        .tm_list.tm_style1 {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
        }

        .tm_list.tm_style1 li {
            display: flex;
            width: 100%;
            font-size: 12px;
            line-height: 1.2em;
            margin-bottom: 7px;
        }

        .text-right {
            text-align: right;
            justify-content: flex-end;
        }

        .tm_list_title {
            color: #111;
            margin-right: 4px;
            font-weight: 500;
        }

        .d-flex {
            display: flex !important;
        }

        .justify-content-between {
            justify-content: space-between !important;
        }

        .tm_list_desc {
            text-align: right;
        }

        .tm_invoice_seperator {
            width: 150px;
            border-top: 1px dashed #666;
            margin: 9px 0;
            margin-left: auto;
        }

        .tm_pos_invoice_table {
            width: 100%;
            margin-top: 10px;
            line-height: 1.3em;
        }

        .tm_pos_invoice_table thead th {
            font-weight: 500;
            color: #111;
            text-align: left;
            padding: 8px 3px;
            border-top: 1px dashed #666;
            border-bottom: 1px dashed #666;
        }

        .tm_pos_invoice_table td {
            padding: 4px;
        }

        .tm_pos_invoice_table tbody tr:first-child td {
            padding-top: 10px;
        }

        .tm_pos_invoice_table tbody tr:last-child td {
            padding-bottom: 10px;
            border-bottom: 1px dashed #666;
        }

        .tm_pos_invoice_table th:last-child,
        .tm_pos_invoice_table td:last-child {
            text-align: right;
            padding-right: 0;
        }

        .tm_pos_invoice_table th:first-child,
        .tm_pos_invoice_table td:first-child {
            padding-left: 0;
        }

        .tm_pos_invoice_table tr {
            vertical-align: baseline;
        }

        .tm_bill_list {
            list-style: none;
            margin: 0;
            padding: 12px 0;
            border-bottom: 1px dashed #666;
        }

        .tm_bill_list_in {
            display: flex;
            text-align: right;
            justify-content: flex-end;
            padding: 3px 0;
        }

        .tm_bill_title {
            padding-right: 20px;
        }

        .tm_bill_value {
            width: 90px;
        }

        .tm_bill_value.tm_bill_focus,
        .tm_bill_title.tm_bill_focus {
            font-weight: 500;
            color: #111;
        }

        .tm_pos_invoice_footer {
            text-align: center;
            margin-top: 20px;
            color: #0018A8;
        }

        .tm_pos_sample_text {
            text-align: center;
            padding: 12px 0;
            border-bottom: 1px dashed #666;
            line-height: 1.6em;
            color: #9c9c9c;
        }

        .tm_pos_company_name {
            font-weight: bold;
            color: #111;
            font-size: 20px;
            line-height: 1.4em;
            color: #0018A8;

        }
    

        /* Start Receipt Section */
        .tm_container {
            max-width: 480px;
            padding: 30px 15px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }

        @media (min-width: 575px) {
            .tm_invoice_btns {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                margin-top: 0px;
                margin-left: 0;
                position: absolute;
                right: 0px;
                top: 30px;
                -webkit-box-shadow: -2px 0 24px -2px rgba(43, 55, 72, 0.05);
                box-shadow: -2px 0 24px -2px rgba(43, 55, 72, 0.05);
                border: 3px solid #fff;
                border-radius: 6px;
                background-color: #fff;
            }

            .tm_invoice_btn {
                display: -webkit-inline-box;
                display: -ms-inline-flexbox;
                display: inline-flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                border: none;
                font-weight: 600;
                cursor: pointer;
                padding: 0;
                background-color: transparent;
                position: relative;
            }

            .tm_invoice_btn svg {
                width: 24px;
            }

            .tm_invoice_btn .tm_btn_icon {
                padding: 0;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                height: 42px;
                width: 42px;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
            }

            .tm_invoice_btn .tm_btn_text {
                position: absolute;
                left: 100%;
                background-color: #111;
                color: #fff;
                padding: 3px 12px;
                display: inline-block;
                margin-left: 10px;
                border-radius: 5px;
                top: 50%;
                -webkit-transform: translateY(-50%);
                transform: translateY(-50%);
                font-weight: 500;
                min-height: 28px;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                opacity: 0;
                visibility: hidden;
            }

            .tm_invoice_btn .tm_btn_text:before {
                content: "";
                height: 10px;
                width: 10px;
                position: absolute;
                background-color: #111;
                -webkit-transform: rotate(45deg);
                transform: rotate(45deg);
                left: -3px;
                top: 50%;
                margin-top: -6px;
                border-radius: 2px;
            }

            .tm_invoice_btn:hover .tm_btn_text {
                opacity: 1;
                visibility: visible;
            }

            .tm_invoice_btn:not(:last-child) {
                margin-bottom: 3px;
            }

            .tm_invoice_btn.tm_color1 {
                background-color: rgba(0, 122, 255, 0.1);
                color: #007aff;
                border-radius: 5px 5px 0 0;
            }

            .tm_invoice_btn.tm_color1:hover {
                background-color: rgba(0, 122, 255, 0.2);
            }

            .tm_invoice_btn.tm_color2 {
                background-color: rgba(52, 199, 89, 0.1);
                color: #34c759;
                border-radius: 0 0 5px 5px;
            }

            .tm_invoice_btn.tm_color2:hover {
                background-color: rgba(52, 199, 89, 0.2);
            }
        }

        @media (max-width: 574px) {
            .tm_invoice_btns {
                display: -webkit-inline-box;
                display: -ms-inline-flexbox;
                display: inline-flex;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                margin-top: 0px;
                margin-top: 20px;
                -webkit-box-shadow: -2px 0 24px -2px rgba(43, 55, 72, 0.05);
                box-shadow: -2px 0 24px -2px rgba(43, 55, 72, 0.05);
                border: 3px solid #fff;
                border-radius: 6px;
                background-color: #fff;
                position: relative;
                left: 50%;
                -webkit-transform: translateX(-50%);
                transform: translateX(-50%);
            }

            .tm_invoice_btn {
                display: -webkit-inline-box;
                display: -ms-inline-flexbox;
                display: inline-flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                border: none;
                font-weight: 600;
                cursor: pointer;
                padding: 0;
                background-color: transparent;
                position: relative;
                border-radius: 5px;
                padding: 6px 15px;
                text-decoration: none;
            }

            .tm_invoice_btn svg {
                width: 24px;
            }

            .tm_invoice_btn .tm_btn_icon {
                padding: 0;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                margin-right: 8px;
            }

            .tm_invoice_btn:not(:last-child) {
                margin-right: 3px;
            }

            .tm_invoice_btn.tm_color1 {
                background-color: rgba(0, 122, 255, 0.1);
                color: #007aff;
            }

            .tm_invoice_btn.tm_color1:hover {
                background-color: rgba(0, 122, 255, 0.2);
            }

            .tm_invoice_btn.tm_color2 {
                background-color: rgba(52, 199, 89, 0.1);
                color: #34c759;
            }

            .tm_invoice_btn.tm_color2:hover {
                background-color: rgba(52, 199, 89, 0.2);
            }
        }

        @media print {
            .tm_hide_print {
                display: none !important;
            }
        }

        /* End Receipt Section */
    </style>
</head>

<body>
    <div class="tm_container">
        {{ $slot ?? '' }}
    </div>

    <script src="{{ asset('pub-pages/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('pub-pages/assets/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('pub-pages/assets/js/html2canvas.min.js') }}"></script>
    
    <script type="text/javascript">
        document.getElementById('tm_download_btn').addEventListener('click',
            Export);

        function Export() {
            html2canvas(document.getElementById('tm_download_section'), {
                onrendered: function(canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("receipt.pdf");
                }
            });
        }
    </script>
</body>

</html>