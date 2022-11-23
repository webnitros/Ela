<?php

namespace Ela\Http\Controllers\Analysis;

use Ela\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyzerController extends Controller
{
    public function get(Request $request)
    {
        $this->validatorResponse($request, [
            'analyzer' => 'required',
            'text' => 'required',
        ]);


        $returnedTokens = $this->index()->analyze([
            'analyzer' => $request->get('analyzer'),
            'text' => $request->get('text')
        ]);

        return new JsonResponse($returnedTokens, 200);
    }

}
