<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 15:49
 */

namespace App\Http\Service;


use App\Models\Customer as Model;

class AuthService
{
    /**
     * 校验输入信息
     *
     * @author yeiz
     *
     * @param $request
     * @return array
     */
    public function validRegister($request)
    {
        $rules = [
            'nickname' => 'required | between:2,16 | unique:customers,nickname',
            'phone' => 'required | unique:customers,phone',
            'password' => 'required | between:6,16',
            'password_confirmation' => 'required',
        ];
        $message = [
            'nickname.required' => '昵称不能为空',
            'nickname.between' => '昵称必须是2~16个字符',
            'nickname.unique' => '昵称已存在',
            'phone.required' => '手机号不能为空',
            'phone.unique' => '手机号已存在',
            'password.required' => '密码不能为空',
            'password.between' => '密码必须是6~16个字符',
            'password_confirmation.required' => '确认密码不能为空',
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['valid'=>false,'message'=>$errors->first()];
        }else{
            return ['valid'=>true,'message'=>'success'];
        }
    }

    /**
     * 保存用户信息
     *
     * @author yezi
     * @param $nickname
     * @param $phone
     * @param $password
     * @return mixed
     */
    public function createCustomer($nickname,$phone,$password)
    {
        $salt = randomKeys(8);
        $rememberToken = randomKeys(64);

        $result = Model::create([
            Model::FIELD_NICKNAME=>$nickname,
            Model::FIELD_PASSWORD=>$this->encrypt($password,$salt),
            Model::FIELD_PHONE=>$phone,
            Model::FIELD_SALT=>$salt,
            Model::FIELD_REMEMBER_TOKEN=>$rememberToken
        ]);

        return $result;
    }

    /**
     * 加密
     *
     * @author yezi
     * @param $password
     * @param $salt
     * @return string
     */
    public function encrypt($password,$salt)
    {
        return encrypt(md5($password).$salt);
    }
}