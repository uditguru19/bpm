<?php
namespace ProcessMaker\Http\Controllers\Management;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Model\User;

class UsersController extends Controller
{

    /**
     * Get the list of users.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View
     */
    public function index()
    {
        $users = User::paginate(25);
        return view('management.users.index');
    }
}
