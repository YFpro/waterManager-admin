<?php


namespace app\user\controller;


use think\Controller;
use think\Db;
use think\JWT;

class Login extends Controller
{
    public function check(){
        $method = $this->request->method();
        if($method!='POST'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data=$this->request->post();
        $whereArr=['uname'=>$data['uname']];
        $user=Db::table('user')->where($whereArr)->find();
        if($user){
            $password= md5(crypt($data['password'],config('salt')));
            if($password === $user['password']){
                $payload = [
                    'uid'=>$user['uid'],
                    'uname' =>$user['uname'],
                ];
                $token = JWT::getToken($payload, config('jwtkey'));
                $avatar = Db::table('information')->field('avatar')->where('uid', $user['uid'])->find();
                return json([
                    'code'=>200,
                    'msg'=>'登录成功',
                    'token'=>$token,
                    'uid'=>$user['uid'],
                    'avatar'=>$avatar,
                    'isadmin'=>$user['isadmin']
                ]);
            }else{
                return json([
                    'code'=>404,
                    'msg'=>'密码错误'
                ]);
            }
        }else{
            return json([
                'code'=>404,
                'msg'=>'该用户不存在'
            ]);
        }
    }
    public function updatepass(){
        $method = $this->request->method();
        if($method!='POST'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data=$this->request->post();
        $uid=$data['uid'];
        $oldpass=md5(crypt($data['oldpass'],config('salt')));
        $newpass=md5(crypt($data['newpass'],config('salt')));
        $result=Db::table('user')->field('password')->where('uid',$uid)->find();
        $password=$result['password'];
        if($password != $oldpass){
            return json([
                'code'=>404,
                'msg'=>'原密码错误'
            ]);
        }
        $result = Db::table('user')->where('uid',$uid)->update(['password'=>$newpass]);
        if($result){
            return json([
                'code'=>200,
                'msg'=>'密码修改成功',
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'密码修改失败',
            ]);
        }
    }
    public function adduser(){
        $method = $this->request->method();
        if($method!='POST'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data=$this->request->post();
        $uname=$data['uname'];
        $isExist= Db::table('user')->where('uname',$uname)->count();
        if($isExist){
            return json([
                'code'=>404,
                'msg'=>'该用户已经存在'
            ]);
        }
        unset($data['aginpassword']);
        $data['password'] = md5(crypt($data['password'],config('salt')));
        $result =Db::table('user')->insert($data);
        if($result){
            $uid = Db::table('user')->field('uid,uname')->where('uname',$data['uname'])->find();
            Db::table('water')->insert($uid);
            Db::table('electric')->insert($uid);
            Db::table('information')->insert($uid);
            return json([
                'code'=>200,
                'msg'=>'注册成功'
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'注册失败'
            ]);
        }
    }
    public function readwater(){
        $method = $this->request->method();
        if($method!='GET'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $category = Db::table('water')->where('uid', $data['uid'])->select();
        if ($category) {
            return json([
                'code' => 200,
                'data' =>$category
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '查询失败',
            ]);
        }
    }
    public function readelectric(){
        $method = $this->request->method();
        if($method!='GET'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $category = Db::table('electric')->where('uid', $data['uid'])->select();
        if ($category) {
            return json([
                'code' => 200,
                'data' =>$category
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '查询失败',
            ]);
        }
    }
    public function readinformation(){
        $method = $this->request->method();
        if($method!='GET'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $category = Db::table('information')->where('uid', $data['uid'])->select();
        $water = Db::table('water')->field('waters')->where('uid', $data['uid'])->select();
        $electric = Db::table('electric')->field('electrics')->where('uid', $data['uid'])->select();
        if ($category) {
            return json([
                'code' => 200,
                'data' =>$category,
                'water'=>$water,
                'electric'=>$electric
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '暂无数据',
            ]);
        }
    }
    public function updateinformation(){
        $method = $this->request->method();
        if($method!='POST'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data=$this->request->post();
        $uid=$data['uid'];
        $uname=$data['uname'];
        unset($data['uid']);
        $result = Db::table('information')->where('uid',$uid)->update($data);
        Db::table('user')->where('uid',$uid)->update(['uname'=>$uname]);
        Db::table('water')->where('uid',$uid)->update(['uname'=>$uname]);
        Db::table('electric')->where('uid',$uid)->update(['uname'=>$uname]);
        if($result){
            return json([
                'code'=>200,
                'msg'=>'修改成功',
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'信息无改动',
            ]);
        }
    }
    public function paywater(){
        $method = $this->request->method();
        if($method!='POST'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data=$this->request->post();
        $uid=$data['uid'];
        $water=Db::table('water')->field('water')->where('uid',$uid)->find();
        $paywater = $data['num'] + $water['water'];
        $result = Db::table('water')->where('uid',$uid)->update(['water'=>$paywater]);
        if($result){
            return json([
                'code'=>200,
                'msg'=>'缴费成功',
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'缴费失败',
            ]);
        }
    }
    public function payelectric(){
        $method = $this->request->method();
        if($method!='POST'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data=$this->request->post();
        $uid=$data['uid'];
        $electric=Db::table('electric')->field('electric')->where('uid',$uid)->find();
        $payelectric = $data['num'] + $electric['electric'];
        $result = Db::table('electric')->where('uid',$uid)->update(['electric'=>$payelectric]);
        if($result){
            return json([
                'code'=>200,
                'msg'=>'缴费成功',
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'缴费失败',
            ]);
        }
    }
    public function remandwater(){
        $method = $this->request->method();
        if($method!='GET'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $category = Db::table('water')->field('isremand')->where('uid', $data['uid'])->find();
        if ($category) {
            return json([
                'code' => 200,
                'data' =>$category
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '失败',
            ]);
        }
    }
    public function remandelectric(){
        $method = $this->request->method();
        if($method!='GET'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $category = Db::table('electric')->field('isremand')->where('uid', $data['uid'])->find();
        if ($category) {
            return json([
                'code' => 200,
                'data' =>$category
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '失败',
            ]);
        }
    }
    public function updateremwater(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $result = Db::table('water')->where('uid',$data['uid'])->update(['isremand'=>0]);
        if ($result) {
            return json([
                'code' => 200,
                'msg' => '收到提醒',
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '已确认收到',
            ]);
        }
    }
    public function updateremelectric(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $result = Db::table('electric')->where('uid',$data['uid'])->update(['isremand'=>0]);
        if ($result) {
            return json([
                'code' => 200,
                'msg' => '收到提醒',
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '已确认收到',
            ]);
        }
    }
    public function avatar(){
        $file =$this->request->file('file');
        if($file){
            $info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $imgpath=date('Ymd').'/'.$info->getFilename();
                return json([
                    'code'=>200,
                    'msg'=>'图片上传成功',
                    'imgpath'=>'/water-admin/public/uploads/'.$imgpath
                ]);
            }else{
                return json([
                    'code'=>404,
                    'msg'=>$file->getError(),
                ]);

            }
        }else{
            return json([
                'code'=>404,
                'msg'=>'上传文件不能为空'
            ]);
        }
    }
}