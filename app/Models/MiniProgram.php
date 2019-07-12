<?php
/**
 * Created by PhpStorm.
 * User: icon
 * Date: 2019/7/11
 * Time: 下午4:06
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MiniProgram extends Model {
    protected $table = 'mini_program_user';
    protected $dates = [''];

    protected $guarded = ['id', 'created_at'];
}