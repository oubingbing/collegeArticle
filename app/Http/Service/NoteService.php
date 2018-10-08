<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 11:48
 */

namespace App\Http\Service;


use App\Models\Note as Model;
use PhpParser\Node\Expr\AssignOp\Mod;

class NoteService
{
    public function create($categoryId,$title,$content,$attachments,$type)
    {
        $result = Model::create([
            Model::FIELD_ID_CATEGORY=>$categoryId,
            Model::FIELD_TITLE=>$title,
            Model::FIELD_CONTENT=>$content,
            Model::FIELD_ATTACHMENTS=>empty($attachments)?[]:$attachments,
            Model::FIELD_TYPE=>$type
        ]);

        return $result;
    }

    public function formatSingle($item)
    {
        if(!isset($item->{Model::FIELD_CONTENT})){
            $item->{Model::FIELD_CONTENT} = '';
        }

        return $item;
    }

    public function getNoteById($id)
    {
        $result = Model::query()->where(Model::FIELD_ID,$id)->first();
        return $result;
    }

    public function updateContent($content,$note)
    {
        $note->{Model::FIELD_CONTENT} = $content;
        $result = $note->save();

        return $result;
    }

    public function updateTitle($title,$note)
    {
        $note->{Model::FIELD_TITLE} = $title;
        $result = $note->save();
        return $result;
    }


}