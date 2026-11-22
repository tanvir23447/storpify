@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">আমার উইশলিস্ট</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">আপনার পছন্দের সেভ করে রাখা প্রোডাক্টসমূহ</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-purple-600 hover:underline">
                ← ড্যাশবোর্ডে ফিরুন
            </a>
        </div>

        @if($wishlists->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-xs max-w-md mx-auto space-y-3">
                <div class="w-16 h-16 rounded-full bg-pink-50 text-pink-600 mx-auto flex items-center justify-center">
                    <i data-lucide="heart" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">আপনার উইশলিস্ট খালি</h3>
                <p class="text-xs text-gray-500">শপে প্রোডাক্ট ব্রাউজ করার সময় হার্ট আইকনে ক্লিক করে পছন্দের তালিকায় যুক্ত করুন।</p>
                <a href="{{ route('shop') }}" class="inline-block px-5 py-2.5 bg-purple-600 text-white font-bold text-xs rounded-xl shadow-md">
                    প্রোডাক্ট দেখুন →
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($wishlists as $w)
                    @php $p = $w->product; @endphp
                    @if($p)
                        <div class="bg-white rounded-3xl border border-gray-100 p-5 shadow-xs flex flex-col justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 mb-2">
                                    <a href="{{ route('product.show', $p->slug) }}" class="hover:text-purple-600">
                                        {{ $p->name }}
                                    </a>
                                </h4>
                                <div class="text-base font-extrabold text-purple-700">
                                    {{ format_price($p->effective_price) }}
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                <a href="{{ route('product.show', $p->slug) }}" class="text-xs font-bold text-purple-600 hover:underline">
                                    বিস্তারিত দেখুন
                                </a>
                                <form action="{{ route('customer.wishlist.toggle', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold p-1">
                                        মুছুন
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
