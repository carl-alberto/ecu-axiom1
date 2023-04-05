<?php

namespace NF_FU_VENDOR\Composer\Installers;

class RadPHPInstaller extends BaseInstaller
{
    protected $locations = array('bundle' => 'src/{$name}/');
    /**
     * Format package name to CamelCase
     */
    public function inflectPackageVars($vars)
    {
        $nameParts = \explode('/', $vars['name']);
        foreach ($nameParts as &$value) {
            $value = \strtolower(\preg_replace('/(?<=\\w)([A-Z])/', 'NF_FU_VENDOR\\_\\1', $value));
            $value = \str_replace(array('-', '_'), ' ', $value);
            $value = \str_replace(' ', '', \ucwords($value));
        }
        $vars['name'] = \implode('/', $nameParts);
        return $vars;
    }
}
