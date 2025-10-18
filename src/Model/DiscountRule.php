<?php

namespace Pantono\Products\Model;

class DiscountRule
{
    private ?int $id = null;
    private int $discountId;
    private string $field;
    private string $value;
    private string $operand;
    private bool $include;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDiscountId(): int
    {
        return $this->discountId;
    }

    public function setDiscountId(int $discountId): void
    {
        $this->discountId = $discountId;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getOperand(): string
    {
        return $this->operand;
    }

    public function setOperand(string $operand): void
    {
        $allowed = ['=', '<', '>', '<=', '>=', '!=', 'in', 'not in', 'like', 'not like'];
        if (!in_array($operand, $allowed)) {
            throw new \RuntimeException('Invalid operand for discount rule');
        }
        $this->operand = $operand;
    }

    public function isInclude(): bool
    {
        return $this->include;
    }

    public function setInclude(bool $include): void
    {
        $this->include = $include;
    }
}
