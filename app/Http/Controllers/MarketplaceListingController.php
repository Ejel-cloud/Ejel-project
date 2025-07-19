<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceListing;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MarketplaceListingController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = MarketplaceListing::query()->where('status', 'active');

        // فلتر حسب النوع (أنواع المواشي أو الأعلاف أو غيرها)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فلتر حسب المدينة
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // فلتر حسب السعر الأدنى
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // فلتر حسب السعر الأعلى
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // تنفيذ الاستعلام مع التصفية
        $listings = $query->paginate(15);

        return view('marketplace.listings.index', compact('listings'));
    }


    public function create()
    {
        return view('marketplace.listings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'nullable|exists:animals,id',
            'type' => 'required|string|in:cattle,sheep,horse,camel,goat,feed',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $listing = new MarketplaceListing($validated);
        $listing->user_id = auth()->Auth::id()();
        $listing->status = 'active';
        $listing->save();

        return redirect()->route('marketplace.listings.index')
            ->with('success', 'Listing created successfully.');
    }

    public function edit(MarketplaceListing $listing)
    {
        $this->authorize('update', $listing);  // <-- هنا تفويض صلاحية التعديل
        return view('marketplace.listings.edit', compact('listing'));
    }

    public function update(Request $request, MarketplaceListing $listing)
    {
        $this->authorize('update', $listing);  // <-- تفويض صلاحية التحديث

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $listing->update($validated);

        return redirect()->route('marketplace.listings.index')
            ->with('success', 'Listing updated successfully.');
    }

    public function destroy(MarketplaceListing $listing)
    {
        $this->authorize('delete', $listing); // <-- تفويض صلاحية الحذف
        $listing->delete();

        return redirect()->route('marketplace.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }

    public function show(MarketplaceListing $listing)
    {
        $this->authorize('view', $listing); // <-- تفويض صلاحية العرض
        return view('marketplace.listings.show', compact('listing'));
    }
}
