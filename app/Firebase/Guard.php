<?php
namespace App\Firebase;

use Firebase\Auth\Token\Verifier;
use App\PublicUser;

class Guard
{
    protected $verifier;
    public function __construct(Verifier $verifier)
    {
        $this->verifier = $verifier;
    }

    public function public_user($request)
    {
        $token = $request->bearerToken();
        try {
            $token = $this->verifier->verifyIdToken($token);
            return new PublicUser($token->getClaims());
        }
        catch (\Exception $e) {
            return;
        }
    }
}