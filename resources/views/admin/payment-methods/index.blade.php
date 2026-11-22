@extends('layouts.admin', ['title' => 'STORPIFY - পেমেন্ট মেথড ও নাম্বার সেটিংস'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">পেমেন্ট মেথড ও অ্যাকাউন্ট নাম্বার</h1>
            <p class="text-xs text-gray-500 mt-1">bKash, Nagad, Rocket এর মার্চেন্ট ও পার্সোনাল নাম্বার পরিচালনা করুন। এখানে নাম্বার পরিবর্তন করলেই সরাসরি চেকআউটে আপডেট হবে।</p>
        </div>
    </div>

    <!-- Payment Methods Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($methods as $method)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-white text-base shadow-xs {{ $method->code === 'bkash' ? 'bg-[#D12053]' : ($method->code === 'nagad' ? 'bg-[#F7941D]' : ($method->code === 'rocket' ? 'bg-[#8C3494]' : 'bg-purple-600')) }}">
                                {{ substr(strtoupper($method->code), 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">{{ $method->name_bn ?: $method->name }}</h3>
                                <span class="text-xs text-gray-500 capitalize">{{ $method->account_type }} Account</span>
                            </div>
                        </div>

                        <div>
                            @if($method->is_active)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    সক্রিয়
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                                    নিষ্ক্রিয়
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Configured Numbers -->
                    <div class="bg-gray-50 rounded-xl p-3.5 space-y-2 border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">কনফিগার করা নাম্বার সমূহ:</p>
                        @if(!empty($method->account_numbers) && is_array($method->account_numbers))
                            @foreach($method->account_numbers as $acc)
                                <div class="flex items-center justify-between text-xs font-mono font-bold text-gray-800 bg-white px-3 py-1.5 rounded-lg border border-gray-200/60">
                                    <span>{{ $acc['number'] ?? '' }}</span>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 font-sans font-normal">{{ $acc['type'] ?? $method->account_type }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-xs text-gray-400 italic">কোন নাম্বার যুক্ত নেই</p>
                        @endif
                    </div>

                    <!-- Instructions preview -->
                    @if($method->instructions_bn || $method->instructions)
                        <div class="text-xs text-gray-600 bg-purple-50/50 p-3 rounded-xl border border-purple-100">
                            <p class="font-bold text-purple-900 mb-1">কাস্টমার নির্দেশিকা:</p>
                            <p class="line-clamp-2 text-gray-600">{{ $method->instructions_bn ?: $method->instructions }}</p>
                        </div>
                    @endif
                </div>

                <div class="pt-6 mt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[11px] text-gray-400">সিরিয়াল: {{ $method->sort_order }}</span>
                    <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold hover:bg-purple-700 transition-all shadow-xs">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        নাম্বার ও সেটিংস এডিট
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
