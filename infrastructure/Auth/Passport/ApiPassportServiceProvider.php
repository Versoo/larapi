<?php

namespace Infrastructure\Auth;

use App;
use Infrastructure\Auth\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use Laravel\Passport\PassportServiceProvider;
use League\OAuth2\Server\AuthorizationServer;

class ApiPassportServiceProvider extends PassportServiceProvider {
	/**
	 * Bootstrap the application services.
	 */
	public function boot() {
		parent::boot();
	}

	/**
	 * Make the authorization service instance.
	 *
	 * @return AuthorizationServer
	 */
	public function makeAuthorizationServer() {
		return new AuthorizationServer(
			$this->app->make( ClientRepository::class ),
			$this->app->make( AccessTokenRepository::class ), // Overwrite default AccessTokenRepository -> \Infrastructure\Auth\Passport\Bridge\AccessTokenRepository::class
			$this->app->make( ScopeRepository::class ),
			$this->makeCryptKey( 'oauth-private.key' ),
			app( 'encrypter' )->getKey()
		);
	}
}