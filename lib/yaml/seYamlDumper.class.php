<?php

/*
 * This file is part of the SiteEdit package.
 */

require_once(dirname(__FILE__).'/seYamlInline.class.php');

/**
 * seYamlDumper class.
 *
 * @package    SiteEdit
 * @subpackage util
 * @author     EDGESTILE
 * @version    4.0
 */
class seYamlDumper
{
  /**
   * Dumps a PHP value to YAML.
   *
   * @param  mixed   The PHP value
   * @param  integer The level where you switch to inline YAML
   * @param  integer The level o indentation indentation (used internally)
   *
   * @return string  The YAML representation of the PHP value
   */
  public function dump($input, $inline = 0, $indent = 0)
  {
    $output = '';
    $prefix = $indent ? str_repeat(' ', $indent) : '';

    if ($inline <= 0 || !is_array($input) || empty($input))
    {
      $output .= $prefix.seYamlInline::dump($input);
    }
    else
    {
      $isAHash = array_keys($input) !== range(0, count($input) - 1);

      foreach ($input as $key => $value)
      {
        $willBeInlined = $inline - 1 <= 0 || !is_array($value) || empty($value);

        $output .= sprintf('%s%s%s%s',
          $prefix,
          $isAHash ? seYamlInline::dump($key).':' : '-',
          $willBeInlined ? ' ' : "\n",
          $this->dump($value, $inline - 1, $willBeInlined ? 0 : $indent + 2)
        ).($willBeInlined ? "\n" : '');
      }
    }

    return $output;
  }
}
