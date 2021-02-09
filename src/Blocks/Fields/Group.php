<?php

namespace Sitepilot\Blocks\Fields;

class Group extends Field
{
    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
    {
        return [
            'type' => 'accordion'
        ];
    }

    /**
     * Set the group fields.
     *
     * @param array $fields
     * @return self
     */
    public function fields(array $fields): self
    {
        $this->subfields = $fields;

        return $this;
    }
}
