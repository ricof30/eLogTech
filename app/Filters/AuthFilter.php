<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');
        $currentURL = current_url();
        $baseURL = base_url();

        // Check if the user is logged in
        if (!session()->get('is_logged_in')) {
            if ($currentURL !== $baseURL && $currentURL !== rtrim($baseURL, '/') . '/') {
                return redirect()->to('/signin')->with('error', 'You must be logged in to access this page.');
            }
            if ($currentURL == $baseURL && $currentURL == rtrim($baseURL, '/') . '/') {
                return redirect()->to('/signin');
            }
        }

        // Restrict pages based on user role
        if ($role === 'user') {
            // Allow the user to access only these pages
            $allowedPagesForUser = [
                // base_url('/'),
                base_url('/'),
                base_url('/alertHistory'),  
                base_url('/logout'),  
            ];

            // If the current page is not allowed for users, redirect to the home page
            if (!in_array($currentURL, $allowedPagesForUser)) {
                return redirect()->to('/');
            }
        }

        if ($role === 'admin') {
            // Allow the user to access only these pages
            $allowedPagesForUser = [
                // base_url('/'),
                base_url('/dashboard'),
                base_url('/alertHistory'),
                base_url('/contact'),
                base_url('/sentMessage'),
                base_url('/status'),
                base_url('/user'),  
                base_url('/logout'),  
            ];

            // If the current page is not allowed for users, redirect to the home page
            if (!in_array($currentURL, $allowedPagesForUser)) {
                return redirect()->to('/dashboard');
            }
        }
    }


    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}   
