<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Plant Nursery Inventory') | {{ config('app.name', 'PNIMS') }}</title>
    <style>
        :root { color-scheme: light; font-family: Arial, sans-serif; color: #20342b; background: #f5f8f5; }
        * { box-sizing: border-box; }
        body { margin: 0; }
        a { color: #176b45; }
        .topbar { background: #173d2b; color: #fff; }
        .nav, .container { width: min(1120px, calc(100% - 32px)); margin: 0 auto; }
        .nav { min-height: 64px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { color: #fff; font-size: 18px; font-weight: 700; text-decoration: none; }
        .nav-links { display: flex; align-items: center; flex-wrap: wrap; gap: 18px; }
        .nav-links a { color: #e7f2e9; text-decoration: none; }
        .nav form { margin: 0; }
        .nav button { border: 0; color: #e7f2e9; background: transparent; cursor: pointer; font-size: 14px; }
        .container { padding: 32px 0 56px; }
        h1 { margin: 0 0 8px; font-size: 28px; }
        h2 { margin-top: 0; }
        .muted { color: #65756b; }
        .page-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px; }
        .panel { background: #fff; border: 1px solid #e0e9e1; border-radius: 12px; padding: 22px; box-shadow: 0 4px 16px #173d2b0a; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; }
        .filters { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; align-items: end; }
        label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; }
        input, select, textarea { display: block; width: 100%; padding: 10px 12px; border: 1px solid #cbd8ce; border-radius: 7px; background: #fff; color: #20342b; font: inherit; }
        textarea { min-height: 120px; resize: vertical; }
        .field { margin-bottom: 16px; }
        .btn { display: inline-block; border: 1px solid #176b45; border-radius: 7px; padding: 9px 14px; background: #176b45; color: #fff; text-decoration: none; cursor: pointer; font: inherit; }
        .btn.secondary { background: #fff; color: #176b45; }
        .btn.danger { background: #a83232; border-color: #a83232; }
        .actions { display: flex; flex-wrap: wrap; align-items: center; gap: 9px; }
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 18px; }
        .alert.success { color: #155b38; background: #e3f5e9; }
        .alert.error { color: #8d2929; background: #fdeaea; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 10px; text-align: left; vertical-align: top; border-bottom: 1px solid #e7eee8; }
        th { color: #516459; font-size: 13px; text-transform: uppercase; letter-spacing: .04em; }
        .badge { display: inline-block; padding: 4px 9px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge.in { color: #155b38; background: #e3f5e9; }
        .badge.out { color: #8d2929; background: #fdeaea; }
        .error-text { color: #a83232; font-size: 13px; margin-top: 5px; }
        .pagination { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 18px; }
        .pagination a, .pagination span { padding: 7px 10px; border: 1px solid #d5e0d7; border-radius: 6px; text-decoration: none; }
        .pagination .active span { background: #176b45; color: #fff; }
        @media (max-width: 640px) { .nav { align-items: flex-start; padding: 14px 0; flex-direction: column; } .page-head { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
    <header class="topbar">
        <nav class="nav">
            <a class="brand" href="{{ route('plants.index') }}">PNIMS · Nursery inventory</a>
            <div class="nav-links">
                <a href="{{ route('plants.index') }}">Plants</a>
                <a href="{{ route('plants.reports.index') }}">Reports</a>
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Log out</button>
                </form>
            </div>
        </nav>
    </header>
    <main class="container">
        @if (session('success'))
            <div class="alert success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert error" role="alert">Please correct the highlighted fields.</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
