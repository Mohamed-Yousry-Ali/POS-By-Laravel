<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Laratrust\Models\LaratrustPermission;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:users_read'])->only('index');
        $this->middleware(['permission:users_create'])->only('create');
        $this->middleware(['permission:users_update'])->only('edit');
        $this->middleware(['permission:users_delete'])->only('destroy');
    }
    public function index(Request $request)
    {
    
       $users = User::whereRoleIs('admin')->where(function($q) use ($request){
            
        return $q->when($request->search , function($query) use ($request){
            return $query->where( 'first_name' , 'like' , '%' . $request->search . '%' )
                   ->orWhere( 'last_name' , 'like' , '%' . $request->search . '%' );
        });
    })->latest()->paginate(5);

        // $users = User::whereRoleIs('admin')->when($request->search , function($query) use ($request){
        //         return $query->where( 'first_name' , 'like' , '%' . $request->search . '%' )
        //                ->orWhere( 'last_name' , 'like' , '%' . $request->search . '%' )
        //                ->orWhere( 'email' , 'like' , '%' . $request->search . '%' );
        //     })->latest()->paginate(5);
 
        return view('dashboard.users.index',compact('users'));
        
    }
    public function create()
    {
        return view('dashboard.users.create');
    }


    public function store(Request $request)
    {
   //  dd($request->permissions);
        $messages = [
            'first_name.required' => Lang::get('site.first_name') . ' ' .Lang::get('site.required'),
            'last_name.required'  => Lang::get('site.last_name')  . ' ' .Lang::get('site.required'),
            'email.required'      => Lang::get('site.email')      . ' ' .Lang::get('site.required'),
            'image.image'         =>  Lang::get('site.image_type'),
            'password.required'   => Lang::get('site.password')   . ' ' .Lang::get('site.required'),
            'permissions.required'   => Lang::get('site.permissions')   . ' ' .Lang::get('site.required'),
        ];
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'image' => 'image',
            'password' => 'required | confirmed',
            'permissions' => 'required|min:1',
        ], $messages);

        $request_data = $request->except(['password','password_confirmation','permissions','image']);
        $request_data['password'] = bcrypt($request->password);

        if($request->image){
            Image::make($request->image)->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/users_images/' . $request->image->hashName()));
            $request_data['image']  = $request->image->hashName();
        }
        $user = User::create($request_data);

         $user->attachRole('admin');
        $user->syncPermissions($request->permissions);

        session()->flash('success',Lang::get('site.added_successfully'));
        return redirect()->route('dashboard.users.index');
    }
    public function edit(User $user)
    {
        return view('dashboard.users.edit',compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $messages = [
            'first_name.required' => Lang::get('site.first_name') . ' ' .Lang::get('site.required'),
            'last_name.required'  => Lang::get('site.last_name')  . ' ' .Lang::get('site.required'),
            'email.required'      => Lang::get('site.email')      . ' ' .Lang::get('site.required'),
            'image.image'         =>  Lang::get('site.image_type'),
            'password.required'   => Lang::get('site.password')   . ' ' .Lang::get('site.required'),
            'permissions.required'   => Lang::get('site.permissions')   . ' ' .Lang::get('site.required'),
        ];
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required',Rule::unique('users')->ignore($user->id),],
            'image' => 'image',
            'permissions' => 'required|min:1',
        ], $messages);

        $request_data = $request->except(['permissions','image']);
        if($request->image){
            if($user->image != 'default.png'){
                Storage::disk('public_uploads')->delete('/users_images/' . $user->image);
            }
            Image::make($request->image)->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/users_images/' . $request->image->hashName()));
            $request_data['image']  = $request->image->hashName();
        }


        $user->update($request_data);

        $user->syncPermissions($request->permissions);

        session()->flash('success',Lang::get('site.updated_successfully'));
        return redirect()->route('dashboard.users.index');
    }
    public function destroy(User $user)
    {
        if($user->image != 'default.png'){
            Storage::disk('public_uploads')->delete('/users_images/' . $user->image);
        }
        $user->delete();
        session()->flash('success',Lang::get('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');
    }
}
