<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Hash;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RegisterAccountResolver
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        event(new Registerd($account = $this->create($args)));

        $authManager = app(AuthManager::class);

        $guard = $authManager->guard('api');
        $token = $guard->login($account);

        return [
            'account' => $account,
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $guard->factory()->getTTL() * 60,
            ],
        ];
    }

    /**
     * @param array $data
     * @return \App\Models\Account
     */
    protected function create(array $data)
    {
        return Account::create([
            'name' => $data['name'],
            'twitter_id' => $data['twitter_id'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'logged_in_at' => Carbon::now(),
            'signed_up_at' => Carbon::now(),
        ]);
    }
}
