<?php

namespace WsPackages\CombinationGenerate\Tests;

use PHPUnit\Framework\TestCase;
use WsPackages\CombinationGenerate\Services\CombinationService;

class CombinationServiceTest extends TestCase
{
    private CombinationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CombinationService();
    }

    /** @test */
    public function it_generates_combinations_from_two_arrays()
    {
        $arrays = [
            ['A', 'B'],
            [1, 2]
        ];

        $result = $this->service->generateCombination($arrays);

        $expected = [
            ['A', 1],
            ['A', 2],
            ['B', 1],
            ['B', 2]
        ];

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_generates_combinations_from_three_arrays()
    {
        $arrays = [
            ['red', 'blue'],
            ['small', 'large'],
            ['cotton']
        ];

        $result = $this->service->generateCombination($arrays);

        $expected = [
            ['red', 'small', 'cotton'],
            ['red', 'large', 'cotton'],
            ['blue', 'small', 'cotton'],
            ['blue', 'large', 'cotton']
        ];

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_returns_empty_array_for_empty_input()
    {
        $result = $this->service->generateCombination([]);
        $this->assertEquals([], $result);
    }

    /** @test */
    public function it_handles_single_array()
    {
        $arrays = [['A', 'B', 'C']];
        
        $result = $this->service->generateCombination($arrays);
        
        $expected = [
            ['A'],
            ['B'],
            ['C']
        ];
        
        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_handles_empty_sub_arrays()
    {
        $arrays = [
            ['A', 'B'],
            [],
            ['X', 'Y']
        ];

        $result = $this->service->generateCombination($arrays);
        $this->assertEquals([], $result);
    }

    /** @test */
    public function backward_compatibility_with_old_method_name()
    {
        $arrays = [
            ['A', 'B'],
            [1, 2]
        ];

        $result = $this->service->generate_combination($arrays);

        $expected = [
            ['A', 1],
            ['A', 2],
            ['B', 1],
            ['B', 2]
        ];

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_generates_large_combinations()
    {
        $arrays = [
            ['red', 'blue', 'green'],
            ['S', 'M', 'L'],
            ['cotton', 'silk'],
            ['new', 'used']
        ];

        $result = $this->service->generateCombination($arrays);
        
        // Should generate 3 * 3 * 2 * 2 = 36 combinations
        $this->assertCount(36, $result);
        
        // Check first and last combinations
        $this->assertEquals(['red', 'S', 'cotton', 'new'], $result[0]);
        $this->assertEquals(['green', 'L', 'silk', 'used'], $result[35]);
    }
}
