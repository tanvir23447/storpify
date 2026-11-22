@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]" x-data="{ createModal: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">সাপোর্ট টিকিট</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">যেকোনো প্রশ্ন, ডাউনলোড সমস্যা বা পেমেন্ট সহায়তার জন্য টিকিট তৈরি করুন</p>
            </div>
            <button @click="createModal = true" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-transform hover:scale-105 flex items-center gap-2 self-start sm:self-auto">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>নতুন টিকিট খুলুন</span>
            </button>
        </div>

        <!-- Ticket List -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
            @if($tickets->isEmpty())
                <div class="p-12 text-center text-xs text-gray-500">আপনার কোনো সক্রিয় সাপোর্ট টিকিট নেই।</div>
            @else
                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($tickets as $t)
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold font-mono text-purple-700">#{{ $t->ticket_number }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $t->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 mt-1">{{ $t->subject }}</h4>
                                <span class="text-gray-400 text-[11px]">{{ $t->created_at->format('d M Y, h:i A') }} • ক্যাটাগরি: {{ ucfirst($t->category) }}</span>
                            </div>

                            <div>
                                <span class="text-xs text-gray-400 font-semibold">{{ $t->messages->count() }} টি মেসেজ</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>

        <!-- Create Ticket Modal -->
        <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div @click.away="createModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-bold text-gray-900">নতুন সাপোর্ট টিকিট খুলুন</h3>
                    <button @click="createModal = false" class="p-1 text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('customer.tickets.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">বিষয় (Subject) *</label>
                        <input type="text" name="subject" required placeholder="যেমন: ডাউনলোড লিংক কাজ করছে না"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">ক্যাটাগরি *</label>
                            <select name="category" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                                <option value="download_issue">ডাউনলোড সমস্যা</option>
                                <option value="payment_verification">পেমেন্ট সংক্রান্ত</option>
                                <option value="subscription_help">সাবস্ক্রিপশন সহায়তা</option>
                                <option value="general">সাধারণ জিজ্ঞাসা</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">জরুরিতা (Priority) *</label>
                            <select name="priority" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                                <option value="medium">সাধারণ (Medium)</option>
                                <option value="urgent">জরুরি (Urgent)</option>
                                <option value="low">কম জরুরি (Low)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">বিস্তারিত মেসেজ *</label>
                        <textarea name="message" rows="4" required placeholder="আপনার সমস্যার বিস্তারিত বর্ণনা লিখুন..."
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-md transition-colors">
                        টিকিট সাবমিট করুন
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
