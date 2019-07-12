<?php
/**
 * Created by PhpStorm.
 * User: icon
 * Date: 2019/7/11
 * Time: 下午4:52
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\Validator;

class VerifyUserToken {
    public function handle($request, Closure $next) {
        $rule = ['token'=>'required|exists:courier,token'];
        $check['token'] = request()->header('X-auth-token', '');
        $validator = Validator::make($check, $rule);
        if($validator->fails())
        {

            $errors = $validator->errors();

            $data['error_code'] = 9999;
            $data['data'] = (object)[];
            $data['error_message'] = implode(' ', $errors->all());

            return response()->json($data);
        }
        return $next($request);
    }
}