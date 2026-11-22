<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::orderBy('sort_order')->get();
        return view('admin.payment-methods.index', compact('methods'));
    }

    public function edit(int $id)
    {
        $method = PaymentMethod::findOrFail($id);
        return view('admin.payment-methods.edit', compact('method'));
    }

    public function update(Request $request, int $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $oldData = $method->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'account_type' => 'required|string',
            'numbers' => 'required|array',
            'numbers.*.number' => 'required|string',
            'instructions' => 'nullable|string',
            'instructions_bn' => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        $accountNumbers = [];
        foreach ($request->numbers as $row) {
            if (!empty($row['number'])) {
                $accountNumbers[] = [
                    'number' => trim($row['number']),
                    'type' => $row['type'] ?? $method->account_type,
                    'note' => $row['note'] ?? '',
                ];
            }
        }

        $method->update([
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'account_type' => $request->account_type,
            'account_numbers' => $accountNumbers,
            'instructions' => $request->instructions,
            'instructions_bn' => $request->instructions_bn,
            'sort_order' => (int)$request->sort_order,
            'is_active' => $request->has('is_active'),
        ]);

        AuditLog::record('update_payment_method', 'payment_methods', (string)$method->id, $oldData, $method->toArray());

        return redirect()->route('admin.payment-methods.index')->with('success', app()->getLocale() === 'bn'
            ? 'পেমেন্ট নম্বর ও নির্দেশাবলী সফলভাবে আপডেট হয়েছে এবং চেকআউট পেজে পরিবর্তন হয়ে গেছে।'
            : 'Payment numbers and instructions updated successfully.');
    }
}
