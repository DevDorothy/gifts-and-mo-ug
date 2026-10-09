<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Admin') — Gifts&Mo Ug</title>
<style>
  * { box-sizing: border-box; }
  body { margin: 0; font-family: -apple-system, Segoe UI, Roboto, sans-serif; background: #f4f4f6; color: #1a1a1a; }
  .admin-wrap { display: flex; min-height: 100vh; }
  nav.sidebar { width: 200px; background: #1a1a1a; color: #fff; flex-shrink: 0; padding: 18px 0; }
  nav.sidebar .brand { font-weight: 700; padding: 0 18px 18px; font-size: 1.05rem; }
  nav.sidebar a { display: block; padding: 10px 18px; color: #ccc; text-decoration: none; font-size: 0.9rem; }
  nav.sidebar a:hover, nav.sidebar a.active { background: #2a2a2a; color: #fff; }
  nav.sidebar form { padding: 10px 18px; }
  nav.sidebar button { background: none; border: none; color: #ccc; font-size: 0.9rem; cursor: pointer; padding: 0; }
  main { flex: 1; padding: 24px 28px; max-width: 980px; }
  h1 { font-size: 1.4rem; margin-top: 0; }
  .status-msg { background: #e6f7ec; color: #1e7e34; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 0.9rem; }
  .error-box { background: #fdecea; color: #c0392b; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 0.9rem; }
  table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; }
  th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 0.88rem; }
  th { background: #fafafa; font-weight: 600; }
  .btn { display: inline-block; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; border: none; cursor: pointer; }
  .btn-primary { background: #d6336c; color: #fff; }
  .btn-secondary { background: #eee; color: #1a1a1a; }
  .btn-danger { background: #fdecea; color: #c0392b; }
  .card { background: #fff; border-radius: 10px; padding: 18px; margin-bottom: 18px; }
  .stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 20px; }
  .stat-card { background: #fff; border-radius: 10px; padding: 16px; }
  .stat-card .num { font-size: 1.6rem; font-weight: 700; color: #d6336c; }
  .stat-card .label { font-size: 0.8rem; color: #666; }
  label { display: block; font-size: 0.85rem; font-weight: 600; margin: 14px 0 4px; }
  input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem; }
  .pill { display: inline-block; padding: 2px 10px; border-radius: 14px; font-size: 0.75rem; font-weight: 600; }
  .pill-new { background: #fff3cd; color: #856404; }
  .pill-confirmed { background: #d1ecf1; color: #0c5460; }
  .pill-processing { background: #e2d9f3; color: #4b2e83; }
  .pill-delivered { background: #e6f7ec; color: #1e7e34; }
  .pill-cancelled { background: #fdecea; color: #c0392b; }
</style>
</head>
<body>
<div class="admin-wrap">

  <nav class="sidebar">
    <div class="brand">Gifts&Mo Ug</div>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.products.index') }}">Products</a>
    <a href="{{ route('admin.orders.index') }}">Orders</a>
    <a href="{{ route('admin.settings.edit') }}">Settings</a>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit">Log out</button>
    </form>
  </nav>

  <main>
    @if(session('status'))
      <div class="status-msg">{{ session('status') }}</div>
    @endif

    @if($errors->any())
      <div class="error-box">
        @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
      </div>
    @endif

    @yield('content')
  </main>

</div>
</body>
</html>
