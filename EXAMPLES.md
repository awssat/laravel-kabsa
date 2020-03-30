
### Covid 19 Example

```php
class Covid19Date extends \Illuminate\Database\Eloquent\Model
{
    use \Awssat\Kabsa\Traits\Kabsa;

}


class Covid19 extends \Illuminate\Database\Eloquent\Model
{
    use \Awssat\Kabsa\Traits\Kabsa,  \Awssat\Kabsa\Traits\KabsaRelationships;

    protected static $total = 0;

    public function getRows()
    {
        return collect(Http::get('https://pomber.github.io/covid19/timeseries.json')
            ->json())
            ->map(function ($dates, $country) {

                foreach($dates as $date) {
                    $date['country'] = $country;
                    Covid19Date::addRow($date);
                }

                static::$total += end($dates)['confirmed'] ?? 0;

                return ['name' => $country];
            })->toArray();
    }

    public function timeline()
    {
        return $this->hasManyKabsaRows(Covid19Date::class,  'country', 'name');
    }

    public function getTotalAttribute()
    {
        return $this->timeline->last()->confirmed;
    }

    public function yesterday()
    {
        return $this->timeline->firstWhere('date', now()->subDay()->format('Y-n-d'));
    }

    public function today()
    {
        return $this->timeline->firstWhere('date', now()->format('Y-n-d'));
    }

    public function lastWeek()
    {
        return $this->timeline->firstWhere('date', now()->subWeek()->format('Y-n-d'));
    }

    public static function totalAroundTheWorld()
    {
        return static::$total;
    }
}

Covid19::totalAroundTheWorld();

Covid19::firstWhere('name', 'United Kingdom')->lastWeek()->confirmed;
```