<?php

namespace Sitepilot\Traits;

use Sitepilot\Support\Str;
use Sitepilot\Support\Arrayable;

trait HasAttributes
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = [];

    /**
     * The cache of the mutated attributes for each class.
     *
     * @var array
     */
    protected static $mutator_cache = [];

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snake_attributes = true;

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get_attribute($key)
    {
        if (!$key) {
            return;
        }

        // If the attribute exists in the attribute array or has a "get" mutator we will
        // get the attribute's value. Otherwise, we will proceed as if the developers
        // are asking for a relationship's value. This covers both types of values.
        if (
            array_key_exists($key, $this->attributes) ||
            $this->has_get_mutator($key)
        ) {
            return $this->get_attribute_value($key);
        }
    }

    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function get_attributes()
    {
        return $this->attributes;
    }

    /**
     * Get the hidden attributes for the model.
     *
     * @return array
     */
    public function get_hidden()
    {
        return $this->hidden;
    }

    /**
     * Get the visible attributes for the model.
     *
     * @return array
     */
    public function get_visible()
    {
        return $this->visible;
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function has_get_mutator($key)
    {
        return method_exists($this, 'get_' . Str::snake($key) . '_attribute');
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     * @return mixed
     */
    public function get_attribute_value($key)
    {
        return $this->transform_model_value($key, $this->get_attributeFromArray($key));
    }

    /**
     * Transform a raw model value using mutators, casts, etc.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform_model_value($key, $value)
    {
        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->has_get_mutator($key)) {
            return $this->mutate_attribute($key, $value);
        }

        return $value;
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function get_attributeFromArray($key)
    {
        return $this->get_attributes()[$key] ?? null;
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutate_attribute($key, $value)
    {
        return $this->{'get_' . Str::snake($key) . '_attribute'}($value);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function set_attribute($key, $value)
    {
        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the model, such as "json_encoding" an listing of data for storage.
        if ($this->has_set_mutator($key)) {
            return $this->set_mutated_attribute_value($key, $value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function has_set_mutator($key)
    {
        return method_exists($this, 'set_' . Str::snake($key) . '_attribute');
    }

    /**
     * Set the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function set_mutated_attribute_value($key, $value)
    {
        return $this->{'set_' . Str::snake($key) . '_attribute'}($value);
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributes_to_array()
    {
        $attributes = $this->add_mutated_attributes_to_array(
            $this->get_arrayable_attributes(),
            $this->get_mutated_attributes()
        );

        return $attributes;
    }

    /**
     * Get an attribute array of all arrayable attributes.
     *
     * @return array
     */
    protected function get_arrayable_attributes()
    {
        return $this->get_arrayable_items($this->get_attributes());
    }

    /**
     * Get an attribute array of all arrayable values.
     *
     * @param  array  $values
     * @return array
     */
    protected function get_arrayable_items(array $values)
    {
        if (count($this->get_visible()) > 0) {
            $values = array_intersect_key($values, array_flip($this->get_visible()));
        }

        if (count($this->get_hidden()) > 0) {
            $values = array_diff_key($values, array_flip($this->get_hidden()));
        }

        return $values;
    }

    /**
     * Add the mutated attributes to the attributes array.
     *
     * @param  array  $attributes
     * @param  array  $mutatedAttributes
     * @return array
     */
    protected function add_mutated_attributes_to_array(array $attributes, array $mutatedAttributes)
    {
        foreach ($mutatedAttributes as $key) {
            // We want to spin through all the mutated attributes for this model and call
            // the mutator for the attribute. We cache off every mutated attributes so
            // we don't have to constantly check on attributes that actually change.
            if (!array_key_exists($key, $attributes)) {
                continue;
            }

            // Next, we will call the mutator for this attribute so that we can get these
            // mutated attribute's actual values. After we finish mutating each of the
            // attributes we will return this final array of the mutated attributes.
            $attributes[$key] = $this->mutate_attribute_for_array(
                $key,
                $attributes[$key]
            );
        }

        return $attributes;
    }

    /**
     * Get the mutated attributes for a given instance.
     *
     * @return array
     */
    public function get_mutated_attributes()
    {
        $class = static::class;

        if (!isset(static::$mutator_cache[$class])) {
            static::cache_mutated_attributes($class);
        }

        return static::$mutator_cache[$class];
    }

    /**
     * Extract and cache all the mutated attributes of a class.
     *
     * @param  string  $class
     * @return void
     */
    public static function cache_mutated_attributes($class)
    {
        static::$mutator_cache[$class] = array_map(function ($match) {
            return lcfirst(static::$snake_attributes ? Str::snake($match) : $match);
        }, static::get_mutator_methods($class));
    }

    /**
     * Get all of the attribute mutator methods.
     *
     * @param  mixed  $class
     * @return array
     */
    protected static function get_mutator_methods($class)
    {
        preg_match_all('/(?<=^|;)get_([^;]+?)_attribute(;|$)/', implode(';', get_class_methods($class)), $matches);

        return $matches[1];
    }

    /**
     * Get the value of an attribute using its mutator for array conversion.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutate_attribute_for_array($key, $value)
    {
        $value = $this->mutate_attribute($key, $value);

        if (is_array($value)) {
            return array_map(function ($item) {
                return $item instanceof Arrayable ? $item->to_array() : $item;
            }, $value);
        }

        return $value instanceof Arrayable ? $value->to_array() : $value;
    }
}
