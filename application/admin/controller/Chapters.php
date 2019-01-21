<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/18
 * Time: 5:26 PM
 */

namespace app\admin\controller;

use think\Exception;
use think\Request;
use app\model\Book;
use app\model\Chapter;
use app\service\ChapterService;
use think\facade\App;

class Chapters extends Base
{
    protected $chapterService;
    public function initialize()
    {
        $this->chapterService = new ChapterService();
    }

    public function index()
    {
        $book_id = input('book_id');
        $book = Book::get($book_id);
        $data = $this->chapterService->getAdminChapters([
            ['book_id','=',$book_id]
        ]);
        $this->assign([
            'chapters' => $data['chapters'],
            'count' => $data['count'],
            'book_id' => $book_id,
            'book' => $book
        ]);
        return view();
    }

    public function create(){
        $returnUrl = input('returnUrl');
        $book_id = input('book_id');
        $lastChapterOrder = 0;
        $lastChapter = $this->chapterService->getLastChapter($book_id);
        if ($lastChapter){
            $lastChapterOrder = $lastChapter->order;
        }
        $this->assign([
            'book_id' => $book_id,
            'order' => $lastChapterOrder + 1,
            'returnUrl' => $returnUrl
        ]);
        return view();
    }

    public function save(Request $request)
    {
        $data = $request->param();
        if(empty($data['chapter_name'])){
            $this->error('没有填写章节名');
        }
        $map[] = ['chapter_name','=',trim($data['chapter_name'])];
        $map[] = ['book_id','=',$data['book_id']];
        $chapter = Chapter::where($map)->find();
        if ($chapter){
            $this->error('存在同名章节');
        }
        $chapter = new Chapter();
        $chapter->save($data);

        $content =  $request->file('content');
        $dir = App::getRootPath() . '/public/static/upload/book/' . $data['book_id'];
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $info = $content->move($dir,$chapter->id.'.txt');
        if ($info){
            $param = [
                "id" => $data["book_id"],
                "last_time" => time()
            ];
            Book::update($param);
            $this->success('添加成功',$data['returnUrl'],'',1);
        }else{
            $this->error('新增失败');
        }
    }

    public function edit($id)
    {
        $returnUrl = input('returnUrl');
        $id = input('id');
        $chapter = Chapter::get($id);
        if (!$chapter){
            $this->error('不存在的章节');
        }
        $this->assign([
            'chapter' => $chapter,
            'returnUrl' => $returnUrl
        ]);
        return view();
    }

    public function update(Request $request)
    {
        $data = $request->param();
        $returnUrl = $data['returnUrl'];
        $chapter = new Chapter();
        $chapter->isUpdate(true)->save($data);
        $content = $request->file('content');
        $dir = App::getRootPath() . '/public/static/upload/book/' . $data['book_id'].'/'.$chapter->id;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $info = $content->move($dir,$chapter->id.'.txt');
        if ($info){
            $this->success('编辑成功',$returnUrl,'',1);
        }else{
            $this->error('编辑失败');
        }
    }

    public function delete($id)
    {
        $chapter = Chapter::get($id);
        $chapter->delete();
        return ['err'=>0,'msg'=>'删除成功'];
    }

    //展示章节内容
    public function content(Request $request){
        $book_id = $request->param('book_id');
        $chapter_id = $request->param('chapter_id');
        $path = App::getRootPath() . 'public/static/upload/book/' . $book_id.'/'.$chapter_id.'.txt';
        $file = fopen($path,'r');
        $content = fread($file,filesize($path));
        $this->assign('content',$content);
        return view();
    }

    //自动分割处理器
    public function split(Request $request){
        $returnUrl = $request->param('returnUrl');
        $book_id = $request->param('book_id');
        $this->assign([
            'book_id' => $book_id,
            'returnUrl' => $returnUrl
        ]);
        if ($request->isPost()){
            $returnUrl = $request->param('returnUrl');
            $file = $request->file('content');
            $index_file = $request->file('index');
            $dir = App::getRootPath() . '/public/static/upload/book/'.$book_id;
            $file_info = $file->move($dir);
            $index_info = $index_file->move($dir);

                $this->process($file_info->getSaveName(),$index_info->getSaveName(), $book_id);
                $this->success('分割成功',$returnUrl,'',1);

        }
        return view();
    }

    private function process($file,$idex_file,$book_id){
        $book = Book::get($book_id);
        $book->last_time = time();
        $book->isUpdate(true)->save();
        $file_path = App::getRootPath().'public/static/upload/book/'.$book_id.'/'.$file;
        $index_path = App::getRootPath().'public/static/upload/book/'.$book_id.'/'.$idex_file;
        $content = file_get_contents(urldecode($file_path)); //实际小说文件
        $index = file_get_contents(urldecode($index_path)); //目录文件
        $encoding = mb_detect_encoding($content, "auto");
        $encoding2 = mb_detect_encoding($index, "auto");
        if ($encoding != 'UTF-8' || $encoding2 != 'UTF-8'){
            $this->error('上传的文件编码必须是utf-8');
        }
        $arr = array_filter(preg_split('/[;\r\n]+/s',$content)); //将小说文本分行转换成数组
        $index_arr = array_filter(preg_split('/[;\r\n]+/s',$index)); //将目录文本分行转换成数组
        $split_count = ceil(count($arr)/count($index_arr)); //小说总行数除以章节总数，算出每章节多少行
        $new = array_chunk($arr,$split_count); //分割成小数组
        $lastChapterOrder = 0;
        foreach ($new as $key => $value) {
            $lastChapter = $this->chapterService->getLastChapter($book_id);
            if ($lastChapter){
                $lastChapterOrder = $lastChapter->order;
            }
            $chapter = new Chapter();
            $chapter->order = $lastChapterOrder + 1;
            $chapter->save(['chapter_name' => $index_arr[$key], 'book_id' => $book_id]);
            $file = App::getRootPath() . '/public/static/upload/book/'.$book_id.'/'.$chapter->id.'.txt';
            foreach ($value as $item) {
                $handle=fopen($file,"a+");
                fwrite($handle,$item."\n");
                fclose($handle);
            }
        }

    }
}