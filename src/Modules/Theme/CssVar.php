<?php

namespace Sitepilot\Modules\Theme;

use Sitepilot\Support\Str;
use Sitepilot\Support\Model;

/**
 * @property string $key
 * @property string $value
 */
class CssVar extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['key', 'name', 'value'];

    /**
     * Format key attribute.
     *
     * @param string $value
     * @return string
     */
    protected function get_key_attribute($value)
    {
        if (empty($value)) {
            return Str::slug($this->name);
        }

        return $value;
    }

    /**
     * Format name attribute.
     *
     * @param string $value
     * @return string
     */
    protected function get_name_attribute($value)
    {
        if (empty($value)) {
            return Str::studly($this->key);
        }

        return $value;
    }
}
