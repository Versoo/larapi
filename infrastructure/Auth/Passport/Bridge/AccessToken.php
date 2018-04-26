<?php

namespace Infrastructure\Auth\Bridge;

use Laravel\Passport\Bridge\AccessToken as PassportAccessToken;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;

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
			->set( 'scopes', $this->getScopes() );
			/**
			 * Adding custom "claims" for AccessToken
			 */
			//->set( 'custom_claim_name', 'custom_claim_value' );
		return $builder->sign( new Sha256(), new Key( $privateKey->getKeyPath(), $privateKey->getPassPhrase() ) )->getToken();
	}
}