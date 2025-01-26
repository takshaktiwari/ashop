<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail</title>

    <style>
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #dfdfdf;
            color: #2e2e2e;
            font-size: 14px;
        }

        table.main {
            background-color: white;
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            font-family: sans-serif;
        }

        table.main .header td {
            border-bottom: 2px solid #dfdfdf;
        }

        table.main .body>td,
        table.main .header>td,
        table.main .footer>td {
            padding: 1rem 2rem;
        }

        table.main .footer td {
            border-top: 2px solid #dfdfdf;
        }

        ul > li {
            margin-bottom: 5px;
        }

        ul > li > ul {
            margin-top: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fs-10 {
            font-size: 10px;
        }

        .fs-12 {
            font-size: 12px;
        }

        .fs-14 {
            font-size: 14px;
        }

        .mb-5 {
            margin-bottom: 5px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <table class="table main" cellpadding="12" cellspacing="0">
        <tr class="header">
            <td>
                <br />
                <h2 class="text-center">{{ config('ashop.shop.name') }}</h2>
                <br />
            </td>
        </tr>
        <tr class="body">
            <td>
                {{ $slot }}
                <br />
                <p>
                    If you have any questions about your order or need assistance, feel free to contact our support team at <a
                        href="mailto:{{ config('ashop.shop.email') }}">{{ config('ashop.shop.email') }}</a> or <a
                        href="tel:{{ config('ashop.shop.mobile') }}">{{ config('ashop.shop.mobile') }}</a>.
                </p>
                <br />
                <p>
                    Thank you for choosing {{ config('ashop.shop.name') }}. We look forward to serving you again!
                </p>
            </td>
        </tr>
        <tr class="footer">
            <td>
                <p style="margin-bottom: 10px;"><b>Warm regards,</b></p>
                <p>
                    {{ config('ashop.shop.name') }} <br />
                    {{ config('ashop.shop.email') }} <br />
                    {{ config('ashop.shop.website') }}
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
