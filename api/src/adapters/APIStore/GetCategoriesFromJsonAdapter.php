<?php

namespace PSStoreParsing\Adapters\APIStore;

use PSStoreParsing\DTO\APIStore\Responses\Category;
use PSStoreParsing\Exceptions\ApiStore\InvalidArgumentException;

class GetCategoriesFromJsonAdapter
{

    private string $jsonResponseFromApi;
    private array $responseFromApi;
    private array $categories = [];

    /**
     * reporting name of component with categories
     */
    const CHILD_VIEW_REPORTING_NAME = 'DEALSTOP';
    const CATEGORY_LINK_TYPE = 'EMS_CATEGORY';

    public function __construct(string $jsonResponseFromApi)
    {
        $this->jsonResponseFromApi = $jsonResponseFromApi;

        $this->decode();
        $this->parse();
    }

    private function decode(): void
    {
        if (empty($this->jsonResponseFromApi)) {
            throw new InvalidArgumentException('Empty jsonResponseFromApi');
        }

        try {
            $this->responseFromApi = json_decode($this->jsonResponseFromApi, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new InvalidArgumentException('Cant decode jsonResponseFromApi');
        }

    }

    private function parse(): void
    {
        $childViews = $this->responseFromApi['data']['emsExperienceRetrieve']['views'][0]['childViews'] ?? null;

        if (empty($childViews) || !is_array($childViews)) {
            throw new InvalidArgumentException('Empty or wrong type of childViews from api response');
        }

        foreach ($childViews as $childView) {
            if ($childView['reportingName'] === self::CHILD_VIEW_REPORTING_NAME) {

                if (empty($childView['components']) || !is_array($childView['components'])) {
                    throw new InvalidArgumentException('Empty or wrong type of childView.components from api response');
                }

                foreach ($childView['components'] as $component) {
                    if (!empty($component['link']['type']) && $component['link']['type'] === self::CATEGORY_LINK_TYPE) {
                        $this->categories[] = new Category(
                            $component['id'],
                            $component['imageUrl'],
                            $component['link']['target'],
                            $component['name']
                        );;
                    }
                }
            }
        }


    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
