<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Animal,
    MarketplaceListing,
    Disease,
    Vaccine,
    Medication,
    Family
};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalAnimals = Animal::where('user_id', $user->id)->count();
        $animalsForSale = Animal::where('user_id', $user->id)->where('for_sale', true)->count();
        $totalListings = MarketplaceListing::where('user_id', $user->id)->count();
        $recentAnimals = Animal::where('user_id', $user->id)->latest()->take(5)->get();

        // New stats
        $totalDiseases = Disease::whereHas('animal', fn($q) => $q->where('user_id', $user->id))->count();
        $totalVaccines = Vaccine::whereHas('animal', fn($q) => $q->where('user_id', $user->id))->count();
        $totalMedications = Medication::whereHas('animal', fn($q) => $q->where('user_id', $user->id))->count();
        $totalFamilies = Family::whereHas('animal', fn($q) => $q->where('user_id', $user->id))->count();

        return view('dashboard.index', compact(
            'totalAnimals',
            'animalsForSale',
            'totalListings',
            'recentAnimals',
            'totalDiseases',
            'totalVaccines',
            'totalMedications',
            'totalFamilies'
        ));
    }
}
