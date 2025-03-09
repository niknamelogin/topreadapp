<?php
namespace App\Http\Controllers;

use App\Http\Requests\TopReadRequest;
use Illuminate\Support\Facades\Http;

class TopReadController extends Controller {

    private const BOOKAPIBASEURL = 'https://api.nytimes.com/svc/books/v3';
    private const BESTSELLERSURL = '/lists/best-sellers/history.json';

    public function index(TopReadRequest $request) {

        // $params = $request->only(['author', 'title', 'offset', 'isbn']);
        $params = $request->only(['author', 'title', 'offset']);

if( $request->has('isbn')) {
    $params['isbn'] = implode(';', $request->input('isbn'));
}


        dump($params);
        // dd($params);




        $params['api-key'] = config('services.NYB.api-key');
        $response          = Http::get(self::BOOKAPIBASEURL . self::BESTSELLERSURL, $params);

        $response = response()->json($response->json(), $response->status());

        return $response;
    }
}
