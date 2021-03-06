<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zikula\Tools\Converter;

use Zikula\Tools\ConverterAbstract;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class AssignConverter extends ConverterAbstract
{

    public function convert(\SplFileInfo $file, $content)
    {
        $content = $this->replace($content);

        return $content;
    }

    private function replace($content)
    {
        $pattern = '/\{assign\b\s*([^{}]+)?\}/';
        $string = '{% set :key = :value %}';

        return preg_replace_callback($pattern, function ($matches) use ($string) {

            $match = $matches[1];
            $attr = $this->attributes($match);

            $key = isset($attr['var']) ? $attr['var'] : null;
            $value = isset($attr['value']) ? $attr['value'] : null;

            // Short-hand {assign "name" "Bob"}
            if (!isset($key)) {
                reset($attr);
                $key = key($attr);
            }

            if (!isset($value)) {
                next($attr);
                $value = key($attr);
            }

            $value = $this->value($value);
            $key = $this->variable($key);

            $string = $this->vsprintf($string, array('key' => $key, 'value' => $value));
            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);

        }, $content);

    }

    public function getPriority()
    {
        return 100;
    }

    public function getName()
    {
        return 'assign';
    }

    public function getDescription()
    {
        return "Convert smarty {assign} to twig {% set foo = 'foo' %}";
    }

}
