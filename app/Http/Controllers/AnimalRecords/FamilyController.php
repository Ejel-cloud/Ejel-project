<?php

namespace App\Http\Controllers\AnimalRecords;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Family;
use App\Models\AnimalMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller
{
    /**
     * عرض قائمة سجلات العائلة
     */
    public function index()
    {
        $families = Family::with(['animal', 'media'])
            ->whereHas('animal', function (Builder $query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return view('animal_records.families.index', compact('families'));
    }

    /**
     * عرض نموذج إنشاء سجل عائلة جديد
     */
    public function create()
    {
        $animals = Animal::forCurrentUser()
            ->with('families')
            ->get();

        return view('animal_records.families.create', compact('animals'));
    }

    /**
     * حفظ سجل العائلة الجديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
           'relation_type' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $animal = Animal::where('id', $validated['animal_id'])
            ->forCurrentUser()
            ->firstOrFail();

        $family = Family::create([
            'animal_id' => $animal->id,
            'relation_type' => $validated['relation_type'],
            'description' => $validated['description']?? null,
        ]);

        if ($request->hasFile('images')) {
            $uploadedImages = 0;

            foreach ($request->file('images') as $image) {
                if ($uploadedImages >= 3) break;

                $path = $image->store('animal_media/families', 'public');

                AnimalMedia::create([
                    'imageable_id' => $family->id,
                    'imageable_type' => Family::class,
                    'file_path' => $path,
                ]);

                $uploadedImages++;
            }
        }

        return redirect()
            ->route('animal-records.families.index')
            ->with('success', 'تم إضافة سجل العائلة بنجاح');
    }

    /**
     * عرض تفاصيل سجل العائلة
     */
    public function show(Family $family)
    {
        Gate::authorize('view', $family->animal);

        $family->load(['animal', 'media']);

        return view('animal_records.families.show', compact('family'));
    }

    /**
     * عرض نموذج تعديل سجل العائلة
     */
    public function edit(Family $family)
    {
        Gate::authorize('update', $family->animal);

        $animals = Animal::forCurrentUser()->get();
        $family->load('media');

        return view('animal_records.families.edit', compact('family', 'animals'));
    }

    /**
     * تحديث سجل العائلة
     */
    public function update(Request $request, Family $family)
    {
        Gate::authorize('update', $family->animal);

        $validated = $request->validate([
            'relation_type' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delete_images' => 'nullable|array',
        ]);

        $family->update([
            'relation_type' => $validated['relation_type']?? null,
            'description' => $validated['description'],
        ]);

        if (!empty($validated['delete_images'])) {
            $imagesToDelete = AnimalMedia::where('imageable_type', Family::class)
                ->where('imageable_id', $family->id)
                ->whereIn('id', $validated['delete_images'])
                ->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->file_path);
                $image->delete();
            }
        }

        if ($request->hasFile('images')) {
            $currentImagesCount = $family->media()->count();
            $remainingSlots = 3 - $currentImagesCount;

            if ($remainingSlots > 0) {
                $uploadedImages = 0;

                foreach ($request->file('images') as $image) {
                    if ($uploadedImages >= $remainingSlots) break;

                    $path = $image->store('animal_media/families', 'public');

                    AnimalMedia::create([
                        'imageable_id' => $family->id,
                        'imageable_type' => Family::class,
                        'file_path' => $path,
                    ]);

                    $uploadedImages++;
                }
            }
        }

        return redirect()
            ->route('animal-records.families.index')
            ->with('success', 'تم تحديث سجل العائلة بنجاح');
    }

    /**
     * حذف سجل العائلة
     */
    public function destroy(Family $family)
    {
        Gate::authorize('delete', $family->animal);

        foreach ($family->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        $family->delete();

        return redirect()
            ->route('animal-records.families.index')
            ->with('success', 'تم حذف سجل العائلة بنجاح');
    }
}
