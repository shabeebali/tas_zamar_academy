<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $key
 * @property array $value
 * @mixin Eloquent
 */
class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $guarded = [];

    protected $casts = [
      'value' => 'array'
    ];

  /**
   * @param string $key
   * @return array|HigherOrderBuilderProxy|mixed|null
   */
  public static function getByKey(string $key, $default = null) {
    $row = self::where('key',$key)->first();
    if($row){
        return $row->value;
    }
    return $default;
  }

  public static function putByKey(string $key, mixed $value) {
      $row = self::where('key',$key)->first();
      if(!$row) {
          $row = new Settings(['key' => $key]);
      }
      $row->value = $value;
      $row->save();
  }
}
