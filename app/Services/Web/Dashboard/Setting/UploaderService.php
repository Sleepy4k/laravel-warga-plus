<?php

namespace App\Services\Web\Dashboard\Setting;

use App\Enums\FileUploaderType;
use App\Enums\ReportLogType;
use App\Facades\FileUploader;
use App\Facades\Format;
use App\Foundations\Service;
use App\Traits\SystemLog;

class UploaderService extends Service
{
    use SystemLog;

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $fileUploaderTypes = FileUploaderType::cases();
        $fileUploaderConfigs = [];

        foreach ($fileUploaderTypes as $type) {
            $fileUploader = FileUploader::init($type);
            $fileUploaderConfigs[$type->value] = [
                'type' => $fileUploader->get('type', 'image'),
                'mimes' => $fileUploader->get('mimes', 'jpeg,png,jpg'),
                'max_size' => $fileUploader->get('max_size', 8192),
            ];
        }

        $fileTypes = Format::getFileUploadTypes();
        $imageExtensions = Format::getImageExtensions();
        $mimesOptions = Format::getFileExtensions();
        $serverThreshold = Format::getServerMaxUploadSize();
        $maxSizeOptions = Format::uploadSizeOptions($serverThreshold);

        return compact('fileUploaderConfigs', 'maxSizeOptions', 'mimesOptions', 'serverThreshold', 'fileTypes', 'imageExtensions');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            $fileUploaderTypes = FileUploaderType::cases();

            foreach ($fileUploaderTypes as $type) {
                $fileTypeKey = $type->value . '-type';
                $mimesKey = $type->value . '-mimes';
                $maxSizeKey = $type->value . '-max_size';

                if (isset($request[$mimesKey]) && isset($request[$maxSizeKey])) {
                    FileUploader::init($type)->store([
                        'type' => $request[$fileTypeKey],
                        'mimes' => implode(',', $request[$mimesKey]),
                        'max_size' => $request[$maxSizeKey],
                    ]);
                }
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to store uploader settings', [
                'error' => $th->getMessage(),
                'request' => $request,
            ]);
            return false;
        }
    }
}
