<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 9:38
 */

namespace App\Models;


class PraiseNote extends BaseModel
{
    const TABLE_NAME = 'praise_notes';
    protected $table = self::TABLE_NAME;

    /** Field id **/
    const FIELD_ID = 'id';

    /** Field obj_id 点赞对象 **/
    const FIELD_ID_OBJ = 'obj_id';

    /** Field user_id **/
    const FIELD_ID_USER = 'user_id';

    /** Field type **/
    const FIELD_TYPE = 'type';

    const ENUM_TYPE_AUTHOR = 1;
    const ENUM_TYPE_CATEGORY = 2;
    const ENUM_TYPE_NOTE = 3;

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_OBJ,
        self::FIELD_ID_USER,
        self::FIELD_TYPE
    ];

}