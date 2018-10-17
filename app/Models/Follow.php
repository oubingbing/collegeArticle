<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/15 0015
 * Time: 9:34
 */

namespace App\Models;


class Follow extends BaseModel
{
    const TABLE_NAME = 'follows';
    protected $table = self::TABLE_NAME;

    /** Field id **/
    const FIELD_ID = 'id';

    /** Field obj_id 关注对象 **/
    const FIELD_ID_OBJ = 'obj_id';

    /** Field user_id **/
    const FIELD_ID_USER = 'user_id';

    /** Field type **/
    const FIELD_TYPE = 'type';

    const ENUM_TYPE_AUTHOR = 1;
    const ENUM_TYPE_CATEGORY = 2;
    const ENUM_TYPE_NOTE = 3;

    const REL_NOTE = 'note';
    const REL_CUSTOMER = 'customer';
    const REL_CATEGORIES = 'categories';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_OBJ,
        self::FIELD_ID_USER,
        self::FIELD_TYPE
    ];

    public function note()
    {
        return $this->belongsTo(Note::class,self::FIELD_ID_OBJ,Note::FIELD_ID);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,self::FIELD_ID_OBJ,Customer::FIELD_ID);
    }

    public function categories()
    {
        return $this->belongsTo(NoteCategory::class,self::FIELD_ID_OBJ,NoteCategory::FIELD_ID);
    }

}