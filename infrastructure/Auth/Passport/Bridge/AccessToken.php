<?php

namespace Infrastructure\Auth\Bridge;

use Laravel\Passport\Bridge\AccessToken as PassportAccessToken;
use MongoDB\Driver\Exception\RuntimeException;

class AccessToken extends PassportAccessToken {
	public function convertToJWT( CryptKey $privateKey ) {
		$user    = $this->getUser();
		$builder = ( new Builder() )
			->setAudience( $this->getClient()->getIdentifier() )
			->setId( $this->getIdentifier(), true )
			->setIssuedAt( time() )
			->setNotBefore( time() )
			->setExpiration( $this->getExpiryDateTime()->getTimestamp() )
			->setSubject( $this->getUserIdentifier() )
			->set( 'scopes', $this->getScopes() )
			->set( 'roles', $user->roles->pluck( 'name' )->toArray() );

		// sign and return the token
		return $builder->sign( new Sha256(), new Key( $privateKey->getKeyPath(), $privateKey->getPassPhrase() ) )->getToken();
	}
}