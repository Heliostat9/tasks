<?php

namespace Heliostat\Task1;

interface ContainerInterface {
    public function get($id);
    public function has($id);
}