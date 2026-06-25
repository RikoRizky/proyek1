<?php

namespace App\Support;

use App\Models\Module;
use App\Models\Requirement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class AccreditationUpload
{
    public static function maxUploadKb(): int
    {
        return (int) config('accreditation.max_upload_kb', 20480);
    }

    public static function maxUploadMb(): float
    {
        return round(self::maxUploadKb() / 1024, 1);
    }

    public static function maxUploadBytes(): int
    {
        return self::maxUploadKb() * 1024;
    }

    /** @return list<string> */
    public static function allowedMimes(): array
    {
        return config('accreditation.allowed_mimes', ['pdf', 'xlsx', 'xls']);
    }

    /** @return array<string, list<string>> */
    public static function fileRules(): array
    {
        return [
            'file' => array_merge(
                ['required', 'file', 'mimes:'.implode(',', self::allowedMimes())],
                ['max:'.self::maxUploadKb()]
            ),
        ];
    }

    public static function formatMegabytes(int $bytes): string
    {
        return number_format($bytes / 1024 / 1024, 1, ',', '.');
    }

    public static function iniSizeLabel(?string $iniValue): string
    {
        if ($iniValue === null || $iniValue === '' || $iniValue === '-1') {
            return 'tidak terbatas';
        }

        $value = trim($iniValue);
        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return match ($unit) {
            'g' => round($number * 1024, 0).' MB',
            'm' => round($number, 0).' MB',
            'k' => round($number / 1024, 1).' MB',
            default => round($number / 1024 / 1024, 1).' MB',
        };
    }

    public static function validateFile(UploadedFile $file): ?string
    {
        $validator = Validator::make(['file' => $file], self::fileRules());

        if ($validator->passes()) {
            return null;
        }

        return $validator->errors()->first('file');
    }

    public static function fieldErrorMessage(
        Module $module,
        Requirement $requirement,
        UploadedFile $file,
        ?string $validationMessage = null,
    ): string {
        $label = '«'.$requirement->title.'» pada modul «'.$module->name.'»';
        $maxMb = self::maxUploadMb();

        if ($validationMessage !== null) {
            if (str_contains($validationMessage, 'max')) {
                $sizeMb = self::formatMegabytes($file->getSize());

                return 'Berkas '.$label.' terlalu besar ('.$sizeMb.' MB, maks. '.$maxMb.' MB). Unggah ulang berkas ini; berkas lain yang valid tetap tersimpan.';
            }

            if (str_contains($validationMessage, 'mimes')) {
                return 'Berkas '.$label.' harus PDF atau Excel. Unggah ulang berkas ini; berkas lain yang valid tetap tersimpan.';
            }
        }

        return 'Berkas '.$label.' tidak valid. Unggah ulang berkas ini; berkas lain yang valid tetap tersimpan.';
    }
}
