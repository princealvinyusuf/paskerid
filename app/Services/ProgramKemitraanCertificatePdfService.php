<?php

namespace App\Services;

use App\Models\ProgramKemitraanCertificateSetting;
use App\Models\ProgramKemitraanEvaluation;

class ProgramKemitraanCertificatePdfService
{
    private const PAGE_WIDTH = 842.0;
    private const PAGE_HEIGHT = 595.0;

    public function generate(ProgramKemitraanEvaluation $evaluation, ?ProgramKemitraanCertificateSetting $setting = null): string
    {
        $setting = $setting ?? ProgramKemitraanCertificateSetting::query()->orderBy('id')->first();

        $certificateTitle = $this->normalizeText((string) ($setting?->certificate_title ?: 'Sertifikat'));
        $ministryHeader = $this->normalizeText((string) ($setting?->ministry_header_text ?: 'KEMENTERIAN KETENAGAKERJAAN REPUBLIK INDONESIA'));
        $participantName = $this->normalizeText((string) ($evaluation->respondent_name ?: '-'));
        $activityName = $this->normalizeText((string) ($evaluation->activity_name ?: 'Program Kemitraan'));
        $signerName = $this->normalizeText((string) ($setting?->signer_name ?: 'R. Nurhidajat, S.E., M.Ec.Dev.'));
        $signerPosition = $this->normalizeText((string) ($setting?->signer_position ?: 'Kepala Pusat Pasar Kerja'));
        $signPlace = $this->normalizeText((string) ($setting?->sign_place ?: 'Jakarta'));
        $certificateNumber = $this->normalizeText((string) ($evaluation->certificate_code ?: '-'));
        $participantRole = $this->participantRole($evaluation, $setting);
        $activityDate = $evaluation->activity_date ? indo_date($evaluation->activity_date) : '-';
        $activityLocation = $this->normalizeText((string) ($evaluation->activity_location ?: '-'));

        $signaturePath = $this->resolveLocalAssetPath((string) ($setting?->signature_image_path ?? ''));
        $backgroundPath = $this->resolveLocalAssetPath((string) ($setting?->background_image_path ?? ''));
        $logoPath = $this->resolveLocalAssetPath((string) ($setting?->logo_image_path ?? ''));

        $signatureImageObject = null;
        if ($signaturePath !== null) {
            $signatureImageObject = $this->prepareJpegImageObject($signaturePath);
        }
        $backgroundImageObject = null;
        if ($backgroundPath !== null) {
            $backgroundImageObject = $this->prepareJpegImageObject($backgroundPath);
        }
        $logoImageObject = null;
        if ($logoPath !== null) {
            $logoImageObject = $this->prepareJpegImageObject($logoPath);
        }

        $streamParts = [];
        if ($backgroundImageObject !== null) {
            $streamParts[] = 'q';
            $streamParts[] = sprintf('%.2F 0 0 %.2F 0 0 cm', self::PAGE_WIDTH, self::PAGE_HEIGHT);
            $streamParts[] = '/Bg1 Do';
            $streamParts[] = 'Q';
        }

        if ($logoImageObject !== null) {
            $targetHeight = 58.0;
            $targetWidth = $targetHeight * ($logoImageObject['width'] / max(1, $logoImageObject['height']));
            $targetWidth = min($targetWidth, 72.0);
            $streamParts[] = 'q';
            $streamParts[] = sprintf(
                '%.2F 0 0 %.2F %.2F %.2F cm',
                $targetWidth,
                $targetHeight,
                (self::PAGE_WIDTH - $targetWidth) / 2,
                525.0
            );
            $streamParts[] = '/Logo1 Do';
            $streamParts[] = 'Q';
        } else {
            $streamParts[] = $this->kemnakerMark();
        }

        $streamParts[] = $this->text('F2', 16, $this->centeredX($ministryHeader, 16), 505, $ministryHeader);
        $titleSize = 52.0;
        $streamParts[] = $this->text('F3', $titleSize, $this->centeredX($certificateTitle, $titleSize), 442, $certificateTitle);
        $streamParts[] = $this->text('F1', 16, $this->centeredX('NOMOR ' . $certificateNumber, 16), 412, 'NOMOR ' . $certificateNumber);
        $streamParts[] = $this->text('F1', 18, $this->centeredX('Diberikan kepada:', 18), 380, 'Diberikan kepada:');

        $nameSize = $this->fitFontSize($participantName, 30, 18, 620);
        $nameX = $this->centeredX($participantName, $nameSize);
        $streamParts[] = $this->text('F4', $nameSize, $nameX, 340, $participantName);
        $nameWidth = min(620.0, $this->estimateTextWidth($participantName, $nameSize));
        $streamParts[] = '0 0 0 RG';
        $streamParts[] = '1 w';
        $streamParts[] = sprintf('%.2F 334 m %.2F 334 l S', (self::PAGE_WIDTH - $nameWidth) / 2, (self::PAGE_WIDTH + $nameWidth) / 2);

        $description = sprintf(
            'Atas partisipasinya sebagai %s dalam acara %s yang dilaksanakan pada tanggal %s di %s.',
            $participantRole,
            $activityName,
            $this->normalizeText($activityDate),
            $activityLocation
        );
        $descriptionLines = $this->wrapTextByWidth($description, 14, self::PAGE_WIDTH - 150);
        $descriptionY = 300;
        foreach ($descriptionLines as $line) {
            $streamParts[] = $this->text('F1', 14, $this->centeredX($line, 14), $descriptionY, $line);
            $descriptionY -= 21;
        }

        $imageCommands = [];
        if ($signatureImageObject !== null) {
            $targetWidth = 135.0;
            $targetHeight = 60.0;
            $ratio = $signatureImageObject['width'] / max(1, $signatureImageObject['height']);
            if ($ratio > 0) {
                if (($targetWidth / $targetHeight) > $ratio) {
                    $targetWidth = $targetHeight * $ratio;
                } else {
                    $targetHeight = $targetWidth / $ratio;
                }
            }
            $x = 625.0 + ((145.0 - $targetWidth) / 2);
            $y = 76;
            $imageCommands[] = 'q';
            $imageCommands[] = sprintf('%.2F 0 0 %.2F %.2F %.2F cm', $targetWidth, $targetHeight, $x, $y);
            $imageCommands[] = '/Im1 Do';
            $imageCommands[] = 'Q';
        }

        $signatureX = 625.0;
        $streamParts[] = $this->text('F1', 12, $signatureX, 202, $signPlace . ', ' . $this->normalizeText($activityDate));
        $streamParts[] = $this->text('F1', 12, $signatureX, 183, $signerPosition . ',');
        $streamParts[] = $this->text('F1', 12, $signatureX, 151, 'Ttd. (cap)/TTE');
        $streamParts[] = $this->text('F2', 12, $signatureX, 56, $signerName);

        if (!empty($imageCommands)) {
            $streamParts = array_merge($streamParts, $imageCommands);
        }

        $contentStream = implode("\n", $streamParts) . "\n";
        $fontRegularObjectId = 5;
        $fontBoldObjectId = 6;
        $fontScriptObjectId = 7;
        $fontNameObjectId = 8;
        $nextObjectId = 9;

        $backgroundObjectId = null;
        if ($backgroundImageObject !== null) {
            $backgroundObjectId = $nextObjectId;
            $nextObjectId++;
        }

        $signatureObjectId = null;
        if ($signatureImageObject !== null) {
            $signatureObjectId = $nextObjectId;
            $nextObjectId++;
        }

        $logoObjectId = null;
        if ($logoImageObject !== null) {
            $logoObjectId = $nextObjectId;
        }

        $resources = '<< /Font << /F1 ' . $fontRegularObjectId . ' 0 R /F2 ' . $fontBoldObjectId
            . ' 0 R /F3 ' . $fontScriptObjectId . ' 0 R /F4 ' . $fontNameObjectId . ' 0 R >>';
        if ($backgroundObjectId !== null || $signatureObjectId !== null || $logoObjectId !== null) {
            $resources .= ' /XObject <<';
            if ($backgroundObjectId !== null) {
                $resources .= ' /Bg1 ' . $backgroundObjectId . ' 0 R';
            }
            if ($signatureObjectId !== null) {
                $resources .= ' /Im1 ' . $signatureObjectId . ' 0 R';
            }
            if ($logoObjectId !== null) {
                $resources .= ' /Logo1 ' . $logoObjectId . ' 0 R';
            }
            $resources .= ' >>';
        }
        $resources .= ' >>';

        $objects = [];
        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' . self::PAGE_WIDTH . ' ' . self::PAGE_HEIGHT . '] /Resources ' . $resources . ' /Contents 4 0 R >>';
        $objects[] = '<< /Length ' . strlen($contentStream) . " >>\nstream\n" . $contentStream . "endstream";
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Times-Italic /Encoding /WinAnsiEncoding >>';
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Times-Bold /Encoding /WinAnsiEncoding >>';

        if ($backgroundImageObject !== null) {
            $objects[] = '<< /Type /XObject /Subtype /Image /Width ' . $backgroundImageObject['width']
                . ' /Height ' . $backgroundImageObject['height']
                . ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length '
                . strlen($backgroundImageObject['data']) . " >>\nstream\n" . $backgroundImageObject['data'] . "\nendstream";
        }

        if ($signatureImageObject !== null) {
            $objects[] = '<< /Type /XObject /Subtype /Image /Width ' . $signatureImageObject['width']
                . ' /Height ' . $signatureImageObject['height']
                . ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length '
                . strlen($signatureImageObject['data']) . " >>\nstream\n" . $signatureImageObject['data'] . "\nendstream";
        }

        if ($logoImageObject !== null) {
            $objects[] = '<< /Type /XObject /Subtype /Image /Width ' . $logoImageObject['width']
                . ' /Height ' . $logoImageObject['height']
                . ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length '
                . strlen($logoImageObject['data']) . " >>\nstream\n" . $logoImageObject['data'] . "\nendstream";
        }

        return $this->renderPdf($objects);
    }

    private function participantRole(
        ProgramKemitraanEvaluation $evaluation,
        ?ProgramKemitraanCertificateSetting $setting
    ): string {
        $role = trim((string) ($evaluation->respondent_role ?: $setting?->participation_role_default ?: 'Peserta'));

        return $this->normalizeText($role);
    }

    private function fitFontSize(string $text, float $preferredSize, float $minimumSize, float $maxWidth): float
    {
        $size = $preferredSize;
        while ($size > $minimumSize && $this->estimateTextWidth($text, $size) > $maxWidth) {
            $size -= 1.0;
        }

        return $size;
    }

    private function kemnakerMark(): string
    {
        return implode("\n", [
            'q',
            '0.17 0.30 0.39 RG',
            '0.17 0.30 0.39 rg',
            '7 w',
            '1 J',
            '397 575 m 445 527 l S',
            '409 581 m 451 539 l S',
            '445 575 m 397 527 l S',
            '433 581 m 391 539 l S',
            '390 574 8 8 re f',
            '444 574 8 8 re f',
            '390 526 8 8 re f',
            '444 526 8 8 re f',
            'Q',
        ]);
    }

    private function text(string $fontAlias, float $fontSize, float $x, float $y, string $text): string
    {
        return sprintf(
            'BT /%s %.2F Tf %.2F %.2F Td (%s) Tj ET',
            $fontAlias,
            $fontSize,
            $x,
            $y,
            $this->pdfEscape($text)
        );
    }

    private function centeredX(string $text, float $fontSize): float
    {
        $textWidth = $this->estimateTextWidth($text, $fontSize);
        return max(40, (self::PAGE_WIDTH - $textWidth) / 2);
    }

    /**
     * @return array<int, string>
     */
    private function wrapTextByWidth(string $text, float $fontSize, float $maxWidth): array
    {
        $cleanText = trim(preg_replace('/\s+/', ' ', $this->normalizeText($text)) ?? '');
        if ($cleanText === '') {
            return [''];
        }

        $words = preg_split('/\s+/', $cleanText) ?: [];
        $lines = [];
        $currentLine = '';
        foreach ($words as $word) {
            $candidate = $currentLine === '' ? $word : $currentLine . ' ' . $word;
            if ($this->estimateTextWidth($candidate, $fontSize) > $maxWidth && $currentLine !== '') {
                $lines[] = $currentLine;
                $currentLine = $word;
                continue;
            }
            $currentLine = $candidate;
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }

    private function estimateTextWidth(string $text, float $fontSize): float
    {
        $normalized = $this->normalizeText($text);
        $widthUnits = 0.0;
        $length = strlen($normalized);

        for ($i = 0; $i < $length; $i++) {
            $char = $normalized[$i];
            if ($char === ' ') {
                $widthUnits += 0.30;
                continue;
            }
            if (preg_match('/[ilI1\.,:;!\|]/', $char) === 1) {
                $widthUnits += 0.30;
                continue;
            }
            if (preg_match('/[mwMW@#%&]/', $char) === 1) {
                $widthUnits += 0.90;
                continue;
            }
            if (preg_match('/[A-Z0-9]/', $char) === 1) {
                $widthUnits += 0.66;
                continue;
            }
            $widthUnits += 0.56;
        }

        return $widthUnits * $fontSize;
    }

    private function pdfEscape(string $text): string
    {
        $text = $this->normalizeText($text);
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    private function normalizeText(string $text): string
    {
        $text = trim(str_replace(["\r\n", "\r", "\n"], ' ', $text));
        $encoded = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        if ($encoded === false || $encoded === '') {
            return preg_replace('/[^\x20-\x7E]/', '', $text) ?? '';
        }

        return preg_replace('/[^\x20-\x7E\xA0-\xFF]/', '', $encoded) ?? '';
    }

    private function resolveLocalAssetPath(string $storedPath): ?string
    {
        $path = trim($storedPath);
        if ($path === '' || filter_var($path, FILTER_VALIDATE_URL) !== false) {
            return null;
        }

        $normalized = ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
        $candidates = [
            public_path($normalized),
            base_path($normalized),
        ];

        if (str_starts_with($normalized, 'storage' . DIRECTORY_SEPARATOR)) {
            $relative = substr($normalized, strlen('storage' . DIRECTORY_SEPARATOR));
            $candidates[] = storage_path('app/public/' . $relative);
        }

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * @return array{data:string,width:int,height:int}|null
     */
    private function prepareJpegImageObject(string $sourcePath): ?array
    {
        if (!is_file($sourcePath) || !function_exists('imagejpeg')) {
            return null;
        }

        $imageData = @file_get_contents($sourcePath);
        if ($imageData === false) {
            return null;
        }

        $rawImage = @imagecreatefromstring($imageData);
        if ($rawImage === false) {
            return null;
        }

        $width = imagesx($rawImage);
        $height = imagesy($rawImage);
        if ($width <= 0 || $height <= 0) {
            imagedestroy($rawImage);
            return null;
        }

        $canvas = imagecreatetruecolor($width, $height);
        if ($canvas === false) {
            imagedestroy($rawImage);
            return null;
        }

        // Flatten to white so PNG alpha channels render consistently in PDF.
        $bg = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $bg);
        imagecopy($canvas, $rawImage, 0, 0, 0, 0, $width, $height);
        imagedestroy($rawImage);

        ob_start();
        imagejpeg($canvas, null, 90);
        $jpegData = (string) ob_get_clean();
        imagedestroy($canvas);

        if ($jpegData === '') {
            return null;
        }

        return [
            'data' => $jpegData,
            'width' => $width,
            'height' => $height,
        ];
    }

    /**
     * @param array<int, string> $objects
     */
    private function renderPdf(array $objects): string
    {
        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $index => $objectContent) {
            $objectId = $index + 1;
            $offsets[$objectId] = strlen($pdf);
            $pdf .= $objectId . " 0 obj\n";
            $pdf .= $objectContent . "\n";
            $pdf .= "endobj\n";
        }

        $xrefOffset = strlen($pdf);
        $totalObjects = count($objects) + 1;
        $pdf .= "xref\n";
        $pdf .= '0 ' . $totalObjects . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i < $totalObjects; $i++) {
            $pdf .= sprintf('%010d 00000 n ', $offsets[$i] ?? 0) . "\n";
        }

        $pdf .= "trailer\n";
        $pdf .= '<< /Size ' . $totalObjects . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n";
        $pdf .= $xrefOffset . "\n";
        $pdf .= "%%EOF";

        return $pdf;
    }
}
