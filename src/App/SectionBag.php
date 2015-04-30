<?php

namespace App;

/**
 * Class SectionBag.
 */
class SectionBag
{
    /** @var string */
    private $sections;

    /**
     * Registers a enabled/disabled section.
     */
    public function registerSection($sectionName, $isSectionEnabled)
    {
        $this->sections[$sectionName] = $isSectionEnabled;
    }

    /**
     * Checks whether a given section is enabled or not. In case the
     * section is not registered, the given default value is returned
     * instead.
     *
     * @param string $sectionName
     * @param bool   $default
     */
    public function isSectionEnabled($sectionName, $default = false)
    {
        if (isset($this->sections[$sectionName])) {
            return $this->sections[$sectionName];
        }

        return $default;
    }

    /**
     * @param string $sectionName The name of the section to be enabled.
     */
    public function enableSection($sectionName)
    {
        $this->registerSection($sectionName, true);
    }
}
