<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class AnimalController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $animals = Animal::where('user_id', Auth::id())
            ->with('media')
            ->paginate(10); // ✅ استخدم paginate بدلاً من get

        return view('animals.index', compact('animals'));
    }


    public function create()
    {
        return view('animals.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'           => 'required|string',
            'gender'         => 'required|in:female,male',
            'birth_date'     => 'nullable|date',
            'notes'          => 'nullable|string',
            'health_status'  => 'nullable|in:healthy,sick,under_treatment',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data['user_id'] = Auth::id();

        $animal = Animal::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('animal_images', 'public');

                AnimalMedia::create([
                    'file_path'       => 'storage/' . $path,
                    'imageable_id'    => $animal->id,
                    'imageable_type'  => Animal::class,
                ]);
            }
        }

        return redirect()->route('animals.index')->with('success', 'تمت إضافة الماشية بنجاح');
    }

    public function show(Animal $animal)
    {
        $this->authorize('view', $animal);

        $animal->load([
            'media',
            'families.media',     // العائلة
            'diseases',           // الأمراض
            'medications',        // الأدوية
            'vaccines',           // اللقاحات
        ]);

        return view('animals.show', compact('animal'));
    }

    public function edit(Animal $animal)
    {
        $this->authorize('update', $animal);
        $animal->load('media');
        return view('animals.edit', compact('animal'));
    }

    public function update(Request $request, Animal $animal)
    {
        $this->authorize('update', $animal);

        $data = $request->validate([
            'type'        => 'sometimes|string',
            'gender'      => 'sometimes|in:female,male',
            'birth_date'  => 'nullable|date',
            'notes'       => 'nullable|string',
            'images.*'    => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $animal->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('animal_images', 'public');

                AnimalMedia::create([
                    'file_path'       => 'storage/' . $path,
                    'imageable_id'    => $animal->id,
                    'imageable_type'  => Animal::class,
                ]);
            }
        }

        return redirect()->route('animals.index')->with('success', 'تم تحديث الماشية بنجاح');
    }

    public function destroy(Animal $animal)
    {
        $this->authorize('delete', $animal);

        foreach ($animal->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        $animal->delete();

        return redirect()->route('animals.index')->with('success', 'تم حذف الماشية بنجاح');
    }
}
