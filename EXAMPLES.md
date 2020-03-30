
### Covid 19 Example

```php
class Covid19 extends \Illuminate\Database\Eloquent\Model
{
    use \Awssat\Kabsa\Traits\Kabsa;
    
    public function getRows()
    {
        return collect(Http::get('https://pomber.github.io/covid19/timeseries.json')
            ->json())
            ->map(function ($timeline, $country) {
                return ['country' => $country, 'timeline' => collect($timeline)];
            })->toArray();
    }
    
    public function yesterday()
    {
        return $this->timeline->firstWhere('date', now()->subDay()->format('Y-n-d'));
    }

    public function latest()
    {
        return $this->timeline->last();
    }

    public function lastWeek()
    {
        return $this->timeline->firstWhere('date', now()->subWeek()->format('Y-n-d'));
    }

    public function getTotalAttribute()
    {
        return $this->timeline->last()['confirmed'] ?? 0;
    }
    
    public static function totalAroundTheWorld()
    {
        return static::all()->sum('total');
    }
}

Covid19::totalAroundTheWorld();

Covid19::firstWhere('country', 'United Kingdom')->lastWeek()['confirmed'];

Covid19::firstWhere('country', 'United Kingdom')->latest()['confirmed'];
```
