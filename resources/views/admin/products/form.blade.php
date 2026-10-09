@extends('admin.layout')
@section('title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
  <h1>{{ $product->exists ? 'Edit Product' : 'Add Product' }}</h1>

  <div class="card">
    <form method="POST"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if($product->exists) @method('PUT') @endif

      <label>Product Name</label>
      <input type="text" name="name" value="{{ old('name', $product->name) }}" required>

      <label>Short Description <span style="font-weight:400;color:#888;">(shown in link previews when shared)</span></label>
      <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" maxlength="300">

      <label>Full Description</label>
      <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>

      <div style="display:flex; gap:14px;">
        <div style="flex:1;">
          <label>Price</label>
          <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
        </div>
        <div style="flex:1;">
          <label>Old Price <span style="font-weight:400;color:#888;">(optional, shows as struck-through)</span></label>
          <input type="number" step="0.01" name="old_price" value="{{ old('old_price', $product->old_price) }}">
        </div>
        <div style="flex:1;">
          <label>Currency</label>
          <input type="text" name="currency" value="{{ old('currency', $product->currency ?? 'UGX') }}" required>
        </div>
      </div>

      <div style="display:flex; gap:14px;">
        <div style="flex:1;">
          <label>Stock Status</label>
          <select name="stock_status" required>
            @foreach(['in_stock' => 'In Stock', 'out_of_stock' => 'Out of Stock', 'preorder' => 'Preorder'] as $val => $label)
              <option value="{{ $val }}" @selected(old('stock_status', $product->stock_status) === $val)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div style="flex:1;">
          <label>Stock Quantity <span style="font-weight:400;color:#888;">(optional)</span></label>
          <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}">
        </div>
        <div style="flex:1;">
          <label>Category <span style="font-weight:400;color:#888;">(optional)</span></label>
          <select name="category_id">
            <option value="">— None —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <label>Main Image {{ $product->exists ? '(leave blank to keep current)' : '' }}</label>
      @if($product->exists && $product->main_image)
        <img src="{{ $product->imageUrl() }}" alt="" style="width:80px;height:80px;object-fit:cover;border-radius:8px;margin-bottom:6px;">
      @endif
      <input type="file" name="main_image" accept="image/*" {{ $product->exists ? '' : 'required' }}>

      @if(count($allProducts) > 0)
        <label>Related Products <span style="font-weight:400;color:#888;">(hold Ctrl/Cmd to select several)</span></label>
        <select name="related_products[]" multiple size="5">
          @foreach($allProducts as $rp)
            <option value="{{ $rp->id }}" @selected(in_array($rp->id, old('related_products', $relatedIds ?? [])))>{{ $rp->name }}</option>
          @endforeach
        </select>
      @endif

      <label style="display:flex; align-items:center; gap:8px; margin-top:16px;">
        <input type="checkbox" name="is_active" value="1" style="width:auto;" @checked(old('is_active', $product->is_active))>
        Active (visible to customers)
      </label>

      <button type="submit" class="btn btn-primary" style="margin-top:20px;">
        {{ $product->exists ? 'Save Changes' : 'Create Product' }}
      </button>
    </form>
  </div>

  @if($product->exists)
    {{-- ===== QR code ===== --}}
    <div class="card">
      <h2 style="margin-top:0; font-size:1rem;">QR Code</h2>
      @if($product->qr_code_path)
        <img src="{{ asset('storage/' . $product->qr_code_path) }}" alt="QR code" style="width:140px;height:140px;margin-bottom:10px;">
        <br>
        <a href="{{ asset('storage/' . $product->qr_code_path) }}" download class="btn btn-secondary">Download QR Code</a>
      @else
        <p style="color:#666; font-size:0.9rem;">No QR code generated yet.</p>
      @endif
      <form method="POST" action="{{ route('admin.products.qr-code', $product) }}" style="display:inline-block; margin-left:8px;">
        @csrf
        <button type="submit" class="btn btn-primary">{{ $product->qr_code_path ? 'Regenerate' : 'Generate' }} QR Code</button>
      </form>
    </div>

    {{-- ===== Share panel ===== --}}
    <div class="card">
      <h2 style="margin-top:0; font-size:1rem;">Share this product</h2>
      <label>Product Link</label>
      <input type="text" readonly value="{{ $shareLinks['url'] }}" onclick="this.select()">

      <label>Caption</label>
      <textarea readonly rows="3" onclick="this.select()">{{ $shareLinks['caption'] }}</textarea>

      <div style="margin-top:12px; display:flex; gap:8px; flex-wrap:wrap;">
        <a class="btn btn-secondary" href="{{ $shareLinks['whatsapp'] }}" target="_blank">WhatsApp</a>
        <a class="btn btn-secondary" href="{{ $shareLinks['facebook'] }}" target="_blank">Facebook</a>
        <a class="btn btn-secondary" href="{{ $shareLinks['x'] }}" target="_blank">X</a>
        <a class="btn btn-secondary" href="{{ $shareLinks['email'] }}" target="_blank">Email</a>
        <a class="btn btn-secondary" href="{{ $shareLinks['sms'] }}" target="_blank">SMS</a>
      </div>
      <p style="font-size:0.8rem; color:#888; margin-top:10px;">
        Instagram and TikTok don't support pre-filled share links — copy the caption above and paste it
        into the post/story composer in those apps, along with the QR code or product image.
      </p>
    </div>
  @endif
@endsection
