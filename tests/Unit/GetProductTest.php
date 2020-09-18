<?php
namespace Tests\Unit;

use Tests\ServiceDatabaseMigrations;
use Tests\TestCase;

/**
 * Class GetProductTest
 * @package Tests\Unit
 */
class GetProductTest extends TestCase
{
    use ServiceDatabaseMigrations;

    public function testGetPaginatedProducts()
    {
        $this->runDatabaseMigrations();

        $this->get('/api/product', [])
            ->assertStatus(200)
            ->assertJsonFragment(['description' => 'description']);
    }
}
