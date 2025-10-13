<?php

namespace Modules\Storage;

use App\Enums\FileUploaderType;
use App\Enums\ReportLogType;
use App\Traits\SystemLog;

class FileUploaderManager
{
    use SystemLog;

    /**
     * Set path root for storing configuration on file uploader.
     *
     * @var string
     */
    private static string $path = 'uploader';

    /**
     * Set file path for storing files.
     *
     * @var string
     */
    protected string $filePath;

    /**
     * Initialize the file uploader manager with the specified type.
     *
     * @param FileUploaderType $type
     * @return self
     *
     * @throws \Throwable
     */
    public function init(FileUploaderType $type): self
    {
        try {
            $fileName = $type->value . '.json';
            $this->filePath = storage_path(self::$path . '/' . $fileName);
            $dirname = dirname($this->filePath);

            if (!is_dir($dirname)) {
                mkdir($dirname, 0755, true);
            }

            return $this;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Get the file path for the specified type.
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * Store a file in the specified uploader type directory.
     *
     * @param string $fileName
     * @param mixed $fileContent
     *
     * @return void
     */
    public function store($fileContent): void
    {
        try {
            $content = json_encode($fileContent, 0);
            $currentContent = file_exists($this->filePath) ? file_get_contents($this->filePath) : null;

            if (is_null($currentContent)) {
                file_put_contents($this->filePath, $content);
            } else {
                $data = json_decode($currentContent, true);
                $data = array_merge($data, $fileContent);
                file_put_contents($this->filePath, json_encode($data, 0));
            }
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Get all files from the specified uploader type directory.
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    public function get(string $key, string $default): mixed
    {
        try {
            if (!file_exists($this->filePath)) {
                $this->store([$key => $default]);
                return $default;
            }

            $content = file_get_contents($this->filePath);
            $data = json_decode($content, true);

            if (!array_key_exists($key, $data)) {
                $data[$key] = $default;
                $this->store($data);
            }

            return $data[$key] ?? $default;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return [];
        }
    }
}
