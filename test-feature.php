<?php

/**
 * Test feature file for testing changelog generation.
 */
class TestFeature
{
    public function testMethod(): string
    {
        return 'This is a test feature for changelog generation';
    }

    public function anotherTestMethod(): bool
    {
        return true;
    }

    public function bugFixMethod(): int
    {
        // Fixed bug in calculation
        return 42;
    }
}
