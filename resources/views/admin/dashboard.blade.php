@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
  <h1>Dashboard</h1>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="num">{{ $productCount }}</div>
      <div class="label">Total products</div>
    </div>
    <div class="stat-card">
      <div class="num">{{ $activeProductCount }}</div>
      <div class="label">Active &amp; live</div>
    </div>
    <div class="stat-card">
      <div class="num">{{ $newOrderCount }}</div>
      <div class="label">New orders awaiting action</div>
    </div>
  </div>

  <div class="card">
    <h2 style="margin-top:0; font-size:1rem;">Recent orders</h2>
    <table>
      <tr>
        <th>Ref</th><th>Product</th><th>Customer</th><th>Total</th><th>Status</th>
      </tr>
      @forelse($recentOrders as $order)
        <tr>
          <td>{{ $order->order_ref }}</td>
          <td>{{ $order->product->name ?? '—' }}</td>
          <td>{{ $order->customer_name }}</td>
          <td>{{ number_format($order->total_price, 0) }} {{ $order->product->currency ?? 'UGX' }}</td>
          <td><span class="pill pill-{{ $order->status }}">{{ $order->status }}</span></td>
        </tr>
      @empty
        <tr><td colspan="5">No orders yet.</td></tr>
      @endforelse
    </table>
    <p style="margin-top:12px;"><a href="{{ route('admin.orders.index') }}">View all orders &rarr;</a></p>
  </div>
@endsection
