@extends('admin.layout')
@section('title', 'Orders')

@section('content')
  <h1>Orders</h1>

  <form method="GET" style="margin-bottom:14px; display:flex; gap:10px;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, phone, ref...">
    <select name="status" onchange="this.form.submit()">
      <option value="">All statuses</option>
      @foreach(['new','confirmed','processing','delivered','cancelled'] as $s)
        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-secondary">Filter</button>
  </form>

  <table>
    <tr>
      <th>Ref</th><th>Product</th><th>Customer</th><th>Phone</th><th>Qty</th><th>Total</th><th>Delivery</th><th>Status</th><th>Placed</th>
    </tr>
    @forelse($orders as $order)
      <tr>
        <td>{{ $order->order_ref }}</td>
        <td>{{ $order->product->name ?? '—' }}</td>
        <td>{{ $order->customer_name }}</td>
        <td>{{ $order->customer_phone }}</td>
        <td>{{ $order->quantity }}</td>
        <td>{{ number_format($order->total_price, 0) }} {{ $order->product->currency ?? 'UGX' }}</td>
        <td>{{ $order->delivery_area ?: $order->delivery_address }}</td>
        <td>
          <form method="POST" action="{{ route('admin.orders.status', $order) }}">
            @csrf
            <select name="status" onchange="this.form.submit()">
              @foreach(['new','confirmed','processing','delivered','cancelled'] as $s)
                <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td>{{ $order->created_at->format('d M, H:i') }}</td>
      </tr>
    @empty
      <tr><td colspan="9">No orders match.</td></tr>
    @endforelse
  </table>

  <div style="margin-top:14px;">{{ $orders->links() }}</div>
@endsection
