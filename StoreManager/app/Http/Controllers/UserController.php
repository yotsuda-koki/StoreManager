<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * indexビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    /**
     * createビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * ユーザーを登録
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|integer',
            ]);

            DB::transaction(function () use ($request) {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->role = $request->role;
                $user->save();
            });

            return redirect()->route('user.index')->with('msg', __('message.add'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', __('error.occurred'));
        }
    }

    /**
     * editビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        $user = User::find($request->id);
        return view('user.edit', compact('user'));
    }

    /**
     * ユーザーの情報を変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'role' => 'required|integer',
            ]);

            DB::transaction(function () use ($request) {
                User::find($request->id)->update([
                    'name' => $request->name,
                    'role' => $request->role,
                ]);
            });

            return redirect()->route('user.index')->with('msg', 'message.edit');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', 'error.occurred');
        }
    }

    /**
     * parsonalビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function parsonal(Request $request)
    {
        $user = Auth::user();
        return view('user.parsonal', compact('user'));
    }

    /**
     * 個人のメールアドレスとパスワードを変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function parsonalUpdate(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email,{$request->id}',
                'password' => 'required|string|min:8|confirmed',
            ]);

            User::find($request->id)->update([
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return redirect()->route('user.index')->with('msg', 'message.edit');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', 'error.occurred');
        }
    }

    /**
     * ユーザーを削除
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        try {
            User::find($request->id)->delete();
            return redirect()->route('user.index')->with('msg', 'message.delete');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', 'error.occurred');
        }
    }
}
