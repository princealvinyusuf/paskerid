<?php

namespace Tests\Unit;

use App\Models\ProgramKemitraanCertificateSetting;
use App\Models\ProgramKemitraanEvaluation;
use App\Services\ProgramKemitraanCertificatePdfService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ProgramKemitraanCertificatePdfServiceTest extends TestCase
{
    public function test_it_generates_the_ministry_certificate_content(): void
    {
        $evaluation = new ProgramKemitraanEvaluation([
            'respondent_name' => 'Nama Peserta',
            'respondent_role' => 'Narasumber',
            'activity_name' => 'Forum Kemitraan',
            'activity_date' => '2026-09-03',
            'activity_location' => 'Jakarta',
            'certificate_code' => 'PKC-2026-ABC123',
        ]);
        $evaluation->activity_date = Carbon::parse('2026-09-03');

        $setting = new ProgramKemitraanCertificateSetting([
            'certificate_title' => 'Sertifikat',
            'ministry_header_text' => 'KEMENTERIAN KETENAGAKERJAAN REPUBLIK INDONESIA',
            'signer_name' => 'Pejabat Penandatangan',
            'signer_position' => 'Kepala Pusat Pasar Kerja',
            'sign_place' => 'Jakarta',
            'participation_role_default' => 'Peserta',
        ]);

        $pdf = (new ProgramKemitraanCertificatePdfService())->generate($evaluation, $setting);

        $this->assertStringStartsWith('%PDF-1.4', $pdf);
        $this->assertStringContainsString('NOMOR PKC-2026-ABC123', $pdf);
        $this->assertStringContainsString('Nama Peserta', $pdf);
        $this->assertStringContainsString('Atas partisipasinya sebagai Narasumber', $pdf);
        $this->assertStringContainsString('03 September 2026', $pdf);
        $this->assertStringContainsString('Pejabat Penandatangan', $pdf);
    }
}
