<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 11:48
 */

namespace App\Http\Service;


use App\Exceptions\WebException;
use App\Models\Note as Model;

class NoteService
{
    /**
     * 新建笔记
     *
     * @author yezi
     * @param $categoryId
     * @param $title
     * @param $content
     * @param $attachments
     * @param $useType
     * @param $type
     * @return mixed
     */
    public function create($categoryId,$title,$content,$attachments,$useType,$type)
    {
        $result = Model::create([
            Model::FIELD_ID_CATEGORY=>$categoryId,
            Model::FIELD_TITLE=>$title,
            Model::FIELD_CONTENT=>$content,
            Model::FIELD_ATTACHMENTS=>empty($attachments)?[]:$attachments,
            Model::FIELD_TYPE=>$type,
            Model::FIELD_USE_TYPE=>$useType
        ]);

        return $result;
    }

    /**
     * 格式化数据
     *
     *@author yezi
     * @param $item
     * @return mixed
     */
    public function formatSingle($item)
    {
        if(!isset($item->{Model::FIELD_CONTENT})){
            $item->{Model::FIELD_CONTENT} = '';
        }

        return $item;
    }

    /***
     * 获取笔记
     *
     * @author yezi
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getNoteById($id)
    {
        $result = Model::query()->where(Model::FIELD_ID,$id)->first();
        return $result;
    }

    /**
     * 更新笔记内容
     *
     * @author yezi
     * @param $content
     * @param $attachments
     * @param $note
     * @return mixed
     */
    public function updateContent($content,$attachments,$note)
    {
        $note->{Model::FIELD_CONTENT} = $content;
        $note->{Model::FIELD_ATTACHMENTS} = $attachments;
        $result = $note->save();

        return $result;
    }

    /**
     * 更新标题
     *
     * @author yezi
     * @param $title
     * @param $note
     * @return mixed
     */
    public function updateTitle($title,$note)
    {
        $note->{Model::FIELD_TITLE} = $title;
        $result = $note->save();
        return $result;
    }

    /**
     * 根据日志类目删除日志
     *
     * @author yezi
     * @param $categoryId
     * @return mixed
     */
    public function deleteByCategory($categoryId)
    {
        $result = Model::query()->where(Model::FIELD_ID_CATEGORY,$categoryId)->delete();
        return $result;
    }

    /**
     * 根据主键删除日志
     *
     * @author yezi
     * @param $id
     * @return bool|mixed|null
     * @throws WebException
     */
    public function deleteById($id)
    {
        $note = $this->getNoteById($id);
        if(!$note){
            throw new WebException("日志不存在");
        }

        $result = $note->delete();

        return $result;
    }

    public function checkRepeat($name,$categoryId)
    {
        $result = Model::query()->where(Model::FIELD_TITLE,$name)->where(Model::FIELD_ID_CATEGORY,$categoryId)->first();
        return $result;
    }

}