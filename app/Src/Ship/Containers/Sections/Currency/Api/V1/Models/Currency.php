<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;

    public const TYPE_ECB = 'ECB';
    public const TYPE_CBR = 'CBR';

    public const TYPES = [
        self::TYPE_ECB,
        self::TYPE_CBR,
    ];


    /** @var string $table */
    protected $table = 'currencies';

    /** @var string[] $fillable */

    protected $fillable =
        [
            'currency',
            'rate',
            'name',
            'type',
            'nominal',
            'num_code'
        ];

}
