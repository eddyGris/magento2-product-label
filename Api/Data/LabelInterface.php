<?php

namespace Magecat\Label\Api\Data;

interface LabelInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const LABEL_ID = 'label_id';

    const NAME = 'name';

    /**#@-*/

    /**
     * Returns label id field
     *
     * @return int|null
     */
    public function getLabelId(): ?int;

    /**
     * @param int $labelId
     * @return $this
     */
    public function setLabelId(int $labelId): static;

    /**
     * Returns label name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static;
}
