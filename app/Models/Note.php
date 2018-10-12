<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 11:29
 */

namespace App\Models;


class Note extends BaseModel
{
    const TABLE_NAME = 'notes';
    protected $table = self::TABLE_NAME;

    const FIELD_ID = 'id';

    /** 笔记所属的笔记分类 **/
    const FIELD_ID_CATEGORY = 'category_id';

    /** 所属用户ID **/
    const FIELD_ID_POSTER = 'poster_id';

    /** 笔记标题 */
    const FIELD_TITLE = 'title';

    /** 笔记的内容 */
    const FIELD_CONTENT = 'content';

    /** 笔记的封面 */
    const FIELD_ATTACHMENTS = 'attachments';

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

    const REL_POSTER = 'poster';
    const REL_CATEGORY = 'category';

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_CATEGORY,
        self::FIELD_ID_POSTER,
        self::FIELD_TITLE,
        self::FIELD_CONTENT,
        self::FIELD_ATTACHMENTS,
        self::FIELD_USE_TYPE,
        self::FIELD_TYPE,
        self::FIELD_STATUS
    ];

    public function poster()
    {
        return $this->belongsTo(Customer::class,self::FIELD_ID_POSTER,Customer::FIELD_ID)->select([
            Customer::FIELD_ID,
            Customer::FIELD_NICKNAME,
            Customer::FIELD_AVATAR
        ]);
    }

    public function category()
    {
        return $this->belongsTo(NoteCategory::class)->select([NoteCategory::FIELD_ID,NoteCategory::FIELD_NAME]);
    }

}