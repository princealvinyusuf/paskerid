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
        $setting = $setting ?? ProgramKemitraanCertificateSetting::query()->orderByDesc('id')->first();

        $certificateTitle = $this->normalizeText((string) ($setting?->certificate_title ?: 'Sertifikat Partisipasi'));
        $participantName = $this->normalizeText((string) ($evaluation->respondent_name ?: '-'));
        $activityName = $this->normalizeText((string) ($evaluation->activity_name ?: 'Program Kemitraan'));
        $signerName = $this->normalizeText((string) ($setting?->signer_name ?: 'R. Nurhidajat, S.E., M.Ec.Dev.'));

        $signaturePath = $this->resolveLocalAssetPath((string) ($setting?->signature_image_path ?? ''));
        $backgroundPath = $this->resolveLocalAssetPath((string) ($setting?->background_image_path ?? ''));

        $signatureImageObject = null;
        if ($signaturePath !== null) {
            $signatureImageObject = $this->prepareJpegImageObject($signaturePath);
        }
        $backgroundImageObject = null;
        if ($backgroundPath !== null) {
            $backgroundImageObject = $this->prepareJpegImageObject($backgroundPath);
        }

        $streamParts = [];
        if ($backgroundImageObject !== null) {
            $streamParts[] = 'q';
            $streamParts[] = sprintf('%.2F 0 0 %.2F 0 0 cm', self::PAGE_WIDTH, self::PAGE_HEIGHT);
            $streamParts[] = '/Bg1 Do';
            $streamParts[] = 'Q';
        }

        $streamParts[] = $this->text('F2', 42, $this->centeredX($certificateTitle, 42), 525, strtoupper($certificateTitle));
        $streamParts[] = $this->text('F1', 18, $this->centeredX('Dengan ini menyatakan bahwa', 18), 495, 'Dengan ini menyatakan bahwa');
        $streamParts[] = $this->text('F2', 54, $this->centeredX($participantName, 54), 420, strtoupper($participantName));
        $streamParts[] = '0.74 0.12 0.12 RG';
        $streamParts[] = '1.2 w';
        $streamParts[] = '138 404 m 704 404 l S';
        $streamParts[] = $this->text('F2', 32, $this->centeredX('Sebagai PESERTA', 32), 360, 'Sebagai PESERTA');

        $descriptionLines = $this->wrapText(
            'Sertifikat ini diberikan sebagai bentuk apresiasi atas partisipasi, kontribusi, dan antusiasme dalam mendukung kelancaran kegiatan ' . $activityName . '.',
            122
        );
        $descriptionY = 302;
        foreach ($descriptionLines as $line) {
            $streamParts[] = $this->text('F1', 16, 92, $descriptionY, $line);
            $descriptionY -= 24;
        }

        $imageCommands = [];
        if ($signatureImageObject !== null) {
            $targetWidth = 190.0;
            $targetHeight = 78.0;
            $ratio = $signatureImageObject['width'] / max(1, $signatureImageObject['height']);
            if ($ratio > 0) {
                if (($targetWidth / $targetHeight) > $ratio) {
                    $targetWidth = $targetHeight * $ratio;
                } else {
                    $targetHeight = $targetWidth / $ratio;
                }
            }
            $x = (self::PAGE_WIDTH - $targetWidth) / 2;
            $y = 102;
            $imageCommands[] = 'q';
            $imageCommands[] = sprintf('%.2F 0 0 %.2F %.2F %.2F cm', $targetWidth, $targetHeight, $x, $y);
            $imageCommands[] = '/Im1 Do';
            $imageCommands[] = 'Q';
        }

        $streamParts[] = $this->text('F2', 20, $this->centeredX($signerName, 20), 64, $signerName);

        if (!empty($imageCommands)) {
            $streamParts = array_merge($streamParts, $imageCommands);
        }

        $contentStream = implode("\n", $streamParts) . "\n";
        $fontRegularObjectId = 5;
        $fontBoldObjectId = 6;
        $nextObjectId = 7;

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

        $resources = '<< /Font << /F1 ' . $fontRegularObjectId . ' 0 R /F2 ' . $fontBoldObjectId . ' 0 R >>';
        if ($backgroundObjectId !== null || $signatureObjectId !== null) {
            $resources .= ' /XObject <<';
            if ($backgroundObjectId !== null) {
                $resources .= ' /Bg1 ' . $backgroundObjectId . ' 0 R';
            }
            if ($signatureObjectId !== null) {
                $resources .= ' /Im1 ' . $signatureObjectId . ' 0 R';
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

        return $this->renderPdf($objects);
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
        $approxCharWidth = $fontSize * 0.52;
        $textWidth = strlen($this->normalizeText($text)) * $approxCharWidth;
        return max(40, (self::PAGE_WIDTH - $textWidth) / 2);
    }

    /**
     * @return array<int, string>
     */
    private function wrapText(string $text, int $maxChars): array
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
            if (strlen($candidate) > $maxChars && $currentLine !== '') {
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
