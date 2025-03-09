<?php
namespace App\Http\Controllers;

use App\Http\Requests\TopReadRequest;
use Illuminate\Support\Facades\Http;

class TopReadController extends Controller {
    private const BOOKAPIBASEURL = 'https://api.nytimes.com/svc/books/v3';
    private const BESTSELLERSURL = '/lists/best-sellers/history.json';

    public function index(TopReadRequest $request) {
        $params = $request->only(['author', 'title', 'offset']);

        if ($request->has('isbn')) {
            $params['isbn'] = implode(';', $request->input('isbn'));
        }

        $params['api-key'] = config('services.NYB.api-key');

        $apiResponse = Http::get(self::BOOKAPIBASEURL . self::BESTSELLERSURL, $params);

        if (! $apiResponse->successful()) {
            return response()->json(['error' => 'Bad Request'], 400);
        }

        return response()->json($apiResponse->json(), 200);
    }

}
