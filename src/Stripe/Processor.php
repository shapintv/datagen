<?php

declare(strict_types=1);

namespace Shapin\Datagen\Stripe;

use Shapin\Datagen\FixtureInterface;
use Shapin\Datagen\ProcessorInterface;
use Shapin\Datagen\Stripe\Exception\UnknownObjectException;
use Shapin\Stripe\Api\HttpApi;
use Shapin\Stripe\Exception\Domain\ResourceAlreadyExistsException;
use Shapin\Stripe\StripeClient;

class Processor implements ProcessorInterface
{
    private $stripeClient;

    public function __construct(StripeClient $stripeClient)
    {
        $this->stripeClient = $stripeClient;
    }

    /**
     * {@inheritdoc}
     */
    public function process(FixtureInterface $fixture, array $options = []): void
    {
        $api = $this->getApi($fixture);

        foreach ($fixture->getObjects() as $object) {
            try {
                $api->create($object);
            } catch (ResourceAlreadyExistsException $e) {
                // Doing nothing for now
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flush(array $options = []): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'stripe';
    }

    private function getApi(FixtureInterface $fixture): HttpApi
    {
        switch ($fixture->getObjectName()) {
            case 'product':
                return $this->stripeClient->products();

            default:
                throw new UnknownObjectException($fixture->getObjectName());
        }
    }
}
