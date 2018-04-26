<?php

namespace Illuminate\Foundation\Auth;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SendrecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        //在注册前验证验证码信息
        $checkNumber = $request->check_number;
        $checkName = $request->check_name;
        $email = $request->email;
        $numberDb = SendrecordController::getRecord($checkName);
        if($numberDb->number != $checkNumber || $email != $numberDb->email){
            $requestInfo = $request->all();
            $requestInfo["recodId"] = $numberDb->id;
            $requestInfo["error"] = "验证码错误~!";
            return Redirect::back()->withInput($requestInfo);
        }

        SendrecordController::setStatus($checkName);

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->all()));
        return redirect($this->redirectPath());
    }
}
