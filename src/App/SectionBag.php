<?php

namespace App;

/**
 * Class SectionBag.
 */
class SectionBag
{
    private $sections;

    public function registerSection($sectionName, $isSectionEnabled)
    {
        $this->sections[$sectionName] = $isSectionEnabled;
    }

    public function isSectionEnabled($sectionName, $default = false)
    {
        if (isset($this->sections[$sectionName])) {
            return $this->sections[$sectionName];
        }

        return $default;
    }
}
