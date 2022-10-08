<?php

namespace App\Services\ImportContent;

interface ImportServiceInterface
{
    public function execute();

    public function getMessages();
}
