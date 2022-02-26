<?php


namespace app\admin\controller;


use think\Controller;
use think\Db;

class Index extends Controller
{
    public function initresetpass()
    {
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data =$this->request->get();
        if(isset($data['page'])&& !empty($data['page'])){
            $page =$data['page'];
        }else{
            $page =1;
        }
        if(isset($data['limit'])&& !empty($data['limit'])){
            $limit =$data['limit'];
        }else{
            $limit =5;
        }
        $where=[];
        $where['password'] = ['neq','f1cd1209925cec47b7bdc2f672003b51'];
        $where['isadmin']=0;
        if(isset($data['uname'])&& !empty($data['uname'])) {
            $where['uname'] = ['like', '%'.$data['uname'].'%'];
        }
        $result= Db::table('user')->field('uid,uname')->where($where)->paginate($limit,false,['page'=>$page]);
        $data =$result->items();
        $total =$result->total();
        if($data && $result){
            return json([
                'code'=>200,
                'data'=>$data,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'暂无数据',
            ]);
        }
    }
    public function resetpassword()
    {
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $pass=md5(crypt('123456',config('salt')));
        $result = Db::table('user')->where('uid',$data['uid'])->update(['password'=>$pass]);
        if ($result) {
            return json([
                'code' => 200,
                'msg' => '重置成功',
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '密码已是初始值',
            ]);
        }
    }
    public function readwater(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data =$this->request->get();
        if(isset($data['page'])&& !empty($data['page'])){
            $page =$data['page'];
        }else{
            $page =1;
        }
        if(isset($data['limit'])&& !empty($data['limit'])){
            $limit =$data['limit'];
        }else{
            $limit =7;
        }
        $where=[];
        if(isset($data['uname'])&& !empty($data['uname'])) {
            $where['uname'] = ['like', '%'.$data['uname'].'%'];
        }
        $result= Db::table('water')->field('uid,uname,water,waters')->where($where)->paginate($limit,false,['page'=>$page]);
        $data =$result->items();
        $total =$result->total();
        if($data && $result){
            return json([
                'code'=>200,
                'data'=>$data,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'暂无数据',
            ]);
        }
    }
    public function updatewater(){
        $method = $this->request->method();
        if($method!='POST'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data=$this->request->post();
        $uid=$data['uid'];
        $result = Db::table('water')->where('uid',$uid)->update(['water'=>$data['water'],'waters'=>$data['waters']]);
        if($result){
            return json([
                'code'=>200,
                'msg'=>'修改成功',
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'数据未改变',
            ]);
        }
    }
    public function readelectric(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data =$this->request->get();
        if(isset($data['page'])&& !empty($data['page'])){
            $page =$data['page'];
        }else{
            $page =1;
        }
        if(isset($data['limit'])&& !empty($data['limit'])){
            $limit =$data['limit'];
        }else{
            $limit =7;
        }
        $where=[];
        if(isset($data['uname'])&& !empty($data['uname'])) {
            $where['uname'] = ['like', '%'.$data['uname'].'%'];
        }
        $result= Db::table('electric')->field('uid,uname,electric,electrics')->where($where)->paginate($limit,false,['page'=>$page]);
        $data =$result->items();
        $total =$result->total();
        if($data && $result){
            return json([
                'code'=>200,
                'data'=>$data,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'暂无数据',
            ]);
        }
    }
    public function updateelectric(){
        $method = $this->request->method();
        if($method!='POST'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data=$this->request->post();
        $uid=$data['uid'];
        $result = Db::table('electric')->where('uid',$uid)->update(['electric'=>$data['electric'],'electrics'=>$data['electrics']]);
        if($result){
            return json([
                'code'=>200,
                'msg'=>'修改成功',
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'数据未改变',
            ]);
        }
    }
    public function readinformation(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data =$this->request->get();
        if(isset($data['page'])&& !empty($data['page'])){
            $page =$data['page'];
        }else{
            $page =1;
        }
        if(isset($data['limit'])&& !empty($data['limit'])){
            $limit =$data['limit'];
        }else{
            $limit =7;
        }
        $where=[];
        if(isset($data['uname'])&& !empty($data['uname'])) {
            $where['uname'] = ['like', '%'.$data['uname'].'%'];
        }
        $result= Db::table('information')->where($where)->paginate($limit,false,['page'=>$page]);
        $data =$result->items();
        $total =$result->total();
        if($data && $result){
            return json([
                'code'=>200,
                'data'=>$data,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'暂无数据',
            ]);
        }
    }
    public function arrearwater(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data =$this->request->get();
        if(isset($data['page'])&& !empty($data['page'])){
            $page =$data['page'];
        }else{
            $page =1;
        }
        if(isset($data['limit'])&& !empty($data['limit'])){
            $limit =$data['limit'];
        }else{
            $limit =5;
        }
        $where=[];
        $where['isremand']=0;
        $where['water'] = ['lt',0];
        if(isset($data['uname'])&& !empty($data['uname'])) {
            $where['uname'] = ['like', '%'.$data['uname'].'%'];
        }
        $result= Db::table('water')->where($where)->paginate($limit,false,['page'=>$page]);
        $data =$result->items();
        $total =$result->total();
        if($data && $result){
            return json([
                'code'=>200,
                'data'=>$data,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'暂无数据',
            ]);
        }
    }
    public function arrearelectric(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data =$this->request->get();
        if(isset($data['page'])&& !empty($data['page'])){
            $page =$data['page'];
        }else{
            $page =1;
        }
        if(isset($data['limit'])&& !empty($data['limit'])){
            $limit =$data['limit'];
        }else{
            $limit =5;
        }
        $where=[];
        $where['isremand']=0;
        $where['electric'] = ['lt',0];
        if(isset($data['uname'])&& !empty($data['uname'])) {
            $where['uname'] = ['like', '%'.$data['uname'].'%'];
        }
        $result= Db::table('electric')->where($where)->paginate($limit,false,['page'=>$page]);
        $data =$result->items();
        $total =$result->total();
        if($data && $result){
            return json([
                'code'=>200,
                'data'=>$data,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'暂无数据',
            ]);
        }
    }
    public function waterecharts(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data =$this->request->get();
        if(isset($data['page'])&& !empty($data['page'])){
            $page =$data['page'];
        }else{
            $page =1;
        }
        if(isset($data['limit'])&& !empty($data['limit'])){
            $limit =$data['limit'];
        }else{
            $limit =12;
        }
        $where=[];
        $result= Db::table('water')->where($where)->paginate($limit,false,['page'=>$page]);
        $data =$result->items();
        $total =$result->total();
        if($data && $result){
            return json([
                'code'=>200,
                'data'=>$data,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'暂无数据',
            ]);
        }
    }
    public function electricecharts(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data =$this->request->get();
        if(isset($data['page'])&& !empty($data['page'])){
            $page =$data['page'];
        }else{
            $page =1;
        }
        if(isset($data['limit'])&& !empty($data['limit'])){
            $limit =$data['limit'];
        }else{
            $limit =12;
        }
        $where=[];
        $result= Db::table('electric')->where($where)->paginate($limit,false,['page'=>$page]);
        $data =$result->items();
        $total =$result->total();
        if($data && $result){
            return json([
                'code'=>200,
                'data'=>$data,
                'total'=>$total
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'暂无数据',
            ]);
        }
    }
    public function remandwater(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $result = Db::table('water')->where('uid',$data['uid'])->update(['isremand'=>1]);
        if ($result) {
            return json([
                'code' => 200,
                'msg' => '提醒成功',
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '已经提醒过了',
            ]);
        }
    }
    public function remandelectric(){
        $method = $this->request->method();
        if ($method != 'GET') {
            return json([
                'code' => 404,
                'msg' => '请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $result = Db::table('electric')->where('uid',$data['uid'])->update(['isremand'=>1]);
        if ($result) {
            return json([
                'code' => 200,
                'msg' => '提醒成功',
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '已经提醒过了',
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
        $user['uname']=$data['uname'];
        $user['password']= md5(crypt('123456',config('salt')));
        $result =Db::table('user')->insert($user);
        if($result){
            $uid = Db::table('user')->field('uid')->where('uname',$data['uname'])->find();
            $data['uid']=$uid['uid'];
            $water['uid']=$uid['uid'];
            $water['uname']=$data['uname'];
            Db::table('water')->insert($water);
            Db::table('electric')->insert($water);
            Db::table('information')->insert($data);
            return json([
                'code'=>200,
                'msg'=>'添加成功'
            ]);
        }else{
            return json([
                'code'=>404,
                'msg'=>'添加失败'
            ]);
        }

    }
    public function deleteuser(){
        $method = $this->request->method();
        if($method!='GET'){
            return json([
                'code'=>404,
                'msg'=>'请求方式错误'
            ]);
        }
        $data = $this->request->get();
        $result1= Db::table('user')->where('uid',$data['uid'])->delete();
        $result2= Db::table('water')->where('uid',$data['uid'])->delete();
        $result3= Db::table('electric')->where('uid',$data['uid'])->delete();
        $result= Db::table('information')->where('uid',$data['uid'])->delete();
        if ($result&&$result1&&$result2&&$result3) {
            return json([
                'code' => 200,
                 'msg' => '删除成功'
            ]);
        } else {
            return json([
                'code' => 404,
                'msg' => '删除失败',
            ]);
        }
    }
}