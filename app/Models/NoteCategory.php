<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28 0028
 * Time: 15:50
 */

namespace App\Models;


class NoteCategory extends BaseModel
{
    const TABLE_NAME = 'note_categories';
    protected $table = self::TABLE_NAME;

    /** 主键ID **/
    const FIELD_ID = 'id';

    /** 所属用户ID **/
    const FIELD_ID_POSTER = 'poster_id';

    /** 笔记簿名字 **/
    const FIELD_NAME = 'name';

    /** 用途 **/
    const FIELD_USE_TYPE = 'use_type';

    /** 类型 **/
    const FIELD_TYPE = 'type';

    /** 状态 **/
    const FIELD_STATUS = 'status';

    /** 日志 **/
    const ENUM_USE_TYPE_NOTE = 1;
    /** 大学成长日志 **/
    const ENUM_USE_TYPE_COLLEGE = 2;

    /** 公开 **/
    const ENUM_TYPE_PRIVATE = 1;
    /** 私密 */
    const ENUM_TYPE_PUBLIC = 2;

    const REL_NOTE = 'notes';
    const REL_CUSTOMER = 'customer';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_POSTER,
        self::FIELD_NAME,
        self::FIELD_TYPE,
        self::FIELD_USE_TYPE,
        self::FIELD_STATUS
    ];

    public function notes()
    {
        return $this->hasMany(Note::class,Note::FIELD_ID_CATEGORY,self::FIELD_ID);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,self::FIELD_ID_POSTER,Customer::FIELD_ID)->select([Customer::FIELD_ID,Customer::FIELD_NICKNAME,Customer::FIELD_AVATAR]);
    }
}