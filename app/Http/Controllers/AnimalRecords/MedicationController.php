<?php

namespace App\Http\Controllers\AnimalRecords;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Medication;
use App\Models\AnimalMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class MedicationController extends Controller
{
    public function index()
    {
        $medications = Medication::with(['animal', 'media'])
            ->whereHas('animal', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('administration_date', 'desc')
            ->paginate(10);

        return view('animal_records.medications.index', compact('medications'));
    }

    public function create()
    {
        $animals = Animal::forCurrentUser()
            ->where('health_status', '!=', 'صحي')
            ->get();

        return view('animal_records.medications.create', compact('animals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'medicine_name' => 'required|string|max:100',
            'dosage' => 'required|string|max:50',
            'administration_date' => 'required|date',
            'next_dose_date' => 'nullable|date|after:administration_date',
            'instructions' => 'nullable|string|max:500',
            'is_completed' => 'nullable|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $animal = Animal::where('id', $validated['animal_id'])
            ->forCurrentUser()
            ->firstOrFail();

        $medication = Medication::create([
            'animal_id' => $animal->id,
            'medicine_name' => $validated['medicine_name'],
            'dosage' => $validated['dosage'],
            'administration_date' => $validated['administration_date'],
            'next_dose_date' => $validated['next_dose_date'],
            'instructions' => $validated['instructions'],
            'is_completed' => $request->has('is_completed'),
        ]);

        if ($request->hasFile('images')) {
            $this->uploadMedicationImages($request->file('images'), $medication);
        }

        $animal->update([
            'health_status' => 'قيد العلاج'
        ]);

        return redirect()
            ->route('medications.index')
            ->with('success', 'تم إضافة سجل الدواء بنجاح');
    }

    public function show(Medication $medication)
    {
        Gate::authorize('view', $medication->animal);
        $medication->load(['animal', 'media']);

        return view('animal_records.medications.show', compact('medication'));
    }

    public function edit(Medication $medication)
    {
        Gate::authorize('update', $medication->animal);

        $animals = Animal::forCurrentUser()->get();
        $medication->load('media');

        return view('animal_records.medications.edit', compact('medication', 'animals'));
    }

    public function update(Request $request, Medication $medication)
    {
        Gate::authorize('update', $medication->animal);

        $validated = $request->validate([
            'medicine_name' => 'required|string|max:100',
            'dosage' => 'required|string|max:50',
            'administration_date' => 'required|date',
            'next_dose_date' => 'nullable|date|after:administration_date',
            'instructions' => 'nullable|string|max:500',
            'is_completed' => 'nullable|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delete_images' => 'nullable|array',
        ]);

        $medication->update([
            'medicine_name' => $validated['medicine_name'],
            'dosage' => $validated['dosage'],
            'administration_date' => $validated['administration_date'],
            'next_dose_date' => $validated['next_dose_date'],
            'instructions' => $validated['instructions'],
            'is_completed' => $request->has('is_completed'),
        ]);

        if (!empty($validated['delete_images'])) {
            $this->deleteMedicationImages($validated['delete_images']);
        }

        if ($request->hasFile('images')) {
            $this->uploadMedicationImages($request->file('images'), $medication);
        }

        return redirect()
            ->route('medications.index')
            ->with('success', 'تم تحديث سجل الدواء بنجاح');
    }

    public function destroy(Medication $medication)
    {
        Gate::authorize('delete', $medication->animal);
        $this->deleteAllMedicationImages($medication);
        $medication->delete();

        return redirect()
            ->route('medications.index')
            ->with('success', 'تم حذف سجل الدواء بنجاح');
    }

    private function uploadMedicationImages($images, $medication)
    {
        $currentImagesCount = $medication->media()->count();
        $remainingSlots = 3 - $currentImagesCount;

        if ($remainingSlots > 0) {
            $uploadedImages = 0;
            foreach ($images as $image) {
                if ($uploadedImages >= $remainingSlots) break;

                $path = $image->store('animal_media/medications', 'public');

                AnimalMedia::create([
                    'imageable_id' => $medication->id,
                    'imageable_type' => Medication::class,
                    'file_path' => $path,
                ]);

                $uploadedImages++;
            }
        }
    }

    private function deleteMedicationImages($imageIds)
    {
        $imagesToDelete = AnimalMedia::whereIn('id', $imageIds)->get();

        foreach ($imagesToDelete as $image) {
            Storage::disk('public')->delete($image->file_path);
            $image->delete();
        }
    }

    private function deleteAllMedicationImages($medication)
    {
        foreach ($medication->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }
    }
}
