<?php

namespace Core\StorageProvider;

use Aws\S3\S3Client;
use Core\Exception\MissingParamsException;
use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WyriHaximus\SliFly\FlysystemServiceProvider;

/**
 * Storage class to manage Storage provider from FlySystem
 *
 * Class StorageProvider
 * @package Core\Provider
 */
class SkydreamsStorageProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     *
     * @return string
     * @throws MissingParamsException
     */
    public function register(Container $app)
    {
        $params = $app['params']['skydreams'];

        $skyDreamsClient = new Client([
            'base_uri' => $params['host']
        ]);

        $app->register(
            new FlysystemServiceProvider(),
            [
                'flysystem.filesystems' => [
                    'upload_dir' => [
                        'adapter' => 'Adapter\SkyDreamsAdapter',
                        'args' => [
                            $skyDreamsClient,
                            'flyimg'
                        ],
                    ],
                ],
            ]
        );

        $app['flysystems']['file_path_resolver'] = function () {
            return 'NOT_YET_IMPLEMENTED'; // TODO this might prove difficult for a flexible solution but is unused for now
        };

        return;
    }
}
