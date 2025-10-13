<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Print Table | {{ config('app.name') }}</title>
        <meta charset="UTF-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                margin: 30px;
                background: #fff;
                color: #222;
                font-family: 'Segoe UI', Arial, sans-serif;
            }
            .table {
                background: #fff;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }
            th, td {
                vertical-align: middle !important;
                padding: 10px 12px !important;
                font-size: 15px;
            }
            th {
                background: #f8f9fa !important;
                font-weight: 600;
                border-bottom: 2px solid #dee2e6 !important;
            }
            tr:nth-child(even) td {
                background: #f4f6f8;
            }
            @media print {
                body {
                    margin: 0;
                }
                .table {
                    box-shadow: none;
                }
            }
            .print-title {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
                text-align: center;
                letter-spacing: 1px;
            }
            .print-meta {
                text-align: center;
                margin-bottom: 2rem;
                color: #888;
                font-size: 1rem;
            }
        </style>
    </head>
    <body>
        <div class="print-title">{{ config('app.name') }}</div>
        <div class="print-meta">Printed at {{ now()->format('Y-m-d H:i') }}</div>
        <table class="table table-bordered table-striped">
            @foreach($data as $row)
                @if ($loop->first)
                    <thead>
                        <tr>
                            @foreach($row as $key => $value)
                                <th>{!! $key !!}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                @endif
                <tr>
                    @foreach($row as $key => $value)
                        @if(is_string($value) || is_numeric($value))
                            <td>{!! $value !!}</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
                @if ($loop->last)
                    </tbody>
                @endif
            @endforeach
        </table>

        <script @cspNonce>
            window.print();
            window.onafterprint = function() {
                fadeOutAndClose();
            };

            let printCanceled = false;

            function fadeOutAndClose() {
                document.body.style.transition = 'opacity 0.3s ease-out';
                document.body.style.opacity = '0';
                setTimeout(function() {
                    window.close();
                }, 300);
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    printCanceled = true;
                    setTimeout(function() {
                        fadeOutAndClose();
                    }, 100);
                }
            });

            setTimeout(function() {
                if (!printCanceled) {
                    fadeOutAndClose();
                }
            }, 1000);
        </script>
    </body>
</html>
