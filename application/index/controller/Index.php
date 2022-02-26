<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $yf = Db::table('user')->select();
        return json([
            $yf
        ]);
    }
}
