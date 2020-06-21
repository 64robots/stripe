<?php

namespace R64\Stripe\Mocks;

use Mockery as m;
use Faker\Factory;
use R64\Stripe\Adapters\Token;
use Stripe\Token as StripeToken;

trait TokenMock
{
    public function getToken(string $id, $noWrapper = false)
    {
        $stripeToken = $this->getMockStripeToken('get-token', ['id' => $id]);
        $token = $stripeToken::retrieve($id, $this->stripeConnectParam());

        m::close();
        
        if ($token) {
            return $noWrapper ? $token : new Token($token);
        }
    }

    private function getMockStripeToken(string $slug, $params = [])
    {
        $token = m::mock('alias:StripeToken');

        switch ($slug) {
            case 'get-token':
                $token
                    ->shouldReceive('retrieve')
                    ->with($params['id'], $this->stripeConnectParam())
                    ->andReturn($this->getStripeToken());

                break;
        }

        $this->successful = true;

        return $token;
    }

    private function getStripeToken()
    {
        $token = new StripeToken(['id' => 'tok_1234']);

        $faker = Factory::create();
        $token->object = 'token';
        $token->type = 'card';
        $token->created = time();
        $token->card = $this->getStripeCard();

        return $token;
    }
}
