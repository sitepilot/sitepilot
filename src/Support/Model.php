<?php

namespace Sitepilot\Support;

use Sitepilot\Support\Arrayable;
use Sitepilot\Traits\HasAttributes;
use Sitepilot\Traits\GuardsAttributes;

abstract class Model implements Arrayable
{
    use HasAttributes,
        GuardsAttributes;

    /**
     * Create a new model instance.
     * 
     * @return void
     */
    public function __construct($attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        $totally_guarded = $this->totally_guarded();

        foreach ($this->fillable_from_array($attributes) as $key => $value) {
            // The developers may choose to place some attributes in the "fillable" array
            // which means only those attributes may be set through mass assignment to
            // the model, and all others will just get ignored for security reasons.
            if ($this->is_fillable($key)) {
                $this->set_attribute($key, $value);
            } elseif ($totally_guarded) {
                wp_die(
                    sprintf(
                        __('Add [%s] to fillable property to allow mass assignment on [%s].', 'sitepilot'),
                        $key,
                        get_class($this)
                    )
                );
            }
        }

        return $this;
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function to_json($options = 0)
    {
        $json = json_encode($this->json_serialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            wp_die(
                sprintf(
                    __('Could not convert model [%s] to json: %s.', 'sitepilot'),
                    get_class($this),
                    json_last_error_msg()
                )
            );
        }

        return $json;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function json_serialize()
    {
        return $this->to_array();
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function to_array()
    {
        return $this->attributes_to_array();
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get_attribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set_attribute($key, $value);
    }
}
