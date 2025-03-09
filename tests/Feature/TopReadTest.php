<?php
namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TopReadTest extends TestCase {

    public function test_it_returns_successful_response_with_all_parameters() {
        config(['services.NYB.api-key' => 'test-key']); // Ensure API key is set

        Http::fake([
            'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json*' => Http::response([
                'results' => [['book' => 'data']],
            ], 200),
        ]);

        $response = $this->getJson('/api/v1/topread?author=John%20Doe&isbn[]=1234567890&isbn[]=0987654321&title=Example%20Book&offset=10');

        $response->assertStatus(200)
            ->assertJson(['results' => [['book' => 'data']]]);
    }

    public function test_it_handles_nyt_api_error() {
        config(['services.NYB.api-key' => 'test-key']); // Ensure API key is set

        Http::fake([
            'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json*' => Http::response([
                'error' => 'Bad Request',
            ], 400),
        ]);

        $response = $this->getJson('/api/v1/topread?author=John%20Doe');
        $response->assertStatus(400)
            ->assertJson(['error' => 'Bad Request']);
    }

    public function test_it_includes_api_key_from_config() {
        config(['services.NYB.api-key' => 'test-api-key']);

        Http::fake([
            'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json*' => function ($request) {
                parse_str(parse_url($request->url(), PHP_URL_QUERY), $query);
                $this->assertEquals('test-api-key', $query['api-key']);
                return Http::response(['results' => []], 200);
            },
        ]);

        $response = $this->getJson('/api/v1/topread');
        $response->assertStatus(200);
    }
}
