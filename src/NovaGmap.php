<?php

namespace Ashraam\NovaGmap;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaGmap extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-gmap';

    public $textAlign = 'center';

    public function coords($latitude, $longitude, $hasCoordinates)
    {
        return $this->withMeta([
            'lat' => $latitude ?: env('DEFAULT_LATITUDE'),
            'lng' => $longitude ?: env('DEFAULT_LONGITUDE'),
            'hasCoordinates' => $hasCoordinates
        ]);
    }

    public function zoom($zoom)
    {
        return $this->withMeta([
            'zoom' => $zoom ?: env('DEFAULT_ZOOM')
        ]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $model->latitude = $request['latitude'];
        $model->longitude = $request['longitude'];
    }
}
