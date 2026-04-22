<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Relationship;
use Illuminate\Validation\ValidationException;

class RelationshipGuard
{
    public function validate(Person $person, Person $relative, string $type): void
    {
        if ($person->id === $relative->id) {
            throw ValidationException::withMessages(['relative_id' => 'Нельзя связать персону саму с собой.']);
        }

        if ($person->tree_id !== $relative->tree_id) {
            throw ValidationException::withMessages(['relative_id' => 'Связь возможна только внутри одного дерева.']);
        }

        $exists = Relationship::where('tree_id', $person->tree_id)
            ->where('person_id', $person->id)
            ->where('relative_id', $relative->id)
            ->where('type', $type)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages(['type' => 'Такая связь уже существует.']);
        }

        if (in_array($type, ['father', 'mother', 'child'], true) && $this->wouldCreateAncestorCycle($person, $relative, $type)) {
            throw ValidationException::withMessages(['type' => 'Связь приводит к циклу предок/потомок.']);
        }
    }

    private function wouldCreateAncestorCycle(Person $person, Person $relative, string $type): bool
    {
        $parentId = $type === 'child' ? $person->id : $relative->id;
        $childId = $type === 'child' ? $relative->id : $person->id;

        $graph = Relationship::where('tree_id', $person->tree_id)
            ->whereIn('type', ['father', 'mother', 'child'])
            ->get(['person_id', 'relative_id', 'type']);

        $adjacency = [];
        foreach ($graph as $edge) {
            if (in_array($edge->type, ['father', 'mother'], true)) {
                $adjacency[$edge->relative_id][] = $edge->person_id;
            } else {
                $adjacency[$edge->person_id][] = $edge->relative_id;
            }
        }

        $stack = [$childId];
        $visited = [];
        while ($stack) {
            $current = array_pop($stack);
            if ($current === $parentId) {
                return true;
            }
            if (isset($visited[$current])) {
                continue;
            }
            $visited[$current] = true;
            foreach ($adjacency[$current] ?? [] as $next) {
                $stack[] = $next;
            }
        }

        return false;
    }
}
