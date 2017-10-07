<?php

namespace TestDummies;

class DummyAuthAdapter {
	
	protected $fakePermissionTable = [
		"brando" => ["contact.create", "contact.read"],
		"admin_boi" => ["contact.create", "contact.read", "contact.delete"],
	];

	public function isAllowed($user, $permission){

		if ( ! array_key_exists($user, $this->fakePermissionTable) ) {
			return false;
		}

		return in_array($permission, $this->fakePermissionTable[$user]);
	}

}