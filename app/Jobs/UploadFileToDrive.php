<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\File;
use App\Models\MonthlyCompliance;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class UploadFileToDrive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $drivePath;

    public function __construct($filePath, $drivePath)
    {
        $this->filePath = $filePath;
        $this->drivePath = $drivePath;
    }

    public function handle()
    {
        // Upload the file to Google Drive
        $localFilePath = storage_path('app/public/' . $this->filePath);
        if (file_exists($localFilePath)) {
            $file = new File($localFilePath);
            Gdrive::put($this->drivePath, $file);
        }
    }
}