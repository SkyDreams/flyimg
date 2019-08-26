<?php

namespace Core\StorageAdapter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use Symfony\Component\HttpFoundation\Response;

class SkyDreamsAdapter extends AbstractAdapter
{

    protected $client;
    protected $bucket;

    public function __construct(Client $client, $bucket)
    {
        $this->client = $client;
        $this->bucket = $bucket;
    }

    /**
     * @inheritdoc
     */
    public function has($path)
    {
        $response = $this->client->get("has/$this->bucket/$path.json");
        $jsonDecode = json_decode((string)$response->getBody(), true);

        return $jsonDecode['result'];
    }

    /**
     * @inheritdoc
     */
    public function read($path)
    {
        try {
            $response = $this->client->get("get/$this->bucket/$path.json");
        } catch (ClientException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === Response::HTTP_NOT_FOUND) {
                return false;
            }
        }

        $jsonDecode = json_decode((string)$response->getBody(), true);

        return [
            'contents' => base64_decode($jsonDecode['data'])
        ];
    }

    /**
     * @inheritdoc
     */
    public function delete($path)
    {
        $response = $this->client->put(
            'put.json',
            [
                'json' => [
                    'filename' => "$this->bucket/$path",
                ],
            ]
        );

        $data = json_decode((string)$response->getBody(), true);

        return $data['result'] === 'success';
    }

    /**
     * @inheritdoc
     */
    public function write($path, $contents, Config $config)
    {
        $response = $this->client->put(
            'put.json',
            [
                'json' => [
                    'filename'      => "$this->bucket/$path",
                    'data'          => base64_encode($contents),
                    'forceFilename' => true,
                ],
            ]
        );

        $data = json_decode((string)$response->getBody(), true);

        return $data['result'] === 'success' ? [
            'path'     => $data['filename'],
            'type'     => 'file',
            'contents' => $contents,
        ] : false;
    }

    /**
     * @inheritdoc
     */
    public function readStream($path)
    {
        // TODO: Implement readStream() method.
    }

    /**
     * @inheritdoc
     */
    public function listContents($directory = '', $recursive = false)
    {
        // TODO: Implement listContents() method.
    }

    /**
     * @inheritdoc
     */
    public function getMetadata($path)
    {
        // TODO: Implement getMetadata() method.
    }

    /**
     * @inheritdoc
     */
    public function getSize($path)
    {
        // TODO: Implement getSize() method.
    }

    /**
     * @inheritdoc
     */
    public function getMimetype($path)
    {
        // TODO: Implement getMimetype() method.
    }

    /**
     * @inheritdoc
     */
    public function getTimestamp($path)
    {
        // TODO: Implement getTimestamp() method.
    }

    /**
     * @inheritdoc
     */
    public function getVisibility($path)
    {
        // TODO: Implement getVisibility() method.
    }

    /**
     * @inheritdoc
     */
    public function writeStream($path, $resource, Config $config)
    {
        // TODO: Implement writeStream() method.
    }

    /**
     * @inheritdoc
     */
    public function update($path, $contents, Config $config)
    {
        // TODO: Implement update() method.
    }

    /**
     * @inheritdoc
     */
    public function updateStream($path, $resource, Config $config)
    {
        // TODO: Implement updateStream() method.
    }

    /**
     * @inheritdoc
     */
    public function rename($path, $newpath)
    {
        // TODO: Implement rename() method.
    }

    /**
     * @inheritdoc
     */
    public function copy($path, $newpath)
    {
        // TODO: Implement copy() method.
    }

    /**
     * @inheritdoc
     */
    public function deleteDir($dirname)
    {
        // TODO: Implement deleteDir() method.
    }

    /**
     * @inheritdoc
     */
    public function createDir($dirname, Config $config)
    {
        // TODO: Implement createDir() method.
    }

    /**
     * @inheritdoc
     */
    public function setVisibility($path, $visibility)
    {
        // TODO: Implement setVisibility() method.
    }
}
