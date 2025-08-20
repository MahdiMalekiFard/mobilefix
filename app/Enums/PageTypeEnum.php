<?php

declare(strict_types=1);

namespace App\Enums;

enum PageTypeEnum: string
{
    use EnumToArray;

    case TERMS_OF_SERVICE    = 'terms_of_service';
    case PRIVACY_POLICY      = 'privacy_policy';
    case ABOUT_US            = 'about-us';

    public function title(?string $locale = null): string
    {
        return match ($this) {
            self::ABOUT_US         => __('page.enum.type.about-us', locale: $locale),
            self::TERMS_OF_SERVICE => __('page.enum.type.terms-of_service', locale: $locale),
            self::PRIVACY_POLICY   => __('page.enum.type.privacy_policy', locale: $locale),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
        ];
    }
}
