<?php
/**
 * @category     Tests
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

namespace Helper;

use Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Dogs extends Module
{
    /**
     * Assert that a header string value contains the given sub-string.
     *
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    public function seeHeaderContains(string $needle, string $haystack, string $message)
    {
        $this->assertContains($needle, $haystack, $message);
    }
}
