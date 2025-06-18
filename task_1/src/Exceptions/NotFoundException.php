<?php

namespace Heliostat\Task1\Exceptions;

use Exception;
use NotFoundExceptionInterface;

class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}