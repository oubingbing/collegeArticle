<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/9/12
 * Time: 22:06
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LocationLogModel extends Model
{
    const TABLE_NAME = "location_logs";
    protected $table = self::TABLE_NAME;

    const FIELD_ID = "id";

    const FIELD_ID_USER = "user_id";

    const FIELD_LONGITUDE = "longitude";

    const FIELD_LATITUDE = "latitude";

    const FIELD_LOCATE_AT = "locate_at";

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_LONGITUDE,
        self::FIELD_LATITUDE,
        self::FIELD_LOCATE_AT
    ];
}