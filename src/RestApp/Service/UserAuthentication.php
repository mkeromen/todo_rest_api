<?php
namespace RestApp\Service;

use Symfony\Component\HttpKernel\Exception\ AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;

class UserAuthentication
{
	
    private $validToken;

    public function __construct($validToken)
    {
        $this->validToken = $validToken;
    }
	
    /**
     * Check if user is authorized to authenticate
     *
     * @param int $requestToken Token pass in the request
     * @return Request $request HttpFoundation request
     */
    public function authorize($requestToken, Request $request)
    {
        if ($this->validToken !== $requestToken) {
            throw new  AccessDeniedHttpException(sprintf('Token %s is invalid', $requestToken));
        }
		
        return $request;
    }
}
?>
