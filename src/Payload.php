<?php

namespace Tymon\JWTAuth;

use ArrayAccess;
use Tymon\JWTAuth\Exceptions\PayloadException;
use Tymon\JWTAuth\Claims\ClaimInterface;

class Payload implements ArrayAccess
{

    /**
     * The array of claims
     *
     * @var array \Tymon\JWTAuth\Claims\ClaimInterface[]
     */
	private $claims = [];

    /**
     * Build the Payload
     *
     * @param array  $claims
     */
	public function __construct(array $claims)
	{
		$this->claims = $claims;
	}

    /**
     * Get the array of claims
     *
     * @return array
     */
    public function getClaims()
    {
        return array_map([$this, 'getClaimArray'], $this->claims);
    }

    /**
     * Get the array representation of the claim
     *
     * @param  \Tymon\JWTAuth\Claims\ClaimInterface  $claim
     * @return array
     */
    protected function getClaimArray(ClaimInterface $claim)
    {
        return $claim->toArray();
    }

    /**
     * Get the payload
     *
     * @param  string  $claim
     * @return array
     */
    public function get($claim = null)
    {
        if (! is_null($claim)) {

            if (is_array($claim)) {
                return array_map([$this, 'get'], $claim);
            }

            return $this->getClaims()[$claim];
        }

        return $this->getClaims();
    }

    /**
     * Get the payload as a string
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getClaims());
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->getClaims());
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->getClaims()[$key];
    }

    /**
     * Don't allow changing the payload as it should be immutable
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        throw new PayloadException('You cannot change the payload');
    }

    /**
     * Don't allow changing the payload as it should be immutable
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        throw new PayloadException('You cannot change the payload');
    }

}