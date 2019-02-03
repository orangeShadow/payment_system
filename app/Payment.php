<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['purse_from', 'purse_to', 'amount_from', 'amount_to', 'amount_usd'];

    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purseFrom() {
        return $this->belongsTo(Purse::class,'purse_from');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purseTo() {
        return $this->belongsTo(Purse::class,'purse_to');
    }
}
