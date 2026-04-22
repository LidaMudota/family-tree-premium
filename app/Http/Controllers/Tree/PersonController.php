<?php

namespace App\Http\Controllers\Tree;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tree\StorePersonRequest;
use App\Models\Person;
use App\Models\Tree;
use App\Services\AuditService;
use App\Services\PhotoService;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    public function __construct(private readonly PhotoService $photoService, private readonly AuditService $auditService) {}

    public function store(StorePersonRequest $request, Tree $tree)
    {
        $this->authorize('update', $tree);
        $person = $tree->people()->create($request->safe()->except('photo'));

        if ($request->hasFile('photo')) {
            $person->update(['photo_path' => $this->photoService->storePersonPhoto($person, $request->file('photo'))]);
        }

        $this->auditService->log('person.created', Person::class, $person->id);
        return back()->with('status', 'Персона добавлена.');
    }

    public function update(StorePersonRequest $request, Tree $tree, Person $person)
    {
        $this->authorize('update', $tree);
        abort_unless($person->tree_id === $tree->id, 404);

        $person->update($request->safe()->except('photo'));
        if ($request->hasFile('photo')) {
            $person->update(['photo_path' => $this->photoService->storePersonPhoto($person, $request->file('photo'))]);
        }

        $this->auditService->log('person.updated', Person::class, $person->id);
        return back()->with('status', 'Персона обновлена.');
    }

    public function destroy(Tree $tree, Person $person)
    {
        $this->authorize('update', $tree);
        abort_unless($person->tree_id === $tree->id, 404);

        if ($person->photo_path) {
            Storage::disk('private')->delete($person->photo_path);
        }

        $personId = $person->id;
        $person->delete();
        $this->auditService->log('person.deleted', Person::class, $personId);

        return back()->with('status', 'Персона удалена.');
    }

    public function photo(Tree $tree, Person $person)
    {
        $this->authorize('view', $tree);
        abort_unless($person->tree_id === $tree->id && $person->photo_path, 404);
        return Storage::disk('private')->response($person->photo_path);
    }

    public function deletePhoto(Tree $tree, Person $person)
    {
        $this->authorize('update', $tree);
        abort_unless($person->tree_id === $tree->id, 404);

        if ($person->photo_path) {
            Storage::disk('private')->delete($person->photo_path);
            $person->update(['photo_path' => null]);
        }

        $this->auditService->log('person.photo_deleted', Person::class, $person->id);
        return back()->with('status', 'Фото удалено.');
    }
}
