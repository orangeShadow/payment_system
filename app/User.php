<?php

namespace App;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'country', 'city'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function purse()
    {
        return $this->hasOne(Purse::class);
    }

    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeSearch(Builder $query, Request $request)
    {
        $name = $request->get('name');
        $query->when($name, function ($query, $name) use ($request) {
            $query->where('name', 'like', $name . '%');
        });

        $country = $request->get('country');
        $query->when($country, function ($query, $country) use ($request) {
            $query->where('country', 'like', $country . '%');
        });

        $city = $request->get('city');
        $query->when($request->has($city), function ($query, $city) use ($request) {
            $query->where('city', 'like', $request->get($city) . '%');
        });

        return $query;
    }
}
