<?php

namespace Application\Domain\User;

use Spot\Mapper as SpotMapper;

/**
 * Class Mapper
 * @package Application\Domain\User
 */
class Mapper extends SpotMapper
{
    /**
     * @param string $username
     * @param string $password
     * @return Entity|null
     */
    public function getUsingCredentials(string $username, string $password): ?Entity
    {
        $user = $this->where(compact('username'))->first();

        if (!$user instanceof Entity) {
            return null;
        }

        if (!password_verify($password, $user->get('password'))) {
            return null;
        }

        $info = password_get_info($user->get(('password')));

        if (password_needs_rehash($user->get('password'), $info['algo'])) {
            $hash = password_hash($password, $info['algo']);

            if ($hash !== false) {
                $user->set('password', $hash);

                return $this->update($user);
            }
        }

        return $user;
    }
}