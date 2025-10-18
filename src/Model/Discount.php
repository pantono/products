<?php

namespace Pantono\Products\Model;

use Crell\Serde\Attributes\Field;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Discounts;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;

class Discount
{
    use SavableModel;

    private ?int $id = null;
    #[Field('base_id')]
    private DiscountBase $base;
    private string $name;
    private ?float $amount = null;
    private ?float $minSpend = null;
    private ?float $maxSpend = null;
    private ?int $buyXMin = null;
    private ?int $buyXFree = null;
    private bool $live;
    private int $priority;
    private bool $stack;
    /**
     * @var DiscountRule[]
     */
    #[Locator(methodName: 'getRulesForDiscount', className: Discounts::class), FieldName('$this)')]
    private array $rules = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getBase(): DiscountBase
    {
        return $this->base;
    }

    public function setBase(DiscountBase $base): void
    {
        $this->base = $base;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    public function getMinSpend(): ?float
    {
        return $this->minSpend;
    }

    public function setMinSpend(?float $minSpend): void
    {
        $this->minSpend = $minSpend;
    }

    public function getMaxSpend(): ?float
    {
        return $this->maxSpend;
    }

    public function setMaxSpend(?float $maxSpend): void
    {
        $this->maxSpend = $maxSpend;
    }

    public function getBuyXMin(): ?int
    {
        return $this->buyXMin;
    }

    public function setBuyXMin(?int $buyXMin): void
    {
        $this->buyXMin = $buyXMin;
    }

    public function getBuyXFree(): ?int
    {
        return $this->buyXFree;
    }

    public function setBuyXFree(?int $buyXFree): void
    {
        $this->buyXFree = $buyXFree;
    }

    public function isLive(): bool
    {
        return $this->live;
    }

    public function setLive(bool $live): void
    {
        $this->live = $live;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function isStack(): bool
    {
        return $this->stack;
    }

    public function setStack(bool $stack): void
    {
        $this->stack = $stack;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function addRule(DiscountRule $rule): void
    {
        $this->rules[] = $rule;
    }

    public function isApplicable(ProductVersion $product): bool
    {
        $valid = true;
        foreach ($this->getRules() as $rule) {
            $data = $product->toArray();
            $value = $data[$rule->getField()] ?? null;
            if ($value === null) {
                $valid = false;
            }
            if ($rule->getOperand() === '=' && $value !== $rule->getValue()) {
                $valid = false;
            }
            if ($value->getOperand() === '<' && $value > $rule->getValue()) {
                $valid = false;
            }
            if ($value->getOperand() === '>' && $value < $rule->getValue()) {
                $valid = false;
            }
            if ($value->getOperand() === '<=' && $value >= $rule->getValue()) {
                $valid = false;
            }
            if ($value->getOperand() === '>=' && $value <= $rule->getValue()) {
                $valid = false;
            }
            if ($value->getOperand() === 'in' && !in_array($value, explode(',', $rule->getValue()))) {
                $valid = false;
            }
        }
        return $valid;
    }
}
