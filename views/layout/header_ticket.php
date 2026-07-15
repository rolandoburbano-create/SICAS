<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - SICAS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            background: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .ticket {
            background: #fff;
            width: 340px;
            padding: 14px 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            border-radius: 6px;
        }
        .ticket-header {
            text-align: center;
            border-bottom: 2px dashed #1B5E20;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .ticket-header h2 {
            font-size: 14px;
            color: #1B5E20;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .ticket-header .sub {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }
        .ticket-body { font-size: 11px; line-height: 1.7; }
        .ticket-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dotted #ddd;
        }
        .ticket-row .label { color: #555; font-weight: bold; flex: 1; }
        .ticket-row .value { text-align: right; font-weight: bold; color: #222; }
        .ticket-row.diff .value { color: #c0392b; }
        .ticket-qr {
            text-align: center;
            padding: 10px 0;
            border-top: 1px dashed #1B5E20;
            margin-top: 8px;
        }
        .ticket-qr img { width: 130px; height: 130px; }
        .ticket-qr p { font-size: 7px; color: #888; margin-top: 4px; word-break: break-all; }
        .ticket-footer {
            text-align: center;
            border-top: 2px dashed #1B5E20;
            padding-top: 8px;
            margin-top: 8px;
            font-size: 8px;
            color: #888;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 14px;
            justify-content: center;
        }
        .actions a, .actions button {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .actions .btn-print {
            background: #1B5E20;
            color: #fff;
        }
        .actions .btn-back {
            background: #e0e0e0;
            color: #333;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .ticket { box-shadow: none; border-radius: 0; width: 100%; padding: 10px; }
            .actions { display: none !important; }
            @page { margin: 6mm; }
        }
    </style>
</head>
<body>
