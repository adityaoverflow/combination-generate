<?php

namespace WsPackages\CombinationGenerate\Services;

/**
 * Service for generating combinations from multiple arrays
 */
class CombinationService
{
    /**
     * Generate all possible combinations from multiple arrays
     *
     * @param array $arrays Array of arrays to combine
     * @param int $index Current array index (used for recursion)
     * @return array Array of all possible combinations
     */
    public function generateCombination(array $arrays, int $index = 0): array
    {
        // Return empty array if the current index doesn't exist
        if (!isset($arrays[$index])) {
            return [];
        }

        // Base case: if we're at the last array, return each element as a single-item array
        if ($index === count($arrays) - 1) {
            $result = [];
            foreach ($arrays[$index] as $value) {
                $result[] = [$value];
            }
            return $result;
        }

        // Get combinations from subsequent arrays (recursive call)
        $subCombinations = $this->generateCombination($arrays, $index + 1);

        $result = [];

        // Combine each element from current array with each sub-combination
        foreach ($arrays[$index] as $value) {
            foreach ($subCombinations as $combination) {
                $result[] = is_array($combination) 
                    ? array_merge([$value], $combination)
                    : [$value, $combination];
            }
        }

        return $result;
    }

    /**
     * Alias for generateCombination for backward compatibility
     * 
     * @deprecated Use generateCombination() instead
     * @param array $arrays
     * @param int $i
     * @return array
     */
    public function generate_combination(array $arrays, int $i = 0): array
    {
        return $this->generateCombination($arrays, $i);
    }
}