<?php

namespace Sitepilot\Blocks\Fields;

class Repeater extends Field
{
    /**
     * Returns the ACF field configuration.
     *
     * @return array
     */
    protected function acf_config(): array
    {
        $subfields = [];
        foreach ($this->get_subfields() as $subfield) {
            if ($subfield instanceof Field) {
                $subfields[] = $subfield->get_config('acf', $this->get_attribute());
            }
        }

        $this->register_subfields = false;

        return [
            'type' => 'repeater',
            'layout' => 'block',
            'button_label' => __('New item', 'sitepilot-block'),
            'sub_fields' => $subfields
        ];
    }

    /**
     * Set the repeater fields.
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
