<?php

namespace App\Services\ContentImporters;

interface ImportServiceInterface
{
    public function execute();

    public function getMessages();
}
