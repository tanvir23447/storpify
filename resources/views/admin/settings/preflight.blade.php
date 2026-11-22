@extends('layouts.admin', ['title' => 'STORPIFY - হোস্টিং ও সুপাবেস গেট প্রিফ্লাইট'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Hostinger + Supabase প্রিফ্লাইট অডিট</h1>
            <p class="text-xs text-gray-500 mt-1">হোস্টিং পরিবেশ, পিএইচপি এক্সটেনশন, আউটবাউন্ড নেটওয়ার্ক ও সুপাবেস সংযোগের রিয়েল-টাইম ডায়াগনস্টিক।</p>
        </div>
        <a href="{{ route('admin.settings.preflight') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            পুনরায় টেস্ট চালান
        </a>
    </div>

    <!-- Overall Status Banner -->
    <div class="p-6 rounded-2xl border {{ $results['compatible'] ? 'bg-emerald-50/80 border-emerald-200' : 'bg-amber-50/80 border-amber-200' }} flex items-start gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $results['compatible'] ? 'bg-emerald-600 text-white' : 'bg-amber-600 text-white' }}">
            <i data-lucide="{{ $results['compatible'] ? 'check-circle' : 'alert-triangle' }}" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="font-black text-base {{ $results['compatible'] ? 'text-emerald-950' : 'text-amber-950' }}">
                {{ $results['compatible'] ? 'সিস্টেম হোস্টইঞ্জার ও সুপাবেস প্রোডাকশন রেডি!' : 'কিছু নির্ভরতা বা কনফিগারেশন যাচাই প্রয়োজন' }}
            </h3>
            <p class="text-xs {{ $results['compatible'] ? 'text-emerald-800' : 'text-amber-800' }} mt-1">
                {{ $results['compatible'] ? 'সকল প্রয়োজনীয় PHP এক্সটেনশন (pdo_pgsql, curl, openssl), মেমোরি লিমিট এবং আউটবাউন্ড নেটওয়ার্ক সফলভাবে সক্রিয় রয়েছে।' : 'নিচের চেকলিস্ট থেকে অমীমাংসিত আইটেমগুলো সমাধান করুন।' }}
            </p>
        </div>
    </div>

    <!-- Diagnostic Checks Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($results['checks'] as $category => $data)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-black text-gray-900 text-sm uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="check-square" class="w-4 h-4 text-purple-600"></i>
                        {{ strtoupper(str_replace('_', ' ', $category)) }}
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ ($data['status'] ?? '') === 'pass' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $data['status'] ?? 'Checked' }}
                    </span>
                </div>

                <div class="space-y-2 text-xs">
                    @foreach($data['details'] as $key => $val)
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-none">
                            <span class="text-gray-600 font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                            <span class="font-mono font-bold {{ is_bool($val) ? ($val ? 'text-emerald-600' : 'text-rose-600') : 'text-gray-900' }}">
                                @if(is_bool($val))
                                    {{ $val ? 'Enabled / OK' : 'Missing / Disabled' }}
                                @elseif(is_array($val))
                                    {{ json_encode($val) }}
                                @else
                                    {{ $val }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Production Architecture Guide Box -->
    <div class="bg-purple-900 text-white rounded-2xl p-6 shadow-xl space-y-3">
        <div class="flex items-center gap-2 text-yellow-300">
            <i data-lucide="info" class="w-5 h-5"></i>
            <h4 class="font-bold text-sm">হোস্টইঞ্জার ওয়েব হোস্টিং ও সুপাবেস কানেকশন গাইড</h4>
        </div>
        <p class="text-xs text-purple-200 leading-relaxed">
            Hostinger Web Hosting শেয়ার্ড পরিবেশে পরিচালিত হয় এবং সরাসরি লোকাল PostgreSQL সরবরাহ করে না। তাই STORPIFY বাহ্যিক Supabase Session Pooler (<code class="bg-purple-950 px-1.5 py-0.5 rounded text-white font-mono">aws-0-ap-southeast-1.pooler.supabase.com:5432</code>) এবং SSL Require মোড ব্যবহার করার জন্য আর্কিটেকচারালি কনফিগার করা হয়েছে। ফাইল ডেলিভারির ক্ষেত্রে Supabase Storage Signed URLs স্বয়ংক্রিয়ভাবে ব্যবহার হয়।
        </p>
    </div>
</div>
@endsection
