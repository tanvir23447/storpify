@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">প্রোডাক্ট ম্যানেজমেন্ট</h1>
            <p class="text-xs text-gray-500 mt-1">স্টোরের সকল ডিজিটাল প্রোডাক্ট, প্রাইসিং ও ফাইল ব্যবস্থাপনা</p>
        </div>
        @if(auth()->user()->hasPermission('product.create'))
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md transition-transform hover:scale-105 flex items-center gap-1.5 self-start sm:self-auto">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>নতুন প্রোডাক্ট যুক্ত করুন</span>
            </a>
        @endif
    </div>

    <!-- Filters & Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center gap-3 w-full sm:w-auto">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="প্রোডাক্ট খুঁজুন..." 
                    class="px-4 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-purple-600">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-gray-200 text-xs bg-gray-50">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="pending_approval" {{ request('status') === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="py-3 px-6">প্রোডাক্ট</th>
                        <th class="py-3 px-6">ক্যাটাগরি</th>
                        <th class="py-3 px-6">মূল্য</th>
                        <th class="py-3 px-6">ভার্সন</th>
                        <th class="py-3 px-6">স্ট্যাটাস</th>
                        <th class="py-3 px-6 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-bold text-gray-900 block text-sm">{{ $product->name }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">SKU: {{ $product->sku }} • {{ $product->variants->count() }} Variations</span>
                            </td>
                            <td class="py-4 px-6">
                                @foreach($product->categories as $c)
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[10px] font-semibold mr-1">
                                        {{ $c->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="py-4 px-6 font-extrabold text-gray-900">
                                {{ format_price($product->effective_price) }}
                                @if($product->sale_price && $product->sale_price < $product->regular_price)
                                    <span class="text-[10px] text-pink-600 block line-through">{{ format_price($product->regular_price) }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-mono text-gray-600">
                                v{{ $product->version }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $product->status === 'published' ? 'bg-emerald-100 text-emerald-800' : ($product->status === 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700') }}">
                                    {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="p-1.5 rounded-lg bg-gray-100 hover:bg-purple-100 text-purple-700 inline-block" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                @if(auth()->user()->hasPermission('product.delete'))
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('আপনি কি নিশ্চিত এই প্রোডাক্টটি মুছে ফেলতে চান?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-gray-100 hover:bg-red-100 text-red-600" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">কোনো প্রোডাক্ট পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
