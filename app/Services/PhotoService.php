<?php

namespace App\Services;

use App\Models\Person;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    public function storePersonPhoto(Person $person, UploadedFile $file): string
    {
        if ($person->photo_path) {
            Storage::disk('private')->delete($person->photo_path);
        }

        $safeName = sprintf('person_%d_%s.%s', $person->id, str()->random(16), $file->extension());
        return $file->storeAs('photos/tree_'.$person->tree_id, $safeName, 'private');
    }
}
