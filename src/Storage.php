<?php

namespace Gotipath\Storage;

use Exception;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\PhpseclibV2\SftpAdapter;
use League\Flysystem\PhpseclibV2\SftpConnectionProvider;
use League\Flysystem\StorageAttributes;

class Storage
{
    /**
     * Driver
     * @var string
     */
    private $driver;
    /**
     * @var array
     */
    private $config;

    private $sftpConfig = [];

    public $fileSystem;
    /**
     * @var null
     */
    private $baseUrl;

    public function __construct(string $driver, $config, $baseUrl = null)
    {
        $this->driver = $driver;

        $this->config = $config;

        if ($driver === 'sftp') {
            $adapter = new SftpAdapter(
                new SftpConnectionProvider(
                    $this->config['host'],
                    $this->config['username'],
                    $this->config['password'],
                    $this->config['privateKey'] ?? null,
                    $this->config['password'] ?? null,
                    $config['port'] ?? 22,
                ),
                $config['root'] ?? '/pub',
            );
        }

        $this->fileSystem = new Filesystem($adapter);
        $this->baseUrl = $baseUrl;
    }

    /**
     * All file list
     * @throws FilesystemException
     * @throws Exception
     */
    public function allFiles(string $path, bool $recursive = false): array
    {
        try {
            $listing = $this->fileSystem->listContents($path, $recursive);

            return $listing->filter(function (StorageAttributes $attributes) {
                return $attributes->isFile();
            })->map(function (StorageAttributes $attributes) {
                return isset($this->baseUrl) ? $this->baseUrl . '/' . $attributes->path() : $attributes->path();
            })->toArray();
        } catch (Exception $exception) {
            throw new $exception();
        } catch (FilesystemException $e) {
            throw new $e();
        }
    }

    /**
     * @throws FilesystemException
     * @throws Exception
     */
    public function allDirectories(string $path, bool $recursive = false): array
    {
        try {
            $listing = $this->fileSystem->listContents($path, $recursive);

            return $listing->filter(function (StorageAttributes $attributes) {
                return $attributes->isDir();
            })->map(function (StorageAttributes $attributes) {
                return $attributes->path();
            })->toArray();
        } catch (Exception $exception) {
            throw new $exception();
        } catch (FilesystemException $e) {
            throw new $e();
        }
    }

    public function get($path)
    {
        if ($this->exists($path) && $this->baseUrl) {
            return $this->baseUrl . '/' . $path;
        }
        if ($this->exists($path)) {
            return $path;
        }

        return null;
    }

    /**
     * make directory
     * @param string $path
     * @param array $config
     * @return bool
     * @throws FilesystemException
     */
    public function makeDirectory(string $path, array $config = []): bool
    {
        try {
            $this->fileSystem->createDirectory($path, $config);

            return true;
        } catch (FilesystemException $e) {
            throw  new $e();
        }
    }

    public function move()
    {
    }

    public function copy(string $from, string $to): bool
    {
        return true;
    }

    /**
     * @throws FilesystemException
     */
    public function exists(string $path): bool
    {
        return $this->fileSystem->fileExists($path);
    }

    /**
     * @throws FilesystemException
     */
    public function size(string $path): int
    {
        return $this->fileSystem->fileSize($path);
    }

    /**
     * @throws FilesystemException
     */
    public function lastModified($path): int
    {
        return $this->fileSystem->lastModified($path);
    }

    /**
     *
     * @throws FilesystemException
     */
    public function delete($path): bool
    {
        try {
            $this->fileSystem->delete($path);

            return true;
        } catch (Exception | FilesystemException $exception) {
            throw new  $exception();
        }
    }

    /**
     * use at your own risk
     *
     * @param $directory
     * @return bool
     * @throws FilesystemException
     */
    public function deleteDirectory($directory): bool
    {
        try {
            $this->fileSystem->deleteDirectory($directory);

            return true;
        } catch (Exception | FilesystemException $exception) {
            throw new  $exception();
        }
    }

    /**
     * @throws FilesystemException
     */
    public function put(string $path, $content): bool
    {
        try {
            $this->fileSystem->write($path, $content);

            return true;
        } catch (FilesystemException $exception) {
            throw  new  $exception();
        }
    }

    /**
     * @throws FilesystemException
     */
    public function upload($path, $tmp_name): bool
    {
        try {
            $stream = fopen($tmp_name, 'r+');

            $this->fileSystem->writeStream($path, $stream);

            if (is_resource($stream)) {
                fclose($stream);
            }

            return true;
        } catch (FilesystemException $exception) {
            throw  new $exception();
        }
    }
}
