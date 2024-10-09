<?php

namespace Edge\PowerbiEmbed\Tests;

use Edge\PowerbiEmbed\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
