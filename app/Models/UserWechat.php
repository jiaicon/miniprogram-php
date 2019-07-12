<?php
/**
 * Created by PhpStorm.
 * User: icon
 * Date: 2019/7/11
 * Time: 下午2:51
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UserWechat extends Model
{
    //
    protected  $table = 'user_wechat';
    protected  $guarded = ['created_at'];
    public function loadModel($openid)
    {
        return $this->where('openid', $openid)->first();
    }
}