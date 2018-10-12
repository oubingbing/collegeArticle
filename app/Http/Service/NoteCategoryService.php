<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28 0028
 * Time: 15:54
 */

namespace App\Http\Service;


use App\Exceptions\WebException;
use App\Models\Note;
use App\Models\NoteCategory as Model;
use App\Models\NoteCategory;
use PhpParser\Node\Expr\AssignOp\Mod;

class NoteCategoryService
{
    /**
     * 保存日记簿
     *
     * @author yezi
     * @param $userId
     * @param $name
     * @param $type
     * @return mixed
     */
    public function create($userId,$name,$type)
    {
        $result = Model::create([
            Model::FIELD_ID_POSTER=>$userId,
            Model::FIELD_NAME=>$name,
            Model::FIELD_USE_TYPE=>$type
        ]);

        return $result;
    }

    /**
     * 检测名字是否重复
     *
     * @author yezi
     * @param $userId
     * @param $name
     * @return bool
     */
    public function checkRepeat($userId,$name)
    {
        $result = $this->findByUserAndName($userId,$name);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 根据用户和笔记簿的名字查询
     *
     * @author yezi
     * @param $userId
     * @param $name
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findByUserAndName($userId,$name)
    {
        $result = Model::query()->where(Model::FIELD_ID_POSTER,$userId)->where(Model::FIELD_NAME,$name)->first();
        return $result;
    }

    /**
     * 获取笔记簿
     *
     * @author yezi
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNoteCategories($userId)
    {
        $result = Model::query()
            ->with([Model::REL_NOTE=>function($query){
                $query->select([
                    Note::FIELD_ID,
                    Note::FIELD_ID_CATEGORY,
                    Note::FIELD_TITLE,
                    Note::FIELD_TYPE,
                    Note::FIELD_USE_TYPE
                ]);
            }])
            ->where(Model::FIELD_ID_POSTER,$userId)
            ->select([
                Model::FIELD_ID,
                Model::FIELD_NAME,
                Model::FIELD_TYPE,
                Model::FIELD_USE_TYPE
            ])
            ->orderBy(Model::FIELD_CREATED_AT,'ASC')
            ->get();

        return $result;
    }

    /**
     * 根据分类ID获取用户的日志类目
     *
     * @author yezi
     * @param $userId
     * @param $categoryId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCategoryById($userId,$categoryId)
    {
        $result = Model::query()->where(Model::FIELD_ID_POSTER,$userId)->where(Model::FIELD_ID,$categoryId)->first();
        return $result;
    }

    /**
     * 根据分类ID获取用户的日志类目
     *
     * @author yezi
     * @param $userId
     * @param $categoryId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCategory($categoryId)
    {
        $result = Model::query()->find($categoryId);
        return $result;
    }

    /**
     * 格式化单条数据
     *
     * @author yezi
     * @param $item
     * @return mixed
     */
    public function formatSingle($item)
    {
        if(!isset($item[Model::REL_NOTE])){
            $noteService = app(NoteService::class);
            $item->{Model::REL_NOTE}->map(function ($note)use($noteService){
                return $noteService->formatSingle($note);
            });
        }

        return $item;
    }

    /**
     * 删除日志类目
     *
     * @author yezi
     * @param $userId
     * @param $categoryId
     * @return mixed
     * @throws WebException
     */
    public function delete($userId,$categoryId)
    {
        $category = $this->getCategoryById($userId,$categoryId);
        if(!$category){
            throw new WebException("日记类目不存在",500);
        }

        $result = $category->delete();

        return $result;
    }

    /**
     * 更新日志簿名字
     *
     * @author yezi
     * @param $name
     * @param $id
     * @return bool
     * @throws WebException
     */
    public function updateName($name,$id)
    {
        $category = $this->getCategory($id);
        if(!$category){
            throw new WebException("笔记本不存在");
        }

        $category->{NoteCategory::FIELD_NAME} = $name;
        $result = $category->save();

        return $result;
    }

}