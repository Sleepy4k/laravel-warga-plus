<?php

namespace App\Models;

use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Enums\FileUploaderType;
use App\Facades\FileUploader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'group' => 'string',
            'key' => 'string',
            'value' => 'string',
            'type' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Set the cache prefix.
     *
     * @return string
     */
    public function setCachePrefix(): string {
        return 'setting.cache';
    }

    /**
     * Get all settings as a key => value array with casted values.
     *
     * @return array<string, mixed>
     */
    public static function allAsKeyValue(): array
    {
        return static::all('group', 'key', 'value', 'type')
            ->mapWithKeys(function ($setting) {
                $groupKey = strtolower(trim("{$setting->group}_{$setting->key}", '_'));
                $castedValue = match ($setting->type) {
                    'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                    'integer' => (int) $setting->value,
                    'float' => (float) $setting->value,
                    default => $setting->value,
                };
                return [$groupKey => $castedValue];
            })->toArray();
    }

    /**
     * Get validation rules for settings.
     *
     * @return array<string, array<string>>
     */
    public static function getValidationData(): array
    {
        $fileUploader = FileUploader::init(FileUploaderType::SETTING);
        $fileType = $fileUploader->get('type', 'file');
        $fileMimes = $fileUploader->get('mimes', 'jpeg,png,jpg');
        $fileMaxSize = $fileUploader->get('max_size', 8192);

        $result = [];
        static::all('group', 'key', 'type', 'is_file', 'is_required')
            ->each(function ($setting) use (&$result, $fileMimes, $fileMaxSize, $fileType) {
            $rules = [
                $setting->is_required ? 'required' : 'nullable',
            ];

            if ($setting->is_file) {
                $rules[] = $fileType;
                $rules[] = 'mimes:' . $fileMimes;
                $rules[] = 'max:' . $fileMaxSize;
            } else {
                switch ($setting->type) {
                case 'boolean':
                    $rules[] = 'boolean';
                    break;
                case 'integer':
                    $rules[] = 'integer';
                    $rules[] = 'min:0';
                    break;
                case 'float':
                    $rules[] = 'numeric';
                    $rules[] = 'min:0';
                    break;
                default:
                    $rules[] = 'string';
                    $rules[] = 'max:255';
                }
            }

            $groupKey = strtolower(trim("{$setting->group}_{$setting->key}", '_'));
            $result[$groupKey] = $rules;
            });
        return $result;
    }
}
