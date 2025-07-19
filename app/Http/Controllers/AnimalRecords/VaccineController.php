<?php

namespace App\Http\Controllers\AnimalRecords;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Vaccine;
use App\Models\AnimalMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class VaccineController extends Controller
{
    public function index()
    {
        $vaccines = Vaccine::with(['animal', 'media'])
            ->whereHas('animal', fn($query) => $query->forCurrentUser())
            ->orderBy('vaccination_date', 'desc')
            ->paginate(10);

        return view('animal_records.vaccines.index', [
            'vaccines' => $vaccines,
            'pageTitle' => 'Vaccination Records'
        ]);
    }

    public function create()
    {
        $animals = Animal::forCurrentUser()
            ->whereDoesntHave('vaccines', function($query) {
                $query->where('vaccine_type', '=', 'Basic Vaccine');
            })
            ->get();

        $defaultVaccines = [
            'Basic Vaccine',
            'Foot-and-Mouth Disease',
            'Sheep Pox',
            'Pneumonia Vaccine'
        ];

        return view('animal_records.vaccines.create', compact('animals', 'defaultVaccines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'name' => 'required|string|max:255', // ✅ Added
            'vaccine_type' => 'required|string|max:100',
            'vaccination_date' => 'required|date',
            'next_vaccination_date' => 'nullable|date|after:vaccination_date',
            'notes' => 'nullable|string|max:500',
            'veterinarian' => 'nullable|string|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $animal = Animal::where('id', $validated['animal_id'])
            ->forCurrentUser()
            ->firstOrFail();

        $vaccine = Vaccine::create([
            'animal_id' => $animal->id,
            'name' => $validated['name'], // ✅ Fixed
            'vaccine_type' => $validated['vaccine_type'],
            'vaccination_date' => $validated['vaccination_date'],
            'next_vaccination_date' => $validated['next_vaccination_date'],
            'notes' => $validated['notes'] ?? null,
            'veterinarian' => $validated['veterinarian'] ?? null,
        ]);

        $this->handleVaccineImages($request->file('images'), $vaccine);

        return redirect()
            ->route('animal-records.vaccines.index')
            ->with('success', 'Vaccine record added successfully');
    }

    public function show(Vaccine $vaccine)
    {
        Gate::authorize('view', $vaccine->animal);
        $vaccine->load(['animal', 'media']);
        return view('animal_records.vaccines.show', compact('vaccine'));
    }

    public function edit(Vaccine $vaccine)
    {
        Gate::authorize('update', $vaccine->animal);
        $animals = Animal::forCurrentUser()->get();
        $vaccine->load('media');

        $defaultVaccines = [
            'Basic Vaccine',
            'Foot-and-Mouth Disease',
            'Sheep Pox',
            'Pneumonia Vaccine'
        ];

        return view('animal_records.vaccines.edit', compact('vaccine', 'animals', 'defaultVaccines'));
    }

    public function update(Request $request, Vaccine $vaccine)
    {
        Gate::authorize('update', $vaccine->animal);

        $validated = $request->validate([
            'name' => 'required|string|max:255', // ✅ Added
            'vaccine_type' => 'required|string|max:100',
            'vaccination_date' => 'required|date',
            'next_vaccination_date' => 'nullable|date|after:vaccination_date',
            'notes' => 'nullable|string|max:500',
            'veterinarian' => 'nullable|string|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delete_images' => 'nullable|array',
        ]);

        $vaccine->update($validated); // ✅ Now includes name

        if (!empty($validated['delete_images'])) {
            $this->deleteVaccineImages($validated['delete_images']);
        }

        if ($request->hasFile('images')) {
            $this->handleVaccineImages($request->file('images'), $vaccine);
        }

        return redirect()
            ->route('animal-records.vaccines.index')
            ->with('success', 'Vaccine record updated successfully');
    }

    public function destroy(Vaccine $vaccine)
    {
        Gate::authorize('delete', $vaccine->animal);
        $this->deleteAllVaccineImages($vaccine);
        $vaccine->delete();

        return redirect()
            ->route('animal-records.vaccines.index')
            ->with('success', 'Vaccine record deleted successfully');
    }

    private function handleVaccineImages($images, $vaccine)
    {
        if (!$images) return;

        $currentImagesCount = $vaccine->media()->count();
        $remainingSlots = 3 - $currentImagesCount;

        if ($remainingSlots > 0) {
            $uploaded = 0;
            foreach ($images as $image) {
                if ($uploaded >= $remainingSlots) break;

                $path = $image->store('animal_media/vaccines', 'public');
                AnimalMedia::create([
                    'imageable_id' => $vaccine->id,
                    'imageable_type' => Vaccine::class,
                    'file_path' => $path,
                ]);
                $uploaded++;
            }
        }
    }

    private function deleteVaccineImages($imageIds)
    {
        $images = AnimalMedia::whereIn('id', $imageIds)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->file_path);
            $image->delete();
        }
    }

    private function deleteAllVaccineImages($vaccine)
    {
        foreach ($vaccine->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }
    }
}
