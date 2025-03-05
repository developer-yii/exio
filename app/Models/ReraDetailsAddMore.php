<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ReraDetailsAddMore extends Model
{
    use SoftDeletes;

    protected $fillable = ['project_id', 'title', 'document'];

    protected $table = 'rera_details_add_mores';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getReraDocumentUrl()
    {
        $document = $this->document;
        if ($document) {
            $filePath = "public/rera_documents/{$document}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/rera_documents/' . $document);  // Correct URL structure
            }
        }
        return asset('images/no_image_available.jpg');
    }
}
