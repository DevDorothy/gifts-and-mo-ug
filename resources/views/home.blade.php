<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $settings->business_name }}</title>
<style>
  * { box-sizing: border-box; }
  body { margin: 0; font-family: -apple-system, Segoe UI, Roboto, sans-serif; background: #fafafa; }
  .wrap { max-width: 480px; margin: 0 auto; padding: 16px; }
  h1 { font-size: 1.3rem; }
  .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
  .card { background: #fff; border: 1px solid #eee; border-radius: 10px; overflow: hidden; text-decoration: none; color: inherit; }
  .card img { width: 100%; aspect-ratio: 1/1; object-fit: cover; }
  .card .info { padding: 8px 10px; }
  .card strong { display: block; font-size: 0.85rem; }
  .card .p { color: #d6336c; font-weight: 700; font-size: 0.9rem; }
</style>
</head>
<body>
<div class="wrap">
  <h1>{{ $settings->business_name }}</h1>
  <div class="grid">
    @foreach($products as $p)
      <a class="card" href="{{ route('products.show', $p) }}">
        <img src="{{ $p->imageUrl() }}" alt="{{ $p->name }}">
        <div class="info">
          <strong>{{ $p->name }}</strong>
          <span class="p">{{ $p->formattedPrice() }}</span>
        </div>
      </a>
    @endforeach
  </div>
</div>
</body>
</html>
