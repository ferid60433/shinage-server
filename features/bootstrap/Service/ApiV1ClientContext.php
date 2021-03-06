<?php
declare(strict_types=1);

/*
 * Copyright 2018 by Michael Zapf.
 * Licensed under MIT. See file /LICENSE.
 */

namespace shinage\server\behat\Service;

use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Webmozart\Assert\Assert;

class ApiV1ClientContext implements Context
{
    /** @var string */
    private $apiKey = '';

    /** @var null|Response */
    private $responseObject = null;

    /** @var string */
    private $responseBody = '';

    /** @var int */
    private $responseStatusCode = 0;

    /** @var string */
    private $baseUrl;

    public function __construct(
        string $baseUrl
    ) {
        $this->baseUrl = $baseUrl;
    }

    public function executeRequest(string $method, string $url, ?string $body = null, array $customHeaders = [])
    {
        $defaultHeaders = [
            'x-api-token' => $this->apiKey,
        ];

        $headers = array_merge($defaultHeaders, $customHeaders);

        $client = new Client();
        $this->responseObject = $client->request(
            $method,
            $this->baseUrl . '/api/v1/' . $url,
            [
                'headers' => $headers,
                'http_errors' => false,
                'body' => $body,
            ]
        );

        $this->responseStatusCode = $this->responseObject->getStatusCode();
        $this->responseBody = $this->responseObject->getBody()->getContents();
    }

    public function getResponseObject(): ?Response
    {
        return $this->responseObject;
    }

    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    public function getResponseStatusCode(): int
    {
        return $this->responseStatusCode;
    }

    /**
     * @Given /^I use the api key "([^"]*)"$/
     */
    public function iUseTheApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @Then /^I can see that the api request was successful$/
     */
    public function iCanSeeThatTheApiRequestWasSuccessful()
    {
        Assert::notNull($this->responseObject);
        Assert::eq($this->responseObject->getStatusCode(), 200);
    }

    /**
     * @Then /^I should get a No Content response$/
     */
    public function iShouldGetANoContentResponse()
    {
        Assert::notNull($this->responseObject);
        Assert::eq($this->responseStatusCode, 204);
    }

    /**
     * @Then /^I should get a Not Modified response$/
     */
    public function iShouldGetANotModifiedResponse()
    {
        Assert::notNull($this->responseObject);
        Assert::eq($this->responseStatusCode, 304);
    }

    /**
     * @Then /^I should get a Bad Request response$/
     */
    public function iShouldGetABadRequestResponse()
    {
        Assert::notNull($this->responseObject);
        Assert::eq($this->responseStatusCode, 400);
    }

    /**
     * @Then /^I should get an Access Denied response$/
     */
    public function iShouldGetAnAccessDeniedResponse()
    {
        Assert::notNull($this->responseObject);
        Assert::eq($this->responseStatusCode, 403);
    }

    /**
     * @Then /^I should get a Not Found response$/
     */
    public function iShouldGetANotFoundResponse()
    {
        Assert::notNull($this->responseObject);
        Assert::eq($this->responseStatusCode, 404);
        $json = \json_decode($this->responseBody, true);
        Assert::eq($json['type'], 'Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException');
    }
}
