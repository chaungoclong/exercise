<?php

namespace App\Services\User;


use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Get User Datatables
     *
     * @return void
     */
    public function userDatatables()
    {
        $model = $this->userModel->with('role');

        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('card', function ($user) {
                return '
                    <div class="card col-3">
                        <div class="card-body">
                            <h1>' . $user->email . '</h1>
                        </div>
                    </div>
                ';
            })
            ->addColumn('fullname', function ($user) {
                return  '<div class="d-flex justify-content-left align-items-center"><div class="avatar-wrapper"><div class="avatar  me-1"><img src="' .
                    ($user->avatar ?? '') .
                    '" alt="Avatar" height="32" width="32"></div></div><div class="d-flex flex-column"><a href="http://127.0.0.1:8000/app/user/view/account" class="user_name text-body text-truncate"><span class="fw-bolder">' .
                    $user->full_name
                    . '</span></a><small class="emp_post text-muted">' . $user->email . '</small></div></div>';
            })
            ->filter(function ($query) {
                if (request()->has('role_id')) {
                    $query->where('role_id', request('role_id'));
                }

                if (request()->has('key')) {
                    // dd(request()->all());
                    $key = request('key');

                    $query
                        ->where('first_name', 'LIKE', "%$key%")
                        ->orWhere('last_name', 'LIKE', "%$key)%")
                        ->orWhere('email', 'LIKE', "%$key%");
                }

                if (request()->has('sort')) {
                    switch (request('sort')) {
                        case '2':
                            $query->orderBy('created_at', 'desc');
                        case '3':
                            $query->orderBy('first_name', 'asc');
                        case '4':
                            $query->orderBy('first_name', 'desc');
                        case '1':
                        default:
                            $query->orderBy('created_at', 'asc');
                            break;
                    }
                }
            }, true)
            ->rawColumns(['fullname', 'card'])
            ->make(true);
    }
}
