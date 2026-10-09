@extends('admin.layout')
@section('title', 'Products')

@section('content')
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <h1>Products</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add Product</a>
  </div>

  <form method="GET" style="margin-bottom:14px;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
  </form>

  <table>
    <tr>
      <th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Active</th><th></th>
    </tr>
    @forelse($products as $product)
      <tr>
        <td><img src="{{ $product->imageUrl() }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:6px;"></td>
        <td>{{ $product->name }}</td>
        <td>{{ $product->formattedPrice() }}</td>
        <td>
          <form method="POST" action="{{ route('admin.products.toggle-stock', $product) }}">
            @csrf
            <button type="submit" class="pill pill-{{ $product->stock_status === 'in_stock' ? 'delivered' : 'cancelled' }}" style="border:none; cursor:pointer;">
              {{ str_replace('_', ' ', $product->stock_status) }}
            </button>
          </form>
        </td>
        <td>{{ $product->is_active ? 'Yes' : 'No' }}</td>
        <td style="white-space:nowrap;">
          <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary">Edit</a>
          <a href="{{ route('products.show', $product) }}" class="btn btn-secondary" target="_blank">View</a>
          <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Delete this product? This cannot be undone.');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="6">No products yet — add your first one.</td></tr>
    @endforelse
  </table>

  <div style="margin-top:14px;">{{ $products->links() }}</div>
@endsection
