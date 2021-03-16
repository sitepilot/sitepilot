<?php

namespace Sitepilot\Fields;

use Sitepilot\Support\Model;

/**
 * @property string $key
 * @property string $operator
 * @property string $value
 */
class FieldConditional extends Model
{
    /**
     * Create a new conditional instance.
     * 
     * @return void
     */
    public function __construct(string $key, array $config)
    {
        $this->key = $key;

        parent::__construct($config);
    }

    /**
     * The support attributes.
     *
     * @var array[]
     */
    protected $attributes = [
        'key' => '',
        'operator' => '!=',
        'value' => ''
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'key',
        'operator',
        'value'
    ];
}
