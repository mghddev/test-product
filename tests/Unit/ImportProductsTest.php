<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\ServiceDatabaseMigrations;
use Tests\TestCase;

class ImportProductsTest extends TestCase
{
    use ServiceDatabaseMigrations;

    /**
     * This is test of upload csv file and insert data of products to db
     *
     * @return void
     */
    public function testUploadAndImport()
    {
        $this->runDatabaseMigrations();

        ### test login admin
        $token = $this->post(
            '/api/login',
            [
                "email" => "admin@product.test",
                "password" => "12345678",
            ]
        )->assertStatus(200);

        $token = json_decode($token->content(), true);
        $stub = app_path('Constant/Document/products.csv');
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'products.csv';
        copy($stub, $path);

        $file = new UploadedFile($path, 'products.csv', 'csv', null, true);

        ### test success insert
        $this->call(
            'post',
            '/admin/product',
            [],
            ['auth_token' => $token['access_token']],
            ['products' => $file],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token['access_token']
            ]
        )->assertStatus(201)->assertJsonFragment(["category" => "beans"]);

        ###test validation
        $this->call(
            'post',
            '/admin/product',
            [],
            ['auth_token' => $token['access_token']],
            [],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token['access_token']
            ]
        )->assertStatus(400);
    }
}
