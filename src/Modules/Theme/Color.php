<?php

namespace Sitepilot\Modules\Theme;

use Sitepilot\Support\Str;
use Sitepilot\Support\Model;

/**
 * @property string $key
 * @property string $name
 * @property string $color
 */
class Color extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['key', 'name', 'value'];

    /**
     * Create color instance.
     * 
     * @param string $key
     * @param array $attributes
     * @return void
     */
    public function __construct(string $key, array $attributes = [])
    {
        $this->key = $key;

        parent::__construct($attributes);
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
            return Str::title($this->key);
        }

        return $value;
    }
}
