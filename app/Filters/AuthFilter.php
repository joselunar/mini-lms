<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = session()->get('user_id');

        if (! $userId) {
            return redirect()->to('/login');
        }

        $user = model(UserModel::class)->find($userId);

        if ($user === null) {
            session()->destroy();

            return redirect()->to('/login');
        }

        service('currentUser')->set($user);

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
