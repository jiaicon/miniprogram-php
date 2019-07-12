<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    private $_data;

    public function __construct()
    {
        $this->_data['error_code'] = 0;
        $this->_data['data'] = (object)[];
        $this->_data['error_message'] = '';
    }

    public function response()
    {
        if($this->_data['error_code'] != 0)
        {
            Log::warning('error code is not 0', ['data'=>$this->_data, 'input'=>$_REQUEST, 'URI'=>$_SERVER['REQUEST_URI']]);
        }
        return response()->json($this->_data);
    }

    public function returnJSON($data, $status = 0, $msg = '', $StatusCode = 200)
    {
        $ret = [
            'error_code' => $status,
            'error_message' => $msg,
            'data' => $data,
        ];
        $this->_data = $ret;
        if($this->_data['error_code'] != 0)
        {
            Log::warning('error code is not 0', ['data'=>$this->_data, 'input'=>$_REQUEST, 'URI'=>$_SERVER['REQUEST_URI']]);
        }

        return new JsonResponse($ret, $StatusCode);
    }

    public  function getToken()
    {
        return request()->header('X-auth-token', '');
    }
}
