<?php

namespace App\Services;

use App\Services\ImportContent\BibleImportService;
use App\Services\ImportContent\ImportServiceInterface;
use Intervention\Image\Exception\InvalidArgumentException;

class ImportContentOrchestratorService
{
    private array $options;


    public function __construct()
    {
        $this->options = [
            BibleImportService::IMPORTER_KEY => BibleImportService::class,
        ];
    }

    /**
     * getOptions Method.
     *
     * @return string[]
     */
    public function getOptionKeys(): array
    {
        return array_keys($this->options);
    }

    /**
     * getInstance Method.
     *
     * @param string $key
     * @return ImportServiceInterface
     */
    public function getInstance(string $key): ImportServiceInterface
    {
        if (!array_key_exists($key, $this->options)) {
            throw new InvalidArgumentException("Option {$key} not found");
        }

        $class = $this->options[$key];
        return new $class;
    }
}
