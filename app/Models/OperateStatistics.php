<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 9:55
 */

namespace App\Models;


class OperateStatistics extends BaseModel
{
    const TABLE_NAME = 'operate_statistics';
    protected $table = self::TABLE_NAME;

    /** Field id **/
    const FIELD_ID = 'id';

    /** Field obj_id  计数对象**/
    const FIELD_ID_OBJ = 'obj_id';

    /** Field type 类型,1=作者，2=笔记簿，3=笔记本 **/
    const FIELD_TYPE = "type";

    /** Field follow **/
    const FIELD_FOLLOW = 'follow';

    /** Field collect **/
    const FIELD_COLLECT = 'collect';

    /** Field praise **/
    const FIELD_PRAISE = 'praise';

    /** Field view **/
    const FIELD_VIEW = 'view';

    const ENUM_TYPE_AUTHOR = 1;
    const ENUM_TYPE_CATEGORY = 2;
    const ENUM_TYPE_NOTE = 3;

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_OBJ,
        self::FIELD_TYPE,
        self::FIELD_FOLLOW,
        self::FIELD_COLLECT,
        self::FIELD_PRAISE,
        self::FIELD_VIEW
    ];
}