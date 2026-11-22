@extends('layouts.admin', ['title' => 'STORPIFY - পেমেন্ট মেথড এডিট'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">পেমেন্ট মেথড ও নাম্বার সম্পাদনা</h1>
            <p class="text-xs text-gray-500 mt-1">{{ $method->name }} এর বিকাশ/নগদ নাম্বার এবং পেমেন্ট নির্দেশিকা পরিবর্তন করুন।</p>
        </div>
        <a href="{{ route('admin.payment-methods.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-100 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('admin.payment-methods.update', $method->id) }}" method="POST" class="space-y-6"
          x-data="{
              numbers: {{ json_encode(!empty($method->account_numbers) ? $method->account_numbers : [['number' => '', 'type' => 'personal', 'note' => '']]) }},
              addNumber() {
                  this.numbers.push({ number: '', type: 'personal', note: '' });
              },
              removeNumber(index) {
                  if (this.numbers.length > 1) {
                      this.numbers.splice(index, 1);
                  }
              }
          }">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">মেথডের নাম (English)</label>
                    <input type="text" name="name" value="{{ old('name', $method->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">মেথডের নাম (বাংলা)</label>
                    <input type="text" name="name_bn" value="{{ old('name_bn', $method->name_bn) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">ডিফল্ট অ্যাকাউন্ট ধরণ</label>
                    <select name="account_type" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="personal" {{ $method->account_type === 'personal' ? 'selected' : '' }}>Personal (Send Money)</option>
                        <option value="merchant" {{ $method->account_type === 'merchant' ? 'selected' : '' }}>Merchant (Payment)</option>
                        <option value="agent" {{ $method->account_type === 'agent' ? 'selected' : '' }}>Agent (Cash In)</option>
                        <option value="automated" {{ $method->account_type === 'automated' ? 'selected' : '' }}>Automated Gateway</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">প্রদর্শনের ক্রম (Sort Order)</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $method->sort_order) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
            </div>

            <!-- Dynamic Phone Numbers Repeater -->
            <div class="pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-sm font-black text-gray-900">অ্যাকাউন্ট ও মোবাইল নাম্বার সমূহ</h3>
                        <p class="text-xs text-gray-500">একাধিক নাম্বার যোগ করা থাকলে গ্রাহককে রোটেশন বা পছন্দের সুযোগ দেয়া যাবে।</p>
                    </div>
                    <button type="button" @click="addNumber()" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 font-bold hover:bg-purple-100 text-xs transition-colors">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        আরেকটি নাম্বার যোগ করুন
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in numbers" :key="index">
                        <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-200/60">
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">ফোন নাম্বার (যেমন: 01700000000)</label>
                                <input type="text" :name="'numbers[' + index + '][number]'" x-model="item.number" required
                                       class="w-full px-3 py-2 rounded-lg border border-gray-300 font-mono text-xs font-bold text-gray-900 focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white">
                            </div>
                            <div class="w-36">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">অ্যাকাউন্ট ধরণ</label>
                                <select :name="'numbers[' + index + '][type]'" x-model="item.type"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white">
                                    <option value="personal">Personal</option>
                                    <option value="merchant">Merchant</option>
                                    <option value="agent">Agent</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">নোট / বিবরণ (ঐচ্ছিক)</label>
                                <input type="text" :name="'numbers[' + index + '][note]'" x-model="item.note" placeholder="যেমন: শুধুমাত্র সেন্ড মানি"
                                       class="w-full px-3 py-2 rounded-lg border border-gray-300 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white">
                            </div>
                            <div class="pt-5">
                                <button type="button" @click="removeNumber(index)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Remove">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Customer Instructions -->
            <div class="pt-4 border-t border-gray-100 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">গ্রাহকের পেমেন্ট নির্দেশিকা (বাংলায় - চেকআউট পেজে প্রদর্শিত হবে)</label>
                    <textarea name="instructions_bn" rows="3"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none">{{ old('instructions_bn', $method->instructions_bn) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">গ্রাহকের পেমেন্ট নির্দেশিকা (English)</label>
                    <textarea name="instructions" rows="2"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none">{{ old('instructions', $method->instructions) }}</textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $method->is_active ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                    <label for="is_active" class="text-xs font-bold text-gray-800">এই পেমেন্ট মেথডটি চেকআউটে সক্রিয় রাখুন</label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.payment-methods.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                বাতিল করুন
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-black text-xs shadow-md transition-all">
                সংরক্ষণ করুন ও চেকআউটে প্রয়োগ করুন
            </button>
        </div>
    </form>
</div>
@endsection
