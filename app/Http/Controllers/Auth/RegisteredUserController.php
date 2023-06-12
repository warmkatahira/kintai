<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Base;
use App\Http\Requests\UserCreateRequest;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // 全ての拠点を取得
        $bases = Base::getAll()->get();
        return view('auth.register')->with([
            'bases' => $bases,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserCreateRequest $request): RedirectResponse
    {

        $user = User::create([
            'base_id' => $request->base_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_id' => $request->user_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
        ]);

        event(new Registered($user));

        // 自動ログインさせない
        //Auth::login($user);
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'ユーザー登録が完了しました。承認されるまでお待ち下さい。',
        ]);
        //return redirect(RouteServiceProvider::HOME);
    }
}
