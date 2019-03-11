<?php

namespace App\Http\Controllers;

use App\User;
use foo\bar;
use Illuminate\Http\Request;
use function PHPSTORM_META\elementType;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        $data = [];
//        $users = User::all()->paginate(10);
        $users = User::where('rejected', 'off')->orderBy('created_at', 'desc')->paginate(10);
        $data['users'] = $users;
        $url = $_SERVER['REQUEST_URI'];
        $data['url'] = $url;
        $page = $users->currentPage();

        $data['view'] = '';

        // get user
        $user = auth()->user();

        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }

        $data['user'] = $user;


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            foreach ($received_inquiry_rows as $row) {
                if ($row->inquiry_id == $received_inquiry_id['inquiry_id']) {
                    $inquiry['received_id'] = $row->id;
                }

            }
            array_push($received_inquiries, $inquiry);
        }

        $data['received_inquiries'] = $received_inquiries;

        if (auth()->user()->admin == 'on' || auth()->user()->super_admin == 'on') {
            return view('users.index-list', $data);
        } else {
            return redirect('/dashboard');
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexNew()
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        $data = [];
        $matchThese = ['approved' => 'off', 'admin' => 'off', 'rejected' => 'off'];
        $users = User::where($matchThese)->orderBy('created_at', 'desc')->paginate(10);
        $data['users'] = $users;
        $url = $_SERVER['REQUEST_URI'];
        $data['url'] = $url;
        $page = $users->currentPage();

        $data['view'] = '';

        // get user
        $user = auth()->user();

        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }

        $data['user'] = $user;


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            foreach ($received_inquiry_rows as $row) {
                if ($row->inquiry_id == $received_inquiry_id['inquiry_id']) {
                    $inquiry['received_id'] = $row->id;
                }

            }
            array_push($received_inquiries, $inquiry);
        }

        $data['received_inquiries'] = $received_inquiries;

        if (auth()->user()->admin == 'on' || auth()->user()->super_admin == 'on') {
            return view('users.new.index', $data);
        } else {
            return redirect('/dashboard');
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRejected()
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        $data = [];
//        $users = User::all()->paginate(10);
        $users = User::where('rejected', 'on')->orderBy('created_at', 'desc')->paginate(10);
        $data['users'] = $users;
        $url = $_SERVER['REQUEST_URI'];
        $data['url'] = $url;
        $page = $users->currentPage();

        $data['view'] = '';

        // get user
        $user = auth()->user();

        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }

        $data['user'] = $user;


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            foreach ($received_inquiry_rows as $row) {
                if ($row->inquiry_id == $received_inquiry_id['inquiry_id']) {
                    $inquiry['received_id'] = $row->id;
                }

            }
            array_push($received_inquiries, $inquiry);
        }

        $data['received_inquiries'] = $received_inquiries;

        if (auth()->user()->admin == 'on' || auth()->user()->super_admin == 'on') {
            return view('users.rejected.index', $data);
        } else {
            return redirect('/dashboard');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => ['required', 'min:8'],
            'license' => 'required',
            'agreed' => 'accepted',
        ]);

        if (request('avatar') == '') {
            $avatar = '';
        } else {
            $avatar = "'avatar',";
        }
            User::create(request([
                'first_name',
                'last_name',
                'email',
                'password',
                'license',
                'company_name',
                'company_website',
                'main_market',
                'phone_number',
                'agreed',
                $avatar
            ]));

        return redirect('/users');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on' || $user->id != auth()->id(), 403 );
        $data = [];
        $data['user'] = $user;


        return view('users.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on' || $user->id != auth()->id(), 403 );
        $data = [];

        $data['view'] = '';


        $data['user'] = auth()->user();

        $data['update'] = $user;


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            foreach ($received_inquiry_rows as $row) {
                if ($row->inquiry_id == $received_inquiry_id['inquiry_id']) {
                    $inquiry['received_id'] = $row->id;
                }

            }
            array_push($received_inquiries, $inquiry);
        }

        $data['received_inquiries'] = $received_inquiries;

        if (auth()->user()->admin == 'on' || auth()->user()->super_admin == 'on') {
            return view('users.edit', $data);
        } else {
            return redirect('/dashboard');
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $approve_reject = false;
        if (request()->has('approved-btn')) {
            $user->update(request([
                'approved'
            ]));
            $user->update([
                'rejected' => 'off',
            ]);
            session()->flash('success', 'User approved');
            $approve_reject = true;

        } elseif (request()->has('rejected-btn')){
            $user->update(request([
                'rejected'
            ]));
            $user->update([
                'approved' => 'off',
            ]);
            session()->flash('success', 'User rejected');
            $approve_reject = true;

        } elseif (request()->has('notifications')){
            $user->update(request([
                'notifications'
            ]));
            session()->flash('success', 'Email Notifications Setting Updated');
            return back();
        }
        else {
            request()->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'license' => 'required',
            ]);

            if (request('avatar') == '') {
                // no avatar was uploaded, use default
                $avatar = '';
            } else {
                // avatar was uploaded
                // update path to users avatar and return

                $file = request()->avatar;

                if (request()->hasFile('avatar')) {
                    // validate the file
                    request()->validate([
                        'avatar' => 'image|max:1024|dimensions:ratio=1/1',
                    ]);
                    $directory = '';
                    if (auth()->user()->admin == 'on' || auth()->user()->super_admin == true && $user->id != auth()->id()) {
                        $directory = $user->id;
                    } else {
                        $directory = auth()->id();
                    }
                    $filename = $file->hashName();
                    $file->storeAs('/public/attachments/' . $directory, $filename);
                    $path = url('storage/attachments/' . $directory . '/' . $filename);
                    $avatar = $path;
                } else {
                    $avatar = '';
                }
                $user->avatar = $avatar;

                $user->save();

                if ($approve_reject != true) {
                    session()->flash('success', 'User Profile Updated!');
                }


                return back();

            }

            $user->update(request([
                'first_name',
                'last_name',
                'email',
                'license',
                'company_name',
                'company_website',
                'main_market',
                'phone_number',
                'admin',
                'approved',
            ]));


        }
        if ($approve_reject != true) {
            session()->flash('success', 'User Profile Updated!');
        }

        if ($user->admin == true && $user->approved == 'off') {
            $user->approved = 'on';
            $user->save();
        }


        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $logged_user = auth()->user();
        if ($user->admin == 'on'  && $user->super_admin != 'on') {
            session()->flash('error', "Admins can't be deleted. If you are sure, turn admin off, update, then delete this user");
            return back();
        }

        if ($user->super_admin == 'on') {
            session()->flash('error', "Super Admins can not be deleted");
            return back();
        }
        $user->delete();

        if (request()->has('delete-btn')) {
            return redirect('rejected/users');
        }

        return redirect('/users');
    }
}
