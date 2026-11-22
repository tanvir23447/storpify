<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\HeroSlide;
use App\Models\PromotionalBanner;
use App\Models\TrustFeature;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\HomepageSection;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function home()
    {
        $heroSlides = HeroSlide::active()->get();
        $categories = ProductCategory::active()->withCount('products')->orderBy('sort_order')->get();
        $featuredProducts = Product::published()->featured()->with(['categories', 'variants'])->take(8)->get();
        $banners = PromotionalBanner::active()->get();
        $trustFeatures = TrustFeature::active()->get();
        $testimonials = Testimonial::active()->take(6)->get();
        $faqs = Faq::active()->take(6)->get();
        $sections = HomepageSection::where('is_active', true)->orderBy('sort_order')->get()->keyBy('section_type');

        return view('store.home', compact(
            'heroSlides',
            'categories',
            'featuredProducts',
            'banners',
            'trustFeatures',
            'testimonials',
            'faqs',
            'sections'
        ));
    }

    public function shop(Request $request)
    {
        $query = Product::published()->with(['categories', 'variants']);

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('name_bn', 'like', "%{$q}%")
                  ->orWhere('short_description', 'like', "%{$q}%")
                  ->orWhere('short_description_bn', 'like', "%{$q}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $catSlug = $request->category;
            $query->whereHas('categories', function ($c) use ($catSlug) {
                $c->where('slug', $catSlug);
            });
        }

        // Discounted filter
        if ($request->get('filter') === 'discounted') {
            $query->whereNotNull('sale_price')->whereRaw('sale_price < regular_price');
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('regular_price', 'desc');
                break;
            case 'popular':
                $query->orderByDesc('sales_count');
                break;
            case 'rating':
                $query->orderByDesc('avg_rating');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = ProductCategory::active()->withCount('products')->orderBy('sort_order')->get();

        return view('store.shop', compact('products', 'categories'));
    }

    public function product(string $slug)
    {
        $product = Product::published()
            ->where('slug', $slug)
            ->with(['categories', 'variants', 'files', 'images', 'reviews.user'])
            ->firstOrFail();

        $product->increment('view_count');

        $relatedProducts = Product::published()
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function ($q) use ($product) {
                $q->whereIn('product_categories.id', $product->categories->pluck('id'));
            })
            ->take(4)
            ->get();

        return view('store.product', compact('product', 'relatedProducts'));
    }

    public function faq()
    {
        $faqs = Faq::active()->get()->groupBy('category');
        return view('store.faq', compact('faqs'));
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            ['is_active' => true, 'subscribed_at' => now()]
        );

        return back()->with('success', app()->getLocale() === 'bn' 
            ? 'ধন্যবাদ! আপনি সফলভাবে সাবস্ক্রাইব করেছেন।' 
            : 'Thank you! You have subscribed successfully.');
    }
}
