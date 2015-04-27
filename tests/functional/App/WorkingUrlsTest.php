<?php

namespace App;

class WorkingUrlsTest extends WebTestCase
{
    /**
     * @dataProvider getTesteableUris
     *
     * @param string $uri
     */
    public function test_uri($uri)
    {
        $client = $this->createClient();
        $client->request('GET', $uri);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function getTesteableUris()
    {
        return [['/']];
    }
}
