<?php

namespace App\Http\Controllers\Tree;

use App\Http\Controllers\Controller;
use App\Models\ExportJob;
use App\Models\Tree;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function __construct(private readonly AuditService $auditService) {}

    public function png(Request $request, Tree $tree)
    {
        $this->authorize('view', $tree);
        $payload = $request->validate(['image' => ['required', 'string']]);

        $raw = preg_replace('/^data:image\/png;base64,/', '', (string) $payload['image']);
        $binary = base64_decode((string) $raw, true);
        abort_unless($binary !== false, 422);

        $job = ExportJob::create(['tree_id' => $tree->id, 'user_id' => auth()->id(), 'format' => 'png', 'status' => 'done']);
        $path = 'exports/tree_'.$tree->id.'_'.now()->format('Ymd_His').'.png';
        Storage::disk('private')->put($path, $binary);
        $job->update(['file_path' => $path]);
        $this->auditService->log('export.png', Tree::class, $tree->id);

        return Storage::disk('private')->download($path, 'family_tree_'.$tree->id.'.png');
    }

    public function pdf(Tree $tree)
    {
        $this->authorize('view', $tree);
        $this->auditService->log('export.pdf_view', Tree::class, $tree->id);
        return view('tree.print', ['tree' => $tree->load('people', 'relationships')]);
    }
}
