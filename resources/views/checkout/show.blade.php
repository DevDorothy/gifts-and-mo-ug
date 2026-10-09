<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout — {{ $product->name }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: -apple-system, Segoe UI, Roboto, sans-serif;
            background: #fafafa;
        }

        .wrap {
            max-width: 480px;
            margin: 0 auto;
            background: #fff;
            min-height: 100vh;
            padding: 20px 18px;
        }

        h1 {
            font-size: 1.2rem;
        }

        .summary {
            display: flex;
            gap: 12px;
            align-items: center;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .summary img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .summary strong {
            display: block;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin: 14px 0 4px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            background: #d6336c;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
        }

        .error-box {
            background: #fdecea;
            color: #c0392b;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .success-box {
            background: #e6f7ec;
            color: #1e7e34;
            padding: 16px;
            border-radius: 10px;
            text-align: center;
        }

        .success-box h2 {
            margin-top: 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
            font-weight: 700;
        }

        .total-row span:last-child {
            color: #d6336c;
        }
    </style>
</head>

<body>
    <div class="wrap">

        @if (isset($orderRef))
            <div class="success-box">
                <h2>Order placed! 🎉</h2>
                <p>Your order reference is <strong>{{ $orderRef }}</strong>.</p>
                <p>We'll contact you shortly on the number you provided to confirm delivery.</p>
                <p><a href="{{ route('products.show', $product) }}">&larr; Back to product</a></p>
            </div>
        @else
            <h1>Checkout</h1>

            <div class="summary">
                <img src="{{ $product->imageUrl() }}" alt="">
                <div>
                    <strong>{{ $product->name }}</strong>
                    {{ $product->formattedPrice() }}
                </div>
            </div>

            {{-- Server-side validation errors (shown if JS is off or something slips past it) --}}
            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Client-side validation errors get injected here by checkout.js --}}
            <div class="error-box" id="js-error-box" style="display:none;"></div>

            <form method="POST" action="{{ route('checkout.store', $product) }}" id="checkout-form">
                @csrf

                <label>Quantity</label>
                <input type="number" name="quantity" id="quantity-input"
                    value="{{ old('quantity', request('quantity', 1)) }}" min="1">

                <div class="total-row">
                    <span>Total</span>
                    <span id="total-price">{{ $product->formattedPrice() }}</span>
                </div>

                <label>Full Name</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>

                <label>Phone Number</label>
                <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}"
                    placeholder="07XXXXXXXX" required>

                <label>Email (optional)</label>
                <input type="email" name="customer_email" value="{{ old('customer_email') }}">

                <label>Delivery Address / Landmark</label>
                <textarea name="delivery_address" rows="2" required>{{ old('delivery_address') }}</textarea>

                <label>Area / Town</label>
                <input type="text" name="delivery_area" value="{{ old('delivery_area') }}"
                    placeholder="e.g. Ntinda, Kampala">

                <label>Notes (optional)</label>
                <textarea name="notes" rows="2">{{ old('notes') }}</textarea>

                <button type="submit">Place Order</button>
            </form>

        @endif

    </div>

    <script>
        window.checkoutConfig = {
            unitPrice: {{ $product->price }},
            currency: @json($product->currency),
        };
    </script>
    <script src="{{ asset('js/checkout.js') }}"></script>
</body>

</html>
