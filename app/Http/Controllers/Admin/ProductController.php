<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\ProductFile;
use App\Models\AuditLog;
use App\Models\ApprovalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'variants'])->latest();

        // Category scoped check for moderator
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !empty($user->assigned_categories)) {
            $query->whereHas('categories', function ($q) use ($user) {
                $q->whereIn('product_categories.id', $user->assigned_categories);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = ProductCategory::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'product_type' => 'required|string',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Enforce Category-scoped permission
        if (!$user->canAccessCategory((int)$request->category_id)) {
            abort(403, 'You are not assigned to manage products in this category.');
        }

        // Determine status: If Moderator, change may go to pending_approval
        $status = 'published';
        if ($user->isModerator()) {
            $status = 'pending_approval';
        }

        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $product = Product::create([
            'name' => $request->name,
            'name_bn' => $request->name_bn ?: $request->name,
            'slug' => $slug,
            'sku' => $request->sku ?: 'SP-' . strtoupper(Str::random(6)),
            'product_type' => $request->product_type,
            'short_description' => $request->short_description,
            'short_description_bn' => $request->short_description_bn,
            'full_description' => $request->full_description,
            'full_description_bn' => $request->full_description_bn,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price ?: null,
            'is_featured' => $request->has('is_featured'),
            'status' => $status,
            'version' => $request->version ?: '1.0.0',
            'download_instructions' => $request->download_instructions,
            'created_by' => $user->id,
            'approved_by' => $status === 'published' ? $user->id : null,
        ]);

        $product->categories()->sync([$request->category_id]);

        // Audit Log
        AuditLog::record('create', 'products', (string)$product->id, null, $product->toArray());

        return redirect()->route('admin.products.index')->with('success', $status === 'pending_approval'
            ? 'প্রোডাক্টটি সফলভাবে তৈরি হয়েছে এবং অনুমোদনের অপেক্ষায় রয়েছে।'
            : 'প্রোডাক্ট সফলভাবে যুক্ত হয়েছে।');
    }

    public function edit(int $id)
    {
        $product = Product::with(['categories', 'variants', 'files'])->findOrFail($id);
        $categories = ProductCategory::active()->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        if (!$user->canAccessCategory((int)$request->category_id)) {
            abort(403, 'Permission denied for this category.');
        }

        $oldData = $product->toArray();

        $status = $request->status;
        if ($user->isModerator() && $status === 'published') {
            $status = 'pending_approval';
        }

        $product->update([
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'sku' => $request->sku,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price ?: null,
            'is_featured' => $request->has('is_featured'),
            'status' => $status,
            'version' => $request->version,
            'short_description' => $request->short_description,
            'short_description_bn' => $request->short_description_bn,
            'full_description' => $request->full_description,
            'full_description_bn' => $request->full_description_bn,
        ]);

        $product->categories()->sync([$request->category_id]);

        AuditLog::record('update', 'products', (string)$product->id, $oldData, $product->toArray());

        return redirect()->route('admin.products.index')->with('success', 'প্রোডাক্ট তথ্য সফলভাবে আপডেট হয়েছে।');
    }

    public function destroy(int $id)
    {
        $user = auth()->user();
        if (!$user->hasPermission('product.delete')) {
            abort(403, 'Unauthorized to delete products.');
        }

        $product = Product::findOrFail($id);
        $oldData = $product->toArray();
        $product->delete();

        AuditLog::record('delete', 'products', (string)$id, $oldData, null);

        return redirect()->route('admin.products.index')->with('success', 'প্রোডাক্ট মুছে ফেলা হয়েছে।');
    }

    // Categories management
    public function categories()
    {
        $categories = ProductCategory::withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name);
        ProductCategory::create([
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'slug' => $slug,
            'icon' => $request->icon ?: 'folder',
            'description' => $request->description,
            'sort_order' => (int)$request->sort_order,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'নতুন ক্যাটাগরি তৈরি হয়েছে।');
    }

    // Product File Upload
    public function uploadFile(Request $request, int $productId)
    {
        $request->validate([
            'file_name' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'digital_file' => 'required|file|max:102400', // max 100MB
        ]);

        $product = Product::findOrFail($productId);
        $uploaded = $request->file('digital_file');
        $fileName = $request->file_name;
        $path = $uploaded->storeAs('products', time() . '_' . $uploaded->getClientOriginalName(), 'public');

        ProductFile::create([
            'product_id' => $product->id,
            'file_name' => $fileName,
            'file_path' => $path,
            'storage_bucket' => 'storpify-product-downloads',
            'file_size' => $uploaded->getSize(),
            'file_type' => $uploaded->getClientOriginalExtension(),
            'version' => $request->version,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'ডিজিটাল ফাইল সফলভাবে আপলোড হয়েছে।');
    }
}
