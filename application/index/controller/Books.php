<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/29
 * Time: 3:50 PM
 */

namespace app\index\controller;


use app\model\Book;
use app\model\Category;
use think\Db;
use app\service\ChapterService;
use think\Request;

class Books extends Base
{
    protected $chapterService;
    protected function initialize()
    {
        $this->chapterService = new ChapterService();
    }

    public function index($id){
        $book = cache('book'.$id);
        if ($book == false){
            $book = Book::with(['author'=>['books']])->find($id);
            cache('book'.$id,$book);
        }
        $book->click = $book->click + 1;
        $book->isUpdate(true)->save();
        $start = cache('book_start' . $id);
        if ($start == false) {
            $db = Db::query('SELECT id FROM xwx_chapter WHERE book_id = ' . $id . ' ORDER BY `order` LIMIT 1');
            $start = $db ? $db[0]['id'] : -1;
            cache('book_start' . $id, $start);
        }
        $category = Category::where('cate_name','=',$book->category)->cache('category'.$id)->find();
        $last_chapter = cache('last_chapter'.$id);
        if ($last_chapter == false){
            $last_chapter = $this->chapterService->getLastChapter($id);
            cache('last_chapter'.$id,$last_chapter);
        }
        $this->assign([
            'book' => $book,
            'start' => $start,
            'gender' => $category->gender,
            'header_title' => $book->book_name,
            'last_chapter' => $last_chapter
        ]);
        return view($this->tpl);
    }

    public function chapterlist(){
        $book_id = input('book_id');
        $order = input('order');
        $data = $this->chapterService->getChapters(20,$order,[
            ['book_id','=',$book_id]
        ]);
        $book = cache('book'.$book_id);
        if ($book == false){
            $book = Book::with(['author'=>['books']])->find($book_id);
            cache('book'.$book_id,$book);
        }
        $this->assign([
            'chapters' => $data['chapters'],
            'count' => $data['count'],
            'book' => $book,
            'order' => $order,
            'header_title' => $book->book_name
        ]);
        return view();
    }

}