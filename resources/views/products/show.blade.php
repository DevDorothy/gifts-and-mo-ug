<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} — {{ $settings->business_name }}</title>

    {{-- ===== Open Graph tags: control how this looks when shared on WhatsApp/Facebook/X/etc ===== --}}
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $product->name }}">
    <meta property="og:description" content="{{ $product->short_description ?: $product->name }}">
    <meta property="og:image" content="{{ $product->imageUrl() }}">
    <meta property="og:url" content="{{ $product->url() }}">
    <meta property="product:price:amount" content="{{ $product->price }}">
    <meta property="product:price:currency" content="{{ $product->currency }}">

    {{-- ===== Twitter/X card tags ===== --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->name }}">
    <meta name="twitter:description" content="{{ $product->short_description ?: $product->name }}">
    <meta name="twitter:image" content="{{ $product->imageUrl() }}">

    {{-- ===== Structured data: tells Google this is a Product, with price & stock ===== --}}
    <script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@type": "Product",
  "name": @json($product->name),
  "description": @json($product->description ?: $product->short_description),
  "image": @json($product->imageUrl()),
  "offers": {
    "@type": "Offer",
    "url": @json($product->url()),
    "priceCurrency": @json($product->currency),
    "price": @json($product->price),
    "availability": "{{ $product->stock_status === 'in_stock' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
  }
}
</script>

    <style>
        :root {
            --brand: #d6336c;
            --dark: #1a1a1a;
            --gray: #666;
            --bg: #fafafa;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: -apple-system, Segoe UI, Roboto, sans-serif;
            background: var(--bg);
            color: var(--dark);
        }

        .wrap {
            max-width: 480px;
            margin: 0 auto;
            background: #fff;
            min-height: 100vh;
        }

        .product-image {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            display: block;
        }

        .content {
            padding: 16px 18px 100px;
        }

        h1 {
            font-size: 1.3rem;
            margin: 0 0 6px;
        }

        .price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--brand);
            margin: 4px 0 12px;
        }

        .old-price {
            text-decoration: line-through;
            color: var(--gray);
            font-size: 1rem;
            margin-left: 8px;
        }

        .stock {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-bottom: 14px;
        }

        .stock.in {
            background: #e6f7ec;
            color: #1e7e34;
        }

        .stock.out {
            background: #fdecea;
            color: #c0392b;
        }

        .description {
            color: #333;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .order-buttons {
            display: grid;
            gap: 10px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
        }

        .btn-whatsapp {
            background: #25D366;
            color: #fff;
        }

        .btn-call {
            background: #1a1a1a;
            color: #fff;
        }

        .btn-sms {
            background: #eee;
            color: #1a1a1a;
        }

        .btn-site {
            background: var(--brand);
            color: #fff;
        }

        .related {
            padding: 0 18px 30px;
        }

        .related h2 {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .related-item {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
        }

        .related-item img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
        }

        .related-item .ri-info {
            padding: 6px 8px;
            font-size: 0.8rem;
        }

        .qty-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }

        .qty-stepper {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .qty-stepper button {
            width: 36px;
            height: 36px;
            border: none;
            background: #f2f2f2;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .qty-stepper span {
            width: 32px;
            text-align: center;
            font-weight: 600;
        }

        .share-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .btn-share {
            background: #fff;
            color: var(--dark);
            border: 1px solid #ddd;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .share-feedback {
            font-size: 0.8rem;
            color: #1e7e34;
        }
    </style>
</head>

<body>
    <div class="wrap">

        <img class="product-image" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">

        <div class="content">
            <h1>{{ $product->name }}</h1>

            <div class="price" id="live-price">
                {{ $product->formattedPrice() }}
                @if ($product->old_price)
                    <span class="old-price">{{ number_format($product->old_price, 0) }}
                        {{ $product->currency }}</span>
                @endif
            </div>

            <span class="stock {{ $product->stock_status === 'in_stock' ? 'in' : 'out' }}">
                {{ $product->stock_status === 'in_stock' ? 'In Stock' : 'Out of Stock' }}
            </span>

            <div class="qty-row">
                <span>Quantity</span>
                <div class="qty-stepper">
                    <button type="button" id="qty-minus" aria-label="Decrease quantity">−</button>
                    <span id="qty-display">1</span>
                    <button type="button" id="qty-plus" aria-label="Increase quantity">+</button>
                </div>
            </div>

            <div class="share-row">
                <button type="button" class="btn-share" id="btn-share">🔗 Share this product</button>
                <span class="share-feedback" id="share-feedback"></span>
            </div>

            <p class="description">{{ $product->description ?: $product->short_description }}</p>

            {{-- ===== The four order options ===== --}}
            <div class="order-buttons">
                <a class="btn btn-whatsapp" id="btn-whatsapp" href="{{ $whatsappLink }}" target="_blank"
                    rel="noopener">💬 Shop on WhatsApp</a>
                <a class="btn btn-call" href="{{ $callLink }}">📞 Call to Place Order</a>
                <a class="btn btn-sms" id="btn-sms" href="{{ $smsLink }}">✉️ Send SMS to Order</a>
                <a class="btn btn-site" id="btn-site" href="{{ route('checkout.show', $product) }}">🛒 Shop from
                    Site</a>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <div class="related">
                <h2>You may also like</h2>
                <div class="related-grid">
                    @foreach ($related as $r)
                        <a class="related-item" href="{{ route('products.show', $r) }}">
                            <img src="{{ $r->imageUrl() }}" alt="{{ $r->name }}">
                            <div class="ri-info">
                                <div>{{ $r->name }}</div>
                                <strong>{{ $r->formattedPrice() }}</strong>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <script>
        // Values the product.js file needs to rebuild prices/links client-side when quantity changes.
        window.productConfig = {
            price: {{ $product->price }},
            currency: @json($product->currency),
            productUrl: @json($product->url()),
            productName: @json($product->name),
            whatsappNumber: {!! json_encode(preg_replace('/\D/', '', $settings->whatsapp_number)) !!},
            smsNumber: @json($settings->sms_number),
        };
    </script>
    <script src="{{ asset('js/product.js') }}"></script>
</body>

</html>
