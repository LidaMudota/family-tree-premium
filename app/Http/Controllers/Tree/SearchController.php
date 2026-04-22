<?php

namespace App\Http\Controllers\Tree;

use App\Http\Controllers\Controller;
use App\Models\Tree;

class SearchController extends Controller
{
    public function __invoke(Tree $tree)
    {
        $this->authorize('view', $tree);
        $q = trim((string) request('q'));

        $people = $tree->people()
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($inner) use ($q): void {
                    $inner->where('first_name', 'like', "%{$q}%")
                        ->orWhere('last_name', 'like', "%{$q}%")
                        ->orWhere('birth_last_name', 'like', "%{$q}%")
                        ->orWhere('summary_note', 'like', "%{$q}%")
                        ->orWhere('full_note', 'like', "%{$q}%");
                });
            })
            ->limit(20)
            ->get(['id', 'first_name', 'last_name', 'middle_name']);

        return response()->json($people);
    }
}
