<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 11:38
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\NoteService;
use App\Models\Note;

class NoteController extends Controller
{
    private $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function createNote()
    {
        $title = request()->input("title");
        $categoryId = request()->input("category_id");
        $content = request()->input("content","");
        $attachments = request()->input("attachments",[]);
        $type = request()->input("type",Note::ENUM_TYPE_PRIVATE);
        //$userId = request()->get("user");
        $userId = 1;

        $result = $this->noteService->create($categoryId,$title,$content,$attachments,$type);

        return webResponse("新建成功",200,$this->noteService->formatSingle($result));
    }
}