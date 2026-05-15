<?php

namespace Tests\Unit;

use App\Models\Peminjaman;
use App\Services\SawCalculatorService;
use PHPUnit\Framework\TestCase;

class SawCalculatorServiceTest extends TestCase
{
    /**
     * @test WT-01: Uji logika jika selisih >= 7 hari
     */
    public function test_calculate_lead_time_score_returns_3_for_7_days_or_more()
    {
        $tglSubmit = '2026-05-10';
        $tglPakai  = '2026-05-18'; // 8 days later

        $score = SawCalculatorService::calculateLeadTimeScore($tglPakai, $tglSubmit);

        $this->assertEquals(Peminjaman::LEAD_TIME_H7, $score);
        $this->assertEquals(3, $score);
    }

    /**
     * @test WT-02: Uji logika jika selisih 3 - 6 hari
     */
    public function test_calculate_lead_time_score_returns_2_for_3_to_6_days()
    {
        $tglSubmit = '2026-05-10';
        $tglPakai  = '2026-05-14'; // 4 days later

        $score = SawCalculatorService::calculateLeadTimeScore($tglPakai, $tglSubmit);

        $this->assertEquals(Peminjaman::LEAD_TIME_H3, $score);
        $this->assertEquals(2, $score);
    }

    /**
     * @test WT-03: Uji logika jika selisih kurang dari 3 hari
     */
    public function test_calculate_lead_time_score_returns_1_for_less_than_3_days()
    {
        $tglSubmit = '2026-05-10';
        $tglPakai  = '2026-05-11'; // 1 day later

        $score = SawCalculatorService::calculateLeadTimeScore($tglPakai, $tglSubmit);

        $this->assertEquals(Peminjaman::LEAD_TIME_HARIH, $score);
        $this->assertEquals(1, $score);
    }

    /**
     * @test: Pastikan metode mengambil weights yang sudah ditentukan.
     */
    public function test_get_weights_returns_correct_array_structure()
    {
        $weights = SawCalculatorService::getWeights();

        $this->assertIsArray($weights);
        $this->assertArrayHasKey('C1', $weights);
        $this->assertArrayHasKey('C2', $weights);
        $this->assertArrayHasKey('C3', $weights);
        
        $sum = array_sum($weights);
        $this->assertEquals(1.0, round($sum, 2)); // Bobot total SAW harus bernilai 1.0
    }
}
