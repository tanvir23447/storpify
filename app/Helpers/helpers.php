<?php

if (!function_exists('format_price')) {
    function format_price($amount, string $currency = 'BDT'): string
    {
        $numeric = (float)$amount;
        $formatted = number_format($numeric, 0);

        if (app()->getLocale() === 'bn') {
            $formatted = bangla_number($formatted);
            return '৳ ' . $formatted;
        }

        return '৳ ' . $formatted;
    }
}

if (!function_exists('bangla_number')) {
    function bangla_number($number): string
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($en, $bn, (string)$number);
    }
}

if (!function_exists('current_currency_symbol')) {
    function current_currency_symbol(): string
    {
        return config('storpify.currency.symbol', '৳');
    }
}

if (!function_exists('cart_count')) {
    function cart_count(): int
    {
        $sessionId = session()->getId();
        $userId = auth()->id();

        $cart = \App\Models\Cart::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId)->orWhere('session_id', $sessionId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->with('items')->first();

        return $cart ? $cart->total_items : 0;
    }
}

if (!function_exists('has_active_subscription')) {
    function has_active_subscription(?int $userId = null): bool
    {
        $uid = $userId ?: auth()->id();
        if (!$uid) {
            return false;
        }

        return \App\Models\UserSubscription::where('user_id', $uid)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();
    }
}
