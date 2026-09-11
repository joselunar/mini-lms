<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! service('currentUser')->isAdmin()) {
            return service('response')->setStatusCode(403)->setJSON([
                'status'  => 403,
                'error'   => 'forbidden',
                'message' => 'Apenas administradores podem executar esta ação.',
            ]);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
