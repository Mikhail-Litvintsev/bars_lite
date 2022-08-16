<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\LPU
 *
 * @property int $id
 * @property string $version
 * @property mixed $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LPU newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LPU newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LPU query()
 * @method static \Illuminate\Database\Eloquent\Builder|LPU whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LPU whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LPU whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LPU whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LPU whereVersion($value)
 * @mixin Eloquent
 */
class LPU extends Model
{
    use HasFactory;

    protected $table = 'l_p_u_s';
    protected $fillable = ['version', 'file'];
}
