<?php
namespace RestApp\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConverter
{
	public function authorize($token)
    {
        if ('007' !== $token) {
            throw new NotFoundHttpException(sprintf('Token %s is invalid', $token));
        }
    }
}
?>
