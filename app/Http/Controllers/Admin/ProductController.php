<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\ImageWatermarker;
use App\Support\ShareLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductController extends Controller
{
    // Shown across the bottom of every uploaded product photo. Edit this if the brand name or
    // contact number changes — or wire it up to the settings table later if you want it editable
    // from the admin UI without a code change.
    private const WATERMARK_TEXT = 'Gifts&Mo Ug  |  0701385493';

    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', ['products' => $products]);
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product(['stock_status' => 'in_stock', 'currency' => 'UGX', 'is_active' => true]),
            'categories' => Category::orderBy('name')->get(),
            'allProducts' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['slug'] = $this->uniqueSlug($data['name']);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
            ImageWatermarker::stamp(Storage::disk('public')->path($data['main_image']), self::WATERMARK_TEXT);
        }

        $product = Product::create($data);

        $this->syncRelated($product, $request->input('related_products', []));

        return redirect()->route('admin.products.edit', $product)
            ->with('status', 'Product created. You can now generate its QR code or share it below.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'allProducts' => Product::where('id', '!=', $product->id)->orderBy('name')->get(),
            'relatedIds' => $product->relatedProducts()->pluck('products.id')->toArray(),
            'shareLinks' => ShareLinks::forProduct($product),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request, $product);

        if ($product->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
            ImageWatermarker::stamp(Storage::disk('public')->path($data['main_image']), self::WATERMARK_TEXT);
        }

        $product->update($data);

        $this->syncRelated($product, $request->input('related_products', []));

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }
        if ($product->qr_code_path) {
            Storage::disk('public')->delete($product->qr_code_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    // Toggle in-stock / out-of-stock with one click from the product list
    public function toggleStock(Product $product)
    {
        $product->update([
            'stock_status' => $product->stock_status === 'in_stock' ? 'out_of_stock' : 'in_stock',
        ]);

        return back()->with('status', "{$product->name} marked as " . str_replace('_', ' ', $product->stock_status) . '.');
    }

    // Generates (or regenerates) a QR code PNG pointing straight at the product's public URL.
    // Requires: composer require simplesoftwareio/simple-qrcode — see README.
    public function generateQrCode(Product $product)
    {
        $path = 'qrcodes/' . $product->slug . '.png';

        $qrImage = QrCode::format('png')->size(500)->margin(1)->generate($product->url());
        Storage::disk('public')->put($path, $qrImage);

        $product->update(['qr_code_path' => $path]);

        return back()->with('status', 'QR code generated — it now links straight to this product.');
    }

    private function validateData(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'short_description' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'old_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'stock_status' => ['required', 'in:in_stock,out_of_stock,preorder'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'main_image' => [$product && $product->exists ? 'nullable' : 'required', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Product::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-" . ++$i;
        }

        return $slug;
    }

    private function syncRelated(Product $product, array $relatedIds): void
    {
        // Keep it symmetric: if A relates to B, B relates to A too — simpler for admins to reason about.
        $relatedIds = array_filter(array_map('intval', $relatedIds), fn ($id) => $id !== $product->id);
        $product->relatedProducts()->sync($relatedIds);

        foreach (Product::whereIn('id', $relatedIds)->get() as $related) {
            $related->relatedProducts()->syncWithoutDetaching([$product->id]);
        }
    }
}
