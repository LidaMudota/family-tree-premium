<?php

namespace App\Http\Controllers\Tree;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tree\StoreRelationshipRequest;
use App\Models\Person;
use App\Models\Relationship;
use App\Models\Tree;
use App\Services\AuditService;
use App\Services\RelationshipGuard;

class RelationshipController extends Controller
{
    public function __construct(private readonly RelationshipGuard $relationshipGuard, private readonly AuditService $auditService) {}

    public function store(StoreRelationshipRequest $request, Tree $tree)
    {
        $this->authorize('update', $tree);

        $person = Person::findOrFail($request->integer('person_id'));
        $relative = Person::findOrFail($request->integer('relative_id'));
        abort_unless($person->tree_id === $tree->id && $relative->tree_id === $tree->id, 404);

        $type = (string) $request->input('type');
        $this->relationshipGuard->validate($person, $relative, $type);

        $relationship = Relationship::create([
            'tree_id' => $tree->id,
            'person_id' => $person->id,
            'relative_id' => $relative->id,
            'type' => $type,
        ]);

        $this->auditService->log('relationship.created', Relationship::class, $relationship->id);
        return back()->with('status', 'Связь добавлена.');
    }

    public function destroy(Tree $tree, Relationship $relationship)
    {
        $this->authorize('update', $tree);
        abort_unless($relationship->tree_id === $tree->id, 404);
        $id = $relationship->id;
        $relationship->delete();
        $this->auditService->log('relationship.deleted', Relationship::class, $id);
        return back()->with('status', 'Связь удалена.');
    }
}
