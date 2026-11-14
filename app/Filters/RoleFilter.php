<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $requiredRole = $arguments[0] ?? null;

        if ($requiredRole === null || session()->get('user_role') !== $requiredRole) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
