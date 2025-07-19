<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceListing;
use App\Models\Animal;
use App\Models\MarketplaceChat;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
        use AuthorizesRequests;
    /**
     * 1. عرض جميع الإعلانات العامة مع فلاتر
     */
    public function index(Request $request)
    {
        $query = MarketplaceListing::with(['user', 'animal.media'])
            ->where('status', 'active');

        if ($request->city) {
            $query->where('city', $request->city);
        }
        if ($request->animal_type) {
            $query->where('animal_type', $request->animal_type);
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        $listings = $query->paginate(12)->withQueryString();

        $cities       = ['Damascus','Aleppo','Homs','Latakia','Hama','Deir ez-Zor','Raqqa','Idlib','Daraa','As-Suwayda','Qamishli','Tartus','Al-Hasakah','Al-Quneitra'];
        $animalTypes  = ['cow','sheep','horse','camel','goat'];

        return view('marketplace.index', compact('listings','cities','animalTypes'));
    }

    /**
     * 2. عرض إعلانات المستخدم
     */
    public function myListings()
    {
        $listings = MarketplaceListing::with('animal.media')
            ->where('user_id', Auth::id())
            ->paginate(12);

        $cities       = ['Damascus','Aleppo','Homs','Latakia','Hama','Deir ez-Zor','Raqqa','Idlib','Daraa','As-Suwayda','Qamishli','Tartus','Al-Hasakah','Al-Quneitra'];
        $animalTypes  = ['cow','sheep','horse','camel','goat'];

        return view('marketplace.my-listings', compact('listings','cities','animalTypes'));
    }

    /**
     * 3. نموذج إنشاء إعلان
     */
    public function create(Request $request)
    {
        $selectedAnimal = null;
        if ($request->has('animal_id')) {
            $selectedAnimal = Animal::where('id', $request->animal_id)
                ->where('user_id', Auth::id())
                ->first();
        }

        $userAnimals  = Animal::where('user_id', Auth::id())->get();
        $cities       = ['Damascus','Aleppo','Homs','Latakia','Hama','Deir ez-Zor','Raqqa','Idlib','Daraa','As-Suwayda','Qamishli','Tartus','Al-Hasakah','Al-Quneitra'];
        $animalTypes  = ['cow','sheep','horse','camel','goat'];

        return view('marketplace.create', compact('selectedAnimal','userAnimals','cities','animalTypes'));
    }

    /**
     * 4. حفظ إعلان جديد
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:150',
            'animal_id'    => 'nullable|exists:animals,id',
            'category'     => 'required|in:animal,feed,equipment',
            'animal_type'  => 'nullable|string',
            'city'         => 'required|string',
            'price'        => 'required|numeric|min:0',
            'description'  => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        $data['status']  = 'active';

        MarketplaceListing::create($data);

        return redirect()->route('marketplace.my-listings')
                         ->with('success','Listing created successfully.');
    }

    /**
     * 5. عرض تفاصيل الإعلان مع الشات العام
     */
    public function show(MarketplaceListing $listing)
    {
        $listing->load([
            'user',
            'animal.media',
            'animal.families',
            'animal.diseases',
            'animal.medications',
            'animal.vaccines',
        ]);

        $chat = MarketplaceChat::firstOrCreate([
            'listing_id' => $listing->id,
            'type'       => 'public',
        ]);
        $chat->load('messages.user');

        return view('marketplace.show', compact('listing','chat'));
    }

    /**
     * 6. نموذج تعديل الإعلان
     */
    public function edit(MarketplaceListing $listing)
    {
        $this->authorize('update', $listing);

        $cities       = ['Damascus','Aleppo','Homs','Latakia','Hama','Deir ez-Zor','Raqqa','Idlib','Daraa','As-Suwayda','Qamishli','Tartus','Al-Hasakah','Al-Quneitra'];
        $animalTypes  = ['cow','sheep','horse','camel','goat'];

        return view('marketplace.edit', compact('listing','cities','animalTypes'));
    }

    /**
     * 7. تحديث الإعلان
     */
    public function update(Request $request, MarketplaceListing $listing)
    {
        $this->authorize('update', $listing);

        $data = $request->validate([
            'title'        => 'required|string|max:150',
            'category'     => 'required|in:animal,feed,equipment',
            'animal_type'  => 'nullable|string',
            'city'         => 'required|string',
            'price'        => 'required|numeric|min:0',
            'description'  => 'nullable|string',
        ]);

        $listing->update($data);

        return redirect()->route('marketplace.my-listings')
                         ->with('success','Listing updated successfully.');
    }

    /**
     * 8. حذف الإعلان
     */
    public function destroy(MarketplaceListing $listing)
    {
        $this->authorize('delete', $listing);

        $listing->delete();

        return redirect()->route('marketplace.my-listings')
                         ->with('success','Listing deleted successfully.');
    }
}
