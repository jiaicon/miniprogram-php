<?php
/**
 * Created by PhpStorm.
 * User: icon
 * Date: 2019/7/11
 * Time: 下午2:42
 */

namespace App\Http\Controllers;

use App\Models\MiniProgram;
use App\Models\UserWechat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Payment\Order;
use EasyWeChat\Factory;

class WeixinController extends Controller
{
    //

    public function login(Request $request)
    {

        $options = [
            'app_id'    => 'wxf9d06df7755a05ee',
            'secret'    => '8dd72bb49348c1d954ba3bf2dd3e4072',
            'token'     => 'easywechat',
            'log' => [
                'level' => 'debug',
                'file'  => storage_path('logs/wechat.log'),
            ],
            'response_type'=> 'array'
        ];
        $code = $request->input('code');
        $app = Factory::miniProgram($options);
        $en = $app->auth->session($code);    //openid  session_key
        $miniProgramUser = MiniProgram::where('open_id', $en['openid'])->first();
        if(empty($miniProgramUser)) {
            $miniProgramUser = new MiniProgram();

            $sessionToken = md5(uniqid());   //php唯一ID
            $data['open_id'] = $en['openid'];
            $data['session_key'] = $en['session_key'];
            $data['expires_in'] = $en['expires_in'];
            $data['request_time'] = time();
            $data['session_token'] = $sessionToken;

            $miniProgramUser->fill($data);
            if($miniProgramUser->save())
            {
                return $this->returnJSON(['session_token'=>$sessionToken], 0, '');
            }else
            {
                return $this->returnJSON(null, 10001, '记录登陆信息失败');
            }
        }else {
            //更新新的session_key
            $sessionToken = md5(uniqid());
            $data['session_key'] = $en['session_key'];
            $data['expires_in'] = $en['expires_in'];
            $data['request_time'] = time();
            $data['session_token'] = $sessionToken;
            $miniProgramUser->fill($data)->save();

            if(empty($miniProgramUser->user_id))
            {
                return $this->returnJSON($miniProgramUser, 0, '');
            }else{
                //已经绑定了用户id,返回用户信息
                return $this->returnJSON($miniProgramUser, 0, '');
            }
        }
    }
}
