@extends('layouts.admin', ['title' => 'STORPIFY - অডিট ও সিকিউরিটি লগ'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">অডিট ও সিকিউরিটি ইভেন্ট লগ</h1>
            <p class="text-xs text-gray-500 mt-1">প্ল্যাটফর্মের সকল প্রশাসনিক কর্মকাণ্ড, পারমিশন পরিবর্তন ও সিকিউরিটি অ্যালার্টের অপরিবর্তনযোগ্য (Immutable) রেকর্ড।</p>
        </div>
    </div>

    <!-- Security Events Card -->
    @if($securityEvents->count() > 0)
        <div class="bg-rose-50/60 rounded-2xl border border-rose-200 p-5 space-y-3">
            <div class="flex items-center gap-2 text-rose-800">
                <i data-lucide="shield-alert" class="w-5 h-5 text-rose-600"></i>
                <h3 class="font-bold text-sm">সাম্প্রতিক সিকিউরিটি ইভেন্ট ও অ্যালার্ট</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($securityEvents as $event)
                    <div class="bg-white p-3 rounded-xl border border-rose-100 text-xs">
                        <div class="flex items-center justify-between font-mono text-[10px] text-gray-500 mb-1">
                            <span class="px-2 py-0.5 rounded font-bold uppercase {{ $event->severity === 'danger' || $event->severity === 'warning' ? 'bg-rose-100 text-rose-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $event->event_type }}
                            </span>
                            <span>{{ $event->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-800 font-medium">IP: <span class="font-mono text-purple-700">{{ $event->ip_address }}</span></p>
                        @if(!empty($event->details))
                            <pre class="mt-1 text-[10px] bg-gray-50 p-1.5 rounded overflow-x-auto text-gray-600 font-mono">{{ json_encode($event->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Audit Logs Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-black text-gray-900 text-sm">প্রশাসনিক অডিট ট্রেইল (Audit Trail)</h3>
            <span class="text-xs text-gray-400">সর্বমোট {{ $logs->total() }} টি রেকর্ড</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">স্টাফ মেম্বার</th>
                        <th class="px-5 py-4">অ্যাকশন</th>
                        <th class="px-5 py-4">মডিউল / টেবিল</th>
                        <th class="px-5 py-4">রেকর্ড আইডি</th>
                        <th class="px-5 py-4">তারিখ ও সময়</th>
                        <th class="px-5 py-4">পরিবর্তনের বিবরণ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($logs as $log)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4">
                                @if($log->staff)
                                    <span class="font-bold text-gray-900">{{ $log->staff->name }}</span>
                                    <span class="block text-[10px] text-gray-500 font-mono">{{ $log->staff->email }}</span>
                                @else
                                    <span class="text-gray-400 italic">সিস্টেম / স্বয়ংক্রিয়</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase {{ $log->action === 'delete' ? 'bg-rose-100 text-rose-700' : ($log->action === 'create' ? 'bg-emerald-100 text-emerald-700' : 'bg-purple-100 text-purple-700') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono text-purple-800 font-bold">
                                {{ $log->table_name }}
                            </td>
                            <td class="px-5 py-4 font-mono text-gray-600">
                                #{{ $log->record_id }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $log->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-5 py-4 max-w-xs truncate">
                                @if(!empty($log->new_values))
                                    <span class="text-xs text-gray-700 font-mono bg-gray-50 px-2 py-1 rounded border border-gray-200">
                                        {{ Str::limit(json_encode($log->new_values, JSON_UNESCAPED_UNICODE), 60) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">কোন অডিট লগ রেকর্ড পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
