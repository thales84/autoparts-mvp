<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f4f4; font-family: Arial, sans-serif; font-size: 15px; color: #333; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #1B3A6B; padding: 24px 32px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 20px; letter-spacing: .5px; }
        .header span { color: #E8820C; }
        .body { padding: 32px; }
        .body h2 { margin-top: 0; font-size: 18px; color: #1B3A6B; }
        .info-box { background: #f8f9fa; border-left: 4px solid #1B3A6B; padding: 16px 20px; border-radius: 4px; margin: 20px 0; }
        .info-box p { margin: 6px 0; }
        .info-box strong { color: #1B3A6B; }
        table.items { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px; }
        table.items th { background: #1B3A6B; color: #fff; padding: 10px 12px; text-align: left; }
        table.items td { padding: 10px 12px; border-bottom: 1px solid #e9ecef; }
        table.items tr:last-child td { border-bottom: none; }
        .total-row td { font-weight: bold; background: #f8f9fa; }
        .btn { display: inline-block; background: #E8820C; color: #ffffff !important; text-decoration: none; padding: 12px 28px; border-radius: 4px; font-weight: bold; margin: 16px 0; }
        .footer { background: #f8f9fa; padding: 20px 32px; text-align: center; font-size: 13px; color: #888; border-top: 1px solid #e9ecef; }
        .badge-success { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: bold; }
        .badge-danger  { background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1><span>●</span> {{ config('app.name') }}</h1>
    </div>
    <div class="body">
        @yield('content')
    </div>
    <div class="footer">
        <p>{{ config('app.name') }} — Pièces détachées automobiles d'occasion</p>
        <p>Vous recevez cet e-mail car vous avez passé une commande sur notre site.</p>
    </div>
</div>
</body>
</html>
