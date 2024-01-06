<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\FileSystem;

class Project extends Model
{

    protected $fillable = [
        'team_id',
        'title',
        'description',
        'start_date',
        'end_date',
    ];


    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Team::class);
    }


    public static function getDisk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk('projects');
    }


}
