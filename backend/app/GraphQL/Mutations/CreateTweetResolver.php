<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Timeline;
use App\Models\Tweet;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateTweetResolver
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
        $account = auth()->user();

        $tweet = $this->createTweet($account, $args);

        $this->addTweetToTimeline($account, $tweet);

        return $tweet;
    }

    protected function createTweet(Account $account, array $data)
    {
        return Tweet::create([
            'account_id' => $account->id,
            'content' => $data['content'],
            'tweeted_at' => Carbon::now(),
        ]);
    }

    protected function addTweetToTimeline(Account $account, Tweet $tweet)
    {
        return Timeline::create([
            'account_id' => $account->id,
            'tweet_id' => $tweet->id,
        ]);
    }
}
