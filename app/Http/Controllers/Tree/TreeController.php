<?php

namespace App\Http\Controllers\Tree;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tree\StoreTreeRequest;
use App\Models\Tree;
use App\Services\AuditService;
use Illuminate\Http\Request;

class TreeController extends Controller
{
    public function __construct(private readonly AuditService $auditService) {}

    public function index()
    {
        $trees = auth()->user()->trees()->latest()->paginate(12);
        return view('tree.index', compact('trees'));
    }

    public function store(StoreTreeRequest $request)
    {
        $tree = auth()->user()->trees()->create($request->validated());
        $this->auditService->log('tree.created', Tree::class, $tree->id);
        return redirect()->route('trees.show', $tree)->with('status', 'Дерево создано.');
    }

    public function show(Tree $tree)
    {
        $this->authorize('view', $tree);

        $tree->load(['people', 'relationships']);

        return view('tree.show', [
            'tree' => $tree,
            'people' => $tree->people,
            'relationships' => $tree->relationships,
        ]);
    }

    public function update(StoreTreeRequest $request, Tree $tree)
    {
        $this->authorize('update', $tree);
        $tree->update($request->validated());
        $this->auditService->log('tree.updated', Tree::class, $tree->id);

        return back()->with('status', 'Дерево обновлено.');
    }

    public function archive(Tree $tree)
    {
        $this->authorize('update', $tree);
        $tree->update(['is_archived' => true, 'archived_at' => now()]);
        $this->auditService->log('tree.archived', Tree::class, $tree->id);

        return back()->with('status', 'Дерево архивировано.');
    }

    public function destroy(Tree $tree)
    {
        $this->authorize('delete', $tree);
        $treeId = $tree->id;
        $tree->delete();
        $this->auditService->log('tree.deleted', Tree::class, $treeId);

        return redirect()->route('trees.index')->with('status', 'Дерево удалено.');
    }

    public function saveViewport(Request $request, Tree $tree)
    {
        $this->authorize('update', $tree);
        $data = $request->validate(['x' => 'required|numeric', 'y' => 'required|numeric', 'scale' => 'required|numeric|min:0.1|max:3']);
        $tree->update(['viewport' => $data]);
        return response()->json(['ok' => true]);
    }
}
