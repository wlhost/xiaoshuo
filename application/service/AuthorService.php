<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/11/15
 * Time: 9:36 PM
 */

namespace app\service;

use app\model\Author;
use app\model\Book;

class AuthorService
{
    public function getByName($name){
        return Author::where('author_name','=',$name)->find();
    }

    public function getAuthors($where = '1=1'){
        $authors = Author::where($where)->with('books')->paginate(5,false,[
            'type'     => 'util\AdminPage',
                'var_page' => 'page',
        ]);
        foreach ($authors as &$author) {
            $author['count'] = count($author->books);
        }
        return [
            'authors' => $authors,
            'count' => $authors->count()
        ];
    }

    public function getBooksByAuthor($author_name){
        $author_id = Author::where('author_name','=',$author_name)->find()->id;
        $data = Book::where('author_id','=',$author_id);
        $books = $data->with('author')->paginate(5);
        foreach ($books as &$book){
            $book['chapter_count'] = count($book->chapters);
        }
        return [
            'books' => $books,
            'count' => $data->count()
        ];
    }
}