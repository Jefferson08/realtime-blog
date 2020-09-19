<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    use MediaUploadingTrait;

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('can:edit-user,user')->only(['edit', 'update']);
    }

    public function edit(User $user){
        return view('users.edit')->with('user', $user);
    }

    public function update(Request $request, User $user){
        
        $data = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation'
        ]);

        $validation = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ];

        if($data['password']){
            $validation['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validator = Validator::make($data, $validation);

        if($validator->fails()){
            return redirect()->route('users.edit', $user)
            ->withErrors($validator);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];

        if($data['password']){
            $user->password = Hash::make($data['password']);
        }
        
        $user->save();

        if ($request->input('photo', false)) {

            if(file_exists(storage_path('tmp/uploads/' . $request->input('photo')))){

                if ($user->photo) {
                    $user->photo->delete();
                }

                $user->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))->toMediaCollection('profile');
            }

        } elseif ($user->photo) {
            $user->photo->delete();
        }

        return redirect()->route('users.edit', $user)->with('success', 'Dados alterados com sucesso!!!');

    }

    public function posts(){

        $posts = Post::where('user_id', Auth::id())->paginate(5);

        return view('posts.myposts')->with('posts', $posts);
    }
}
