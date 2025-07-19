<?php

namespace App\Http\Controllers\AnimalRecords;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Disease;
use App\Models\AnimalMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class DiseaseController extends Controller
{
    public function index()
    {
        $diseases = Disease::with(['animal', 'media'])
            ->whereHas('animal', fn($query) => $query->forCurrentUser())
            ->orderBy('diagnosed_at', 'desc')
            ->paginate(10);

        return view('animal_records.diseases.index', [
            'diseases' => $diseases,
            'pageTitle' => 'Disease Records',
        ]);
    }

    public function create()
    {
        $animals = Animal::forCurrentUser()
            ->where('health_status', '!=', 'healthy')
            ->get();

        return view('animal_records.diseases.create', compact('animals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'name' => 'required|string|max:255',
            'symptoms' => 'nullable|string',
            'treatment' => 'nullable|string',
            'diagnosed_at' => 'nullable|date',
            'recovery_date' => 'nullable|date|after_or_equal:diagnosed_at',
            'veterinarian' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // تأكد أن الحيوان يخص المستخدم الحالي
        $animal = Animal::where('id', $validated['animal_id'])
            ->forCurrentUser()
            ->firstOrFail();

        // إنشاء سجل المرض
        $disease = Disease::create([
            'animal_id' => $animal->id,
            'name' => $validated['name'],
            'symptoms' => $validated['symptoms'] ?? null,
            'treatment' => $validated['treatment'] ?? null,
            'diagnosed_at' => $validated['diagnosed_at'] ?? null,
            'recovery_date' => $validated['recovery_date'] ?? null,
            'veterinarian' => $validated['veterinarian'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_cured' => false,
        ]);

        // رفع الصور (حتى 3 صور)
        $this->handleDiseaseImages($request->file('images'), $disease);

        // تحديث حالة صحة الحيوان إذا لم تكن تحت العلاج
        if ($animal->health_status !== 'under_treatment') {
            $animal->update(['health_status' => 'under_treatment']);
        }

        return redirect()
            ->route('animal-records.diseases.index')
            ->with('success', 'Disease record added successfully');
    }

    public function show(Disease $disease)
    {
        Gate::authorize('view', $disease->animal);
        $disease->load(['animal', 'media']);
        return view('animal_records.diseases.show', compact('disease'));
    }

    public function edit(Disease $disease)
    {
        Gate::authorize('update', $disease->animal);
        $animals = Animal::forCurrentUser()->get();
        $disease->load('media');

        return view('animal_records.diseases.create', compact('disease', 'animals'));
    }

    public function update(Request $request, Disease $disease)
    {
        Gate::authorize('update', $disease->animal);

        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'name' => 'required|string|max:255',
            'symptoms' => 'nullable|string',
            'treatment' => 'nullable|string',
            'diagnosed_at' => 'nullable|date',
            'recovery_date' => 'nullable|date|after_or_equal:diagnosed_at',
            'veterinarian' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_cured' => 'sometimes|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delete_images' => 'nullable|array',
        ]);

        $disease->update([
            'animal_id' => $validated['animal_id'],
            'name' => $validated['name'],
            'symptoms' => $validated['symptoms'] ?? null,
            'treatment' => $validated['treatment'] ?? null,
            'diagnosed_at' => $validated['diagnosed_at'] ?? null,
            'recovery_date' => $validated['recovery_date'] ?? null,
            'veterinarian' => $validated['veterinarian'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_cured' => $validated['is_cured'] ?? false,
        ]);

        if (!empty($validated['delete_images'])) {
            $this->deleteDiseaseImages($validated['delete_images']);
        }

        if ($request->hasFile('images')) {
            $remainingSlots = 3 - $disease->media()->count();
            if ($remainingSlots > 0) {
                $this->handleDiseaseImages($request->file('images'), $disease);
            }
        }

        if ($disease->is_cured) {
            $disease->animal->update(['health_status' => 'healthy']);
        }

        return redirect()
            ->route('animal-records.diseases.index')
            ->with('success', 'Disease record updated successfully');
    }

    public function destroy(Disease $disease)
    {
        Gate::authorize('delete', $disease->animal);
        $this->deleteAllDiseaseImages($disease);
        $disease->delete();

        return redirect()
            ->route('animal-records.diseases.index')
            ->with('success', 'Disease record deleted successfully');
    }

    private function handleDiseaseImages($images, $disease)
    {
        if (empty($images)) return;

        $currentImagesCount = $disease->media()->count();
        $remainingSlots = 3 - $currentImagesCount;

        if ($remainingSlots > 0) {
            $uploaded = 0;
            foreach ($images as $image) {
                if ($uploaded >= $remainingSlots) break;

                $path = $image->store('animal_media/diseases', 'public');
                AnimalMedia::create([
                    'imageable_id' => $disease->id,
                    'imageable_type' => Disease::class,
                    'file_path' => $path,
                ]);
                $uploaded++;
            }
        }
    }

    private function deleteDiseaseImages($imageIds)
    {
        $images = AnimalMedia::whereIn('id', $imageIds)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->file_path);
            $image->delete();
        }
    }

    private function deleteAllDiseaseImages($disease)
    {
        foreach ($disease->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }
    }

    public function markAsCured(Disease $disease)
    {
        Gate::authorize('update', $disease->animal);

        $disease->update([
            'is_cured' => true,
            'recovery_date' => now(),
        ]);

        $disease->animal->update(['health_status' => 'healthy']);

        return back()->with('success', 'Disease marked as cured successfully');
    }
}
